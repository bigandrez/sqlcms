<?php
// $show_field_node_select[1] - даные из выводимого поля в текстовом виде
// если следующие поля присутствуют в аргументе - то поле будет выведено с поддержкой редактирования
// $show_field_node_select[2] - идентификатор редактируемой таблицы (nid если таблица node и id во всех других случаях)
// $show_field_node_select[3] - название редактируемой таблицы
// $show_field_node_select[4] - название редактируемой колонки
// $show_field_node_select[5] - [эксперементально] режим "полноэкранного" редактора
global $nodes;

if (isset($nodes[$show_field_node_select[1]])){
  return '['.$show_field_node_select[1].'] '.$nodes[$show_field_node_select[1]]['title'];
}

return $show_field_node_select[1];