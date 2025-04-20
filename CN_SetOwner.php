<?php
/******** ODG * 20250329 * Сохранение цвета карт в iframe
 *        End * 20250415 * CN_SetOwner.php
 */
include 'ODG.php';
print '
<body onload=\'parent.SetVoice();parent.document.body.style.display="";parent.CN_Timeout=parent.setTimeout("GetStat()",100)\'>
  <script>
  <!--
';
$sql='select Team,Word_R,Word_C from CN_Vocabulary where Used=1 order by Word_R,Word_C';
foreach ($db->query($sql) as $row)
  {
    print '  parent.Owner['.$row['Word_R'].']['.$row['Word_C'].']='.$row['Team'].'
';
  }
print '  //-->
  </script>
  CN_SetOwner: '.date('h:i:s').'
</body>';
/*
 ******** End ********/
?>
