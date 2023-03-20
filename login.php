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
    <form id="login_form" action="/_login.php" method="post">
        <div class="form-group">
            <label for="username">Benutzername:</label>
            <input type="text" class="form-control" id="username" placeholder="Benutzername" name="username">
        </div>
        <div class="form-group">
            <label for="pwd">Passwort:</label>
            <input type="password" class="form-control" id="pwd" placeholder="Passwort" name="pwd">
        </div>
        <div class="checkbox">
            <label><input type="checkbox" name="remember"> Eingeloggt bleiben (Benötigt Cookies)</label>
        </div>
        <button id="submit_login" type="submit" class="btn btn-default" data-toggle="modal" data-target="#login_modal">Submit</button>
    </form>
</div>

<div class="modal fade" id="login_modal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Login Status</h4>
            </div>
            <div class="modal-body">
                <p id="login_answer"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

</body>
<script>
    $('#submit_login').click(function (){
        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: '_login.php',
            data: $('#login_form').serialize(),
            success: function (response) {
                //location.reload();
                if(response == 1){
                    $('#login_answer').text('Login erfolgreich! Weiterleitung in 3 Sekunden.');
                    setTimeout(function(){
                        //do what you need here
                        location.href = '/';
                    }, 3000);
                }else{
                    $('#login_answer').text('Login fehlgeschlagen!');
                }


            }
        });
    });
</script>
</html>