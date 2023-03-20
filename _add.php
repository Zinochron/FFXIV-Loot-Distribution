<?php
require_once __DIR__ . '/../cred/_db.php';

$message = 0;
$chars = [];

$count = 0;
foreach ($_POST as $entry){
//echo $entry[0];
    $chars[$count]['Name'] = $entry['Name'];
    $chars[$count]['Rolle'] = $entry['Rolle'];
    $count ++;
}

$name = ""; $role = "";
$id_list = [];
$sth = $pdo->prepare('INSERT INTO `raid_member`(`raid_member_id`, `raid_member_name`, `raid_member_role`) VALUES (0,?,?)');
$sth->bindParam(1, $name);
$sth->bindParam(2, $role);

foreach($chars as $char) {
    if(!empty($char['Name'])){

        $name = $char['Name'];
        $role = $char['Rolle'];

        $sth->execute();
        $last_id = $pdo->lastInsertId();
        array_push($id_list,$last_id);


        $message = 1;
    }
}

foreach ($id_list as $id){
    $raid_tier_stmt = $pdo->prepare('
INSERT INTO `raid_tier_1`(raid_member_id,raid_loot_pos) VALUES (?,0);
INSERT INTO `raid_tier_2`(raid_member_id,raid_loot_pos) VALUES (?,0);
INSERT INTO `raid_tier_3`(raid_member_id,raid_loot_pos) VALUES (?,0);');



    $raid_tier_stmt->execute([$id,$id,$id]);
}
echo $message;