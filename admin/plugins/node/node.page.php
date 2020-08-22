<?php
global $core;
$core->headers[]='<title>Node add/view/edit/delete</title>';
$core->footers[]='<link rel="stylesheet" type="text/css" href="'.$core->pathes['EXEC_PAGE_LINK'].'inc/css.css">';
$core->footers[]='<script type="text/javascript" src="'.$core->pathes['EXEC_PAGE_LINK'].'inc/nodeedit.js"></script>';


$crlf='
';
  $page = array();
  if (!preg_match("/([^?\.]*)/", $_SERVER['REQUEST_URI'], $page)) return;
  $page = $page[0]=='/' ? '' : $page[0];



  $nid = 0+is_numeric($_GET['node'])?$_GET['node']:0;

  if ($nid) 
    return include('nodeedit.php');

  // ѕолучаем список доступных типов материалов
  $node_tables = $core->db->getAll("
    SELECT information_schema.tables.table_name FROM information_schema.tables
    where (information_schema.tables.table_name like 'node\_%') AND information_schema.tables.TABLE_SCHEMA=database();
  ");
  $nodetypes = array();
  foreach($node_tables as $v) $nodetypes[$v['table_name']]='';

  if (isset($nodetypes[$_GET['node']]))
    return include('node_by_type.php');


  return include('show_nodetypes.php');



return 'default';