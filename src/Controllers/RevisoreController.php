<?php
namespace Controllers;

class RevisoreController {
    private $dbModel;

    public function __construct($dbModel){
        $this->dbModel = $dbModel;
        
        // DA SISTEMARE 
        /* 
        // Controllo utente loggato e tipo utente
        if (
            !$this->dbModel->isLoggedIn() || 
            $_SESSION["tipo_utente"] !== "revisore"
        ) {
            header("Location: /auth/login");
            exit;
        }
*/
    }
    // Dashboard revisore
    public function index() {

        $username = $_SESSION['username'];

        $sbloccaValutazioni = 
            $this->dbModel->haBilanciDaValutare($username);

        include BASE_PATH . '/Views/Revisore/RevisoreView.php';
    }

    // Gestione competenze
    public function competenze() {

        $data = [
            'messaggio_successo' => null,
            'messaggio_errore' => null
        ];

        if (
            $_SERVER["REQUEST_METHOD"] == "POST" &&
            isset($_POST['salva_competenza'])
        ) {

            $username = $_SESSION["username"];
            $competenza = $_POST['nome_competenza'];
            $livello = $_POST['livello'];

            if ($livello < 0 || $livello > 5) {

                $data['messaggio_errore'] =
                    "Livello non valido (0-5)";

            } else {

                try {

                    $this->dbModel->inserisciCompetenza(
                        $username,
                        $competenza,
                        $livello
                    );

                    $data['messaggio_successo'] =
                        "Competenza salvata con successo!";

                } catch (\PDOException $e) {

                    if ($e->getCode() == 23000) {

                        $data['messaggio_errore'] =
                            "Competenza già assegnata.";

                    } else {

                        $data['messaggio_errore'] =
                            "Errore DB: " . $e->getMessage();
                    }
                }
            }
        }

        include BASE_PATH . '/Views/Revisore/CompetenceView.php';
    }

    // Gestione note
    public function note() {

        $username = $_SESSION['username'];

        $data = [
            'messaggio_successo' => null,
            'messaggio_errore' => null,
            'bilanci_assegnati' => [],
            'voci_contabili' => []
        ];

        if (
            $_SERVER["REQUEST_METHOD"] == "POST" &&
            isset($_POST['salva_nota'])
        ) {

            $nomeVoce = $_POST['nome_voce'];

            $bilancioSelezionato =
                $_POST['bilancio_selezionato'] ?? '';

            $parts = explode('|', $bilancioSelezionato);

            $ragioneSociale = $parts[0] ?? '';
            $dataCreazione = $parts[1] ?? '';

            $dataNota = $_POST['data_nota'];
            $testo = $_POST['testo'];

            try {

                $this->dbModel->inserisciNota(
                    $username,
                    $nomeVoce,
                    $ragioneSociale,
                    $dataCreazione,
                    $dataNota,
                    $testo
                );

                $data['messaggio_successo'] =
                    "Nota aggiunta con successo!";

            } catch (\PDOException $e) {

                if ($e->getCode() == 23000) {

                    $data['messaggio_errore'] =
                        "Nota già inserita.";

                } else {

                    $data['messaggio_errore'] =
                        "Errore database: " . $e->getMessage();
                }
            }
        }

        try {

            $data['bilanci_assegnati'] =
                $this->dbModel->getBilanciRevisore($username);

            $data['voci_contabili'] =
                $this->dbModel->getVociContabili();

        } catch (\PDOException $e) {

            $data['messaggio_errore'] =
                "Errore caricamento dati: " . $e->getMessage();
        }

        include BASE_PATH . '/Views/Revisore/NoteView.php';
    }

    // Gestione giudizi
    public function giudizio() {

        $usernameRevisore = $_SESSION['username'];

        $data = [
            'messaggio_successo' => null,
            'messaggio_errore' => null,
            'bilanci_assegnati' => []
        ];

        if (
            $_SERVER["REQUEST_METHOD"] == "POST" &&
            isset($_POST['salva_giudizio'])
        ) {

            $esito = $_POST['esito'];
            $dataGiudizio = $_POST['data_giudizio'];
            $campoRilievi = trim($_POST['campo_rilievi']);

            $parts = explode('|', $_POST['bilancio_selezionato']);

            $ragioneSociale = $parts[0] ?? '';
            $dataCreazione = $parts[1] ?? '';

            $esiti_validi = [
                'approvazione',
                'approvazione con rilievi',
                'respingimento'
            ];

            if (!in_array($esito, $esiti_validi)) {

                $data['messaggio_errore'] =
                    "Esito non valido.";

            } else {

                try {

                    $this->dbModel->salvaGiudizio(
                        $usernameRevisore,
                        $ragioneSociale,
                        $dataCreazione,
                        $esito,
                        $dataGiudizio,
                        $campoRilievi
                    );

                    $data['messaggio_successo'] =
                        "Giudizio salvato con successo!";

                } catch (\PDOException $e) {

                    $data['messaggio_errore'] =
                        "Errore DB: " . $e->getMessage();
                }
            }
        }

        $data['bilanci_assegnati'] =
            $this->dbModel->getBilanciRevisore($usernameRevisore);

        include BASE_PATH . '/Views/Revisore/GiudizioView.php';
    }
}
?>