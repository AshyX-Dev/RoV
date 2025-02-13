<?php

include_once("interface.php");

class Manager{
    private $db;
    private $pdo;

    public function __construct(?string $database_path){
        $this->db = $database_path;
        $this->pdo = new PDO("sqlite:rov.sqlite3");
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->setupDbs();
    }

    private function setupDbs(): void {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS session (
            sid INTEGER PRIMARY KEY,
            uptime TEXT,
            locks TEXT,
            alpha_range TEXT,
            stickers TEXT,
            is_media INTEGER,
            media_type TEXT,
            media_caption TEXT,
            file_id TEXT
        ) ");

        $result = $this->pdo->query("SELECT * FROM session");
        $sessions = $result->fetchAll(PDO::FETCH_ASSOC);
        foreach ($sessions as $row){
            if ($row['sid'] === 1) break;
            else {
                $statement = $this->pdo->prepare("INSERT INTO session (
                    sid,
                    locks,
                    alpha_range,
                    stickers,
                    is_media,
                    media_type,
                    media_caption,
                    file_id
                ) VALUES (
                    :sid,
                    :locks,
                    :alpha_range,
                    :stickers,
                    :is_media,
                    :media_type,
                    :media_caption,
                    :file_id
                )");
                
                $null_data = "";
                $null_array = "[]";
                $sid = 1;
                $range = 10;
                $is_media = 0;

                $statement->bindParam(":sid", $sid);
                $statement->bindParam(":locks", $null_array);
                $statement->bindParam(":alpha_range", $range);
                $statement->bindParam(":stickers", $null_array);
                $statement->bindParam(":is_media", $is_media);
                $statement->bindParam(":media_type", $null_data);
                $statement->bindParam(":media_caption", $null_data);
                $statement->bindParam(":file_id", $null_data);
                $statement->execute();
                break;
            }
        }

        echo "[RoV] database passed \n";

    }

    public function getSession(): ?Session {
        $result = $this->pdo->query("SELECT * FROM session");
        return new Session($result->fetchAll()[0]);
    }    

}

$mng = new Manager("./dbdbdb.sqlite3");
$ss = $mng->getSession();
echo $ss->createDumpObject();