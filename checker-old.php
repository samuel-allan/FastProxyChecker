<?php
/*
Please note - Do not use this script after 11/16/2015 When a new verion will be released
*/

$useonly = false;
$disabled = false;
foreach($argv as $arg)
{
	if($arg == "-nolicense" || $arg == "--nolicense")
	{
		$disabled = true;
	}
	if($arg == "-useonly" || $arg == "--useonly")
	{
		$useonly = true;
	}
}
if(!$disabled)
{
	echo "Based on PHP code found here: http://packetstormsecurity.com/files/114965/Multithreaded-Proxy-Checker.html\n\n\nSecondary Author : Samuel Allan\nOriginal Author : Miyachung<Miyachung@hotmail.com>\n use -nolicense to ignore this text\n\n\n";
}
set_time_limit(0);
if(!isset($argv[1]))
{
  echo "You can also supply all the info via command line: php checker.php InputFile.txt noThreads TimeoutSec";
  echo "\n[+]Enter your proxy list: ";
  $proxy_list = fgets(STDIN);
  $proxy_list = str_replace("\r\n","",$proxy_list);
  $proxy_list = trim($proxy_list);
}
else
{
  $proxy_list = $argv[1];
  $proxy_list = str_replace("\r\n","",$proxy_list);
  $proxy_list = trim($proxy_list);
}

if(!isset($argv[2]))
{
  echo "[+]Enter number of thread: ";
  $thread = fgets(STDIN);
  $thread = str_replace("\r\n","",$thread);
  $thread = trim($thread);
}
else
{
  $thread = $argv[2];
  $thread = str_replace("\r\n","",$thread);
  $thread = trim($thread);
}
if(!isset($argv[3]))
{
  echo "[+]Enter timeout sec: ";
  $timeout = fgets(STDIN);
  $timeout = str_replace("\r\n","",$timeout);
  $timeout = trim($timeout);
}
else
{
  $timeout = $argv[3];
  $timeout = str_replace("\r\n","",$timeout);
  $timeout = trim($timeout);
}
  if(!$useonly){echo "[+]Checking proxies (To disable everything except proxies please use -useonly option)\n";
  echo "-------------------------------------------------------\n";}
  $open_file  =  file($proxy_list);
  $open_file  =  preg_replace("#\r\n#si","",$open_file);

    
  checker($open_file,$thread);
/*-----------------------------------------------------------------------*/
function checker($ips,$thread)
{
  global $timeout;
  
  $multi   = curl_multi_init();
  $ips   = array_chunk($ips,$thread);
  $total   = 0;
  $time1  = time();
    foreach($ips as $ip)
    {
      for($i=0;$i<=count($ip)-1;$i++)
      {
      $curl[$i] = curl_init();
      curl_setopt($curl[$i],CURLOPT_RETURNTRANSFER,1);
      curl_setopt($curl[$i],CURLOPT_URL,$ip[$i]);
      curl_setopt($curl[$i],CURLOPT_TIMEOUT,$timeout);
      curl_multi_add_handle($multi,$curl[$i]);
      }
      
      do
      {
      curl_multi_exec($multi,$active);
      usleep(11);
      }while( $active > 0 );
      
      foreach($curl as $cid => $cend)
      {
        $info = curl_getinfo($cend);
        curl_multi_remove_handle($multi,$cend);
        if($info['http_code'] != 0)
        {
          $total++;
          echo $ip[$cid];
          save_file("works.txt",$ip[$cid]);
        }
      }
    }
  $time2 = time();
  if($useonly){echo "\n[+]Total working proxies: $total,checking completed\n";
  echo "[+]Elapsed time -> ".($time2-$time1)." seconds\n";}
}
  
function save_file($file,$content)
{
  $open = fopen($file,'ab');
  fwrite($open,$content."\r\n");
  fclose($open);
}

?>
