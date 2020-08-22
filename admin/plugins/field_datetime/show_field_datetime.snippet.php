<?php  // Сниппет для вывода поля типа "text"

// $show_field_text[1] - даные из выводимого поля в текстовом виде
// если следующие поля присутствуют в аргументе - то поле будет выведено с поддержкой редактирования
// $show_field_text[2] - идентификатор редактируемой таблицы (nid если таблица node и id во всех других случаях)
// $show_field_text[3] - название редактируемой таблицы
// $show_field_text[4] - название редактируемой колонки
// $show_field_text[5] - [эксперементально] режим "полноэкранного" редактора

//print_r($show_field_datetime[1]);
// Если редактирования не будет, то просто выводим поле
  if (!$show_field_datetime[2]) return $show_field_datetime[1].' UTC';

  global $core;
  $plugin_url = ($core->pathes['ADMIN_LINK'] ? $core->pathes['ADMIN_LINK'] : '').'plugins/'.basename(dirname(__FILE__));

// Если будет "редактироваться", то подключаем скрипт редакторв
  $core->footers['show_field_datetime']='<script type="text/javascript" src="'.$plugin_url.'/show_field_datetime.js"></script>';
  return $show_field_datetime[1] ? date("d.m.Y H:i:s",$show_field_datetime[1]) : '';
