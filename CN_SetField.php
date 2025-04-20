<?php
/******** ODG * 20250315 * Заполнение игрового поля
 *        End * 20250419 * CN_SetField.php
 */
include 'ODG.php';
$fCards='';
$i=0;
$w='';
$r=0;
$c=0;
$t=0;
$s='*';
$fPlayer=null;
$fState=null;
$fTeam=null;
$fCards='';
$fStep=0;
foreach($_GET as $key => $value)
  {
    switch(mb_strtolower($key))
      {
        case 'fplayer':$fPlayer=$value;Break;
        case 'fstate':$fState=$value;Break;
        case 'fteam':$fTeam=$value;Break;
        case 'cards':$fCards=$value;Break;
        case 'fstep':$fStep=$value;Break;
      }
  }
print '<title>SetField</title>
<body onload=top.location.href="CodeNames.php?Player='.$fPlayer.'">';
$sql='select Name from CN_Players where State=1 and Name<>"'.$fPlayer.'" and Team='.$fTeam;
foreach ($db->query($sql) as $row)
  {
    $s=$row['Name'];
  }
if($s=='*')
  {
    $sql='select count(*)Cnt from CN_Players where Step is not null';
    foreach ($db->query($sql) as $row)
      {
        $i=$row['Cnt'];
      }
    if($i==0)
      {
        $sql='update CN_Players set State='.TestNull($fState).',Team='.TestNull($fTeam).',Step='.TestNull($fStep).' where Name="'.$fPlayer.'"';
        $db->query($sql);
        while($fCards!='*')
          {
            $fCards=substr($fCards,1);
            $i=strpos($fCards,'|');
            $w=substr($fCards,0,$i);
            $fCards=substr($fCards,$i+1);
            $i=strpos($fCards,'|');
            $r=substr($fCards,0,$i);
            $fCards=substr($fCards,$i+1);
            $i=strpos($fCards,'|');
            $c=substr($fCards,0,$i);
            $fCards=substr($fCards,$i+1);
            $i=strpos($fCards,'*');
            $t=substr($fCards,0,$i);
            $fCards=substr($fCards,$i);
            $sql='update CN_Vocabulary set Used=1,Word_R='.$r.',Word_C='.$c.',Team='.$t.',Card=null where Word="'.$w.'"';
            $db->query($sql);
          }
        $sql='update CN_Last_Word set Protocol="'.date('h:i:s, d.m.Y ').$fPlayer.' начинает игру"';
        $db->query($sql);
        $sql='commit';
        $db->query($sql);
      }
  }
print'</body>';
/*
 ******** End ********/
?>
