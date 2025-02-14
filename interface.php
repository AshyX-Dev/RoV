<?php

class Session{
    private $dataArray;
    public $sid;
    public $uptime;
    public $locks;
    public $mutes;
    public $alpha_range;
    public $stickers;
    public $is_media;
    public $media_type;
    public $media_caption;
    public $file_id;

    public function __construct($data){
        $this->dataArray = $data;
        if (!($this->dataArray === null) || !is_array($this->dataArray) || count($this->dataArray) < 1){
            $this->sid = $data['sid'];
            $this->uptime = $data['uptime'];
            $this->locks = json_decode($data['locks']);
            $this->mutes = json_decode($data['mutes']);
            $this->alpha_range = $data['alpha_range'];
            $this->stickers = json_decode($data['stickers']);
            $this->is_media = $data['is_media'];
            $this->media_type = $data['media_type'];
            $this->media_caption = $data['media_caption'];
            $this->file_id = $data['file_id'];
        }else{
            $this->sid = null;
            $this->uptime = null;
            $this->locks = null;
            $this->mutes = null;
            $this->alpha_range = null;
            $this->stickers = null;
            $this->is_media = null;
            $this->media_type = null;
            $this->media_caption = null;
            $this->file_id = null;
        }
    }

    public function createDumpObject(){
        if (!($this->dataArray === null)){
            return var_dump($this->dataArray);
        }else{
            return var_dump([]);
        }
    }

}