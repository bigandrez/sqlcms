<?php
global $core;
  include_once($core->pathes['CORE_PATH'].'less.php');

  $less = new lessc;
  $ret=$less->compileFile($core->pathes['SITE_PATH'].'themes/'.$core->pathes['THEME'].'/less/top.less');

  $ret = minify($ret);
  return '<style>'.$ret.'</style>';
