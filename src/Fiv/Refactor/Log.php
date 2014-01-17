<?php

  namespace Fiv\Refactor;

  /**
   * @author Ivan Shcherbak <dev@funivan.com> 1/16/14
   */
  class Log {

    const LEVEL_ERROR = 1;

    const LEVEL_INFO = 2;

    const LEVEL_VERBOSE = 3;

    /**
     * @var int
     */
    protected $level = 3;

    /**
     * @param null|int $level
     */
    public function __construct($level = null) {
      if (is_int($level)) {
        $this->level = $level;
      }
    }

    /**
     * Output message
     *
     * @param string $message
     * @param int $level
     */
    public function log($message, $level) {
      if ($level <= $this->level) {
        echo '[' . $level . ']' . $message . "\n";
      }
    }
  }