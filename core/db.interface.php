<?php // Файл, с перечислением и первоначальной инициализацией всех глобальных переменных

interface iSqlJsonDb
{
  // Функция загрузки sql запроса с поддержкой пейджера
  // &$into - ссылка на переменную, куда будет загружен результат
  // $request - sql запрос
  // $count_request - sql запрос количества строк. Может отсутствовать
  // $useget - использовать параметры page, limit, offset из GET запроса
  public function sql_load(&$into, $request, $count_request='', $useget=TRUE, $limit=15);

  // Функция загрузки одной ноды по идентификатору
  // $node_id - идентификатор ноды
  // $params - параметры - массив типа "table_test.row_limit" = >10
  // $useget - использовать параметр "page" из GET запроса
  public function node_load($node_id, $params=array(), $useget=TRUE);

  // Функция загрузки нод по результатам sql запроса, возвращающий список nid
  // $request - sql запрос в виде select 1 as nid
  // $params - параметры - массив типа "table_test.row_limit" = >10
  // $useget - использовать параметр "page" из GET запроса
  public function sql_node_load($request, $params=array(), $useget=TRUE);
  
  public function get_field($nid,$table,$column);

  public function getAll();

  // Функция создания ноды.
  // Если $node_type не указан - то будет создана пустая нода, без указания типа
  public function node_create($parent_nid, $node_type='', $weight=0) : int;
}