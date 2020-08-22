<?php
global $core;

  $plugin = $_GET['plugins_manager_disable'];
  $settings_file = $core->pathes['SITE_PATH'].'config/'.$plugin;
  if (is_file($settings_file.'.enabled')){
    rename($settings_file.'.enabled',$settings_file.'.disabled');
  }
  header('Location: ?plugins_manager');

exit(0);


return 'default';

