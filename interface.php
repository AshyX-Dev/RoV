<?php

class Session{
    private $dataArray;

    public function __construct($data){
        $this->dataArray = $data;
        if (!($this->dataArray === null) || !is_array($this->dataArray) || count($this->dataArray) < 1){
            $this->sid = $data['sid'];
            $this->uptime = $data['uptime'];
            $this->alpha_range = $data['alpha_range'];
            $this->stickers = $data['stickers'];
            $this->is_media = $data['is_media'];
            $this->media_type = $data['media_type'];
            $this->media_caption = $data['media_caption'];
            $this->file_id = $data['file_id'];
        }else{
            $this->sid = null;
            $this->uptime = null;
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