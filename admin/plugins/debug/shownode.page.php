<h1>Shownodes </h1>
<?php
$headers[]='<title>Shownodes plugin</title>';
global $core;
$crlf='
';
  $page = array();
  if (!preg_match("/([^?\.]*)/", $_SERVER['REQUEST_URI'], $page)) return;
  $page = $page[0]=='/' ? '' : $page[0];

  $nodetypes = array('node_users', 'node_roles','node_groups','node_rights');
  $ret='';

  $nid = $_GET['shownode'];
  if (is_numeric($nid[0])){

  $time = -microtime(TRUE);

    $t1 = $core->db->node_load($nid,array(),TRUE);
//print $t1;exit(0);

    $t2 = json_decode($t1,TRUE);
  $time += microtime(TRUE);



?>
<div>
<h2>Time: <?=$time?></h2>
<h2>Result json:</h2>
<pre style="white-space: pre-wrap;"><?=$t1;?></pre>
<br/>
<h2>Result array:</h2>
<pre style="white-space: pre-wrap;">
<?=print_r($t2,TRUE);?>
</pre></div>
<?php  } else
  if (in_array($nid,$nodetypes)){
    $t1 = $core->db->sql_node_load("select nid from node where res=\\'".$nid."\\'",array(),TRUE);
    $t2 = json_decode('['.$t1.']',TRUE);?>
<div>
<h2>Result json:</h2>
<pre style="white-space: pre-wrap;"><?=$t1;?></pre>
<br/>
<h2>Result array:</h2>
<pre style="white-space: pre-wrap;">
<?=print_r($t2,TRUE);?>
</pre></div>
<?php
    print '<pre>'.print_r($t,TRUE).'</pre>';
  } else {
    print '<pre>';
    print 'node id or node type required. Next parameters available: '.$crlf;
    print '<a href='.$page.'?shownode=1>'.$page.'?shownode=1</a>'.$crlf;

    foreach($nodetypes as $v){
      print '<a href='.$page.'?shownode='.$v.'>'.$page.'?shownode='.$v.'</a>'.$crlf;
    }
  }
?>
<?php return 'default';