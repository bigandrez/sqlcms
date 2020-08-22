<?php
$crlf = '
';
global $core;

  if ($core->settings['debug']['req_log_enable']){
    $dir = $core->pathes['CACHE_PATH'].'reqlog';
    if (!is_dir($dir))
      mkdir($dir);
    $file = $dir.'/'.time().'.req';
    file_put_contents($file,date('d.m.Y H:i:s').$crlf.$crlf,FILE_APPEND);
    if ($core->settings['debug']['log_get'])
    file_put_contents($file,'$_GET:'.print_r($_GET,TRUE).$crlf,FILE_APPEND);
    if ($core->settings['debug']['log_post'])
    file_put_contents($file,'$_POST:'.print_r($_POST,TRUE).$crlf,FILE_APPEND);
    if ($core->settings['debug']['log_files'])
    file_put_contents($file,'$_FILES:'.print_r($_FILES,TRUE).$crlf,FILE_APPEND);
    if ($core->settings['debug']['log_cookie'])
    file_put_contents($file,'$_COOKIE:'.print_r($_COOKIE,TRUE).$crlf,FILE_APPEND);
    if ($core->settings['debug']['log_session'])
    file_put_contents($file,'$_SESSION:'.print_r($_SESSION,TRUE).$crlf,FILE_APPEND);
    if ($core->settings['debug']['log_request'])
    file_put_contents($file,'$_REQUEST:'.print_r($_REQUEST,TRUE).$crlf,FILE_APPEND);
    if ($core->settings['debug']['log_env'])
    file_put_contents($file,'$_ENV:'.print_r($_ENV,TRUE).$crlf,FILE_APPEND);
    if ($core->settings['debug']['log_server'])
    file_put_contents($file,'$_SERVER:'.print_r($_SERVER,TRUE).$crlf,FILE_APPEND);
    if ($core->settings['debug']['log_globals'])
      file_put_contents($file,'$GLOBALS:'.print_r($GLOBALS,TRUE).$crlf,FILE_APPEND);

  }
return;




  if (isset($_GET['update_dbproc'])){
//    update_table_to_json();
//    update_sqlnode_to_json();
    update_table_to_json_proc();
    update_parents_for_node();
    print 'update complete';
    exit(0);
  }

  if (!isset($_GET['debug'])) return;



  for ($i=0;$i<100;$i++){
    $r = "insert into table_test (nid,weight,data) values (7,0,'".md5($i)."')";
//    $db->query($r);
//print $r.$crlf;
  }












  $nid = 0 + $_GET['debug'];

  if ($nid>0){


/*
    $a = $db->query("set @j='';");
    $a = $db->query("call NodeToJson(".$nid.",@j);");
    $db->clear_result();
    $t = $db->getOne("select @j ;");
*/

//    $t = $db->getOne("select _node_to_json(".$nid.");");
/*
    $db->query("call _node_to_json_proc('table_test.row_offset=\"2\"',".$nid.",@j);");
    $db->clear_result();
    $t = $db->getOne("select @j;");
*/
    $t = $db->node_load($nid,array(),TRUE);

    print 'result json:'.$crlf;
    print $t;
    print $crlf.$crlf;
    print 'result array:'.$crlf;


    $t = json_decode($t,TRUE);
    print_r($t);
    print $crlf.$crlf;


  }

  exit(0);




//print_r($_SERVER);


//print_r($data);
//print 'before_process signal!!';
