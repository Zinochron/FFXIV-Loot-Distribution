<?php
session_start();
$_SESSION['item_list'] = ['Waffe', 'Helm', 'Rüstung', 'Handschuhe', 'Hose', 'Schuhe', 'Ohrring', 'Halskette', 'Armreif', 'Ring', 'Diverses'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FFXIV Raid Log</title>
    <link rel="stylesheet" type="text/css" href="css/index.css"/>


    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">

    <script type="text/javascript" charset="utf8"
            src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

    <link rel="icon" type="image/x-icon" href="/images/favicon.ico">
</head>
<body>
<?php
require_once 'header.php';
?>


<div id="form_container" class="container">
    <h2>Login</h2>
    <form action="/_reg_user.php" method="post">
        <div class="form-group">
            <label for="username">Benutzername:</label>
            <input type="text" class="form-control" id="username" placeholder="Benutzername" name="username">
        </div>
        <div class="form-group">
            <label for="pwd">Passwort:</label>
            <input type="password" class="form-control" id="pwd" placeholder="Passwort" name="pwd">
        </div>
        <div class="form-group">
            <input type="password" class="form-control" id="rep_pwd" placeholder="Passwort wiederholen" name="rep_pwd">
        </div>
        <div class="form-group">
            <label for="ot_pwd">One-Time Passwort:</label>
            <input type="password" class="form-control" id="ot_pwd" placeholder="One Time Passwort" name="ot_pwd">
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
    </form>
</div>

</body>
</html>