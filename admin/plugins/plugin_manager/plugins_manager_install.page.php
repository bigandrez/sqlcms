<?php
global $core;

  $plugin = $_GET['plugins_manager_install'];

  if (strlen($plugin)>255 || !preg_match("#^[aA-zZ0-9\-_]+$#",$plugin)) {
    header('Location: ?plugins_manager');
    exit(0);
  }

  $site_plugin_settings = array();
  $admin_plugin_settings = array();

  try{
    $installl_file = $core->pathes['SITE_PATH'].'plugins/'.$plugin.'/install.php';
    if (is_file($installl_file))
        @include ($installl_file);
    $installl_file = $core->pathes['ADMIN_PATH'].'plugins/'.$plugin.'/install.php';
    if (is_file($installl_file))
        @include ($installl_file);
  } catch (Error $e) {
    print 'Ошибка при попытке установки плагина '.$plugin.'<br/>Вызов файла '.$installl_file.' привёл к ошибке в строке '.$e->getLine().':<br/>'.$e->getMessage();
//print_r($e);
    return 'default';
  } catch (Exception $e) {
    print 'Ошибка при попытке установки плагина '.$plugin.'<br/>Вызов файла '.$installl_file.' привёл к ошибке в строке '.$e->getLine().':<br/>'.$e->getMessage();
//print_r($e);
    return 'default';
  }
  $settings_file = $core->pathes['SITE_PATH'].'plugins/'.$plugin.'/settings.json';
  if (is_file($settings_file))
      $site_plugin_settings = json_decode(file_get_contents($settings_file),TRUE);

  $settings_file = $core->pathes['ADMIN_PATH'].'plugins/'.$plugin.'/settings.json';
  if (is_file($settings_file))
      $admin_plugin_settings = json_decode(file_get_contents($settings_file),TRUE);
  
  $settings = array_replace_recursive($site_plugin_settings,$admin_plugin_settings);
//print_r($settings);exit(0);

  // Удаляем все, что не относится к настройкам - то есть все поля, начинающиеся с символа #
//  foreach($settings as $i=>$v) if (substr($i,0,1)=='#') unset($settings[$i]);

  $settings_file = $core->pathes['SITE_PATH'].'config/'.$plugin.'.disabled';
  file_put_contents($settings_file,json_encode($settings,JSON_UNESCAPED_UNICODE/*|JSON_NUMERIC_CHECK*/));

  header('Location: ?plugins_manager');

  exit(0);

  return 'default';

