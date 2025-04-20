<?php
/******** ODG * 20250315 * Проверка данных CodeNames
 *        End * 20250420 * CN_Statistic.php
 */
include 'ODG.php';
print '<title>Statistic</title><body onload=alert("Готово")><center>
 <table border=1 width="90%" cellpadding=3 cellspacing=0>
   <tr>
     <th>№</th>
     <th>Имя</th>
     <th>Статус</th>
     <th>Команда</th>
     <th>Шаг</th>
     <th>Голос</th>
   </tr>';
$n=1;
$sql='select * from CN_Players where State is not null';
foreach ($db->query($sql) as $row)
  {
    print '
     <tr>
       <td align=center>'.$n.'</td>
       <td>&nbsp;'.$row['Name'].'</td>
       <td align=center>'.$row['State'].'</td>
       <td align=center>'.$row['Team'].'</td>
       <td align=center>'.$row['Step'].'</td>
       <td>&nbsp;'.$row['Voice'].'</td>
     </tr>';
    $n++;
  }
print '
 </table><br><br>
   <table border=1 width="90%" cellpadding=3 cellspacing=0>
   <tr>
     <th>Протокол</th>
     <th>Строка</th>
     <th>Столбец</th>
     <th>Цвет</th>
     <th>Карточка</th>
     <th>Игроки</th>
     <th>Чат</th>
     <th>Вопрос</th>
   </tr>';
$sql='select * from CN_Last_Word';
foreach ($db->query($sql) as $row)
  {
    print '
     <tr>
       <td valign=top>'.$row['Protocol'].'&nbsp;</td>
       <td valign=top align=center>&nbsp;'.$row['Word_R'].'</td>
       <td valign=top align=center>&nbsp;'.$row['Word_C'].'</td>
       <td valign=top align=center>&nbsp;'.$row['Team'].'</td>
       <td valign=top align=center>&nbsp;'.$row['Card'].'</td>
       <td valign=top>'.$row['Players'].'&nbsp;</td>
       <td valign=top>'.$row['Chat'].'&nbsp;</td>
       <td valign=top>'.$row['Question'].'&nbsp;</td>
     </tr>';
  }
print '
 </table><br><br>
 <table border=1 width="90%" cellpadding=3 cellspacing=0>
   <tr>
     <th>№</th>
     <th>Слово</th>
     <th>Строка</th>
     <th>Столбец</th>
     <th>Цвет</th>
     <th>Карточка</th>
     <th>Время</th>
   </tr>';
$n=1;
$sql='select * from CN_Vocabulary where Used=1 order by Word_R,Word_C';
foreach ($db->query($sql) as $row)
  {
    print '
     <tr>
       <td align=center>'.$n.'</td>
       <td>&nbsp;'.$row['Word'].'</td>
       <td align=center>'.$row['Word_R'].'</td>
       <td align=center>'.$row['Word_C'].'</td>
       <td align=center>'.$row['Team'].'</td>
       <td align=center>'.TestNull($row['Card']).'</td>
       <td align=center>&nbsp;'.$row['Time'].'</td>
     </tr>';
    $n++;
  }
print '</table>';
/*
 ******** End ********/
?>
