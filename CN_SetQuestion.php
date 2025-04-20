<?php
/******** ODG * 20250316 * Смена вопроса
 *        End * 20250417 * CN_SetQuestion.php
 */
include 'ODG.php';
$Player=null;
$Question=null;
foreach($_GET as $key => $value)
  {
    switch(mb_strtolower($key))
      {
        case 'player':$Player=$value;Break;
        case 'question':$Question=$value;Break;
      }
  }

$sql='update CN_Last_Word set Question="'.$Question.'"'.(($Question==null || $Question=='')?'':',Protocol=concat("'.date('h:i:s ').$Player.' задает вопрос: '.$Question.'<br>",Protocol)');
$db->query($sql);

$sql='commit';
$db->query($sql);
/*
 ******** End ********/
?>
