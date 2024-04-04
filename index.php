<?php
session_start();
require_once __DIR__ . '/inc/header.php';
?>
<section>
    <h2>Login</h2>
    <form action="operations/login.php" method="post">
        <div>
            <label for="username">Username</label>
            <input type="text" name="username" id="username">
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" name="password" id="password">
        </div>
        <div>
            <input type="submit" value="Login">
        </div>
    </form>
</section>
<section>
    <h2>Register</h2>
    <form action="operations/register.php" method="post">
        <div>
            <label for="username">Username</label>
            <input type="text" name="username" id="username">
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" name="password" id="password">
        </div>
        <div>
            <label for="email">Email</label>
            <input type="email" name="email" id="email">
        </div>
        <div>
            <input type="submit" value="Register">
        </div>
</section>
<?php
require_once __DIR__ . '/inc/footer.php';
?>