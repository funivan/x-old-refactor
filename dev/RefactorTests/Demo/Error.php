<?php

  namespace RefactorTests\Demo;

  /**
   * @author Ivan Shcherbak <dev@funivan.com> 1/17/14
   */
  class Error {

    protected $level = null;

    protected $message = null;

    public function __construct($message = null, $level = null) {
      $this->level = $level;
      $this->message = $message;
    }


    /**
     * @param null $level
     * @return $this
     */
    public function setLevel($level) {
      $this->level = $level;
      return $this;
    }

    /**
     * @return null
     */
    public function getLevel() {
      return $this->level;
    }

    /**
     * @param null $message
     * @return $this
     */
    public function setMessage($message) {
      $this->message = $message;
      return $this;
    }

    /**
     * @return null
     */
    public function getMessage() {
      return $this->message;
    }

  }