<?php
  session_start();
/*
  $rq = strpos($_SERVER['REQUEST_URI'],'?');
  $rq = $rq==FALSE ? $_SERVER['REQUEST_URI'] : substr($_SERVER['REQUEST_URI'],0,$rq);
  $rf = strripos($rq,'/');
  $rq = dirname(__FILE__).substr($rq,0,$rf).'/index.php';
  if (is_file($rq)){
    include($rq);
    exit(0);
  }
print $rq;
*/
include 'core/core.php';
  $core = new Core;

  // Если запрашивают css файл, то смотрим - нет ли такого же, но с расширением less, компилим его и выдаем
  $fpath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
  $fname = basename($fpath);
  $fpath = dirname($fpath);
  if (substr($fname,strlen($fname)-4)=='.css'){
    $core->run(TRUE);
    $lessfile = $core->pathes['SITE_PATH'].$fpath.'/'.str_replace('.css','.less',$fname);
    if (is_file($lessfile)){
      $less = new lessc;
      $ret=$less->compileFile($lessfile);
      header('Content-Type: text/css;charset=UTF-8');
      print minify($ret);

    } else
      header('HTTP/1.0 404 Not Found');
    exit(0);
  }

  $core->run();

  $call_default=TRUE;
  if (is_file($core->pathes['SITE_PATH'].'themes/'.$core->pathes['THEME'].'/index.php'))
    if (include($core->pathes['SITE_PATH'].'themes/'.$core->pathes['THEME'].'/index.php'))
      $call_default=FALSE;
  // Если файл инициализации темы что-вернул - то файл инициализации темы по умолчанию не вызываем
  if ($call_default && is_file($core->pathes['SITE_PATH'].'themes/default/index.php'))
    include($core->pathes['SITE_PATH'].'themes/default/index.php');


  // Если для этого пути нет своей страницы
  if (!$core->pathes['EXEC_PAGE_FILE'] || !is_file($core->pathes['EXEC_PAGE_FILE'])) {
    if (is_file($core->pathes['SITE_PATH'].'themes/'.$core->pathes['THEME'].'/templates/404.php'))
      include($core->pathes['SITE_PATH'].'themes/'.$core->pathes['THEME'].'/templates/404.php');
    else
      if (is_file($core->pathes['SITE_PATH'].'themes/default/templates/404.php'))
        include($core->pathes['SITE_PATH'].'themes/default/templates/404.php');
    $core->signal('after_process');
    $core->signal('last_call');
    exit(0);
  } 

  ob_start();
  $template = include($core->pathes['EXEC_PAGE_FILE']) ;
  $content = ob_get_clean();


  // Если исполняемая страница не вернула имя шаблона
  if (!$template) $template = 'default';
  // Ищем и выполняем требуемый шаблон
  if (is_file($core->pathes['SITE_PATH'].'themes/'.$core->pathes['THEME'].'/templates/'.$template.'.tpl.php'))
    include($core->pathes['SITE_PATH'].'themes/'.$core->pathes['THEME'].'/templates/'.$template.'.tpl.php');
  elseif (is_file($core->pathes['SITE_PATH'].'themes/default/templates/'.$template.'.tpl.php'))
    include($core->pathes['SITE_PATH'].'themes/default/templates/'.$template.'.tpl.php');
  else
    include($core->pathes['SITE_PATH'].'themes/default/templates/default.tpl.php');

$testvar = 'testvar';
  $core->signal_name = 'last_call';
  include $core->pathes['SITE_PATH'].'core/signal.php';
//  $core->signal('last_call');

exit(0);
