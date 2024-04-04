<?php
global $DBH;
require_once __DIR__ . '/../db/dbConnect.php';

require_once __DIR__ . '/../MediaProject/MediaItemDatabaseOps.class.php';

$mediaItemDatabaseOps = new MediaProject\MediaItemDatabaseOps($DBH);

if (isset($_GET['id'])) {

    $data = [
        'media_id' => $_GET['id']
    ];

    $mediaItem = $mediaItemDatabaseOps->getMediaItem($data);
    if (!$mediaItem) {
        exit;
    }
    $row = $mediaItem->getMediaItem();

}

?>

<form action="operations/modifyData.php" method="post">
    <input type="hidden" name="media_id" value="<?php echo $row['media_id']; ?>">
    <div>
        <label for="title">Title</label>
        <input type="text" name="title" id="title" value="<?php echo $row['title']; ?>">
    </div>
    <div>
        <label for="description">Description</label>
        <textarea name="description" id="description"><?php echo $row['description']; ?></textarea>
    </div>
    <div>
        <input type="submit" value="Save">
    </div>
</form>