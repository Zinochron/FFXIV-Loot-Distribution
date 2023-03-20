<?php
session_start();
if(isset($_COOKIE['user_login_ff14'])){
    $user_rights = $_COOKIE['user_login_ff14'];
}else{
    if (isset($_SESSION['user_rights'])){
        $user_rights = $_SESSION['user_rights'];
    }
}
$_SESSION['item_list'] = ['Waffe', 'Helm', 'Rüstung', 'Handschuhe', 'Hose', 'Schuhe', 'Ohrring', 'Halskette', 'Armreif', 'Ring', 'Upgrade Schmuck', 'Upgrade Rüstung', 'Upgrade Waffe', 'Diverses'];
require_once __DIR__ . '/../cred/_db.php';
?>
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">FFXIVRaidLog</a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
                <?php
                if (!isset($_GET['raid_tier'])) {
                    $_GET['raid_tier'] = 1;
                }
                if ($_GET['raid_tier'] == 1) {
                    echo '<li class="active"><a href="/?raid_tier=1">Raid Tier 1</a></li>';
                } else {
                    echo '<li><a href="/?raid_tier=1">Raid Tier 1</a></li>';
                }
                if ($_GET['raid_tier'] == 2) {
                    echo '<li class="active"><a href="/?raid_tier=2">Raid Tier 2</a></li>';
                } else {
                    echo '<li><a href="/?raid_tier=2">Raid Tier 2</a></li>';
                }
                if ($_GET['raid_tier'] == 3) {
                    echo '<li class="active"><a href="/?raid_tier=3">Raid Tier 3</a></li>';
                } else {
                    echo '<li><a href="/?raid_tier=3">Raid Tier 3</a></li>';
                }
                ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php
                if(isset($_COOKIE['user_login_ff14']) || isset($_SESSION['user_rights'])){
                    echo '<li><a href="add.php"><span class="glyphicon glyphicon-edit"></span> Spieler anlegen</a></li>';
                    echo '<li id="logout"><a href="/"><span class="glyphicon glyphicon-log-out"></span> Abmelden</a></li>';
                }else{
                    echo '<li><a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>';
                }
                ?>
            </ul>
        </div>
    </div>
</nav>

<script>
    $('#logout').click(function (){
        $.ajax({
            type: 'POST',
            url: '_logout.php',
            success: function (response) {
                location.reload();
            }
        });
    });
</script>