<?php

include_once("interface.php");

class Manager{
    private $database_path;
    private $pdo;

    public function __construct(string $database){
        $this->database_path = $database;
        $this->pdo = new PDO("sqlite:rov.sqlite3");
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->setupDatabase();
    }

    private function setupDatabase(){
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS session ( sid INTEGER PRIMARY KEY, uptime TEXT, locks TEXT, alpha_range INTEGER, stickers TEXT, is_media INTEGER, media_type TEXT, media_caption TEXT, file_id TEXT ) ");
        $result = $this->see();
        if (count(array_keys($result)) === 0){
            $sid = 1;
            $null_string = "";
            $null_array_string = "[]";
            $null_int = 0;

            $stmt = $this->pdo->prepare("INSERT INTO session ( sid, uptime, locks, alpha_range, stickers, is_media, media_type, media_caption, file_id ) VALUES ( :sid, :uptime, :locks, :alpha_range, :stickers, :is_media, :media_type, :media_caption, :file_id )");
            $stmt->bindParam(":sid", $sid);
            $stmt->bindParam(":uptime", $null_string);
            $stmt->bindParam(":locks", $null_array_string);
            $stmt->bindParam(":alpha_range", $null_int);
            $stmt->bindParam(":stickers", $null_array_string);
            $stmt->bindParam(":is_media", $null_int);
            $stmt->bindParam(":media_type", $null_string);
            $stmt->bindParam(":media_caption", $null_string);
            $stmt->bindParam(":file_id", $null_string);
            $stmt->execute();
        }
    }

    private function see(){
        $stmt = $this->pdo->query("SELECT * FROM session");
        $sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $sessions;
    }

    public function getSession(): Session {
        $session = $this->see()[0];
        return new Session($session);
    }

}


$mng = new Manager("dndnd");
echo $mng->getSession()->createDumpObject();

?>