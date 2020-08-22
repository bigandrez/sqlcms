<?php
global $core;
  $plugin = $_GET['plugins_manager_enable'];
  $settings_file = $core->pathes['SITE_PATH'].'config/'.$plugin;
  if (is_file($settings_file.'.disabled')){
    rename($settings_file.'.disabled',$settings_file.'.enabled');
  }
  header('Location: ?plugins_manager');

exit(0);


return 'default';

