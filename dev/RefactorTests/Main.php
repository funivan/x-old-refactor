<?php

  namespace RefactorTests;

  use RefactorTests\Demo\Log;

  /**
   *
   * @package RefactorTests
   */
  class Main extends \PHPUnit_Framework_TestCase {

    /**
     * @return string
     */
    protected function getDemoDataDirectoryPath() {
      return __DIR__ . '/../demo-data/';
    }

    /**
     * @param string $filePath
     * @param string $data
     */
    protected function createFile($filePath, $data) {
      $dir = dirname($filePath);
      if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
      }

      file_put_contents($filePath, $data);
    }

    /**
     * @return \Fiv\Refactor\DirectoryPatch
     */
    protected function getDirectoryPath() {
      $directoryPatch = new \Fiv\Refactor\DirectoryPatch();
      $directoryPatch->setLogger(new Log());
      return $directoryPatch;
    }

  }