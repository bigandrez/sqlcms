<?php
include_once 'sqlcmscore.interface.php';
include 'prepares.php';

class Core implements SqlCmsCore{
  
  use InitFunctions; // подключаем некоторые функции из файла prepares.php

  public $snippets; // Массив имен сниппетов с указаниями на соответствующие файлы
  public $signals;  // Массив сигналов, с указаниями на соответствующие файлы
  public $headers;  // Массив текстовых строк, который выводится перед завершающим тегом head
  public $footers;  // Массив текстовых строк, который выводится перед завершающим тегом body
  public $db;       // Объект базы данных типа iSqlJsonDb
  public $log;      // Объект лога типа iSqlJsonLog
  public $nodes;    // Массив загруженных нод. Первый элемент - главная нода.
  public $pager;    // Информация по пейджеру для последней загрузки данных из базы. Если переменная не пустая, то будет выведен пейджер
  public $pathes;   // Список "путей" - к папке кеша, папке ядра и т.п.
  public $cmf_start_time; // Время создания первого объекта SqlCmsCore
  public $signal_name; //Имя сигнала - например $this->signal_name = 'before_current_node_load';include $this->pathes['SITE_PATH'].'core/signal.php';

  public function __construct() {
    $this->cmf_start_time = microtime(TRUE);
    mb_internal_encoding("UTF8");
    $this->headers = $this->footers = $this->nodes = $this->signals = $this->snippets = $this->templates = $this->pages = array();
    global $core;
    $core = $this;
    $this->pathes['SITE_PATH']=dirname(dirname(__FILE__)).'/';
    $this->pathes['CACHE_PATH'] = $this->pathes['SITE_PATH'].'cache/';
    $this->pathes['CACHE_LINK'] = '/cache/';
    $this->pathes['CORE_PATH'] = dirname(__FILE__).'/';
    $this->pathes['CORE_LINK'] = '/core/';
    $this->pathes['THEME']='default';
    $this->nids = array();
  }

  // Запуск процедуры обработки
  public function run($simplemode=FALSE){
    if ($simplemode) return;

    // Первым делом читаем настройки
    $this->prepare_settings();

    $this->prepare_signals();
    // Посылаем первый сигнал, после того, как система инициализирована до возиожности посылать сигналы
    $this->signal('signals_ready');

    $this->prepare_snippets();
    $this->prepare_log();
    $this->prepare_templates();
    $this->prepare_pages();
    $this->prepare_db();

    // Функция get_exec_path требует чтобы сначала был выполнен prepare_pages
    $this->pathes['EXEC_PAGE_FILE']=$this->get_exec_path($_SERVER['REQUEST_URI']);
    $this->pathes['EXEC_PAGE_PATH']=dirname($this->pathes['EXEC_PAGE_FILE']).'/';
    $this->pathes['EXEC_PAGE_LINK'] = '/'.substr(dirname($this->pathes['EXEC_PAGE_FILE']),strlen($this->pathes['SITE_PATH'])).'/';

    $this->signal('after_prepare_settings');

    $this->load_nodes();
  }

  // Определение адреса материала по url и загрузка всех соответствующих материалов
  public function load_nodes(){
    while ($this->db){
      $this->nodes = array();
      $this->nodes['node_path'] = rawurldecode($_SERVER['REQUEST_URI']);
      preg_match_all("/\/([\/\pL_\.\d]+)/u",$this->nodes['node_path'], $res);
      $this->nodes['node_path'] = $res[1][0];
      if (!$res[1][0]) $this->nodes['node_path'] = 'index';
      $this->nodes['node_ids'] = '';

      // Ожидаем от "сигнала" что он установит значение $core->nodes['node_ids'], $core->nodes['node_path']
      // Или создаст массив $core->nodes['nodes'] - для того, чтобы ядро само не загружало ноды
      $this->signal_name = 'before_current_node_load';include $this->pathes['SITE_PATH'].'core/signal.php';

      if (!isset($core->nodes['nodes'])){
        if ($this->nodes['node_ids']){
          $n = $this->db->node_load($this->nodes['node_ids']);
          $this->nodes = array_merge($this->nodes, json_decode($n,TRUE));
        } else {
          $n = $this->db->node_load_by_link($this->nodes['node_path']);
          $this->nodes = array_merge($this->nodes, json_decode($n,TRUE));
        }
      }

      break;
    }
tolog('test','group');
print '123123123';print_r($this->nodes);print '<>';exit(0);
    $this->signal('after_current_node_load');
  }

  // Функция для вызова сниппета по имени
  public function snippet($name){
    $args = func_get_args();
    eval("\$".$name." = \$args;");
    unset($args);
    if (is_file($this->snippets[$name]))
      return include($this->snippets[$name]);
    return '';
  }

  // Вызов "сигнала" - то есть всех файлов из любой папки с названием [имя сигнала].signal.php
  public function signal($name){
    $this->signal_name = $name;
    // Сначала вызываем все сигналы, у которых не указано имя сигнала
    //

    $args = func_get_args();
    eval("\$".$args." = \$args;\$signal='".$name."';");
    unset($args);
    if (is_array($this->signals[''])) foreach($this->signals[''] as $v)
      include($v);

    $s = $this->signals[$name];
    if (is_array($s)) foreach($s as $v)
      include($v);
  }

  public function is_right($right){
  //TODO: Сделать проверку прав
    // Роль с номером nid=1 принадлежит роли разработчика
    if ($_SESSION['user']['node']['node_roles']==1)
      return TRUE;
    return FALSE;
  }

}
