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
        $result = $this->see();
        if (count(array_keys($result)) === 0){
            $stmt = $this->pdo->prepare("INSERT INTO session ( sid, uptime ) VALUES ( :sid, :uptime )");
            $sid = 1;
            $uptime = "";
            $stmt->bindParam(":sid", $sid);
            $stmt->bindParam(":uptime", $uptime);
            $stmt->execute();
        }
    }

    public function see(){
        $stmt = $this->pdo->query("SELECT * FROM session");
        $sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $sessions;
    }

}


$mng = new Manager("dndnd");
echo var_dump($mng->see());
// echo $mng->see();

?>