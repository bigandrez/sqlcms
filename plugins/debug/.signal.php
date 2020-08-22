<?php
global $core;

/*
  if ($name=='last_call' && isset($_GET['debugsignal'])){
    global $registered_signals;
    $registered_signals[] = array('signal'=>'last_call','timeout'=>microtime(TRUE)-$_SERVER['REQUEST_TIME_FLOAT']);
    echo "\r\n=============================\r\nList of registered signals:\r\n\r\n";
    print_r($registered_signals);
    exit(0);
  }

  if (isset($_GET['debugsignal'])){
    global $registered_signals;
    if (!$registered_signals) {
      $registered_signals[] = array('event'=>'apache start','timeout'=>0);
      global $cmf_start_time;
      $registered_signals[] = array('event'=>'cmf start','timeout'=>microtime(TRUE)-$core->cmf_start_time);
    }
    $registered_signals[] = array('signal'=>$name,'timeout'=>microtime(TRUE)-$_SERVER['REQUEST_TIME_FLOAT']);
    return;
  }

*/
global $testpoing;
  if (!$testpoing && $_GET['debugpoint']){
    $testpoing = $_GET['debugpoint'];
    unset($_GET['debugpoint']);
    $s = 'debugpoint='.$testpoing;
    if ( ($p = strpos($_SERVER['QUERY_STRING'],'&'.$s.'&')) !==false)
      $_SERVER['QUERY_STRING'] = substr($_SERVER['QUERY_STRING'],0,$p).substr($_SERVER['QUERY_STRING'],$p+strlen('&'.$s.'&'));
    if ( ($p = strpos($_SERVER['QUERY_STRING'],$s.'&')) !==false)
      $_SERVER['QUERY_STRING'] = substr($_SERVER['QUERY_STRING'],0,$p).substr($_SERVER['QUERY_STRING'],$p+strlen($s.'&'));
    if ( ($p = strpos($_SERVER['QUERY_STRING'],'&'.$s)) !==false)
      $_SERVER['QUERY_STRING'] = substr($_SERVER['QUERY_STRING'],0,$p).substr($_SERVER['QUERY_STRING'],$p+strlen('&'.$s));
    if ( ($p = strpos($_SERVER['QUERY_STRING'],$s)) !==false)
      $_SERVER['QUERY_STRING'] = substr($_SERVER['QUERY_STRING'],0,$p).substr($_SERVER['QUERY_STRING'],$p+strlen($s));

    if ( ($p = strpos($_SERVER['REQUEST_URI'],'&'.$s.'&')) !==false)
      $_SERVER['REQUEST_URI'] = substr($_SERVER['REQUEST_URI'],0,$p).substr($_SERVER['REQUEST_URI'],$p+strlen('&'.$s.'&'));
    if ( ($p = strpos($_SERVER['REQUEST_URI'],$s.'&')) !==false)
      $_SERVER['REQUEST_URI'] = substr($_SERVER['REQUEST_URI'],0,$p).substr($_SERVER['REQUEST_URI'],$p+strlen($s.'&'));
    if ( ($p = strpos($_SERVER['REQUEST_URI'],'&'.$s)) !==false)
      $_SERVER['REQUEST_URI'] = substr($_SERVER['REQUEST_URI'],0,$p).substr($_SERVER['REQUEST_URI'],$p+strlen('&'.$s));
    if ( ($p = strpos($_SERVER['REQUEST_URI'],$s)) !==false)
      $_SERVER['REQUEST_URI'] = substr($_SERVER['REQUEST_URI'],0,$p).substr($_SERVER['REQUEST_URI'],$p+strlen($s));

  }

  if ($testpoing){
    $files = $core->glob_recursive(str_replace('\\','/',dirname(__FILE__)).'/debugpoint/'.$testpoing.'.php');
    $files = array_merge($files, $core->glob_recursive(str_replace('\\','/',dirname(__FILE__)).'/debugpoint/'.$testpoing.'.'.$core->signal_name.'.php'));
    if (is_array($files)) foreach($files as $f){
      ob_start();  
      include($f);
      $content = ob_get_contents();
      ob_end_clean();
      if ($content) {
        print "Break on testpoint [".$testpoing."] with signal [".$core->signal_name."]. File: ".$f."\r\n";
        print $content;
        exit(0);
      }
    }
  }

/*

  $debug_poing = $_GET['debugpoint'];

  //проверка testpoing
  if(!preg_match("/[a-zA-Z0-9_]+$/i",$debug_poing))
    return;

  $test_point = $core->db->getAll("select * from debug_phptest where name=?s limit 1", $debug_poing);
  $test_point = $test_point[0];
  if (!$test_point) return;

  $phpcode = $test_point['phpcode'];

  if (!$phpcode) return;
   
  ob_start();  
  eval($phpcode);
  $content = ob_get_contents();
  ob_end_clean();

  print $content;
  exit(0);
*/
