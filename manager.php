<?php

include_once("interface.php");

class Manager{
    private $db;
    private $cur;

    public function __construct(?string $database_path){
        $this->db = $database_path;
        $this->cur = new SQLite3($this->db);
        $this->setupDbs();
    }

    private function setupDbs(): void {
        $this->cur->exec("CREATE TABLE IF NOT EXISTS session (
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

        $result = $this->cur->query("SELECT * FROM session");
        while ($row = $result->fetchArray(SQLITE3_ASSOC)){
            if ($row['sid'] === 1) break;
            else {
                $statement = $this->cur->prepare("INSERT INTO session (
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
                
                $statement->bindValue(":sid", 1, SQLITE3_INTEGER);
                $statement->bindValue(":locks", "[]");
                $statement->bindValue(":alpha_range", 10, SQLITE3_INTEGER);
                $statement->bindValue(":stickers", "[]");
                $statement->bindValue(":is_media", 0, SQLITE3_INTEGER);
                $statement->bindValue(":media_type", "");
                $statement->bindValue(":media_caption", "");
                $statement->bindValue(":file_id", "");
                $statement->execute();
            }
        }
    }

    public function getFirstSession(): ?array {
        $result = $this->cur->query("SELECT * FROM session LIMIT 1");
        return $result->fetchArray(SQLITE3_ASSOC);
    }    

}

$mng = new Manager("./dbdbdb.sqlite3");
$firstSession = $mng->getFirstSession();
print_r($firstSession);
