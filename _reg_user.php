<?php
require_once __DIR__ . '/../cred/_db.php';
if(!empty($_POST['username']) && !empty($_POST['pwd']) && !empty($_POST['ot_pwd']) && !empty($_POST['rep_pwd'])) {

    $username = $_POST['username'];
    $password = $_POST['pwd'];
    $ot_password = $_POST['ot_pwd'];
    $rep_password = $_POST['rep_pwd'];

    $passwordHash = password_hash('7&8}hU,U~PeP-B6-', PASSWORD_DEFAULT);


    if($rep_password == $password && password_verify($ot_password, $passwordHash)){
        $pwd = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (user_id, user_name, user_password, user_rights) VALUES (0,?,?,0)";
        $stmt= $pdo->prepare($sql);
        $stmt->execute([$username, $pwd]);
    }
}
?>