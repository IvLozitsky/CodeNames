<?php
/******** ODG * 20230223 * Общий файл
 *        End * 20250316 * ODG.php
 */
  ini_set('display_errors', '1');
  error_reporting(E_ALL);
  $dsn='mysql:host=zabviks.lh1.in;dbname=zabviksl_VIKS';
  $db=new PDO($dsn,'zabviksl_ODG','shyamahm');
  $Rome[1]='I';
  $Rome[2]='II';
  $Rome[3]='III';
  $Rome[4]='IV';

  function FullWord($Num,$Root,$Fin1,$Fin2,$Fin3,$Place)
    {
      $Mod100=$Num % 100;
      $Mod10=$Num % 10;
      if(($Mod100==11)||($Mod100==12)||($Mod100==13)||($Mod100==14))
        {
          $Root.=$Fin3;
        }
      else
        if(($Mod10==2)||($Mod10==3)||($Mod10==4))
          {
            $Root.=$Fin2;
          }
        else
          {
            if ($Mod10==1)
              {
                $Root.=$Fin1;
              }
                  else
              {
                $Root.=$Fin3;
              }
          }
      switch($Place)
        {
          case 1:return $Num.' '.$Root;break;
          case -1:return $Root.' '.$Num;break;
          default:return $Root;
        }
    }

  function TestNull($val)
    {
      return $val==null?'null':$val;
    }

  //echo '<pre>';
  //var_dump($aStr);
  //echo '<pre>';

  //echo '<!-- '.$sql.' -->';

/*
$sql='update ';
$db->query($sql);
*/

/*
$sql='';
foreach ($db->query($sql) as $row)
  {
  }
*/

/*
 ******** End ********/
?>
