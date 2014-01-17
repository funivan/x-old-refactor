<?php

  namespace RefactorTests\Fiv\Refactor;

  use RefactorTests\Logger\CustomLogger;

  class DirectoryPatchTest extends \RefactorTests\Main {

    public function testUndefinedDirectory() {

      $logger = new CustomLogger();

      $directoryPatch = new \Fiv\Refactor\DirectoryPatch();
      $directoryPatch->setLogger($logger);

      $directoryPatch->apply('custom_invalid_directory_name');
      $lastErrorLevel = $logger->getLastLogItem()->getLevel();

      $this->assertEquals(CustomLogger::LEVEL_ERROR, $lastErrorLevel);
    }

    public function testLogger() {

      $logger = new CustomLogger();

      $directoryPatch = new \Fiv\Refactor\DirectoryPatch();
      $directoryPatch->setLogger($logger);

      $this->assertEquals($logger, $directoryPatch->getLogger());

      $directoryPatch = new \Fiv\Refactor\DirectoryPatch();
      $directoryPatch->setLogger(new \Fiv\Refactor\Log());
      ob_start();
      $errorType = \Fiv\Refactor\Log::LEVEL_ERROR;
      $errorMessage = "error init";

      $directoryPatch->log($errorMessage, $errorType);
      $data = ob_get_clean();
      $this->assertEquals('[' . $errorType . ']' . $errorMessage . "\n", $data);
    }
  }