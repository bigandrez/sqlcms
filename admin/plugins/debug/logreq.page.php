<h1>Лог запросов к сайту</h1>
<?php
  $headers[]='<title>Лог запросов к сайту</title>';

  $logtable=array();

  $dir = $_SERVER['CACHE_PATH'].'reqlog';
  if (is_dir($dir)){
    $files = glob($dir.'/*.req');
    if (is_array($files)) foreach($files as $logfile){
      $fname = basename($logfile);
      $time = substr($fname,0,strlen($fname)-4);
      $logtable[]=array('time'=>date("d-m-Y H:i:s",$time),'file'=>$logfile);

    }
  }


//  print '<pre>';  print_r($logtable);  print '</pre>';

 


  print '<table class="loglist">';
  foreach($logtable as $l){
    print '<tr><td>'.$l['time'].'</td><td><a href="'.$_SERVER['CACHE_LINK'].'reqlog/'.basename($l['file']).'">'.$l['file'].'</a></td></tr>';
  }
  print '</table>';

?>
<style>
.loglist td{
padding:5px;
}
</style>

<?
return 'default';