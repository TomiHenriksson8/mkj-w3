<?php 

namespace MediaProject;

require_once 'MediaItem.class.php';

class MediaItemDatabaseOps {
    private \PDO $DBH;

    public function __construct($DBH) {
        $this->DBH = $DBH;
    }

    public function getMediaItems(): array {
        $mediaItems = [];
        $sql = 'SELECT * FROM MediaItems;';
        try {
        $STH = $this->DBH->query($sql);
        $STH->setFetchMode(\PDO::FETCH_ASSOC);
        
        while ($row = $STH->fetch()) {
            $mediaItems[] = new MediaItem($row);
        }
        return $mediaItems;  
    } catch (\PDOException $e) {
        file_put_contents(__DIR__ . '/../logs/PDOErrors.txt', 'MediaItemDatabaseOps+>getMediaItems() - ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
        return [];
    }  
    
    }
    
    public function insertMediaItem($data): bool {
        $sql = 'INSERT INTO MediaItems (user_id, filename, filesize, media_type, title, description)
                VALUES (:user_id, :filename, :filesize, :media_type, :title, :description)';
        try {
            $STH = $this->DBH->prepare($sql);
            $STH->execute($data);
            return true;
        } catch (\PDOException $e) {
            file_put_contents(__DIR__ . '/../logs/PDOErrors.txt', 'MediaItemDatabaseOps->insertMediaItem() - ' . $e->getMessage() . PHP_EOL, FILE_APPEND); 
            return false;
        }
    }

    public function updateMediaItem($data): bool {
        $sql = 'UPDATE MediaItems SET title = :title, description = :description WHERE media_id = :media_id AND user_id = :user_id';
        try {
            $STH = $this->DBH->prepare($sql);
            $STH->execute($data);
            if ($STH->rowCount() > 0) {
                return false;
            }
            return true;
        } catch (\PDOException $e) {
            file_put_contents(__DIR__ . '/../logs/PDOErrors.txt', 'MediaItemDatabaseOps->updateMediaItem() - ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
            return false;
        }
    }
}

?>