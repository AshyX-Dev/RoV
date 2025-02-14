<?php

date_default_timezone_set("Asia/Tehran");

include_once("interface.php");

class Manager{
    private $database_path;
    private $pdo;
    private $sid;
    private $booleanTrue;
    private $booleanFalse;

    public function __construct(string $database){
        $this->database_path = $database;
        $this->pdo = new PDO("sqlite:rov.sqlite3");
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->sid = 1;
        $this->booleanTrue = true;
        $this->booleanFalse = false;
        $this->setupDatabase();
    }

    private function setupDatabase(){
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS session ( sid INTEGER PRIMARY KEY, uptime TEXT, locks TEXT, alpha_range INTEGER, stickers TEXT, is_media INTEGER, media_type TEXT, media_caption TEXT, file_id TEXT, mutes TEXT ) ");
        $result = $this->see();
        if (count(array_keys($result)) === 0){
            $sid = 1;
            $null_string = "";
            $null_array_string = "[]";
            $null_int = 0;
            $uptime = date("D M d H:i:s Y", time());

            $stmt = $this->pdo->prepare("INSERT INTO session ( sid, uptime, locks, alpha_range, stickers, is_media, media_type, media_caption, file_id, mutes ) VALUES ( :sid, :uptime, :locks, :alpha_range, :stickers, :is_media, :media_type, :media_caption, :file_id, :mutes )");
            $stmt->bindParam(":sid", $sid);
            $stmt->bindParam(":uptime", $uptime);
            $stmt->bindParam(":locks", $null_array_string);
            $stmt->bindParam(":alpha_range", $null_int);
            $stmt->bindParam(":stickers", $null_array_string);
            $stmt->bindParam(":is_media", $null_int);
            $stmt->bindParam(":media_type", $null_string);
            $stmt->bindParam(":media_caption", $null_string);
            $stmt->bindParam(":file_id", $null_string);
            $stmt->bindParam(":mutes", $null_array_string);
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

    public function getUptime(): mixed {
        $session = $this->getSession();
        return $session->uptime;
    }

    public function getLocks(): mixed {
        $session = $this->getSession();
        return $session->locks;
    }

    public function getAlphaRange(): mixed {
        $session = $this->getSession();
        return $session->alpha_range;
    }

    public function getStickers(): mixed {
        $session = $this->getSession();
        return $session->stickers;
    }

    public function isMedia(): bool {
        $session = $this->getSession();
        if ($session->is_media == 1){
            return true;
        }else{
            return false;
        }
    }

    public function getMediaType(): mixed {
        $session = $this->getSession();
        return $session->media_type;
    }

    public function getMediaCaption(): mixed {
        $session = $this->getSession();
        return $session->media_caption;
    }

    public function getFileId(): mixed {
        $session = $this->getSession();
        return $session->file_id;
    }

    public function getMutes(): mixed {
        $session = $this->getSession();
        return $session->mutes;
    }

    public function addLock(int $userid): array {
        $locks = $this->getLocks();
        if ($locks !== null && !isset($locks[$userid])){
            array_push($locks, $userid);
            $enc_locks = json_encode($locks);

            $stmt = $this->pdo->prepare("UPDATE session SET locks = :locks WHERE sid = :sid");
            $stmt->bindParam(":locks", $enc_locks);
            $stmt->bindParam(":sid", $this->sid);
            $stmt->execute();

            return [
                "status" => "OK"
            ];
        }else{
            return [
                "status" => "LOCKS_ERROR",
                "message" => "list is null or user already in locks"
            ];
        }
    }

    public function removeLock(int $userid): array {
        $locks = $this->getLocks();
        if ($locks !== null && isset($locks[$userid])){
            unset($locks[$userid]);
            $enc_locks = json_encode($locks);
            
            $stmt = $this->pdo->prepare("UPDATE session SET locks = :locks WHERE sid = :sid");
            $stmt->bindParam(":locks", $enc_locks);
            $stmt->bindParam(":sid", $this->sid);
            $stmt->execute();

            return [
                "status" => "OK"
            ];
        }else{
            return [
                "status" => "LOCKS_ERROR",
                "message" => "list is null or user not in locks yet"
            ];
        }
    }

    public function clearLocks(): array {
        $null_list = "[]";
        $stmt = $this->pdo->prepare("UPDATE session SET locks = :locks WHERE sid = :sid");
        $stmt->bindParam(":locks", $null_list);
        $stmt->bindParam(":sid", $this->sid);
        $stmt->execute();
        return [
            "status" => "OK"
        ];
    }

    public function setAlphaRange(int $range): array {
        $pre_range = $this->getAlphaRange();
        $stmt = $this->pdo->prepare("UPDATE session SET alpha_range = :alpha_range WHERE sid = :sid");
        $stmt->bindParam(":alpha_range", $range);
        $stmt->bindParam(":sid", $this->sid);
        $stmt->execute();
        return [
            "status" => "OK",
            "pre_range" => $pre_range,
            "now_range" => $range
        ];

    }

    public function setStickerPackFileId(array $ids): array {
        $enc_ids = json_encode($ids);
        $stmt = $this->pdo->prepare("UPDATE session SET stickers = :stickers WHERE sid = :sid");
        $stmt->bindParam(":stickers", $enc_ids);
        $stmt->bindParam(":sid", $this->sid);
        $stmt->execute();
        return [
            "status" => "OK"
        ];
    }

    public function clearStickerPack(): array {
        $null_list = "[]";
        $stmt = $this->pdo->prepare("UPDATE session SET stickers = :stickers WHERE sid = :sid");
        $stmt->bindParam(":stickers", $null_list);
        $stmt->bindParam(":sid", $this->sid);
        $stmt->execute();
        return [
            "status" => "OK"
        ];
    }

    public function setMediaActivition(bool $status): array {
        $stmt = $this->pdo->prepare("UPDATE session SET is_media = :is_media WHERE sid = :sid");
        if ($status === true) { $stmt->bindParam(":is_media", (int)$this->booleanTrue); }
        else { $stmt->bindParam(":is_media", (int)$this->booleanFalse); }
        $stmt->bindParam(":sid", $this->sid);
        $stmt->execute();
        return [
            "status" => "OK"
        ];
    }

    public function setMediaType(string $media_type): array {
        $pre_type = $this->getMediaType();
        $stmt = $this->pdo->prepare("UPDATE session SET media_type = :media_type WHERE sid = :sid");
        $stmt->bindParam(":media_type", $media_type);
        $stmt->bindParam(":sid", $this->sid);
        $stmt->execute();
        return [
            "status" => "OK",
            "from" => $pre_type,
            "into" => $media_type
        ];
    }

    public function setMediaCaption(string $media_caption): array {
        $stmt = $this->pdo->prepare("UPDATE session SET media_caption = :media_caption WHERE sid = :sid");
        $stmt->bindParam(":media_caption", $media_caption);
        $stmt->bindParam(":sid", $this->sid);
        $stmt->execute();
        return [
            "status" => "OK"
        ];
    }

    public function setFileId(string $file_id): array {
        $stmt = $this->pdo->prepare("UPDATE session SET file_id = :file_id WHERE sid = :sid");
        $stmt->bindParam(":file_id", $file_id);
        $stmt->bindParam(":sid", $this->sid);
        $stmt->execute();
        return [
            "status" => "OK"
        ];
    }

    public function clearMediaCaption(): array {
        $null_string = "";
        $stmt = $this->pdo->prepare("UPDATE session SET media_caption = :media_caption WHERE sid = :sid");
        $stmt->bindParam(":media_caption", $null_string);
        $stmt->bindParam(":sid", $this->sid);
        $stmt->execute();
        return [
            "status" => "OK"
        ];
    }

    public function clearMediaType(): array {
        $null_string = "";
        $stmt = $this->pdo->prepare("UPDATE session SET media_type = :media_type WHERE sid = :sid");
        $stmt->bindParam(":media_type", $null_string);
        $stmt->bindParam(":sid", $this->sid);
        $stmt->execute();
        return [
            "status" => "OK"
        ];
    }

    public function addMute(int $userid): array {
        $mutes = $this->getMutes();
        if ($mutes !== null && !isset($mutes[$userid])){
            array_push($mutes, $userid);
            $enc_mutes = json_encode($mutes);

            $stmt = $this->pdo->prepare("UPDATE session SET mutes = :mutes WHERE sid = :sid");
            $stmt->bindParam(":mutes", $enc_mutes);
            $stmt->bindParam(":sid", $this->sid);
            $stmt->execute();

            return [
                "status" => "OK"
            ];
        }else{
            return [
                "status" => "MUTES_ERROR",
                "message" => "list is null or user already in mutes"
            ];
        }
    }

    public function removeMutes(int $userid): array {
        $mutes = $this->getMutes();
        if ($mutes !== null && isset($mutes[$userid])){
            unset($mutes[$userid]);
            $enc_mutes = json_encode($mutes);
            
            $stmt = $this->pdo->prepare("UPDATE session SET mutes = :mutes WHERE sid = :sid");
            $stmt->bindParam(":mutes", $enc_mutes);
            $stmt->bindParam(":sid", $this->sid);
            $stmt->execute();

            return [
                "status" => "OK"
            ];
        }else{
            return [
                "status" => "LOCKS_ERROR",
                "message" => "list is null or user not in mutes yet"
            ];
        }
    }

    public function clearMutes(): array {
        $null_list = "[]";
        $stmt = $this->pdo->prepare("UPDATE session SET mutes = :mutes WHERE sid = :sid");
        $stmt->bindParam(":mutes", $null_list);
        $stmt->bindParam(":sid", $this->sid);
        $stmt->execute();
        return [
            "status" => "OK"
        ];
    }

}


// $mng = new Manager("dndnd");
// echo $mng->getSession()->createDumpObject();

?>