<?php
global $core;

  $plugin = $_GET['plugins_manager_uninstall'];

  $installl_file = $core->pathes['SITE_PATH'].'plugins/'.$plugin.'/uninstall.php';
  if (is_file($installl_file))
      include ($installl_file);
  $installl_file = $core->pathes['ADMIN_PATH'].'plugins/'.$plugin.'/uninstall.php';
  if (is_file($installl_file))
      include ($installl_file);

  $settings_file = $core->pathes['SITE_PATH'].'config/'.$plugin;
  if (is_file($settings_file.'.disabled')){
    unlink($settings_file.'.disabled');
  }
  header('Location: ?plugins_manager');

exit(0);


return 'default';

