<?php

class Session{
    private $dataArray;

    public function __construct($data){
        $this->dataArray = $data;
        $this->sid = $data['sid'];
        $this->uptime = $data['uptime'];
        $this->alpha_range = $data['alpha_range'];
        $this->stickers = $data['stickers'];
        $this->is_media = $data['is_media'];
        $this->media_type = $data['media_type'];
        $this->media_caption = $data['media_caption'];
        $this->file_id = $data['file_id'];
    }

}