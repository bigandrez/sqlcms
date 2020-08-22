<?php
interface SqlCmsCore{
  // не false, если плагин для указанного файла установлен и включен
  public function is_plugin_enabled_by_path($path);

  // Вызов "сигнала" - то есть всех файлов из любой папки с названием [имя сигнала].signal.php или 
  // ".signal.php", если плагин хочет обрабатывать все сигналы
  // Чтобы осуществить вызов в контексте имен вызывающей функции, используйте следующий метод
  // $core->signal_name = 'last_call';
  // include $core->pathes['SITE_PATH'].'core/signal.php';
  public function signal($name);

  // Проверка существования "права" с названием $right у текущего пользователя
  public function is_right($right);
}
