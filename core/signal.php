<?php
global $core;
  $ret = '';
  if (is_array($core->signals[''])) foreach($core->signals[''] as $v){
    $r = include($v);
    if ($r!==1)
      $ret .= $r;
  }
  if (is_array($core->signals[$core->signal_name])) foreach($core->signals[$core->signal_name] as $v){
    $r = include($v);
    if ($r!==1)
      $ret .= $r;
  }
  return $ret;