<?php
global $core;
include_once($core->pathes['SITE_PATH'].'core/db.interface.php');
include_once('safemysql.class.php');

//return 123;

  $db = new SafeMySQL(array('user'=> $core->settings['mysql']['db_user'],'pass'=> $core->settings['mysql']['db_pass'],'db'=> $core->settings['mysql']['db_name'],'charset' => 'utf8'));
  unset($core->settings['mysql']);


return $db;