<?php

  $less = new lessc;
  $ret=$less->compileFile($_SERVER['SITE_PATH'].'themes/'.$_SERVER['THEME'].'/less/bottom.less');

  $ret = minify($ret);
  return '<style>'.$ret.'</style>';
