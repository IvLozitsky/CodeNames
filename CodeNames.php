<?php
/******** ODG * 20250315 * Игра в CodeNames
 *        End * DateText * CodeNames.php
 */

include 'ODG.php';

/**
 * @var PDO $db
 */

include 'CN_Init.php';

$Player = null;
foreach ($_GET as $key => $value) {
    switch (mb_strtolower($key)) {
        case 'player':
            $Player = $value;
            break;
    }
}
if ($Player == null) {
    foreach ($_GET as $key => $value) {
        switch (mb_strtolower($key)) {
            case 'player':
                $Player = $value;
                break;
        }
    }
}
$q = 0;
$qBC = 0;
$qBP = 0;
$qRC = 0;
$qRP = 0;
$rState = 0;
$rTeam = 0;
$SavedVoice = '';
if ($Player != null) {
    $sql = 'select State,Team,Voice from CN_Players where Name="' . $Player . '"';
    foreach ($db->query($sql) as $row) {
        $rState = $row['State'];
        $rTeam = $row['Team'];
        $SavedVoice = $row['Voice'];
    }
}
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1, m aximum-scale=1.0, minimum-scale=0.1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="Author" content="ODG">
    <style>
        #MainTab tr:hover {
            background: silver
        }
    </style>
    <script>
        const DateText = "20250420";
        const Title = 'ODG\'s CodeNames';

        document.write('<title>', Title, '</title>')
    </script>
</head>
<script type="text/javascript" src=../../html/ODG.js></script>
<script type="text/javascript" src=/VIKS/html/ODG.js></script>
<body onload="SetOwner();Year.innerText=DateText.substring(2,4)=='25'?'':('-'+DateText.substring(2,4))" onbeforeunload="document.body.style.display='none'" background="<?php print $Images_Dir.'Back_'.($rTeam==null?'0':$rTeam).'-04.jpg' ?>" style="display:none">
<div align=right style="position:absolute;top=13px;right:10px" title="Создание игры начато 12.03.2025
в день рождения Дмитрия Юрьевича Зиновьева">Посвящается ДЗ</div>
<sup><font size=1 color=green>
<script type="text/javascript">
<!--
  document.writeln(Title,"'",DateText,". Игра в ",Title," &copy; ODG, 2025",DateText.substring(2,4)=='25'?'':('-'+DateText.substring(2,4)))
//-->
</script>`
</font></sup>
<script type="text/javascript">
<!--
  document.writeln("<center><h3><font color=green><span onclick=\'CN_Timeout=setTimeout(\"GetStat()\",100)\' title=\'Оживить :)\'>",Title,"</span></font></h3>")
//-->
</script>
<style>
  table td.card {width:200px;height:135px;border:1px solid black;text-align:center};
</style>
<?php
print '
   <script type="text/javascript" src="/VIKS/html/SpeechSynthesis.js"></script>
   <script type="text/javascript">
     <!--
       var
         SavedVoice="'.$SavedVoice.'",
         Initialized=0,
         WidthGot=0,
         ChatLength=0

       function Wait4Game()
         {
           document.getElementById("Worker").contentWindow.location.href="CN_Wait4Game.php?Player='.$Player.'"
         }

       function ChangeVoice()
         {
           V=document.getElementById("VoiceSelect")
           SavedVoice=V.options[V.selectedIndex].text
           if(document.getElementById("PlayerName")==null)
             {
               document.getElementById("IF_SetField").contentWindow.location.href="CN_SetVoice.php?Player='.$Player.'&Voice="+SavedVoice
             }
           else
             {
               document.getElementById("IF_SetField").contentWindow.location.href="CN_SetVoice.php?Player="+PlayerName.value+"&Voice="+SavedVoice
             }
         }
  
       function SetVoice()
         {
           if(SpeechSynthesisObject==0)
             {
               return
             }
           with(document.getElementById("VoiceSelect"))
             {
             for(i=0;i<options.length;i++)
               if(options[i].text==SavedVoice)
                 {
                   selectedIndex=i
                   break
                 }
             }
         }
     //-->
     </script>
';

$sql='select count(*)Cnt from CN_Vocabulary where Used=1';
foreach ($db->query($sql) as $row)
  {
    $q1=$row['Cnt'];
  }
/*> не получалось запустить CN_ClearGame */
$sql='select count(*)Cnt from CN_Players where Step>3 and Step is not null';
foreach ($db->query($sql) as $row)
  {
    $q=$row['Cnt'];
  }
if($q>0 || ($q1>0 && $q1!=25))
  {
     print '
      <script type="text/javascript">
      <!--
        location.href="CN_ClearGame.php?Player='.$Player.'"
      //-->
      </script>';
  }
/*< не получалось запустить ClearGame - не было скобок в if ? */
if($q1!=25) // нет игры
  {
    print '
     <script type="text/javascript">
     <!--
//alert("Новая игра: q='.$q.' q1='.$q1.' Player=\"'.$Player.'\"")
       var
         Owner=[],
         Team=[],
         Step=0,
         TeamColor=["'.$TeamColor[0].'","'.$TeamColor[1].'","'.$TeamColor[2].'","'.$TeamColor[3].'","'.$TeamColor[4].'","'.$TeamColor[5].'","'.$TeamColor[6].'"],
         Word=
          [
           ""';
    $sql='select * from CN_Vocabulary where Used is null';
    foreach ($db->query($sql) as $row)
      {
        print ',
           "'.$row['Word'].'"';
      }
    print '
          ]
       
       function SetOwner()
         {
           document.body.style.display=""
           VoiceTimeout=setTimeout("SetVoice()",100)
         }

       function Start(TeamNum)
         {
           clearTimeout(Wait4GameTimeOut)
           AllWords="*"
           Cards="*"
           with(MainTable)
             {
               for(r=0;r<5;r++)
                 {
                   Owner[r]=[]
                   for(c=0;c<5;c++)
                     {
                       Owner[r][c]=0
                       CurWord=""
                       while(CurWord=="" || AllWords.indexOf("*"+CurWord+"*")>-1)
                         CurWord=Word[Math.ceil(Math.random()*Word.length)]
                       AllWords+=CurWord+"*"
                       rows[r+2].cells[c].innerText=CurWord
                     }
                 }
               CurOwner=4
               Step=3-Math.floor(Math.random()*2)
               Team[1]=7           // нейтральные
               Team[2]=Step==2?9:8 // красные
               Team[3]=17-Team[2]  // синие
               Team[4]=1           // черная
               q=0
               while(q<25)
                 {
                   r=Math.floor(Math.random()*5)
                   c=Math.floor(Math.random()*5)
                   if(Owner[r][c]==0)
                     {
                       Owner[r][c]=CurOwner
                       rows[r+2].cells[c].style.color="white"
                       Cards+=rows[r+2].cells[c].innerText+"|"+r+"|"+c+"|"+CurOwner+"*"
                       rows[r+2].cells[c].bgColor=TeamColor[CurOwner];
                       switch(CurOwner)
                         {
                           case 1:rows[r+2].cells[c].style.color="black";Team[1]--;if(Team[1]==0){CurOwner=2};break;
                           case 2:Team[2]--;if(Team[2]==0){CurOwner=3};break;
                           case 3:Team[3]--;if(Team[3]==0){CurOwner=4};break;
                           case 4:Team[4]--;if(Team[4]==0){CurOwner=1};break;
                         }
                       q++
                     }
                 }
             }
           document.getElementById("IF_SetField").contentWindow.location.href="CN_SetField.php?fPlayer="+PlayerName.value+"&fTeam="+TeamNum+"&fState=1&Cards="+Cards+"&fStep="+Step
         }

       function PlayGame(Team)
         {
           if(PlayerName.value=="")
             alert("Не указано имя игрока")
           else
             {
               clearTimeout(Wait4GameTimeOut)
               MainTable.style.display="none"
               document.body.background="'.$Images_Dir.'Back_"+Team+"-04.jpg"
               document.getElementById("Waiting").style.visibility="visible"
               document.getElementById("Worker").contentWindow.location.href="CN_UpdatePlayer.php?fPlayer="+PlayerName.value+"&fTeam="+Team+"&fState=1&NeedPlay=2"
             }
         }
     //-->
     </script>
     <table id=MainTable cellpadding=3 cellspacing=10>
       <tr>
         <td>&nbsp;</td>
         <td>
           <button onclick="PlayGame(2)" style="background-color:'.$TeamColor[2].';color:lime;width:200px;height:135px;background:url('.$Images_Dir.'Team_2-01.jpg);display:flex;flex-direction:column;text-align:center"><br><br><br><br><br><br><br><b>Начать игру капитаном</b></button>
         </td>
         <td align=center valign=middle title="Имя игрока">
           Имя&nbsp;<input type=text id=PlayerName style="width:200px" value="'.$Player.'"><br>
           <span style="visibility:hidden">Имя</span>&nbsp;<select id=PlayerList title="Выбор из списка" style="color:silver;width:204px" onchange="if(this.selectedIndex>0){PlayerName.value=this.value}">
             <option>добавить</option>
             ';
    $sql='select * from CN_Players order by Name';
    foreach ($db->query($sql) as $row)
      {
        print '             <option value="'.$row['Name'].'"'.($row['Name']==$Player?' selected':'').'>'.$row['Name'].'</option>';
      }
    print '           </select>
         </td>
         <td class=head>
           &nbsp;
         </td>
         <td>
           <button onclick="PlayGame(3)" style="background-color:'.$TeamColor[3].';color:lime;width:200px;height:135px;background:url('.$Images_Dir.'Team_3-01.jpg);display:flex;flex-direction:column;text-align:center"><br><br><br><br><br><br><br><b>Начать игру капитаном</b></button>
         </td>
         <td>&nbsp;</td>
       </tr>
       <tr>
         <td class=head>&nbsp;</td>
         <td class=head>
           &nbsp;
         </td>
         <td class=head align=right>
         </td>
         <td class=head>&nbsp;</td>
       </tr>
       <tr style="display:none"><td class=card>&nbsp;</td><td class=card>&nbsp;</td><td class=card>&nbsp;</td><td class=card>&nbsp;</td><td class=card>&nbsp;</td></tr>
       <tr style="display:none"><td class=card>&nbsp;</td><td class=card>&nbsp;</td><td class=card>&nbsp;</td><td class=card>&nbsp;</td><td class=card>&nbsp;</td></tr>
       <tr style="display:none"><td class=card>&nbsp;</td><td class=card>&nbsp;</td><td class=card>&nbsp;</td><td class=card>&nbsp;</td><td class=card>&nbsp;</td></tr>
       <tr style="display:none"><td class=card>&nbsp;</td><td class=card>&nbsp;</td><td class=card>&nbsp;</td><td class=card>&nbsp;</td><td class=card>&nbsp;</td></tr>
       <tr style="display:none"><td class=card>&nbsp;</td><td class=card>&nbsp;</td><td class=card>&nbsp;</td><td class=card>&nbsp;</td><td class=card>&nbsp;</td></tr>
     </table>
     <button id=Waiting style="visibility:hidden;background-color:'.$TeamColor[4].';color:lime;width:200px;height:135px;background:url('.$Images_Dir.'Team_4-02.jpg);display:flex;flex-direction:column;text-align:center"><br><br><br><br><br><br><br><b>Подождите, пожалуйста !<b></button><br><br>
     <script type="text/javascript">
     <!--
       Wait4GameTimeOut=setTimeout("Wait4Game()",100)
     //-->
     </script>
';
  }
else
  {
    $sql='select count(*) qRP from CN_Players where Team=2 and State=0';
    foreach ($db->query($sql) as $row)
      {
        $qRP=$row['qRP'];
      }
    $sql='select count(*) qBP from CN_Players where Team=3 and State=0';
    foreach ($db->query($sql) as $row)
      {
        $qBP=$row['qBP'];
      }
    $sql='select count(*) qRC from CN_Players where Team=2 and State=1';
    foreach ($db->query($sql) as $row)
      {
        $qRC=$row['qRC'];
      }
    $sql='select count(*) qBC from CN_Players where Team=3 and State=1';
    foreach ($db->query($sql) as $row)
      {
        $qBC=$row['qBC'];
      }

    if($Player==null || $rState==null)
      {
        print '
         <script>
         <!--
         function SetOwner()
           {
             document.body.style.display=""
             VoiceTimeout=setTimeout("SetVoice()",100)
           }

         function PlayGame(State,Team)
           {
             if(PlayerName.value=="")
               alert("Не указано имя игрока")
             else
               {
                 MainTable.style.display="none"
                 document.body.background="'.$Images_Dir.'Back_"+Team+"-04.jpg"
                 document.getElementById("Worker").contentWindow.location.href="CN_UpdatePlayer.php?fPlayer="+PlayerName.value+"&fTeam="+Team+"&fState="+State+"&NeedPlay=1"
               }
           }
         //-->
         </script>
         <h3><font class=head color=green size=3>Добавление игрока</font></h3>
         <table id=MainTable cellpadding=3 cellspacing=10>
           <tr>
             <td>&nbsp;</td>
             <td>'.($qRC==0?'<button onclick="PlayGame(1,2)" style="background-color:'.$TeamColor[2]
             .';color:lime;width:200px;height:135px;background:url('.$Images_Dir.'Team_2-01.jpg);display:flex;flex-direction:column;text-align:center"><br><br><br><br><br><br><br><b>Войти капитаном<b></button>':'').'&nbsp;</td>
             <td>
               Имя&nbsp;<input type=text id=PlayerName style="width:200px" value="'.$Player.'"><br>
               <span style="visibility:hidden">Имя</span>&nbsp;<select id=PlayerList title="Выбор из списка" style="color:silver;width:204px" onchange="if(this.selectedIndex>0){PlayerName.value=this.value}">
             <option>добавить</option>
             ';
    $sql='select * from CN_Players order by Name';
    foreach ($db->query($sql) as $row)
      {
        print '             <option value="'.$row['Name'].'"'.($row['Name']==$Player?' selected':'').'>'.$row['Name'].'</option>';
      }
    print '           </select>
                         </td>
             <td>'.($qBC==0?'<button onclick="PlayGame(1,3)" style="background-color:'.$TeamColor[3]
             .';color:lime;width:200px;height:135px;background:url('.$Images_Dir.'Team_3-01.jpg);display:flex;flex-direction:column;text-align:center"><br><br><br><br><br><br><br><b><b>Войти капитаном</button>':'').'&nbsp;</td>
             <td>&nbsp;</td>
           </tr>
           <tr>
             <td>&nbsp;</td>
             <td><button onclick="PlayGame(0,2)" style="background-color:'.$TeamColor[2]
               .';color:lime;width:200px;height:135px;background:url('.$Images_Dir.'Team_2-02.jpg);display:flex;flex-direction:column;text-align:center"><br><br><br><br><br><br><br><b>Войти игроком</b></button></td>
             <td align=center><button onclick="window.close()" style="background-color:'.$TeamColor[4]
               .';color:lime;width:200px;height:135px;background:url('.$Images_Dir.'Team_4-02.jpg);display:flex;flex-direction:column;text-align:center"><br><br><br><br><br><br><br><b>Выйти из игры</b></button></td>
             <td><button onclick="PlayGame(0,3)" style="background-color:'.$TeamColor[3].
                ';color:lime;width:200px;height:135px;background:url('.$Images_Dir.'Team_3-02.jpg);display:flex;flex-direction:column;text-align:center"><br><br><br><br><br><br><br><b>Войти игроком</b></button></td>
             <td>&nbsp;</td>
           </tr>
           </table>';
      }
    else
      {
        $sql='select count(*) q from CN_Vocabulary where Used=1 and Team=2';
        foreach ($db->query($sql) as $row)
          {
            $q=$row['q'];
          }

        print '<font style="font:bold" color="'.$TeamColor[$rTeam].'">'.$Player.', Вы '.($rState==0?'игрок':'капитан').' команды '.($rTeam==2?'КРАСНЫХ':'СИНИХ').'</font>&nbsp;-&nbsp;Первый ход команды <font color="'.($q==8?($TeamColor[3].'">СИНИХ'):($TeamColor[2].'">КРАСНЫХ')).'</font><br><br>
         <span id=Info_2 style="color:'.$TeamColor[2].'"></span>&nbsp;&nbsp;<span id=Info_3 style="color:'.$TeamColor[3].'"></span><br><br>
         <span id=Info_4></span><span id=Info_5></span>'
         .($rState==0?'<span id=TurnStep>&nbsp;&nbsp;<button onclick=Paint(0)>Переход хода</button></span>':'').'&nbsp;&nbsp;
         <span id=Info_6></span>&nbsp;&nbsp;
         <span id=Question style="background-color:#eeeeee;color:black">&nbsp;ВОПРОС:&nbsp;
         <span id=Code style="font-weight:bold'.($rState==1?';cursor:pointer" onclick=Ask()':'"').'></span>&nbsp;</span>
         <img src="/VIKS/images/arrow-down.png" valign=middle onclick=\'TheText=document.getElementById("Code").innerText;if(TheText!="'.$NoQuestion.'" && TheText!=""){document.getElementById("Notes").value="Вопрос "+(Step==2?"КРАСНЫХ":"СИНИХ")+": "+TheText+"\n"+document.getElementById("Notes").value}\' title="Скопировать вопрос в блокнот" style="w idth:23px;h eight:23px;cursor:pointer">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         <span id=ChatSpan onclick=Add2Chat() title="Сказать всем" style="cursor:pointer">&nbsp;Чат&nbsp;</span>
         <script>
         <!--
           var
             TimeDelay=100,
             TeamColor=["'.$TeamColor[0].'","'.$TeamColor[1].'","'.$TeamColor[2].'","'.$TeamColor[3].'","'.$TeamColor[4].'","'.$TeamColor[5].'","'.$TeamColor[6].'"],
             Started=0,
             Step='.($q==8?'3':'2').',
             TooOldStep="",
             OldStep=5-'.$rTeam.',
             qBC='.$qBC.',
             qRC='.$qRC.',
             qBP='.$qBP.',
             qRP='.$qRP.',
             Info,
             Team_2="",
             Team_3="",
             Word_R=-1,
             Word_C=-1,
             CurTeam=0,
             CurCard=0,
             Count="",
             Winner=0,
             Painted=0,
             NeedPaint=0,
             Owner=[[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0]]

           function SetOwner()
             {
               document.getElementById("IF_SetOwner").contentWindow.location.href="CN_SetOwner.php"  
             }

           function Paint(td)
             {
               TooOldStep="*"
               if(NeedPaint>0)
                 {
                   alert("Дождитесь отображения карточки !")
                   return
                 }
               NeedPaint=1
               if(Step>3)
                 {
                   alert("ИГРА ЗАВЕРШЕНА")
                   return
                 }
               if(Started==0)
                 {
                   alert("Команды не укомплектованы")
                   return
                 }
               if('.$rTeam.'!=Step)
                 {
                   alert("Сейчас не Ваш ход")
                   return
                 }
               clearTimeout(CN_Timeout)
               if(td==0)
                 Team=0
               else
                 {
                   Team=Owner[td.parentElement.rowIndex][td.cellIndex]
';
        if($CellBgColorStyle==1)
          {
            print '                   td.bgColor=""';
          }
        else
          {
            print '                   td.bgColor=TeamColor[Team]
';
          }
        print '
                   td.style.cursor="default"
                   td.onclick=""
                   if(Math.random()<0.5)
                     NewCard="1"
                   else
                     NewCard="2"
                   td.style.color=Team==1?"black":"white"
                 }
               Question=""
               if(Team==4)
                 {
                   Step=7-Step
                   Initialized=7
                   alert("ИГРА ЗАВЕРШЕНА")
                 }
               else
                 if('.$rTeam.'!=Team || td==0)
                   {
                     if(td!=0)
                       alert("Переход хода")
                     else
                       if(!confirm("Передать ход команде '.($rTeam==3?'КРАСНЫХ':'СИНИХ').' ?"))
                         return
                     Step=5-Step
                     Question="&Question='.$NoQuestion.'"
                   }
               OldStep=5-'.$rTeam.'
               if(td==0)
                 document.getElementById("IF_SetStep").contentWindow.location.href="CN_SetStep.php?fTeam=0&fStep="+Step+"&fRow=-1&fCol=-1&fCard=0&fPlayer='.$Player.'"+Question
               else
                 document.getElementById("IF_SetStep").contentWindow.location.href="CN_SetStep.php?fTeam="+Team+"&fStep="+Step+"&fRow="+td.parentElement.rowIndex+"&fCol="+td.cellIndex+"&fCard="+NewCard+"&fPlayer='.$Player.'"+Question
               CN_Timeout=setTimeout("GetStat()",TimeDelay)
             }

           function ShowStep()
             {
               if(Step=='.$rTeam.' && TooOldStep!="*")
                 {
                   TooOldStep="*"
                   if(Step<4 && OldStep<4 && Started!=0 && Initialized==8)
                     {
';
        if($rState==0)
          {
            print '                       Text2Speak="'.$Player.', ход вашей команды" //+" "+Step+"/"+OldStep+"/"+Started+"/"+Initialized+"/"+NeedPaint
';
          }
        else
          {
            print '                       Text2Speak="'.$Player.', задайте вопрос !" //+" "+Step+"/"+OldStep+"/"+Started+"/"+Initialized+"/"+NeedPaint
';
          }
        print '
                       if(SpeechSynthesisObject==0 || document.getElementById("VoiceSelect").value<0)
                         alert(Text2Speak)
                       else
                         convertTextToSpeech(Text2Speak)
                     }
                 }
               OldStep=Step
             }

           function GetStat()
             {
               clearTimeout(CN_Timeout)
               W=document.getElementById("MainTable").offsetWidth
               if(W!=0 && WidthGot==0 && document.getElementById("Notes"))
                 {
                   OnResize()
                   WidthGot=1
                 }
               C=document.getElementById("Chat").value.substr(9)
               Q=C.length
               if(ChatLength<Q)
                 {
                   if(ChatLength>0)
                     {
                       i=C.indexOf(":")
                       P=C.substr(0,i)
                       if(P!="'.$Player.'")
                         {
                           j=C.indexOf("\n")
                           Text2Speak=P+" пишет: "+C.substring(i+2,j)
                           if(SpeechSynthesisObject==0 || document.getElementById("VoiceSelect").value<0)
                             alert(Text2Speak)
                           else
                             convertTextToSpeech(Text2Speak)
                         }
                     }
                   ChatLength=Q
                 }
               /*>*
               if(Initialized!=7)
               /*|*
               if(Initialized!=6)
               /*|*
               if(Initialized>6)
               /*<*/
                 try
                   {
                     Started=qRP*qBP*qRC*qBC
                     Info_2.innerText="в команде КРАСНЫХ "+(qRP+qRC==0?"нет игроков":FullWord(qRP+qRC,"игрок","","а","ов",1)+": "+Team_2)
                     Info_3.innerText="в команде СИНИХ "+(qBP+qBC==0?"нет игроков":FullWord(qBP+qBC,"игрок","","а","ов",1)+": "+Team_3)
                     Info_4.innerText=Step>3?("ИГРА ЗАВЕРШЕНА: победа команды "+(Winner==2?"КРАСНЫХ":"СИНИХ")):(Started==0?"Команды не укомплектованы: ":"")
                     Info_4.style.color=(Started==0 && Winner==0)?"black":(Step>3?TeamColor[Winner]:TeamColor[Step])
                     Info_5.innerText=Step>3?"":("ход команды "+(Step==2?"КРАСНЫХ":"СИНИХ"))
                     Info_5.style.color=Started==0?"black":TeamColor[Step]
                     Info_6.innerHTML="Счет: <font color=\''.$TeamColor[2].'\'>"+Count.substr(0,1)+"</font>/<font color=\''.$TeamColor[3].'\'>"+Count.substr(2,3)+"</font>"
                     if(CurCard>0 && Word_R>-1 && Word_C>-1)
                       {
                         td=document.getElementById("MainTable").rows[Word_R].cells[Word_C]
                         if(td.title=="")
                           {
                             if(CurTeam!='.$rTeam.')
                               TooOldStep=""
                             td.title=td.innerText.replace(/[\r\n]/g,"")
                             td.innerHTML="<img src=\''.$Images_Dir.'Team_"+CurTeam+"-0"+CurCard+".jpg\'>"
                   ';
      if($CellBgColorStyle==1)
        {
          print '                           td.bgColor=""
';
        }
      else
        {
          print '                           td.bgColor=TeamColor[CurTeam]
';
        }
      print '
                             td.style.cursor="default"
                             td.onclick=""
                             NeedPaint=0
                           }
                       }
                     if(Initialized==0)
                       Initialized=8
                     if(Step>3 && Painted==0)
                       {
';
        if($rState==0)
          {
            print '                         document.getElementById("TurnStep").style.display="none"
';
          }
            print '
                         Q=document.getElementById("Question")
                         Q.style.cursor="default"
                         Q.style.display="none"
                         Q.onclick=""
                         document.getElementById("ChatSpan").style.display="none"
                         B=document.getElementById("ExitButton")
                         B.onclick=function(){document.body.style.display="none";document.getElementById("Worker").contentWindow.location.href="CodeNames.php?Player='.$Player.'"}
                         B.innerText="Выйти"
                         Info_5.style.display=""
                         if(Painted==0)
                           {
                         if(TooOldStep!="*" || '.$rState.'==1)
                           {
                             Text2Speak="'.$Player.', ИГРА ЗАВЕРШЕНА"
                             if(SpeechSynthesisObject==0 || document.getElementById("VoiceSelect").value<0)
                               alert(Text2Speak)
                             else
                               convertTextToSpeech(Text2Speak)
                           }
                         Painted=1
                         with(MainTable)
                           for(r=0;r<5;r++)
                             for(c=0;c<5;c++)
                               {
                                 td=rows[r].cells[c]
                                 if(td.title=="")
                                   {
                                     td.onclick=""
                                     cColor=Owner[r][c]
                                     td.bgColor=TeamColor[cColor]
';
      if($GameOverColorStyle==2)
        {
          print '                                     td.style.color=cColor==1?"black":"white"
                                     td.innerHTML=td.innerText
';
        }
      print '                                     td.style.cursor="default"
                                   }
                               }
                           }
                         Wait4GameTimeOut=setTimeout("Wait4Game()",100)
                         //return
                       }
                   }
                 catch(e)
                   {
                   }

                 /*>*/
                 ShowStep()
                 document.getElementById("IF_GetStat").contentWindow.location.href="CN_GetStat.php?Player='.$Player.'"
                 /*|*
                 if(Initialized!=6)
                   {
                     ShowStep()
                     document.getElementById("IF_GetStat").contentWindow.location.href="CN_GetStat.php?Player='.$Player.'"
                     if(Initialized==7)
                       Initialized=6
                   }
                 /*<*/
             }
             
           function Ask()
             {
               if(Started==0)
                 {
                   alert("Команды не укомплектованы")
                   return
                 }
               if(Step!='.$rTeam.')
                 {
                   alert("Сейчас не Ваш ход")
                   retun
                 }
               Span=document.getElementById("Code")
               r=prompt("Вопрос: ",Span.innerText)
               if(r)
                 {
                   r=r.toUpperCase()
                   Span.innerText=r
                   document.getElementById("IF_SetQuestion").contentWindow.location.href="CN_SetQuestion.php?Player='.$Player.'&Question="+r
                 }
             }

           function OnResize()
             {
               W=document.getElementById("MainTable").offsetWidth
               document.getElementById("Notes").style.width=(W-26)+"px"
               document.getElementById("Chat").style.width=(W-26)+"px"
               document.getElementById("Protocol").style.width=(W-26)+"px"
             }

           window.onresize=OnResize
           CN_Timeout=setTimeout("GetStat()",TimeDelay)
         //-->
         </script>
         <style>
           table td {width:200px;height:135px;border:1px solid black;text-align:center};
         </style>
         <center>
         <table id=MainTable cellpadding=3 cellspacing=10>
         ';
        $sql='select * from CN_Vocabulary where Used=1 order by Word_R,Word_C';
        foreach ($db->query($sql) as $row)
          {
            if($row['Word_C']==0)
              {
                print '<tr>';
              }
            if($row['Card']==null)
              {
                print '<td'.($rState==1?(' bgcolor="'.$TeamColor[$row['Team']].'" style="'):(' bgcolor="'.$TeamColor[0].'" style="')).($rState==1 && $row['Team']!=1?'color:white':'').($rState==1?('">'.$row['Word']):('" onclick=Paint(this)><button style="cursor:pointer;text-align:center;background-color:'.$TeamColor[0].';width:200px;height:135px;background:url('.$Images_Dir.'Team_0-01.jpg);display:flex;flex-direction:column"><br><br><br><br><br><br><b>'.$row['Word'].'</b></button>')).'</td>';
              }
            else
              {
                print '<td title="'.$row['Word'].'"'.($CellBgColorStyle==1?'':(' bgcolor="'.$TeamColor[$row['Team']].'"')).'><img src="'.$Images_Dir.'Team_'.$row['Team'].'-0'.$row['Card'].'.jpg"></td>';
              }

            if($row['Word_C']==4)
              {
                print '</tr>';
              }
          }
        print '</table>
         <script>
         <!--
           function Add2Chat()
             {
               TheText=prompt("Сказать всем:","")
               if(TheText)
                 document.getElementById("IF_SetField").contentWindow.location.href="CN_Add2Chat.php?Player='.$Player.'&TheText="+TheText
             }
         //-->
         </script>
         <textarea id=Notes cols=131 rows=8 title="Блокнот для заметок"></textarea><br>
         <textarea id=Chat cols=131 rows=8 readonly title="Чат" ondblclick=Add2Chat() style="background-color:'.$TeamColor[0].'"></textarea><br>
         <textarea id=Protocol cols=131 rows=8 readonly title="Протокол игры" style="background-color:#eeffee"></textarea><br><br>';
        if($rState==1)
          {
            print '<button id=ExitButton onclick=document.getElementById("IF_ClearGame").contentWindow.location.href="CN_ClearGame.php?Player='.$Player.'">Начать заново</button>&nbsp;';
          }
        else
          {
            print '<button id=ExitButton onclick=location.href="CN_UpdatePlayer.php?fPlayer='.$Player.'&NeedPlay=1">Выйти</button>&nbsp;';
         }
      }
  }

print '
 <script>
 <!--
   document.writeln(ShowVoiceSelect("","ChangeVoice()"))
 //-->
 </script>
 ';

print '
 <span id=IFrames style="display:none"><br><br>
   IF_GetStat<br><iframe id=IF_GetStat s tyle="display:none" style="width:1000px;height:150px"></iframe><br>
   IF_SetStep<br><iframe id=IF_SetStep s tyle="display:none" style="width:1000px;height:150px"></iframe><br>
   IF_SetQuestion<br><iframe id=IF_SetQuestion s tyle="display:none" style="width:1000px;height:150px"></iframe><br>
   Worker<br><iframe id=Worker s tyle="display:none" style="width:1000px;height:150px"></iframe><br>
   IF_SetField<br><iframe id=IF_SetField s tyle="display:none" style="width:1000px;height:150px"></iframe><br>
   IF_ClearGame<br><iframe id=IF_ClearGame s tyle="display:none" style="width:1000px;height:150px"></iframe><br>
   IF_SetOwner<br><iframe id=IF_SetOwner s tyle="display:none" style="width:1000px;height:150px" s rc="GetOwner"></iframe>
 </span>';

print '</table></center><br><br><DIV align=left><IMG border=no alt="" src="data:image/gif;base64,R0lGODlhLwAPAPcAAAD/AAAAAACAAIAAAP//AP8AAICAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAEAAAEALAAAAAAvAA8ABwjXAAMIHEiwoMEABQYcXMiwoICHCxUOTNiwosGHADJCLEjxIEUDIA8+FCBSI0YAJCMOkBhAogECBEIKPInSocmRGg2uLMBzJcGXMGMGEJCxaMqBRGliPDpwAM+nLAMAhWlgqNGNMzWaRJnTp9OnUAdOpXp1JESiXGueJMmzJdiwPMfGNFo0J9q7SXM2fduz5QC5Bq7WTZkULcqkOt9GHVvVsOGaVg/jhExwZ8/FQYU+Hoz0pt6mTX0WBCpzqGPESE8XdBqxgECQVS9uxJqaaevKFnPrdrubYUAAOw==" align=absMiddle><font color=green size=-2>&nbsp;2025<span id=Year></span></font></div>
</body>
</html>';
/*
 ******** End ********/
?>
