<?php
/******** ODG * 20250315 * Перезапуск игры
 *        End * 20250419 * CN_ClearGame.php
 */
include 'ODG.php';
$Player=null;
foreach($_GET as $key => $value)
  {
    switch(mb_strtolower($key))
      {
        case 'player':$Player=$value;Break;
      }
  }
$Protocol="";
print '<title>ClearGame</title>
<body onload=top.location.href="CodeNames.php?Player='.$Player.'">';
$sql='update CN_Vocabulary set Used=2 where Used=1';
$db->query($sql);
$sql='update CN_Players set Team=null,State=null,Step=null,Updated=null';
$db->query($sql);
$sql='update CN_Last_Word set Team=null,Word_R=-1,Word_C=-1,Card=null,Question=null,Protocol=null,Chat=null,Players=null';
$db->query($sql);
$sql='select count(*)Cnt from CN_Vocabulary where Used is null';
foreach ($db->query($sql) as $row)
  {
    $q=$row['Cnt'];
  }
if($q<25)
  {
    $sql='update CN_Vocabulary set Used=null,Team=null,Word_R=null,Word_C=null,Card=null,Time=null';
    $db->query($sql);
  }
$sql='commit';
$db->query($sql);
print '</body>';
/*
 ******** End ********/
?>
