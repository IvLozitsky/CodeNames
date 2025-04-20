<?php
/******** ODG * 20250315 * Получение информации об игре
 *        End * 20250420 * CN_GetStat.php
 */
include 'ODG.php';
include 'CN_Init.php';
$Player=null;
foreach($_GET as $key => $value)
  {
    switch(mb_strtolower($key))
      {
        case 'player':$Player=$value;Break;
      }
  }
$fProtocol='';
$fPlayers='';
$fChat='';
$fTeam=0;
$fRow=-1;
$fCol=-1;
$fCard='0';
$qBCards=0;
$qRCards=0;
$qBC=0;
$qRC=0;
$qBP=0;
$qRP=0;
$CurStep=0;
$fQuestion=$NoQuestion;
$Team_2='';
$Team_3='';
$sql='select Step from CN_Players where Step is not null';
foreach ($db->query($sql) as $row)
  {
    $CurStep=$row['Step'];
  }
$sql='select count(*)Cnt from CN_Players where Team=2 and State=0';
foreach ($db->query($sql) as $row)
  {
    $qRP=$row['Cnt'];
  }
$sql='select count(*)Cnt from CN_Players where Team=3 and State=0';
foreach ($db->query($sql) as $row)
  {
    $qBP=$row['Cnt'];
  }
$sql='select count(*)Cnt from CN_Players where Team=2 and State=1';
foreach ($db->query($sql) as $row)
  {
    $qRC=$row['Cnt'];
  }
$sql='select count(*)Cnt from CN_Players where Team=3 and State=1';
foreach ($db->query($sql) as $row)
  {
    $qBC=$row['Cnt'];
  }
$sql='select group_concat(concat(" ",Name) order by State desc)Team_2 from(select State,concat(Name,case when State=1 then " (капитан)" else "" end)Name from CN_Players where State is not null and Team=2 order by State desc)a';
foreach ($db->query($sql) as $row)
  {
    $Team_2=$row['Team_2'];
  }
$sql='select group_concat(concat(" ",Name) order by State desc)Team_3 from(select State,concat(Name,case when State=1 then " (капитан)" else "" end)Name from CN_Players where State is not null and Team=3)a';
foreach ($db->query($sql) as $row)
  {
    $Team_3=$row['Team_3'];
  }
$sql='select * from CN_Last_Word';
foreach ($db->query($sql) as $row)
  {
    $fTeam=$row['Team'];
    $fRow=$row['Word_R'];
    $fCol=$row['Word_C'];
    $fCard=$row['Card'];
    $fQuestion=$row['Question'];
    $fProtocol=str_replace('<br>','\n',$row['Protocol']);
    $fChat=str_replace('<br>','\n',str_replace('"','\"',$row['Chat']));
    $fPlayers=$row['Players'];
  }
if($fRow==null || $fRow<0)
  {
    $fTeam=0;
    $fRow=-1;
    $fCol=-1;
    $fCard=0;
  }
if($fQuestion==null || $fQuestion=='')
  {
    $fQuestion=$NoQuestion;
  }
if($fPlayers!=$Team_2.'/'.$Team_3 || $fPlayers==null)
  {
    $fPlayers=$Team_2.'/'.$Team_3;
    $sql='update CN_Last_Word set Protocol=concat("'.date('h:i:s, d.m.Y ').($Team_2.$Team_3==''?' Нет ни одного игрока':(($Team_2==''?'':(' Команда КРАСНЫХ:'.$Team_2.'. ')).($Team_3==''?'':(' Команда СИНИХ:'.$Team_3.'. ')))).'<br>",Protocol),Players="'.$fPlayers.'"';
    $db->query($sql);
    $sql='commit';
    $db->query($sql);
  }
$sql='select count(*)Cnt from CN_Vocabulary where Team=2 and Used=1 and Card is not null';
foreach ($db->query($sql) as $row)
  {
    $qRCards=$row['Cnt'];
  }
$sql='select count(*)Cnt from CN_Vocabulary where Team=3 and Used=1 and Card is not null';
foreach ($db->query($sql) as $row)
  {
    $qBCards=$row['Cnt'];
  }
print '
<body onload=parent.CN_Timeout=parent.setTimeout("GetStat()",100)>
  <script>
  <!--
    parent.Protocol.value="'.$fProtocol.'"
    parent.Chat.value="'.$fChat.'"
    parent.qBC='.$qBC.'
    parent.qRC='.$qRC.'
    parent.qBP='.$qBP.'
    parent.qRP='.$qRP;
if($CurStep!=0)
  {
    print '
    parent.Step='.$CurStep;
    if($CurStep>3)
      {
        print '
    parent.Winner='.($CurStep-2);
      }
    else
      if($CurStep==2 || $CurStep==3)
        {
          print '
    parent.Code.style.color="'.$TeamColor[$CurStep].'"';
        }
  }
print '
    parent.Count="'.$qRCards.'/'.$qBCards.'"
    parent.Team_2="'.$Team_2.'"
    parent.Team_3="'.$Team_3.'"
    parent.Word_R='.$fRow.'
    parent.Word_C='.$fCol.'
    parent.CurTeam='.$fTeam.'
    parent.CurCard='.$fCard.'
    parent.Code.innerText="'.$fQuestion.'"
  //-->
  </script>
  CN_GetStat: '.date('h:i:s').'
</body>';
/*
 ******** End ********/
?>
