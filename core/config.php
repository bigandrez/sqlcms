<?php


  function check_master_pass($pass){return md5('SaLt'.$pass)==md5('SaLt'.'123');}

//include_once "sqljsondb.class.php";
    
global $db;
  try{
//    $db = new SqlJsonDb(array('user'=> 'root2','pass'=> '','db'=> 'sqlcms','charset' => 'utf8'));
  } catch (Exception $e){
    $db=null;
  }

    