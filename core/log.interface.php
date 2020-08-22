<?php // Интерфейс логов

interface iSqlJsonLog{
  // $msgtext - текст сообщения в логе
  // $group - текстовое описание группы лога. Описание желательно короткое и по английски - возможно будет именем папки в папке cache
  // $msgpriority - приоритет сообщения
  //   critical error = -2 - критическая ошибка
  //            error = -1 - ошибка
  //      log message = 0  - простое сообщение
  //          warning = 1  - предупреждение
  // critical warning = 2  - серъезное предупреждение
  // $args - ссыдка на массив аргументов функции, которая выводит сообщение в лог
  public function tolog($msgtext, $group="common", $msgpriority = 0, &$args = array());
}