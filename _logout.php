<?php
if (isset($_COOKIE['user_login_ff14'])) {
    setcookie("user_login_ff14", "", time()-3600);
}

session_start();
unset($_SESSION['user_rights']);
session_destroy();
?>
