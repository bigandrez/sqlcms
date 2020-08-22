<?php  // Сниппет для вывода поля типа "text"

// $show_field_text[1] - даные из выводимого поля в текстовом виде
// если следующие поля присутствуют в аргументе - то поле будет выведено с поддержкой редактирования
// $show_field_text[2] - идентификатор редактируемой таблицы (nid если таблица node и id во всех других случаях)
// $show_field_text[3] - название редактируемой таблицы
// $show_field_text[4] - название редактируемой колонки
// $show_field_text[5] - [эксперементально] режим "полноэкранного" редактора

global $core;
// Если редактирования не будет, то просто выводим поле
  if (!$show_field_text[2]) return $show_field_text[1];

// Если будет "редактироваться", то подключаем скрипт редакторв
  $core->footers['show_field_text.js']='<script type="text/javascript" src="'.$core->pathes['ADMIN_LINK'].'snippets/show_field_text.js"></script>';
  return ''.$show_field_text[1].'';
