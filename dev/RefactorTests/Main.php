<?php

  namespace RefactorTests;

  use RefactorTests\Demo\Log;

  class Main extends \PHPUnit_Framework_TestCase {

    protected function getDemoDataDirectoryPath() {
      return __DIR__ . '/../demo-data/';
    }

    protected function getDirectoryPath() {
      $directoryPatch = new \Fiv\Refactor\DirectoryPatch();
      $directoryPatch->setLogger(new Log());
      return $directoryPatch;
    }

  }