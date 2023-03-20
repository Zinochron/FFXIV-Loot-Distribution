<?php
session_start();
require_once __DIR__ . '/../cred/_db.php';
$message = "0";
if(!empty($_POST['username']) && !empty($_POST['pwd'])){

    $username = $_POST['username'];
    $password = $_POST['pwd'];
    if(isset($_POST['remember'])){
        $remember = $_POST['remember'];
    }else{
        $remember = FALSE;
    }

    $stmt = $pdo->prepare("SELECT user_id, user_name, user_password, user_rights FROM users WHERE user_name=?");
    $stmt->execute([$username]);
    $row = $stmt->fetch();


    if(password_verify($password, $row['user_password'])){

        $_SESSION['user_rights'] = $row['user_rights'];

        if(!empty($_POST["remember"])) {
            setcookie ("user_login_ff14",$row["user_name"],time()+ (10 * 365 * 24 * 60 * 60));
        }
        $message = "1";
    }else{
        $message = "2";
    }
}else{
    $message = "2";
}
echo $message;