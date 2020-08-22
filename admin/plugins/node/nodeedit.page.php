<?php
global $core;

    $json_string = json_decode(file_get_contents('php://input'),TRUE);

//print_r($_GET);
//print_r($json_string);exit(0);


  $sql .= "update ?n set ?n=?s where ";
  if ($_GET['table']=='node')
    $sql .= "nid=?i";
  else
    $sql .= "id=?i";
//print_r($sql);exit(0);
  try{
    $core->db->query($sql,$_GET['table'],$_GET['column'],$json_string['real_value'],0+$_GET['nodeedit']);
  } catch (Exception $e) {
    header('HTTP/1.0 404 Not Found');
  }

return 'empty';