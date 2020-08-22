<?php

  $less = new lessc;
  $ret=$less->compileFile($core->pathes['SITE_PATH'].'themes/'.$core->pathes['THEME'].'/less/bottom.less');

  $ret = minify($ret);
  return '<style>'.$ret.'</style>';
