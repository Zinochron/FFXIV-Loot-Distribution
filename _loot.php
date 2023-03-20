<div>
    <?php
    require_once __DIR__ . '/../cred/_db.php';
    $_SESSION['item_list'] = ['Waffe', 'Helm', 'Rüstung', 'Handschuhe', 'Hose', 'Schuhe', 'Ohrring', 'Halskette', 'Armreif', 'Ring', 'Upgrade Schmuck', 'Upgrade Rüstung', 'Upgrade Waffe', 'Diverses'];

    if (!empty($_POST['party'])) {
        if (!empty($_POST['loot'])) {

            $statement_loot = $pdo->prepare("SELECT `rm`.`raid_member_role`, `rm`.`raid_member_name`, `rm`.`raid_member_id` as id
FROM `raid_member` AS `rm`
WHERE `rm`.raid_member_id = ?
ORDER BY rm.raid_member_role asc");

            echo '<h1>Loot Verteilung:</h1>';
            echo '<div id="loot_list_panel" class="panel panel-default">';

            $count = 0;
            foreach ($_POST['loot'] as $looting_player){
                $statement_loot->execute(array($looting_player));
                $row = $statement_loot->fetch();
                echo '<form id="'.$count.'" class="loot_form form-inline"><div data-user_id="' . $row['id'] . '" data-id="panel' . $row['id'] . '"  class="panel-heading">';
                echo '<img src="images/' . $row['raid_member_role'] . '_border.png" alt="Role Icon" width="32"> ';

                echo $row['raid_member_name'];
                echo '<select class="form-control select_style_right inline" id="sel1">';
                $count = 0;
                foreach ($_SESSION['item_list'] as $item){
                    echo '<option value="'.$count.'">'.$item.'</option>';
                    $count++;
                }
                //<option value="0">1</option>
                echo '</select>';
                echo "</form></div>";
            }



            echo "</div>";
        } else {
            echo '<p>Es wurde kein Loot eingestellt, trotzdem fortfahren?</p>';
        }
        echo '<button onclick="loot_dist" id="loot_dist" type="button" class="btn btn-primary btn-lg btn-block">Loot eintragen</button>';

        $party_string = str_repeat("?,", count($_POST['party'])-1) . "?";

        $statement_party = $pdo->prepare("SELECT `rm`.`raid_member_role`, `rm`.`raid_member_name`, `rm`.`raid_member_id` as id
FROM `raid_member` AS `rm`
WHERE `rm`.raid_member_id in ($party_string)
GROUP BY rm.raid_member_id
ORDER BY rm.raid_member_role asc");

        echo '<h1>Partyliste:</h1>';
        echo '<div id="party_list_panel" class="panel panel-default">';
        $statement_party->execute($_POST['party']);
        while ($row = $statement_party->fetch()) {
            echo '<div data-user_id="' . $row['id'] . '" data-id="panel' . $row['id'] . '"  class="panel-heading">';
            echo '<img src="images/' . $row['raid_member_role'] . '_border.png" alt="Role Icon" width="32"> ';

            echo $row['raid_member_name'];
            echo "</div>";
        }
        echo '</div>';

    } else {
        echo '<p>Bitte Party zusammenstellen!</p>';
    }
    ?>
</div>

<script>
    $('#loot_dist').click(function (){

        let party = [];
        let loot = [];
        $('#party_list_panel > .panel-heading').each(function(index){
            party.push(this.dataset.user_id);
        });

        $('#loot_list_panel > .loot_form > .panel-heading').each(function(index){
            loot.push(this.dataset.user_id);
        });

        console.table(party);
        console.table(loot);
/*
        $.ajax({
            type: 'POST',
            url: '_loot.php',
            data: {party: party, loot: loot},
            success: function (response) {
                document.getElementById("loot_modal_body").innerHTML = response;
            }
        });
*/
    });
</script>
