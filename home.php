<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/inc/header.php';
?>
<section>
    <form action="operations/insertData.php" method="post" enctype="multipart/form-data">
        <div>
            <label for="title">Title</label>
            <input type="text" name="title" id="title">
        </div>
        <div>
            <label for="description">Description</label>
            <textarea name="description" id="description"></textarea>
        </div>
        <div>
            <label for="file">File</label>
            <input type="file" name="file" id="file">
        </div>
        <div>
            <input type="submit" value="Save">
        </div>
    </form>
</section>
<section>
    <table>
        <thead>
            <tr>
                <th>media_id</th>
                <th>user_id</th>
                <th>filename</th>
                <th>filesize</th>
                <th>media_type</th>
                <th>title</th>
                <th>description</th>
                <th>created_at</th>
                <th>Options</th>
            </tr>
        </thead>
        <tbody>
            <?php require_once __DIR__ . '/operations/selectData.php'; ?>
        </tbody>
    </table>
</section>
<dialog id="modify-modal">
    <p><a href="#" class="close-modal">Close</a></p>
    <div id="modify-content"></div>
</dialog>
<?php
require_once __DIR__ . '/inc/footer.php';