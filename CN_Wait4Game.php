<?php
/******** ODG * 20250329 * Ожидание начала игры
 *        End * 20250419 * CN_Wait4Game.php
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
$sql='select count(*)Cnt from CN_Players where State is not null and Step<4 and Step is not null';
foreach ($db->query($sql) as $row)
  {
    $q=$row['Cnt'];
  }
if($q==1)
  {
    print '<body onload=\'top.location.href="CodeNames.php?Player='.$Player.'"\'>CN_Wait4Game, '.date('h:i:s').': $q==1</body>';
  }
else
  {
    print '<body onload=\'parent.Wait4GameTimeOut=parent.setTimeout("Wait4Game()",100)\'>CN_Wait4Game, '.date('h:i:s').': $q='.$q.'
</body>';
  }
/*
 ******** End ********/
?>
