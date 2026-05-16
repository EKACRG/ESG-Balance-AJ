<?php 
namespace Models;

class DatabaseModel {
    private $pdo;

    public function __construct() {
        include __DIR__ . "/../config/db.php";
        $this->pdo = $pdo;
    }

    // METODI PER STATS
    public function getNumAziende() {
        $stmt = $this->pdo->query("SELECT * FROM NUM_AZIENDE");
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getNumRevisori() {
        $stmt = $this->pdo->query("SELECT * FROM NUM_REVISORI_ESG");
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getAziendaPiuAffidabile() {
        $stmt = $this->pdo->query("SELECT * FROM AZIENDA_PIU_AFFIDABILE");
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getClassificaBilanci() {
        $stmt = $this->pdo->query("SELECT * FROM CLASSIFICA_BILANCI");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // METODI PER AUTH
    // Login (verifica credenziali e crea sessione)
    public function login(string $username, string $password): bool {
        $stmt = $this->pdo->prepare("SELECT * FROM UTENTI WHERE Username = :username");
        $stmt->execute([":username" => $username]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!empty($user) && password_verify($password, $user["Password"])) {
            session_regenerate_id();
            $_SESSION["username"] = $username;
            $_SESSION["password"] = $user["Password"];
            $_SESSION["tipo_utente"] = $this->getTipoUtente($username);
            return true;
        }

        return false;
    }

    // Controlla se l'utente è loggato
    public function isLoggedIn(): bool {
        if (!isset($_SESSION["username"], $_SESSION["password"])) {
            return false;
        }

        // Confronto tra dati salvati in sessione con quelli nel db
        // Gestisce due casi: utente eliminato e password cambiata
        $stmt = $this->pdo->prepare("SELECT * FROM UTENTI WHERE Username = :username");
        $stmt->execute([":username" => $_SESSION["username"]]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!empty($user) && $_SESSION["password"] === $user["Password"]) {
            return true;
        }

        $this->logout();
        return false;
    }

    // Logout e rimozione sessione
    public function logout() {
        $_SESSION = [];
        session_destroy();
    }

    // Registrazione
    public function signup(
        string $username,
        string $password,
        string $cf,
        string $dataNascita,
        string $luogoNascita,
        string $email,
        string $tipoUtente,
        ?string $curriculumVitae = null,
        array $emailAggiuntive = []
    ): bool {
        // Per non salvare la password in chiaro
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare("CALL RegistraUtente(:username, :password, :cf, :dataNascita, :luogoNascita, :email)");
        $stmt->execute([
            ":username" => $username,
            ":password" => $passwordHash,
            ":cf" => $cf,
            ":dataNascita" => $dataNascita,
            ":luogoNascita" => $luogoNascita,
            ":email" => $email
        ]);

        // Chiamata alla sp specifica per tipo utente
        if ($tipoUtente === "amministratore") {
            $stmt = $this->pdo->prepare("CALL RegistraAmministratore(:username)");
            $stmt->execute([":username" => $username]);
        } 
        elseif ($tipoUtente === "revisore") {
            $stmt = $this->pdo->prepare("CALL RegistraRevisore(:username)");
            $stmt->execute([":username" => $username]);
        } 
        elseif ($tipoUtente === "responsabile") {
            $stmt = $this->pdo->prepare("CALL RegistraResponsabile(:username, :curriculumVitae)");
            $stmt->execute([
                ":username" => $username,
                ":curriculumVitae" => $curriculumVitae
            ]);
        }

        foreach ($emailAggiuntive as $ea) {
            if (!empty($ea)) {
                $stmt = $this->pdo->prepare("CALL InserisciEmail(:username, :email)");
                $stmt->execute([
                    ":username" => $username,
                    ":email" => $ea
                ]);
            }
        }

        return true;
    }

    // Restituisce il tipo di utente
    private function getTipoUtente(string $username): string {
        $stmt = $this->pdo->prepare("SELECT 1 FROM AMMINISTRATORE WHERE Username = :username");
        $stmt->execute([":username" => $username]);
        if ($stmt->fetch()) {
            return "amministratore";
        }

        $stmt = $this->pdo->prepare("SELECT 1 FROM REVISORE_ESG WHERE Username = :username");
        $stmt->execute([":username" => $username]);
        if ($stmt->fetch()) {
            return "revisore";
        }

        return "responsabile";
    }

    // METODI PER AMMINISTRATORE
    // Inserisce gli indicatori ESG
    public function inserisciIndicatoriESG(
        string $indicatore, 
        string $usernameAmministratore, 
        string $immagine,
        string $rilevanza,
        string $tipoIndicatore,
        ?string $codiceNormativa,
        ?string $ambitoSociale,
        ?string $frequenzaRilevazione
    ): void {
        $stmt = $this->pdo->prepare("CALL InserisciIndicatoreESG(:indicatore, :usernameAmministratore, :immagine, :rilevanza, :tipoIndicatore, :codiceNormativa, :ambitoSociale, :frequenzaRilevazione)");
        $stmt->execute([
            ":indicatore" => $indicatore,
            ":usernameAmministratore" => $usernameAmministratore,
            ":immagine" => $immagine,
            ":rilevanza" => $rilevanza,
            ":tipoIndicatore" => $tipoIndicatore,
            ":codiceNormativa" => $codiceNormativa,
            ":ambitoSociale" => $ambitoSociale,
            ":frequenzaRilevazione" => $frequenzaRilevazione
        ]);
    }

    // Inserisce voce contabile (template)
    public function inserisciVoceContabile($voceContabile, $usernameAmministratore, $descrizione): void {
        $stmt = $this->pdo->prepare("CALL InserisciVoceContabile(:voceContabile, :usernameAmministratore, :descrizione");
        $stmt->execute([":voceContabile" => $voceContabile, ":usernameAmministratore" => $usernameAmministratore, ":descrizione" => $descrizione]);
    }

    // Inserisce associazione tra revisore ESG e bilancio
    public function associaRevisore($usernameRevisore, $ragioneSociale, $dataCreazione): void {
        $stmt = $this->pdo->prepare("CALL AssociaRevisore(:usernameRevisore, :ragioneSociale, :dataCreazione)");
        $stmt->execute([":usernameRevisore" => $usernameRevisore, ":ragioneSociale" => $ragioneSociale, ":dataCreazione" => $dataCreazione]);
    }

    public function getRevisori(): array {
        $stmt = $this->pdo->query("SELECT Username FROM REVISORE_ESG");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getBilanci(): array {
        $stmt = $this->pdo->query("SELECT RagioneSociale, DataCreazione, Stato FROM BILANCIO WHERE Stato IN ('bozza', 'In revisione')");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Inserisce una nuova competenza per un revisore ESG 
    public function inserisciCompetenza(
        string $usernameRevisore, 
        string $competenza, 
        int $livello
    ): void {

        // Prepariamo la chiamata alla Stored Procedure MySQL con 3 parametri    
        $stmt = $this->pdo->prepare("CALL InserimentoCompetenze(?,?,?)");

        // Eseguiamo la query passando l'array con le 3 variabili esattamente nell'ordine previsto
        $stmt->execute([
            $usernameRevisore, 
            $competenza, 
            $livello]);
    }

    // Inserisce una nuova nota testuale su una voce di bilancio
    public function inserisciNota(
        string $usernameRevisore, 
        string $nomeVoceContabile, 
        string $ragioneSociale, 
        string $dataCreazione, 
        string $dataNota, 
        string $testo
    ): void {
        
        // Prepariamo la chiamata alla Stored Procedure MySQL con 6 parametri
        $stmt = $this->pdo->prepare("CALL InserimentoAnnotazione(?, ?, ?, ?, ?, ?)");
        
        // Eseguiamo la query passando l'array con le 6 variabili esattamente nell'ordine previsto
        $stmt->execute([
            $usernameRevisore, 
            $nomeVoceContabile, 
            $ragioneSociale, 
            $dataCreazione, 
            $dataNota, 
            $testo
        ]);
    }

    // Registra il giudizio finale di un revisore
   public function salvaGiudizio(
        string $usernameRevisore, 
        string $ragioneSociale, 
        string $dataCreazione, 
        string $esito, 
        string $campoRilievi,
        string $dataGiudizio

    ): void {

        // Prepariamo la chiamata alla Stored Procedure MySQL con 6 parametri
        $stmt = $this->pdo->prepare("CALL InserimentoGiudizio(?, ?, ?, ?, ?, ?)");
        
        // Eseguiamo la query passando l'array con le 6 variabili esattamente nell'ordine previsto
        $stmt->execute([
            $usernameRevisore, 
            $ragioneSociale, 
            $dataCreazione, 
            $esito, 
            $campoRilievi,
            $dataGiudizio
        ]);
    }

    // METODI PER AZIENDA
    // Registra una nuova azienda collegata a un Responsabile Aziendale
    public function registraAzienda(
        string $usernameResponsabile, 
        string $ragioneSociale, 
        string $nome, 
        string $partitaIva, 
        string $settore, 
        int $numDipendenti, 
        string $logoPath
    ): void {
        
        // Prepariamo la chiamata alla Stored Procedure MySQL con 7 parametri
        $stmt = $this->pdo->prepare("CALL RegistraAzienda(?, ?, ?, ?, ?, ?, ?)");
        
        // Eseguiamo la query passando l'array nell'ordine corretto
        $stmt->execute([
            $usernameResponsabile, 
            $ragioneSociale, 
            $nome, 
            $partitaIva, 
            $settore, 
            $numDipendenti, 
            $logoPath
        ]);
    }

    // METODI PER BILANCIO
    // Recupera le aziende gestite da uno specifico responsabile
    public function getAziendeByResponsabile(string $usernameResponsabile): array {
        $stmt = $this->pdo->prepare("SELECT RagioneSociale, Nome FROM AZIENDA WHERE Username_Responsabile = ?");
        $stmt->execute([$usernameResponsabile]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Recupera tutte le voci del Template del bilancio
    public function getVociTemplate(): array {
        $stmt = $this->pdo->query("SELECT NomeVoceContabile, Descrizione FROM VOCE_CONTABILE");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Crea l'intestazione del bilancio
    public function creaBilancio(string $ragioneSociale, string $dataCreazione): void {
        $stmt = $this->pdo->prepare("CALL CreaBilancio(?, ?)");
        $stmt->execute([$ragioneSociale, $dataCreazione]);
    }

    // Popola le singole voci
    public function popolaVoceBilancio(string $NomeVoceBilancio, string $ragioneSociale, string $dataCreazione, float $valoreNumerico): void {
        $stmt = $this->pdo->prepare("CALL PopolaVoceBilancio(?, ?, ?, ?)");
        $stmt->execute([
            $NomeVoceBilancio, 
            $ragioneSociale, 
            $dataCreazione, 
            $valoreNumerico]);
    }

    // METODI PER INDICATORE ESG
    // Recupera tutti gli Indicatori ESG disponibili a sistema
    public function getIndicatoriESG(): array {
        $stmt = $this->pdo->query("SELECT NomeIndicatore FROM INDICATORE_ESG");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Recupera tutte le voci di bilancio associate alle aziende del Responsabile loggato
    public function getVociBilancioResponsabile(string $usernameResponsabile): array {
        $stmt = $this->pdo->prepare("
            SELECT NomeVoceBilancio, RagioneSociale, DataCreazione 
            FROM VOCE_BILANCIO 
            WHERE RagioneSociale IN (
                SELECT RagioneSociale FROM AZIENDA WHERE Username_Responsabile = ?
            )
        ");
        $stmt->execute([$usernameResponsabile]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Chiama la tua Stored Procedure per inserire i dati nella tabella MEMORIZZA
    public function inserisciValoreESG(string $nomeIndicatore, string $nomeVoce, string $ragioneSociale, string $dataCreazione, float $valore, string $fonte, string $dataRilevazione): void {
        $stmt = $this->pdo->prepare("CALL InserisciValoreESG(?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $nomeIndicatore, 
            $nomeVoce, 
            $ragioneSociale, 
            $dataCreazione, 
            $valore, 
            $fonte, 
            $dataRilevazione
        ]);
    }

    // METODI PER RESPONSABILE
    // Controlla se il responsabile ha già creato almeno un bilancio
    public function haAlmenoUnBilancio(string $usernameResponsabile): bool {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) 
            FROM BILANCIO 
            WHERE RagioneSociale IN (
                SELECT RagioneSociale FROM AZIENDA WHERE Username_Responsabile = ?
            )
        ");
        $stmt->execute([$usernameResponsabile]);
        $conteggio = $stmt->fetchColumn();
        
        return $conteggio > 0;
    }

    //METODI PER REVISORE
    // Controlla se il revisore ha almeno un bilancio assegnato da valutare
    public function haBilanciDaValutare(string $usernameRevisore): bool {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) 
            FROM VALUTA_GIUDIZIO 
            WHERE Username_Revisore = ? AND Esito IS NULL
        ");
        $stmt->execute([$usernameRevisore]);
        $conteggio = $stmt->fetchColumn();
        
        return $conteggio > 0;
    }
}
?>
