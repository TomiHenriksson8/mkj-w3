<?php
global $DBH;
require_once __DIR__ . '/../db/dbConnect.php';

if (isset($_GET['id'])) {
    $sql = 'SELECT * FROM MediaItems WHERE media_id = :media_id';

    $data = [
        'media_id' => $_GET['id']
    ];

    try {
        $STH = $DBH->prepare($sql);
        $STH->execute($data);
        $STH->setFetchMode(PDO::FETCH_ASSOC);
        $row = $STH->fetch();
    } catch (PDOException $e) {
        echo "Could not get data from the database." . $e->getMessage();
        file_put_contents('PDOErrors.txt', 'modifyForm.php - ' . $e->getMessage(), FILE_APPEND);
        exit;
    }

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