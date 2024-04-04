<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="js/main.js" defer></script>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
<?php
if (isset($_GET['success'])):
    ?>
    <dialog id="success-modal">
        <p><a href="#" class="close-modal">Close</a></p>
        <p><?php echo $_GET['success']; ?></p>
    </dialog>
<?php
endif;
?>
<div class="container">
    <header>
        <h1>Media Library</h1>
        <nav>
            <ul>
                <?php
                if (isset($_SESSION['user'])):
                    ?>
                    <li><a href="home.php">Home</a></li>
                    <li><a href="operations/logout.php">Logout</a></li>
                <?php
                else:
                    ?>
                    <li><a href="index.php">Login</a></li>
                <?php
                endif;
                ?>
            </ul>
    </header>