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
    <script src="https://kit.fontawesome.com/e3cffb2ce9.js" crossorigin="anonymous"></script>
    <script type="text/javascript" charset="utf8"
            src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

    <link rel="icon" type="image/x-icon" href="/images/favicon.ico">
</head>
<body>
<?php
require_once 'header.php';

?>


    <?php
    if (isset($_COOKIE['user_login_ff14']) || isset($_SESSION['user_rights'])){
    ?>
<div id="grid_container" class="container-fluid">
    <div class="well">
        <button data-toggle="modal" data-target="#loot_modal" id="lock_in" type="button"
                class="btn btn-primary btn-lg btn-block">Party bestätigen
        </button>
    </div>
    <div class="row">
        <div class="col-sm-4 well">
            <h2 class="small_margin_top">Alle Spieler</h2>
            <div id="player_panel" class="panel panel-default">
                <?php
                $statement_members = $pdo->prepare("
SELECT `rm`.`raid_member_role`, `rm`.`raid_member_name`, `rm`.`raid_member_id` as id, `rl`.*, max(rl.raid_log_date) as MaxDate, rt.raid_loot_pos
FROM `raid_member` AS `rm`
         LEFT JOIN `raid_log` AS `rl` on rl.raid_member_id = rm.raid_member_id
         LEFT JOIN raid_tier_".$_GET['raid_tier']." AS rt ON rt.`raid_member_id` = rm.`raid_member_id`
GROUP BY rm.raid_member_id
ORDER BY rt.raid_loot_pos asc ");

                $statement_members->execute();
                while ($row = $statement_members->fetch()) {
                    echo '<div data-loot_roll="'.$row['raid_loot_pos'].'" data-user_id="' . $row['id'] . '" data-id="panel' . $row['id'] . '"  class="panel-heading">';
                    echo '<img src="images/' . $row['raid_member_role'] . '_border.png" alt="Role Icon" width="32"> ';
                    echo '<span class="badge">'.$row['raid_loot_pos'].'</span>';
                    echo $row['raid_member_name'];
                    echo '<div id="group_panel' . $row['id'] . '" style="position: absolute; right: 30px;" class="btn-group"><button data-id="panel' . $row['id'] . '" type="button" class="btn_toggle btn btn-default btn-sm align-self-end"><span class="glyphicon glyphicon-chevron-down"></span></button></div>';
                    echo "</div>";

                    if ($row['raid_log_date'] != 0 && $row['raid_log_raid_tier'] == $_GET['raid_tier']) {
                        echo '<div hidden class=" panel-body panel' . $row['id'] . '">';
                        echo 'Letzter Roll:</br>';
                        echo date('d.m.Y - h:i:s', strtotime($row['MaxDate']));
                        echo '</div>';
                        echo '<div hidden class=" panel-body panel' . $row['id'] . '">';
                        echo 'Letztes Item:</br>';
                        echo $_SESSION['item_list'][$row['raid_log_item_type']];
                        echo '</div>';
                    } else {
                        echo '<div hidden class=" panel-body panel' . $row['id'] . '">';
                        echo "Kein Eintrag vorhanden.";
                        echo '</div>';
                    }
                }
                ?>
            </div>
        </div>
        <div class="col-sm-4 well">
            <h2 class="small_margin_top">Party</h2>
            <div class="progress">
                <div id="progress" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0"
                     aria-valuemax="100"
                     style="width:0%"><span id="prog_text" class="show">0/8</span>
                </div>
            </div>
            <div id="party_panel" class="panel panel-default">

            </div>
        </div>
        <div class="col-sm-4 well">
            <h2 class="small_margin_top">Loot</h2>
            <div id="loot_panel" class="panel panel-default">

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="loot_modal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Party und Loot Verteilung</h4>
            </div>
            <div id="loot_modal_body" class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
            </div>
        </div>

    </div>
</div>

    <div id="table_container" class="well">
        <table id="raid_log_table">
            <thead>
            <tr>
                <td hidden>
                    Eintrag #
                </td>
                <td>
                    Spieler
                </td>
                <td>
                    Zeitpunkt
                </td>
                <td>
                    Item
                </td>
            </tr>
            </thead>
            <tbody>
            <?php
            $table_sql = '
SELECT rl.*, rm.raid_member_name, rm.raid_member_role
FROM raid_log as rl
left join raid_member as rm on rl.raid_member_id = rm.raid_member_id
where rl.raid_log_raid_tier = '.$_GET['raid_tier'].' 
order by rl.raid_log_id desc;';
            foreach ($pdo->query($table_sql) as $table_row) {
                echo '<tr>';
                #Table Cells
                echo '<td hidden>';
                echo $table_row['raid_log_id'];
                echo '</td>';
                echo '<td>';
                echo '<img src="images/' . $table_row['raid_member_role'] . '_border.png" alt="Role Icon" width="32"> ';
                echo $table_row['raid_member_name'];
                echo '</td>';
                echo '<td>';
                echo date('d.m.Y - h:i:s', strtotime($table_row['raid_log_date']));
                echo '</td>';
                echo '<td>';
                echo $_SESSION['item_list'][$table_row['raid_log_item_type']];
                echo '</td>';
                #Table cells end
                echo '</tr>';
            }
            ?>
            </tbody>
        </table>
    </div>
    <?php
    }else{
?>
        <div id="grid_container" class="container-fluid">
            <div class="row">
                <div class="col-sm-4 well">
                    <h2 class="small_margin_top">Tier <?php echo $_GET['raid_tier']; ?></h2>
                    <div id="player_panel" class="panel panel-default">
                        <?php
                        $statement_members = $pdo->prepare("
SELECT `rm`.`raid_member_role`, `rm`.`raid_member_name`, `rm`.`raid_member_id` as id, `rl`.*, max(rl.raid_log_date) as MaxDate, rt.raid_loot_pos
FROM `raid_member` AS `rm`
         LEFT JOIN `raid_log` AS `rl` on rl.raid_member_id = rm.raid_member_id
         LEFT JOIN raid_tier_".$_GET['raid_tier']." AS rt ON rt.`raid_member_id` = rm.`raid_member_id`
GROUP BY rm.raid_member_id
ORDER BY rt.raid_loot_pos asc ");

                        $statement_members->execute(null);
                        while ($row = $statement_members->fetch()) {
                            echo '<div data-loot_roll="'.$row['raid_loot_pos'].'" data-user_id="' . $row['id'] . '" data-id="panel' . $row['id'] . '"  class="panel-heading">';
                            echo '<img src="images/' . $row['raid_member_role'] . '_border.png" alt="Role Icon" width="32"> ';
                            echo '<span class="badge">'.$row['raid_loot_pos'].'</span>';
                            echo $row['raid_member_name'];
                            echo '<div id="group_panel' . $row['id'] . '" style="position: absolute; right: 30px;" class="btn-group"><button data-id="panel' . $row['id'] . '" type="button" class="btn_toggle btn btn-default btn-sm align-self-end"><span class="glyphicon glyphicon-chevron-down"></span></button></div>';
                            echo "</div>";

                            if ($row['raid_log_date'] != 0 && $row['raid_log_raid_tier'] == $_GET['raid_tier']) {
                                echo '<div hidden class=" panel-body panel' . $row['id'] . '">';
                                echo 'Letzter Roll:</br>';
                                echo date('d.m.Y - h:i:s', strtotime($row['MaxDate']));
                                echo '</div>';
                                echo '<div hidden class=" panel-body panel' . $row['id'] . '">';
                                echo 'Letztes Item:</br>';
                                echo $_SESSION['item_list'][$row['raid_log_item_type']];
                                echo '</div>';
                            } else {
                                echo '<div hidden class=" panel-body panel' . $row['id'] . '">';
                                echo "Kein Eintrag vorhanden.";
                                echo '</div>';
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class="col-sm-8 well">
                    <div id="table_container" class="well">
                        <table id="raid_log_table">
                            <thead>
                            <tr>
                                <td hidden>
                                    Eintrag #
                                </td>
                                <td>
                                    Spieler
                                </td>
                                <td>
                                    Zeitpunkt
                                </td>
                                <td>
                                    Item
                                </td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $table_sql = '
SELECT rl.*, rm.raid_member_name, rm.raid_member_role
FROM raid_log as rl
left join raid_member as rm on rl.raid_member_id = rm.raid_member_id
where rl.raid_log_raid_tier = '.$_GET['raid_tier'].' 
order by rl.raid_log_id desc;';
                            foreach ($pdo->query($table_sql) as $table_row) {
                                echo '<tr>';
                                #Table Cells
                                echo '<td hidden>';
                                echo $table_row['raid_log_id'];
                                echo '</td>';
                                echo '<td>';
                                echo '<img src="images/' . $table_row['raid_member_role'] . '_border.png" alt="Role Icon" width="32"> ';
                                echo $table_row['raid_member_name'];
                                echo '</td>';
                                echo '<td>';
                                echo date('d.m.Y - h:i:s', strtotime($table_row['raid_log_date']));
                                echo '</td>';
                                echo '<td>';
                                echo $_SESSION['item_list'][$table_row['raid_log_item_type']];
                                echo '</td>';
                                #Table cells end
                                echo '</tr>';
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
<?php
    }
    ?>


<?php
echo '<div hidden id="raid_tier_info" data-id="';
if (!isset($_GET['raid_tier'])) {
    echo '1';
}else{
    echo $_GET['raid_tier'];
}
echo '"></div>';
?>

</body>
</html>
<script>
    $(document).ready(function () {
        $('#raid_log_table').DataTable( {
            "order": [[ 0, "desc" ]],
            "ordering": false
        } );
    });

    $('.btn_toggle').click(function () {
        event.stopImmediatePropagation();
        var els = document.getElementsByClassName(this.dataset.id);

        Array.prototype.forEach.call(els, function (el) {
            // Do stuff here
            if (el.hidden == false) {
                el.hidden = true;
            } else {
                el.hidden = false;
            }
        });
        if (this.innerHTML == '<span class="glyphicon glyphicon-chevron-up"></span>') {
            this.innerHTML = '<span class="glyphicon glyphicon-chevron-down"></span>'
        } else {
            this.innerHTML = '<span class="glyphicon glyphicon-chevron-up"></span>'
        }
    });
</script>
<?php
if (isset($_COOKIE['user_login_ff14']) || isset($_SESSION['user_rights'])){
?>
<script>
    let progress_counter = 0;

    $('.panel-heading').click(function () {
        let check = false;
        if (this.parentElement.id == 'player_panel' && progress_counter < 8 && check == false) {

            check = true;

            $("#party_panel").show();
            //console.log(this);
            $('#party_panel').append(this);

            let el_children = document.getElementsByClassName(this.dataset.id);
            Array.from(el_children).forEach(el => {
                $('#party_panel').append(el);
            });

            progress_counter++;
            document.getElementById('prog_text').innerText = progress_counter + "/8";
            document.getElementById('progress').style.width = progress_counter * 12.5 + "%";

            if ($('#group_' + this.dataset.id).children.length < 2) {

            }
            //console.log($('#group_'+this.dataset.id).children.length);
            $('<button onclick="loot_roll(this);" data-id="panel" type="button" class="btn_loot btn btn-default btn-sm align-self-end"><i class="fa-solid fa-dice"></i></button>').prependTo($('#group_' + this.dataset.id));
        }

        if (this.parentElement.id == 'party_panel' && progress_counter > 0 && check == false) {

            check = true;

            $("#player_panel").show();
            //console.log(this);
            let el_children = document.getElementsByClassName(this.dataset.id);
            Array.from(el_children).forEach(el => {
                $('#player_panel').prepend(el);
            });
            $('#player_panel').prepend(this);


            progress_counter--;
            document.getElementById('prog_text').innerText = progress_counter + "/8";
            document.getElementById('progress').style.width = progress_counter * 12.5 + "%";
            $('#group_' + this.dataset.id)[0].firstChild.remove();

        }
    });

    function loot_roll(element) {
        event.stopImmediatePropagation();

        $("#loot_panel").show();

        //console.log(element.parentElement.parentElement.cloneNode());
        //console.log(element.parentElement.parentElement.dataset.id);
        check = true;

        el_copy = element.parentElement.parentElement.cloneNode(true);
        el_copy.lastChild.remove();

        $('<div style="position: absolute; right: 30px;" class="btn-group"><button onclick="remove(this)" data-id="panel" type="button" class="btn_loot btn btn-default btn-sm align-self-end"><span class="glyphicon glyphicon-remove"></span></button></div>').appendTo(el_copy);

        $('#loot_panel').append(el_copy);
    }

    function remove(el) {
        event.stopImmediatePropagation();

        el.parentElement.parentElement.remove();
    }

    $('#lock_in').click(function () {

        let party = [];
        let loot = [];
        $('#party_panel > .panel-heading').each(function (index) {
            party.push(this.dataset.user_id);
        });

        $('#loot_panel > .panel-heading').each(function (index) {
            loot.push(this.dataset.user_id);
        });

        $.ajax({
            type: 'POST',
            url: '_loot.php',
            data: {party: party, loot: loot},
            success: function (response) {
                document.getElementById("loot_modal_body").innerHTML = response;
                $('#loot_dist').click(function () {

                    let party_final = [];
                    let loot_final = [];
                    let loot_order = [];
                    $('#party_list_panel > .panel-heading').each(function (index) {
                        party_final.push(this.dataset.user_id);
                    });

                    $('#party_panel > .panel-heading').each(function (index) {
                        loot_order.push(this.dataset.loot_roll);
                    });

                    $('#loot_list_panel > .loot_form > .panel-heading').each(function (index) {
                        loot_final.push([this.dataset.user_id, this.lastChild.value]);
                    });

                    let raid_tier = document.getElementById('raid_tier_info').dataset.id;
                    console.table(party_final);
                    console.table(loot_final);
                    console.table(loot_order);

                    $.ajax({
                        type: 'POST',
                        url: '_loot_distribution.php',
                        data: {party: party_final, loot: loot_final, loot_order: loot_order, raid_tier: raid_tier},
                        success: function (response) {
                            if(response == 1){
                                $('#loot_modal_body').text('Loot erfolgreich verteilt! Die Seite wird aktualisiert');
                                setTimeout(function(){
                                    //do what you need here
                                    location.reload();
                                }, 3000);
                            }else{
                                $('#loot_modal_body').text('Es ist ein unerwarteter Fehler aufgetreten!');
                            }
                        }
                    });

                });
            }
        });

    });
</script>
<?php
}
?>