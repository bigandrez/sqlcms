<h1>Plugin manager - работа с плагинами</h1>
<?php
global $core;

function prepare_plugin_settings(){
global $core;

  $pathes = array($core->pathes['SITE_PATH'].'plugins/',$core->pathes['ADMIN_PATH'].'plugins/');
  foreach($pathes as $path){

    $dir = opendir($path);
    while($file = readdir($dir)) {
      if ($file == '.' || $file == '..') continue;
  
      $settings_file = $core->pathes['SITE_PATH'].'config/'.$file;
      if (!is_array($core->settings[$file])) $core->settings[$file]=array();
  
      if (is_file($path.$file.'/settings.json')){
        $json = json_decode(file_get_contents($path.$file.'/settings.json'),TRUE);
        if (is_array($json)) $core->settings[$file] = array_replace_recursive ($json, $core->settings[$file]);
      }
  
      if (is_file($settings_file.'.enabled')){
        $core->settings[$file]['#status']='enabled';
      } elseif (is_file($settings_file.'.disabled')){
        $json = json_decode(file_get_contents($settings_file.'.disabled'),TRUE);
        if (is_array($json)) $core->settings[$file] = array_replace_recursive ($core->settings[$file],$json);
        $core->settings[$file]['#status']='disabled';
      } else {
        $core->settings[$file]['#status']='notinstalled';
      }
  
      if (is_dir($core->pathes['SITE_PATH'].'plugins/'.$file)) $core->settings[$file]['#path_site']= $core->pathes['SITE_PATH'].'plugins/'.$file;
      if (is_dir($core->pathes['ADMIN_PATH'].'plugins/'.$file)) $core->settings[$file]['#path_admin']= $core->pathes['ADMIN_PATH'].'plugins/'.$file;
  
    }
  }
  foreach($core->settings as $i=>$v){
    if (!isset($v['#title'])) $core->settings[$i]['#title'] = '[no title] '.$i;
    if (!isset($v['#description'])) $core->settings[$i]['#description'] = 'Нет описания';
  }

}

  $core->headers[]='<title>Plugin manager - работа с плагинами</title>';
  $core->footers[]='<link rel="stylesheet" type="text/css" href="'.$core->pathes['EXEC_PAGE_LINK'].'inc/css.css">';
  prepare_plugin_settings();
  ksort($core->settings); 



//print_r($core->settings);exit(0);

print '<pre>';
//print_r($core->settings);
print '</pre>';

?>

<?php foreach($core->settings as $i=>$plugin):?>

<div class="item">
<div class="hdr">
<div class="title"><?=$plugin['#title']?> <span> <?=$plugin['#version']?></span></div>

<?php if ($plugin['#status']=='enabled'):?>
<div class="enabled">Включен</div>
<div class="disable"><a href="?plugins_manager_disable=<?=$i?>">Отключить</a></div>
<div class="settings"><a href="?editsettings=<?=$i?>.enabled">Настройки</a></div>
<?php endif;?>
<?php if ($plugin['#status']=='disabled'):?>
<div class="disabled">Отключен</div>
<div class="enable"><a href="?plugins_manager_enable=<?=$i?>">Включить</a></div>
<div class="uninstall"><a href="?plugins_manager_uninstall=<?=$i?>">Удалить</a></div>
<div class="settings"><a href="?editsettings=<?=$i?>.disabled">Настройки</a></div>
<?php endif;?>
<?php if ($plugin['#status']=='notinstalled'):?>
<div class="notinstalled">Не установлен</div>
<div class="install"><a href="?plugins_manager_install=<?=$i?>">Установить</a></div>
<?php endif;?>
</div>

<div class="description"><?=$plugin['#description']?></div>
</div>

<?php endforeach;


return 'default';

