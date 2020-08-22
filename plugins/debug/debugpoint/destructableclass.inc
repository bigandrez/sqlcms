<?php
  class MyDestructableClass {
    public $registered_signals;

    function __destruct() {
      echo "\r\n======================================\r\nList of registered signals and events:\r\n\r\n";
      print_r($this->registered_signals);
    }
  }
