<?php
global $core;

  include "config.php";
  include "db.interface.php";
  include "log.interface.php";
  include "less.php";

  include_once 'sqlcmscore.class.php';

  function tolog($msgtext, $group="common", $msgpriority = 0, &$args = array()){
    global $core;
    if ($core->log instanceOf iSqlJsonLog)
      $core->log->tolog($msgtext, $group, $msgpriority, $args);
  }

  function systemlog($message, $priority=LOG_WARNING, $fromfile=""){
  }

return;


print_r('1231231234');
exit(0);



  $headers[] = '<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">';
  $headers[] = '<meta charset="utf-8">';
  $headers[] = '<meta http-equiv="X-UA-Compatible" content="IE=edge">';
  $footers = array();


  prepare_signals();
  prepare_pages();
  prepare_snippets();
  prepare_templates();
  prepare_settings();
  prepare_db();













// Проверка существования таблицы и/или колонки
function check_table_column($table,$column=''){
  return TRUE;
}