<?php 

// Отладочная точка для получения списка зарегестрированных в ходе выполнения скрипта
// сигналов и событий с соответствующими таймаутами.
// Пример использования: localhost/admin/?debugpoint=signal_list

global $core;

  include_once 'destructableclass.inc';

  global $destructableclass;
  if (!$destructableclass) $destructableclass = new MyDestructableClass();
  if (!$destructableclass->registered_signals) {
    $destructableclass->registered_signals[] = array('event'=>'apache start','timeout'=>0);
    $destructableclass->registered_signals[] = array('event'=>'cmf start','timeout'=>microtime(TRUE)-$core->cmf_start_time);
  }
  $destructableclass->registered_signals[] = array('signal'=>$core->signal_name,'timeout'=>microtime(TRUE)-$_SERVER['REQUEST_TIME_FLOAT']);

  if ($name=='last_call'){
//    $registered_signals[] = array('signal'=>'last_call','timeout'=>microtime(TRUE)-$_SERVER['REQUEST_TIME_FLOAT']);
//    echo "\r\n=============================\r\nList of registered signals:\r\n\r\n";
//    print_r($registered_signals);
    return;
  }
