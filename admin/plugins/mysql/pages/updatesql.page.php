<?php
  $headers[]='<title>Сброс внутреннего кеша в БД</title>';
  if (!is_right('update sql procedures')){
    header("HTTP/1.0 403 Forbidden");
    exit(0);
  }?>
<h1>Update sql procedures</h1><?php

include "updatedb.php";

  update_all_db_procedures();

  print 'update complete';
return 'default';