<?php
/******** ODG * 20250417 * Добавление текста в чат
 *        End * 20250417 * CN_Add2Chat.php
 */
include 'ODG.php';
$Player=null;
$TheText=null;
foreach($_GET as $key => $value)
  {
    switch(mb_strtolower($key))
      {
        case 'player':$Player=$value;Break;
        case 'thetext':$TheText=$value;Break;
      }
  }

$sql='update CN_Last_Word set Chat=concat(\''.date('h:i:s ').$Player.': '.$TheText.'<br>\',Chat)';
echo $sql;
$db->query($sql);

$sql='commit';
$db->query($sql);
/*
 ******** End ********/
?>
