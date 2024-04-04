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

    public function getMediaItem($media_id): ?MediaItem {
        $sql = 'SELECT * FROM MediaItems WHERE media_id = :media_id';
        $data = [
            'media_id' => $media_id
        ];
        try {
            $STH = $this->DBH->prepare($sql);
            $STH->execute($data);
            $STH->setFetchMode(\PDO::FETCH_ASSOC);
            $row = $STH->fetch();
            if ($row) {
                return new MediaItem($row);
            }
            return null;
        } catch (\PDOException $e) {
            file_put_contents(__DIR__ . '/../logs/PDOErrors.txt', 'MediaItemDatabaseOps->getMediaItem() - ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
            return null;
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

    public function deleteMediaItem($data): bool {
        $this->DBH->beginTransaction();
        $sql = 'DELETE FROM Likes WHERE media_id = :media_id';
        try {
            $STH = $this->DBH->prepare($sql);
            $STH->execute($data);
        } catch (\PDOException $e) {
            file_put_contents(__DIR__ . '/../logs/PDOErrors.txt', 'MediaItemDatabaseOps->deleteMediaItem() - ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
            $this->DBH->rollBack();
            return false;
        } 

        $sql = 'DELETE FROM Comments WHERE media_id = :media_id';
        try {
            $STH = $this->DBH->prepare($sql);
            $STH->execute($data);
        } catch (\PDOException $e) {
            file_put_contents(__DIR__ . '/../logs/PDOErrors.txt', 'MediaItemDatabaseOps->deleteMediaItem() - ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
            $this->DBH->rollBack();
            return false;
        }

        $sql = 'DELETE FROM Ratings WHERE media_id = :media_id';

        try {
            $STH = $this->DBH->prepare($sql);
            $STH->execute($data);
        } catch (\PDOException $e) {
            file_put_contents(__DIR__ . '/../logs/PDOErrors.txt', 'MediaItemDatabaseOps->deleteMediaItem() - ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
            $this->DBH->rollBack();
            return false;
        }

        $sql = 'DELETE FROM MediaItemTags WHERE media_id = :media_id';

        try {
            $STH = $this->DBH->prepare($sql);
            $STH->execute($data);
        } catch (\PDOException $e) {
            file_put_contents(__DIR__ . '/../logs/PDOErrors.txt', 'MediaItemDatabaseOps->deleteMediaItem() - ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
            $this->DBH->rollBack();
            return false;
        }

        $sql = 'SELECT filename FROM MediaItems WHERE media_id = :media_id AND user_id = :user_id';

        try {
            $STH = $this->DBH->prepare($sql);
            $STH->execute($data);
            $STH->setFetchMode(\PDO::FETCH_ASSOC);
            $row = $STH->fetch();
            if ($row) {
                $filename = $row['filename'];
                unlink(__DIR__ . '/../uploads/' . $filename);
            }
        } catch (\PDOException $e) {
            file_put_contents(__DIR__ . '/../logs/PDOErrors.txt', 'MediaItemDatabaseOps->deleteMediaItem() - ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
            $this->DBH->rollBack();
            return false;
        }

        $sql = 'DELETE FROM MediaItems WHERE media_id = :media_id AND user_id = :user_id';

        try {
            $STH = $this->DBH->prepare($sql);
            $STH->execute($data);
            if($STH->rowCount() > 0) {
                return false;
            }
            $this->DBH->commit();
            unlink(__DIR__ . '/../uploads/' . $filename);
            return true;
        } catch (\PDOException $e) {
            file_put_contents(__DIR__ . '/../logs/PDOErrors.txt', 'MediaItemDatabaseOps->deleteMediaItem() - ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
            $this->DBH->rollBack();
            return false;
        }

    }
}

?>