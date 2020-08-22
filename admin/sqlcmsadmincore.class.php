<?php
include 'sqlcmsadmincore.interface.php';
include '../core/sqlcmscore.class.php';

class AdminCore extends Core implements SqlCmsAdminCore {

  public $fields;

  public function __construct() {
    parent::__construct();
    $this->pathes['ADMIN_PATH']=dirname(__FILE__).'/'; // Признак вызова из админки и он же путь к корню админки
    $this->pathes['ADMIN_LINK'] = '/'.substr($this->pathes['ADMIN_PATH'],strlen($this->pathes['SITE_PATH']));
  }

  // Запуск процедуры обработки
  public function run($simplemode=FALSE){
    parent::run($simplemode);
    if ($simplemode) return;

    // Для админской версии ядра читаем список полей
    $this->prepare_fields_list();
  }

  // Мы не собираемся в админке загружать ноды при старте - делаем "пустую" функцию
  public function load_nodes(){
  }

  public function check_master_pass($pass){
	return md5('SaLt'.$pass)==md5('SaLt'.'123');
  }

  public function get_exec_path($path){
  
    $page='';
    if (is_array($_GET)) foreach($_GET as $i=>$v){
      $page = $i;
      break;
    }
    if (!$page) 
      return  $this->pathes['ADMIN_PATH'].'pages/index.page.php';
  
    if (!isset($this->pages[$page]) || !is_file($this->pages[$page])){
      return $this->pathes['ADMIN_PATH'].'templates/404.tpl.php';
    }
  
    return $this->pages[$page];
  }

  // Подготовка массива настроек
  public function prepare_settings(){
    parent::prepare_settings();
  
    $path = $this->pathes['ADMIN_PATH'].'plugins/';
    $dir = opendir($path);
    while($file = readdir($dir)) {
      if ($file == '.' || $file == '..') continue;
  
      $settings_file = $this->pathes['SITE_PATH'].'config/'.$file;
      if (is_file($path.$file.'/settings.json'))
        $this->settings[$file] = json_decode(file_get_contents($path.$file.'/settings.json'),TRUE);
  
      if (is_file($settings_file.'.enabled')){
        $this->settings[$file] = json_decode(file_get_contents($settings_file.'.enabled'),TRUE);
        $this->settings[$file]['#status']='enabled';
      } elseif (is_file($settings_file.'.disabled')){
        $this->settings[$file] = json_decode(file_get_contents($settings_file.'.disabled'),TRUE);
        $this->settings[$file]['#status']='disabled';
      } else {
        $this->settings[$file]['#status']='notinstalled';
      }
      
      if (is_dir($this->pathes['SITE_PATH'].'plugins/'.$file)) $this->settings[$file]['#path_site']= $this->pathes['SITE_PATH'].'plugins/'.$file;
      if (is_dir($this->pathes['ADMIN_PATH'].'plugins/'.$file)) $this->settings[$file]['#path_admin']= $this->pathes['ADMIN_PATH'].'plugins/'.$file;
    }
  }

  // Подготовка массива сниппетов
  public function prepare_snippets(){
    parent::prepare_snippets();
    $path = $this->pathes['ADMIN_PATH'].'snippets/*.snippet.php';
    $entries = $this->glob_recursive($path, 0);
    foreach($entries as $v){
      $sn = dirname($v);
      $sn = str_replace('.snippet.php','',substr($v,strlen($sn)+1));
      $this->snippets[$sn]=$v;
    }
    $path = $this->pathes['ADMIN_PATH'].'plugins/*/*.snippet.php';
    $entries = $this->glob_recursive($path, 0);
    foreach($entries as $v){
      if (!$this->is_plugin_enabled_by_path($v)) continue;
      $sn = dirname($v);
      $sn = str_replace('.snippet.php','',substr($v,strlen($sn)+1));
  
      $plugin_name = substr(dirname($v),strlen($this->pathes['ADMIN_PATH'])+8);
      if (strpos($plugin_name,'/')!==FALSE) $plugin_name = substr($plugin_name,0,strpos($plugin_name,'/'));
  

      if (is_file($this->pathes['SITE_PATH'].'config/'.$plugin_name.'.enabled'))
        $this->snippets[$sn]=$v;
    }
  }
  


// Функция подготовки массива сигналов
  function prepare_signals(){
    parent::prepare_signals();
    $this->signals = array();
    $path2 = $this->pathes['ADMIN_PATH'].'plugins/.signal.php';
    $entries2 = $this->glob_recursive($path2); 
    $path = $this->pathes['ADMIN_PATH'].'plugins/*.signal.php';
    $entries = array_merge($entries2,$this->glob_recursive($path)); 
    foreach($entries as $v){
      if (!$this->is_plugin_enabled_by_path($v)) continue;
      $n = basename($v);
      $n = str_replace('.signal.php','',$n);

      $plugin_name = substr(dirname($v),strlen($this->pathes['ADMIN_PATH'])+8);
      if (strpos($plugin_name,'/')!==FALSE) $plugin_name = substr($plugin_name,0,strpos($plugin_name,'/'));

      if (is_file($this->pathes['SITE_PATH'].'config/'.$plugin_name.'.enabled'))
        $this->signals[$n][]=$v;
    }
  }
  // Функция подготовки массива шаблонов
  function prepare_templates(){
    parent::prepare_templates();
    $path = $this->pathes['ADMIN_PATH'].'templates/*.tpl.php';
    $entries = $this->glob_recursive($path, 0);
    foreach($entries as $v){
      $sn = dirname($v);
      $sn = str_replace('.tpl.php','',substr($v,strlen($sn)+1));
      $this->templates[$sn]=$v;
    }
    $path = $this->pathes['ADMIN_PATH'].'plugins/*/*.tpl.php';
    $entries = $this->glob_recursive($path, 0);
    foreach($entries as $v){
      $sn = dirname($v);
      $sn = str_replace('.tpl.php','',substr($v,strlen($sn)+1));

      $plugin_name = substr(dirname($v),strlen($this->pathes['ADMIN_PATH'])+8);
      if (strpos($plugin_name,'/')!==FALSE) $plugin_name = substr($plugin_name,0,strpos($plugin_name,'/'));
  
      if (is_file($this->pathes['SITE_PATH'].'config/'.$plugin_name.'.enabled'))
        $this->templates[$sn]=$v;
    }
//print_r($this->templates);exit(0);
  }
  
  // Функция подготовки массива страниц
  function prepare_pages(){
    parent::prepare_pages();
    $path = $this->pathes['ADMIN_PATH'].'pages/*.page.php';
    $entries = $this->glob_recursive($path, 0);
    foreach($entries as $v){
      $index = str_replace('.page.php','',substr($v,strlen($this->pathes['ADMIN_PATH'])+6));
      $sn = dirname($v);
      $sn = str_replace('.page.php','',substr($v,strlen($sn)+1));
      $this->pages[$index]=$v;
    }
  
    $path = $this->pathes['ADMIN_PATH'].'plugins/*/*.page.php';
    $entries = $this->glob_recursive($path, 0);
    foreach($entries as $v){
      if (!$this->is_plugin_enabled_by_path($v)) continue;
      $index = str_replace('.page.php','',substr($v,strlen($this->pathes['ADMIN_PATH'])+9));
      $p = strpos($index,'/');
      $index = substr($index,$p+1);
      $plugin_name = substr(dirname($v),strlen($this->pathes['ADMIN_PATH'])+8);
      if (strpos($plugin_name,'/')!==FALSE) $plugin_name = substr($plugin_name,0,strpos($plugin_name,'/'));
      if (is_file($this->pathes['SITE_PATH'].'config/'.$plugin_name.'.enabled'))
        $this->pages[$index]=$v;
    }

    $path = $this->pathes['ADMIN_PATH'].'pages/*.quickpage.php';
    $entries = $this->glob_recursive($path, 0);
    foreach($entries as $v){
      $index = str_replace('.quickpage.php','',substr($v,strlen($this->pathes['ADMIN_PATH'])+7));
      $sn = dirname($v);
      $sn = str_replace('.quickpage.php','',substr($v,strlen($sn)+1));
      $this->quickpages[$index]=$v;
    }
  
    $path = $this->pathes['ADMIN_PATH'].'plugins/*/*.quickpage.php';
    $entries = $this->glob_recursive($path, 0);
    foreach($entries as $v){
      if (!$this->is_plugin_enabled_by_path($v)) continue;
      $index = str_replace('.quickpage.php','',substr($v,strlen($this->pathes['ADMIN_PATH'])+9));
      $p = strpos($index,'/');
      $index = substr($index,$p+1);
      $plugin_name = substr(dirname($v),strlen($this->pathes['ADMIN_PATH'])+9);
      if (strpos($plugin_name,'/')!==FALSE) $plugin_name = substr($plugin_name,0,strpos($plugin_name,'/'));
      if (is_file($this->pathes['SITE_PATH'].'config/'.$plugin_name.'.enabled'))
        $this->$quickpages[$index]=$v;
    }
  }

  // Список полей таблиц, с их типами и описанием
  function prepare_fields_list(){
    $this->fields = array();
    $tables = $this->db->getAll("SHOW TABLES");
    foreach($tables as $t){
      foreach($t as $table){
        if (substr($table,0,5)=='node_' || substr($table,0,6)=='table_' || in_array($table,array('node'))){
          $fs = $this->db->getAll("show columns from ".$table);
          foreach($fs as $f)
            $this->fields[$table][$f['Field']] = $f;
  
        }
        break;
      }
    }
  
    $fields_settings = $this->db->getAll("select * from fields_settings");
    if (is_array($fields_settings)) foreach($fields_settings as $f){
      $v = explode('.',$f['param']);
      if (!$v[0]) continue;
      unset($f['id']);unset($f['param']);
      $this->fields[$v[0]][$v[1]]['userdata'] = $f;
    }
  }
  
  // не false, если плагин для указанного файла установлен и включен
  // Список "включенных" плагинов берется из $this->settings
  // Именем плагина является имя папки в папках plugins или admin/plugins
  public function is_plugin_enabled_by_path($path){
    $r = parent::is_plugin_enabled_by_path($path);
    if ($r) return $r;
    $pn = substr($path,strlen($this->pathes['ADMIN_PATH'].'plugins/'));
    $to = strpos($pn,'/');
    $pn = substr($pn,0,$to);
    return isset($this->settings[$pn]);
  }


}
