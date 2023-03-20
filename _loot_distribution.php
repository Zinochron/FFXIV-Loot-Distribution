<?php
require_once __DIR__ . '/../cred/_db.php';
$message = 0;

if(!empty($_POST['loot']) && ($_POST['raid_tier'] == 1 || $_POST['raid_tier'] == 2 || $_POST['raid_tier'] == 3 )){


    $loot_order = [];

    $id = ""; $item = ""; $date = ""; $tier = "";

    $sth = $pdo->prepare('
INSERT INTO `raid_log`(`raid_log_id`, `raid_party_id`, `raid_member_id`, `raid_log_item_type`, `raid_log_date`, `raid_log_desc`, `raid_log_raid_tier`) 
VALUES (0,0,?,?,?,0,?)');
    $sth->bindParam(1, $id);
    $sth->bindParam(2, $item);
    $sth->bindParam(3, $date);
    $sth->bindParam(4, $tier);

    $datetime = new DateTime("NOW");
    $date = $datetime->format('Y-m-d H:i:s');

    $tier = $_POST['raid_tier'];
    foreach($_POST['loot'] as $loot) {

            $id = $loot[0];
            $item = $loot[1];
            array_push($loot_order,$id);
            $sth->execute();

    }
/*
    for($i = 0; $i < count($loot_order); $i++){
        echo $loot_order[$i]."<br>";

    }
*/
    $string = str_repeat("?,", count($_POST['loot_order'])-1) . "?";

    $statement_party = $pdo->prepare("SELECT `raid_member`.`raid_member_id`, rt.`raid_loot_pos`
FROM `raid_member` 
	LEFT JOIN raid_tier_".$tier." as rt ON rt.`raid_member_id` = `raid_member`.`raid_member_id`
WHERE rt.`raid_loot_pos` in ($string) order by raid_loot_pos asc;");

    $position = []; $party = [];
    $statement_party->execute($_POST['loot_order']);
    while ($row = $statement_party->fetch()) {
        //echo "Player:".$row['raid_member_id']." - Pos:".$row['raid_loot_pos']."<br>";
        array_push($party, $row['raid_member_id']);
        array_push($position, $row['raid_loot_pos']);
    }

    $party_copy = $party;
    $position_copy = $position;
/*
    for($i = 0; $i < count($position); $i++){
        echo $party[$i]."-".$position[$i];
        echo "<br>";
    }
*/
    foreach ($loot_order as $looting_player){
        for($i = 0; $i < count($party); $i++){
            if($party[$i]==$looting_player){
                $_saved_id = $party[$i];

                for($k = count($party_copy)-2; $k >= $i; $k--){
                    $party_copy[$k]=$party[$k+1];
                }
                $party_copy[count($party_copy)-1] = $_saved_id;
            }else{

            }
        }
        $party = $party_copy;
        $position = $position_copy;
    }
    $position_number = 0; $id_number = 0;
    $update = $pdo->prepare('
UPDATE raid_tier_'.$tier.' SET `raid_member_id`=? WHERE raid_loot_pos = ?');
    $update->bindParam(1, $id_number);
    $update->bindParam(2, $position_number);


    for($i = 0; $i < count($position); $i++){
        $position_number = $position[$i];
        $id_number = $party[$i];
        $update->execute();
    }
    $message = 1;
/*
    echo "---------";
    echo "<br>";
    for($i = 0; $i < count($position); $i++){
        echo $party[$i]."-".$position[$i];
        echo "<br>";
    }
    echo "<br>";
*/
}else{
    $message = 2;
}

echo $message;
