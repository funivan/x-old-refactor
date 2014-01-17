<?php

  namespace Fiv\Refactor;

  /**
   * @author Ivan Shcherbak <dev@funivan.com> 1/17/14
   */
  class Validator {

    protected $tmpDir = '/tmp/';

    /**
     * @param string $code
     * @throws Exception
     * @return bool
     */
    public function isValid($code) {

      if (!is_dir($this->tmpDir)) {
        throw new Exception('Directory does not exist');
      }

      $filePath = tempnam($this->tmpDir, 'validation');

      file_put_contents($filePath, $code);
      $result = shell_exec('php -l ' . $filePath);

      unlink($filePath);

      return (strpos($result, 'No syntax errors detected in ') !== false);
    }

    /**
     * @param string $tmpDir
     * @return $this
     */
    public function setTmpDir($tmpDir) {
      $this->tmpDir = $tmpDir;
      return $this;
    }

  }