<?php
/******** ODG * 20250315 * Очередной ход
 *        End * 20250420 * CN_SetStep.php
 */
include 'ODG.php';
$fPlayer=null;
$fTeam=0;
$rTeam=0;
$fStep=0;
$fRow=0;
$fCol=0;
$fCard=0;
$Winner=0;
$Question="";
$fProtocol="";
$fChat="";
foreach($_GET as $key => $value)
  {
    switch(mb_strtolower($key))
      {
        case 'fplayer':$fPlayer=$value;Break;
        case 'fteam':$fTeam=$value;Break;
        case 'fstep':$fStep=$value;Break;
        case 'frow':$fRow=$value;Break;
        case 'fcol':$fCol=$value;Break;
        case 'fcard':$fCard=$value;Break;
        case 'question':$Question=$value;Break;
      }
  }
if($Question!="" && $Question!=null)
  {
    $sql='update CN_Last_Word set Question="'.$Question.'"';
    $db->query($sql);
  }
if($fTeam!=0)
  {
    $sql='update CN_Vocabulary set Card='.$fCard.' where Word_R='.$fRow.' and Word_C='.$fCol.' and Used=1';
    $db->query($sql);
  }
$sql='select Team from CN_Players where Name="'.$fPlayer.'"';
foreach ($db->query($sql) as $row)
  {
    $rTeam=$row['Team'];
  }
if($fStep>3)
  {
    $Winner=$fStep-2;
    $sql='select Team,sum(case when Card is null then 0 else 1 end)OpenCnt from CN_Vocabulary where Used=1 and(Team=2 or Team=3) group by Team';
    foreach ($db->query($sql) as $row)
      {
        $TeamCnt[$row['Team']]=$row['OpenCnt'];
      }
  }
else
  {
    $Winner=$fStep;
    $sql='select Team,sum(case when Card is null then 1 else 0 end)Cnt,sum(case when Card is null then 0 else 1 end)OpenCnt from CN_Vocabulary where Used=1 and(Team=2 or Team=3) group by Team';
    foreach ($db->query($sql) as $row)
      {
        if($row['Cnt']==0)
          {
            $fStep=$row['Team']+2;
            $Winner=$row['Team'];
          }
        $TeamCnt[$row['Team']]=$row['OpenCnt'];
      }
  }
if($fTeam!=0)
  {
    $sql='update CN_Last_Word set Team='.$fTeam.',Word_R='.$fRow.',Word_C='.$fCol.',Card='.$fCard.',Protocol=concat("'.date('h:i:s ').$fPlayer.' открывает '.($fTeam==1?'НЕЙТРАЛЬНУЮ':($fTeam==2?'КРАСНУЮ':($fTeam==3?'СИНЮЮ':'ЧЁРНУЮ'))).' карту с текстом ",(select Word from CN_Vocabulary where Word_R='.$fRow.' and Word_C='.$fCol.' and Used=1)'.(($fTeam==4 || $rTeam==$fTeam || $fStep>3)?'':'," - переход хода"').',"<br>",Protocol)';
    $db->query($sql);
  }
else
  {
    $sql='update CN_Last_Word set Protocol=concat("'.date('h:i:s ').$fPlayer.' передает ход команде '.($rTeam==3?'КРАСНЫХ':'СИНИХ').'<br>",Protocol)';
    $db->query($sql);
  }
if($fStep>3)
  {
    $sql='update CN_Players set Step='.$fStep.' where State=1 and Step is not null';
    $db->query($sql);
    $sql='select concat("'.date('h:i:s, d.m.Y ').' ИГРА ЗАВЕРШЕНА: победа команды '.($Winner==2?'КРАСНЫХ':'СИНИХ').'<br>",Protocol)Protocol,Chat from CN_Last_Word';
    foreach ($db->query($sql) as $row)
      {
        $fProtocol=$row['Protocol'];
        $fChat=$row['Chat'];
      }
    $sql='insert into CN_History (Protocol,Chat,Team,Team_2_Count,Team_3_Count,C_Date)values("'.$fProtocol.'","'.$fChat.'",'.$Winner.','.$TeamCnt[2].','.$TeamCnt[3].',sysdate())';
    $db->query($sql);
    $sql='update CN_Last_Word set Protocol="'.$fProtocol.'"';
    $db->query($sql);
    print '<body onload=\'parent.Winner='.$Winner.';parent.Info_5.style.display="";parent.CN_Timeout=parent.setTimeout("GetStat()",100)\'>SetStep</body>';
  }
else
  {
    $sql='update CN_Players set Step='.$fStep.' where Step is not null';
    $db->query($sql);
  }
$sql='commit';
$db->query($sql);
/*
 ******** End ********/
?>
