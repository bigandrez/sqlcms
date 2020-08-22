<?php // Функции подготовки глобальных массивов, основанных на файлах: сниппетов, сигналов, шаблонов

// function_exists используем только в случае необходимости, ибо смысла нету

trait InitFunctions{

  // Получить страницу для выполнения по названию пути
  function get_exec_path($path){
    $page = array();
    if (!preg_match("/([^?\.]*)/", $path, $page)) return;
    $page = $page[0]=='/' ? '' : $page[0];
    while (strrpos($page,'/')==strlen($page)-1){
      $page = substr($page,0,strlen($page)-1);
    }
  
    // Если страница не указана - то ищем index.php
    if (!$page) {
      if (is_file($this->pathes['SITE_PATH'].'themes/'.$this->pathes['THEME'].'/pages/index.php'))
        return $this->pathes['SITE_PATH'].'themes/'.$this->pathes['THEME'].'/pages/index.php';
      return $this->pathes['SITE_PATH'].'themes/default/pages/index.php';
    }
  
    // Ищем страницу в плагинах
    $path = $this->pathes['SITE_PATH'].'plugins/*/pages'.$page.'.php';
    $entries = glob($path, 0);
    if ($entries[0])
      return $entries[0];
  
    // Ищем страницу в страницах темы
    $path = $this->pathes['SITE_PATH'].'themes/'.$this->pathes['THEME'].'/pages'.$page;
    if (is_file($path.'.php')) return $path.'.php';
    $path = $this->pathes['SITE_PATH'].'themes/'.$this->pathes['THEME'].'/pages'.$page.'/index';
    if (is_file($path.'.php')) return $path.'.php';
    $path = $this->pathes['SITE_PATH'].'themes/default/pages'.$page;
    if (is_file($path.'.php')) return $path.'.php';
    $path = $this->pathes['SITE_PATH'].'themes/default/pages'.$page.'/index';
    if (is_file($path.'.php')) return $path.'.php';
  
    if (is_file($this->pathes['SITE_PATH'].'themes/'.$this->pathes['THEME'].'/pages/404.php'))
      return $this->pathes['SITE_PATH'].'themes/'.$this->pathes['THEME'].'/pages/404.php';
    return $this->pathes['SITE_PATH'].'themes/default/pages/404.php';
  }
  
  
  // Читаем в $this->settings содержимое site.json и всех файлов с расширением enabled из папки config
  public function prepare_settings(){

    $this->settings = array();
  
    $settings_file = $this->pathes['SITE_PATH'].'config/site.json';
    if (is_file($settings_file)){
      $this->settings['site'] = json_decode(file_get_contents($settings_file),TRUE);
      if (is_array($this->settings['site'])) foreach($this->settings['site'] as $i=>$v){
        if (substr($i,0,1)=='#') unset($this->settings['site'][$i]);
      }
    }
  
    $path = $this->pathes['SITE_PATH'].'plugins/';
    $dir = opendir($path);
    while($file = readdir($dir)) {
      if ($file == '.' || $file == '..') continue;
  
      $settings_file = $this->pathes['SITE_PATH'].'config/'.$file;
  
      if (is_file($settings_file.'.enabled'))
        $this->settings[$file] = json_decode(file_get_contents($settings_file.'.enabled'),TRUE);
  
      if (is_array($this->settings[$file])) foreach($this->settings[$file] as $i=>$v){
        if (substr($i,0,1)=='#') unset($this->settings[$file][$i]);
      }
    }
  }
  
  // Функция полготовка массива сниппетов
  public function prepare_snippets(){

    $entries = array();
  
    $path = $this->pathes['SITE_PATH'].'themes/default/*.snippet.php';
    $entries = array_merge($entries,$this->glob_recursive($path, 0));
    $path = $this->pathes['SITE_PATH'].'themes/'.$this->pathes['THEME'].'/*.snippet.php';
    $entries = array_merge($entries,$this->glob_recursive($path, 0));
    $path = $this->pathes['SITE_PATH'].'plugins/*/*.snippet.php';
    $entries = array_merge($entries,$this->glob_recursive($path, 0));
    foreach($entries as $v){
      if (!$this->is_plugin_enabled_by_path($v)) continue;
      $sn = dirname($v);
      $sn = str_replace('.snippet.php','',substr($v,strlen($sn)+1));
      $this->snippets[$sn]=$v;
    }
  }

  public function prepare_log(){
    $log_snippet_name = $this->settings['site']['getlog_snippet'];
    if (!$log_snippet_name) $log_snippet_name = 'get_log';
    $this->log = $this->snippet($log_snippet_name);
    if (!($this->log instanceof iSqlJsonLog)){
      $this->log = null;
      return;
    }
  }

  public function prepare_db(){
    $db_snippet_name = $this->settings['site']['getdb_snippet'];
    if (!$db_snippet_name) $db_snippet_name = 'get_mysql_db';
    $this->db = $this->snippet($db_snippet_name);
    if (!($this->db instanceof iSqlJsonDb)){
      systemlog('No access to DB', LOG_WARNING, __FILE__);
      $this->db = null;
      return;
    }
  }


  public function prepare_signals(){
    $this->signals = array();
    $path2 = $this->pathes['SITE_PATH'].'plugins/.signal.php';
    $entries2 = $this->glob_recursive($path2); 
    $path = $this->pathes['SITE_PATH'].'plugins/*.signal.php';
    $entries = array_merge($entries2, $this->glob_recursive($path));
        
    foreach($entries as $v){
      if (!$this->is_plugin_enabled_by_path($v)) continue;
      $n = basename($v);
      $n = str_replace('.signal.php','',$n);
      $this->signals[$n][]=$v;
    }
  }
  
  
  // Функция полготовка массива сниппетов
  public function prepare_templates(){
    $entries = array();
    $path = $this->pathes['SITE_PATH'].'themes/default/snippets/*.tpl.php';
    $entries = array_merge($entries,$this->glob_recursive($path, 0));
    $path = $this->pathes['SITE_PATH'].'themes/'.$this->pathes['THEME'].'/templates/*.tpl.php';
    $entries = array_merge($entries,$this->glob_recursive($path, 0));
    $path = $this->pathes['SITE_PATH'].'plugins/*/templates/*.tpl.php';
    $entries = array_merge($entries,$this->glob_recursive($path, 0));
    foreach($entries as $v){
      if (!$this->is_plugin_enabled_by_path($v)) continue;
      $sn = dirname($v);
      $sn = str_replace('.tpl.php','',substr($v,strlen($sn)+1));
      $this->templates[$sn]=$v;
    }
  }
  
  // Функция полготовка массива страниц
  public function prepare_pages(){
    $entries = array();
    $path = $this->pathes['SITE_PATH'].'themes/default/snippets/*.tpl.php';
    $entries = array_merge($entries,$this->glob_recursive($path, 0));
    $path = $this->pathes['SITE_PATH'].'themes/'.$this->pathes['THEME'].'/templates/*.tpl.php';
    $entries = array_merge($entries,$this->glob_recursive($path, 0));

    foreach($entries as $v){
      $sn = dirname($v);
      $sn = str_replace('.tpl.php','',substr($v,strlen($sn)+1));
      $this->pages[$sn]=$v;
    }

    $path = $this->pathes['SITE_PATH'].'plugins/*/templates/*.tpl.php';
    $entries = $this->glob_recursive($path, 0);
    foreach($entries as $v){
      if (!$this->is_plugin_enabled_by_path($v)) continue;
      $sn = dirname($v);
      $sn = str_replace('.tpl.php','',substr($v,strlen($sn)+1));
      $this->pages[$sn]=$v;
    }
  }
  
  public function glob_recursive($pattern, $flags = 0)
  {
    $files = glob($pattern, $flags);
    foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR) as $dir)
    {
      $files = array_merge($files, $this->glob_recursive($dir.'/'.basename($pattern), $flags));
    }
    return $files;
  }
  
  // не false, если плагин для указанного файла установлен и включен
  // Список "включенных" плагинов берется из $this->settings
  // Именем плагина является имя папки в папках plugins или admin/plugins
  public function is_plugin_enabled_by_path($path){
    $pn = substr($path,strlen($this->pathes['SITE_PATH'].'plugins/'));
    $to = strpos($pn,'/');
    $pn = substr($pn,0,$to);
    return isset($this->settings[$pn]);
  }


}
  
  
  
  