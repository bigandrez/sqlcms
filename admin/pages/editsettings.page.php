<?php
global $core;
$core->headers[]='<title>Edit settings - редактирование настроек</title>';



// =============================================================
  // Определяем список допустимых файлов
global $setsfiles;
  $setsfiles = array('site.json'=>'Общие настройки сайта');
  // Список дополнительных настроек определяем из списка файлов *.json в папке config
  $path = $core->pathes['SITE_PATH'].'config/';
  $entries = $core->glob_recursive($path.'*.json'); 
  foreach($entries as $v){
    $p = strpos($v,'.json');
    if ($p!==FALSE) {
      $plugin_name = basename($v);
      $setsfiles[$plugin_name]='Кастомные настройки '.basename(substr($v,0,$p));
    }
  }
  $setsfiles['site.json']= 'Общие настройки сайта';
  // Список плагинов определяем из списка файлов *.enabled в папке config
  $path = $core->pathes['SITE_PATH'].'config/';
  $entries = $core->glob_recursive($path.'*.enabled'); 
  foreach($entries as $v){
    $p = strpos($v,'.enabled');
    if ($p!==FALSE) {
      $plugin_name = basename($v);
      $setsfiles[$plugin_name]='Настройки плагина '.basename(substr($v,0,$p));
    }
  }

  $entries = $core->glob_recursive($path.'*.disabled'); 
  foreach($entries as $v){
    $p = strpos($v,'.disabled');
    if ($p!==FALSE) {
      $plugin_name = basename($v);
      $setsfiles[$plugin_name]='Настройки плагина '.basename(substr($v,0,$p));
    }
  }


  // Список типов материалов определяем напрямую из БД
  if ($core->db){
    $t = $core->db->getAll("
      SELECT information_schema.tables.table_name FROM information_schema.tables
      where (information_schema.tables.table_name like 'node\_%') AND information_schema.tables.TABLE_SCHEMA=database();
    ");
    foreach($t as $v){
      $setsfiles[$v['table_name'].'.node'] = 'Настройки материала '.$v['table_name'];
    }
  }

  // Список шаблонов
  $entries = array();
  $path = $core->pathes['SITE_PATH'].'themes/default/*.tpl.php';
  $entries = array_merge($entries,$core->glob_recursive($path, 0));
  $path = $core->pathes['SITE_PATH'].'themes/'.$_SERVER['THEME'].'/*.tpl.php';
  $entries = array_merge($entries,$core->glob_recursive($path, 0));
  foreach($entries as $v){
    $p = strpos($v,'.tpl.php');
    $tpl_name = basename(substr($v,0,$p));
    $setsfiles[$tpl_name.'.template']='Настройки шаблона '.$tpl_name;
  }

// =============================================================

  $plugin = $_GET['editsettings'];

  $settings_file = $core->pathes['SITE_PATH'].'config/'.$plugin;
  if (!isset($setsfiles[$plugin])){
    // Рисуем шаблон editsettings.tpl.php
    return 'editsettings';
  }

  $settings = array();

  if (is_file($settings_file))
    $settings = json_decode(file_get_contents($settings_file),TRUE);

  $json_string = json_decode(file_get_contents('php://input'),TRUE);

  if (isset($_GET['remove'])){
    unset($settings[$_GET['name']]);
    file_put_contents($settings_file,json_encode($settings,JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK));
    header('Location: '.$_SERVER['ADMIN_LINK'].'?editsettings='.$plugin);
    exit(0);
  }

  if (isset($_GET['addnew'])){
    if (!isset($settings['newparam'])){
      $settings = array_merge(array('newparam'=>''), $settings);
      file_put_contents($settings_file,json_encode($settings,JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK));
    }
    header('Location: '.$_SERVER['ADMIN_LINK'].'?editsettings='.$plugin);
    exit(0);
  }


  if (isset($json_string['real_value'])){
    $real_value = $json_string['real_value'];
    $param = $_GET['param'];
    $name = $_GET['name'];

    if (!isset($settings[$name])) {
      header('HTTP/1.0 400 Bad Request', true, 400);
      exit(0);
    }

    if ($param=='name'){
      if (!strlen($real_value)) {
        header('HTTP/1.0 400 Bad Request', true, 400);
        exit(0);
      }
      $data = json_encode($settings);
      $data = str_replace('"'.$name.'":', '"'.$real_value.'":', $data);
      $data = str_replace('"#'.$name.'":', '"#'.$real_value.'":', $data);
      $settings = json_decode($data, true);
/*
      $settings[$real_value] = $settings[$name];
      if (isset($settings['#'.$name]))
        $settings['#'.$real_value] = $settings['#'.$name];
      unset($settings[$name]);
      unset($settings['#'.$name]);
*/

      file_put_contents($settings_file,json_encode($settings,JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK));
      header('HTTP/1.0 201 Created', true, 201);
      header('Location: '.$_SERVER['ADMIN_LINK'].'?editsettings='.$plugin);
      exit(0);
    }
    if ($param=='value'){
      $settings[$name] = $real_value;
      file_put_contents($settings_file,json_encode($settings,JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK));
      exit(0);
    }
    if (!in_array($param,array('title','description','group','type'))){
      header('HTTP/1.0 400 Bad Request', true, 400);
      exit(0);
    }
    $settings['#'.$name][$param] = $real_value;
    file_put_contents($settings_file,json_encode($settings,JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK));

    exit(0);
  }

  $defgroup = 'Без группы';
  $sets = array();

  foreach($settings as $s=>$v){
    if (substr($s,0,1)=='#') continue;
    $group = trim($settings['#'.$s]['group']);
    $group = $group ? $group : $defgroup;
    $sets[$group][$s]=array(
      'value' => $v,
      'title' => $settings['#'.$s]['title'],
      'description' => $settings['#'.$s]['description'],
      'group' => $settings['#'.$s]['group'],
      'type' => $settings['#'.$s]['type']
    );
  }
//print_r($sets);print_r($settings);


function edit_link($name,$param,$value,$type){
global $core;
  $callback = '?editsettings='.$_GET['editsettings'].'&name='.$name.'&param='.$param;
  return $core->snippet('show_field', $value, $type, $callback);
}
function noedit_link($name,$param,$value,$type){
global $core;
  return $core->snippet('show_field', $value, $type);
}

?><h1>Редактирование настроек <?=$plugin?></h1>
<a href="<?='?editsettings='.$_GET['editsettings']?>&addnew">Добавить настройку с именем newparam</a>
<div class="sets">

<?php foreach($sets as $group=>$gv):?>
<div class="groups">
  <div class="groupname"><?=$group?></div>
  <?php foreach($gv as $name=>$v):?>
<div class="item collapse">
<div class="item1">
    <table>
    <tr><td onclick="this.parentElement.parentElement.parentElement.parentElement.parentElement.classList.add('collapse')">Имя:</td><td><div class="name"><?=edit_link($name, 'name', $name, 'text')?></div></td></tr>
    <tr><td onclick="this.parentElement.parentElement.parentElement.parentElement.parentElement.classList.add('collapse')">Заголовок: </td><td><div class="title"><?=edit_link($name, 'title', $v['title'], 'text')?></div></td></tr>
    <tr><td onclick="this.parentElement.parentElement.parentElement.parentElement.parentElement.classList.add('collapse')">Тип:</td><td><div class="type"><?=edit_link($name, 'type', $v['type'], 'text')?></div></td></tr>
    <tr><td onclick="this.parentElement.parentElement.parentElement.parentElement.parentElement.classList.add('collapse')">Группа: </td><td><div class="group"><?=edit_link($name, 'group', $v['group'], 'text')?></div></td></tr>
    <tr><td onclick="this.parentElement.parentElement.parentElement.parentElement.parentElement.classList.add('collapse')">Описание: </td><td><div class="description"><?=edit_link($name, 'description', $v['description'], 'text')?></div></td></tr>
    </table>
</div>
<div class="item2" onclick="this.parentElement.classList.remove('collapse')">
<div class="name"><?=noedit_link($name, 'name', $name, 'text')?></div><div class="title"><?=noedit_link($name, 'title', $v['title'], 'text')?></div>
</div>
    <div class="value"><?=edit_link($name, 'value', $v['value'], $v['type'])?></div>
<div class="remove"><a href="<?='?editsettings='.$_GET['editsettings'].'&name='.$name.'&remove';?>" title="Удалить">x</a></div>
  </div>
<?php endforeach;?>
</div>
<?php endforeach;?>

</div>

<style>
.groups{
border:1px dashed gray;
margin-bottom:12px;
padding: 15px 10px 10px 10px;
    margin-top:25px;
background: rgba(0,0,0,.05);
}
.groupname{
    background: linear-gradient(to bottom, #fff, #fff 49%, #eee 50%, #eee 100%);
position: absolute;
    font-family: Arial;
    font-size: 20px;
    padding: 0 5px;
margin-top: -27px;
    margin-left: -5px;
}
.sets .item1, .sets .item2{
max-width:600px;
width:50%;
}
.sets .item1 table{
width:100%;
}
.sets .item1 table tr td:first-child{
width:10em;
cursor:pointer;
}
.sets .item1, .sets .value{
display:inline-block;
}
.sets .item:not(:last-child){
border-bottom:1px dashed gray;
}

.sets .name, .sets .group, .sets .title, .sets .description, .sets .type{
width:100%;
}

@media(min-width:600px){
  .sets .item{display:table;width:100%;}
  .sets .item1, .sets .value{display:table-cell;vertical-align:middle;}
}
@media(max-width:599px){
  .sets .item{display:block;width:100%;}
  .sets .item1, .sets .value{display:block;width:100%;}
}

.sets .remove{
float: right;
display:none;
}
.sets .remove a{
    position: absolute;
    font-family: Courier;
    font-size: 18px;
    font-weight: bold;
    text-decoration: none;
    margin-left: -30px;
    background: #ffd3d3;
color:gray;
    padding: 4px 10px;
    text-align: center;
    border-radius: 15px;
}
.sets .item:hover .remove{
display:block;
}
.sets .item:hover .remove:hover a{
    background: #ff8888;
color:black;
}

.sets .item:not(.collapse) .item2{
display:none;
}

.sets .item.collapse .item1{
display:none;
}
.sets .item.collapse .item2{
display:inline-block;
cursor:pointer;
}
.sets .item.collapse .value{
display: inline-block;
    min-width: 50%;
    overflow: hidden;
    width: 0;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.sets .item.collapse .name, .sets .item.collapse .title{
display:inline-block;
width:auto;
margin-right:10px;
}
.sets .item.collapse .name:before{
content:"[";
}
.sets .item.collapse .name{
font-family:Courier;
    font-size: 66%;
}
.sets .item.collapse .name:after{
content:"]";
}

.sets .item.collapse .null:after, .sets .item.collapse .empty:after{
content:"no title";
color:gray;
}
.sets .item .value .empty:after{
content:"empty";
color:gray;
}
.sets .item .null:after{
content:"null";
color:gray;
}
.sets .item .empty:after{
content:"empty";
color:gray;
}


</style>

<?php return 'default';