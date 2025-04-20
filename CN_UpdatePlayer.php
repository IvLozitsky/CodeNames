<?php
/******** ODG * 20250315 * Изменение данных игрока CodeNames
 *        End * 20250419 * CN_UpdatePlayer.php
 */
include 'ODG.php';
$fPlayer=null;
$fState=null;
$fTeam=null;
$NeedPlay=1;
foreach($_GET as $key => $value)
  {
    switch(mb_strtolower($key))
      {
        case 'fplayer':$fPlayer=$value;Break;
        case 'fstate':$fState=$value;Break;
        case 'fteam':$fTeam=$value;Break;
        case 'needplay':$NeedPlay=$value;Break;
      }
  }
$s='*';
$t=0;
if($fState==1)
  {
    if($NeedPlay==2)
      {
        $sql='select Name from CN_Players where Step is not null';
        foreach ($db->query($sql) as $row)
          {
            $s=$row['Name'];
            print '
             <script>
             <!--
               alert("'.$s.' уже начинает игру");
             //-->
             </script>';
            $fState=0;
            $NeedPlay=1;
            print '<body onload=\'top.location.href="CodeNames.php?Player='.$fPlayer.'"\'>';
          }
      }
    if($s=='*')
      {
        $sql='select Name from CN_Players where State=1 and Name<>"'.$fPlayer.'" and Team='.$fTeam;
        foreach ($db->query($sql) as $row)
          {
            $s=$row['Name'];
            print '
             <script>
             <!--
               alert("У команды '.($fTeam==2?'КРАСНЫХ':'СИНИХ').' уже есть капитан: '.$s.'")
             //-->
             </script>';
            $fState=0;
            $NeedPlay=1;
            print '<body onload=\'top.location.href="CodeNames.php?Player='.$fPlayer.'"\'>';
          }
        if($s=='*')
          {
            if($NeedPlay==2)
              {
                print '<body onload="parent.Start('.$fTeam.')">';
                $fNeedPlay=1;
              }
            else
              {
                print '<body onload=\'top.location.href="CodeNames.php?Player='.$fPlayer.'"\'>';
              }
          }
      }
  }
else
  {
    print '<body onload=\'top.location.href="CodeNames.php?Player='.$fPlayer.'"\'>';
  }
if($s=='*')
  {
    $sql='select * from CN_Players where Name="'.$fPlayer.'"';
    foreach ($db->query($sql) as $row)
      {
        if($row['State']==null || $fState==null || $fState==0)
          {
            $sql='update CN_Players set State='.TestNull($fState).',Team='.TestNull($fTeam).' where Name="'.$fPlayer.'"';
            $db->query($sql);
          }
        $s=$fPlayer;
      }
    if($s=='*')
      {
        $sql='insert into CN_Players(Name,State,Team,Add_Date)values("'.$fPlayer.'",'.TestNull($fState).','.TestNull($fTeam).',sysdate())';
        $db->query($sql);
      }
    $sql='commit';
    $db->query($sql);
  }
if($NeedPlay==1)
  {
    print '</body>';
  }
/*
 ******** End ********/
?>
