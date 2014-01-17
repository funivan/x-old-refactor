<?php

  namespace Fiv\Refactor;

  /**
   * Base class for patches
   */
  class Base {

    /**
     * @var null|\Fiv\Refactor\Log
     */
    protected $logger = null;

    /**
     * @param \Fiv\Refactor\Log $logger
     * @return $this
     */
    public function setLogger(Log $logger) {
      $this->logger = $logger;
      return $this;
    }


    /**
     * @return \Fiv\Refactor\Log|null
     */
    public function getLogger() {
      return $this->logger;
    }


    /**
     * Send message to logger
     *
     * @param string $message
     * @param int $level
     */
    public function log($message, $level) {
      if ($this->logger !== null) {
        $this->logger->log($message, $level);
      }
    }

  }
