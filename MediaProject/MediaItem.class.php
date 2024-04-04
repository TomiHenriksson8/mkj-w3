<?php 

namespace MediaProject;

use DateTime;

class  MediaItem {
    private int $media_id;
    private int $user_id;
    private string $filename;
    private int $filesize;
    private string $media_type;
    private string $title;
    private string $description;
    private \DateTime $created_at;


    public function __construct($data) {
        $this->media_id = $data['media_id'];
        $this->user_id = $data['user_id'];
        $this->filename = $data['filename'];
        $this->filesize = $data['filesize'];
        $this->media_type = $data['media_type'];
        $this->title = $data['title'];
        $this->description = $data['description'];
        $this->created_at = new \DateTime($data['created_at']);
    }

    public function getMediaItem(): array {
        return [
            'media_id' => $this->media_id,
            'user_id' => $this->user_id,
            'filename' => $this->filename,
            'filesize' => $this->filesize,
            'media_type' => $this->media_type,
            'title' => $this->title,
            'description' => $this->description,
            'created_at' => $this->created_at->format('Y-m-d H:i:s')
        ];

    }

}



?>