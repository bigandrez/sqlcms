<?php  // Сниппет для вывода поля типа "onoff"

// $show_field_text[1] - даные из выводимого поля в текстовом виде
// если следующие поля присутствуют в аргументе - то поле будет выведено с поддержкой редактирования
// $show_field_text[2] - идентификатор редактируемой таблицы (nid если таблица node и id во всех других случаях)
// $show_field_text[3] - название редактируемой таблицы
// $show_field_text[4] - название редактируемой колонки
// $show_field_text[5] - [эксперементально] режим "полноэкранного" редактора

// Если редактирования не будет, то просто выводим поле
  if (!$show_field_onoff[2]) return $show_field_onoff[1] ? '<i>true</i>' : '<i>false</i>';

// Если будет "редактироваться", то подключаем скрипт редакторв
  global $core;
  $plugin_url = ($core->pathes['ADMIN_LINK'] ? $core->pathes['ADMIN_LINK'] : '').'plugins/'.basename(dirname(__FILE__));
  $core->footers['show_field_onoff']='<script type="text/javascript" src="'.$plugin_url.'/show_field_onoff.js"></script>';
  return $show_field_onoff[1] ? '<i>true</i>' : '<i>false</i>';

