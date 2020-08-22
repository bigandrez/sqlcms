<?php
  if (!$core->is_right('admin panel access')){
    header("HTTP/1.0 403 Forbidden");
    exit(0);
  }

  foreach($core->pages as $i=>$v){

    $f = file_get_contents($v);
    preg_match_all('{<title[^>]*>(.*?)</title>}',$f,$matches);

    $n = $matches[1][0];
    if (!$n) continue;

    $v = str_replace('.page.php','',$v);
    $v = substr($v,strlen($core->pathes['ADMIN_PATH'])+1);

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
//print_r($pages);
//print_r($pag);
?><h1>Список страниц</h1>

<div class="clear"></div>
<?php foreach($core->quickpages as $v):
  ob_start();
  include($v) ;
  $r = ob_get_clean();
?>
<div class="quickpage"><?=$r?></div>
<?php endforeach;?>
<div class="clear"></div>


<?php foreach($pag as $v):break;?>
<a href="<?=$v['link']?>"><?=$v['name']?></a><?=($v['plugin_name']?' ('.$v['plugin_title'].')':'')?><br/>
<?php endforeach;
return 'default';