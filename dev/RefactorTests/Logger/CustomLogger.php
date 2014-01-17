<?php

  namespace RefactorTests\Logger;

  /**
   * @author Ivan Shcherbak <dev@funivan.com> 1/17/14
   */
  class CustomLogger extends \Fiv\Refactor\Log {

    public $logs = [];

    public function log($message, $level) {
      if ($level <= $this->level) {
        $this->logs[] = new Error($message, $level);
      }
    }

    public function getLogs() {
      return $this->logs;
    }

    public function getLastLogItem() {
      $item = end($this->logs);
      if (!empty($item)) {
        return $item;
      }

      return new Error();
    }
  }