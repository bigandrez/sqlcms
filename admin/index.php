<?php

  date_default_timezone_set('Asia/Novosibirsk');

  session_start();

  include 'core.php';

  $core = new AdminCore;
  $core->run();
//print_r($core->pathes);exit(0);
  $core->footers[]='<link rel="stylesheet" type="text/css" href="'.$core->pathes['ADMIN_LINK'].'templates/inc/css.css">';
  $core->footers[]='<script defer src="'.$core->pathes['ADMIN_LINK'].'templates/inc/js.js"></script>';

  // Если для этого пути есть своя страница, то вызываем её
  if (!$core->pathes['EXEC_PAGE_FILE']){
    include($core->pathes['ADMIN_PATH'].'templates/404.tpl.php');
    $core->signal('after_process');
    exit(0);
  }

  ob_start();
  if (is_file($core->pathes['EXEC_PAGE_FILE']))
    $template = include($core->pathes['EXEC_PAGE_FILE']) ;
  $content = ob_get_clean();

  // Ищем и выполняем требуемый шаблон

  $r = $template;
  while(1){
    if (!is_file($core->pathes['ADMIN_PATH'].'templates/'.$r.'.tpl.php'))
      break;
    ob_start();
    $nr = include($core->pathes['ADMIN_PATH'].'templates/'.$r.'.tpl.php');
    $content = ob_get_clean();
    $r = $r=='default' ? FALSE : $nr;
    if ($r === TRUE) $r = 'default';
  }

  print $content;

  $core->signal('last_call');

exit(0);