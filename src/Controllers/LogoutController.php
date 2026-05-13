<?php 
namespace Controllers;

class LogoutController {
    private $dbModel;

    public function __construct($dbModel){
        $this->dbModel = $dbModel;
    }

    public function index() {
        $this->dbModel->logout();
        header("Location: /Login");
        exit(); 
    }
}
?>