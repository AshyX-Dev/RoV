<?php

include_once("interface.php");


class Manager{
    private $database_path;
    private $pdo;

    public function __construct(string $database){
        $this->database_path = $database;
        $this->pdo = new PDO("sqlite:rov.sqlite3");
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->sdbs();
    }

    private function sdbs(){
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS session ( sid INTEGER PRIMARY KEY, uptime TEXT ) ");
    }

    public function see(){
        $result = $this->pdo->query("SELECT * FROM session");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $users;
    }

}


$mng = new Manager("dndnd");
echo var_dump($mng->see());
// echo $mng->see();

?>