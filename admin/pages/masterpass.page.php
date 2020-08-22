<?php
  $pass = $_GET['masterpass'];
  if (!$pass || ! $core->check_master_pass($pass)) {
    header("HTTP/1.0 403 Forbidden");
    exit(0);
  }
  // Признак разработчика
  $_SESSION['user']['node']['node_roles']=1;
//print_r($_SESSION);
?>
success login
<a href="<?=$core->pathes['ADMIN_LINK']?>">admin page here</a>
<?php return 'default';