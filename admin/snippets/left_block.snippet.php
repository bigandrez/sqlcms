<?php


global $core;

  foreach($core->pages as $i=>$v){

    $f = file_get_contents($v);
    preg_match_all('{<title[^>]*>(.*?)</title>}',$f,$matches);

    $n = $matches[1][0];
    if (!$n) continue;

    $v = str_replace('.page.php','',$v);
    $v = substr($v,strlen($core->pathes['ADMIN_PATH']));
    if (substr($v,0,8)=='plugins/'){
      $v = substr($v,8);
      $p = strpos($v,'/');
      $plugin_name = substr($v,0,$p);
      $v = substr($v,strlen($plugin_name)+1);

      $settings_file = $core->pathes['SITE_PATH'].'config/'.$plugin_name.'.enabled';
      if (is_file($settings_file))
        $settings = json_decode(file_get_contents($settings_file),TRUE);
      $plugin_title = $settings['#title'];
      $plugin_description = $settings['#description'];

    } elseif (substr($v,0,6)=='pages/'){
      $p = strpos($v,'/');
      $plugin_name = '';
      $v = substr($v,6);
    } else {
      $plugin_name='';
    }


    if ($v=='index') continue;
    $pag[]=array('name'=>$n,'link'=>$core->pathes['ADMIN_LINK'].'?'.$v,'plugin_name'=>$plugin_name, 'plugin_title'=>$plugin_title?$plugin_title:$plugin_name, 'plugin_description'=>$plugin_description);
  }

global $pagen;
  $pagen=array();
  foreach($pag as $i=>$v){
    $name = $v['plugin_name'] ? $v['plugin_name'] : 'Основные настройки';
    $pagen[$name]['title'] = $v['plugin_name'] ? $v['plugin_title'] : 'Основные настройки';
    $pagen[$name]['description'] = $v['plugin_description'];
    if ($v['plugin_name']) $pagen[$name]['settings'] = $core->pathes['ADMIN_LINK'].'?editsettings='.$v['plugin_name'].'.enabled';
    else $pagen[$name]['settings'] = $core->pathes['ADMIN_LINK'].'?editsettings=site.json';
    unset($v['plugin_title']);
    unset($v['plugin_description']);
    unset($v['plugin_name']);
    $pagen[$name]['pages'][] = $v;
  }

  ob_start();
  include($core->templates['left_block']);
  $content = ob_get_clean();

  return $content;
