<?php

  namespace RefactorTests\Fiv\Refactor;

  use RefactorTests\Demo\Log;

  class DirectoryPatchTest extends \RefactorTests\Main {

    protected function getDirectoryPath() {
      $directoryPatch = new \Fiv\Refactor\DirectoryPatch();
      $directoryPatch->setLogger(new Log());
      return $directoryPatch;
    }

    public function testEmptyPathList() {
      $directoryPatch = $this->getDirectoryPath();

      $directoryPatch->apply('/tmp');
      $lastErrorLevel = $directoryPatch->getLogger()->getLastLogItem()->getLevel();

      $this->assertEquals(Log::LEVEL_ERROR, $lastErrorLevel);
    }

    public function testDirectoryValidation() {
      $directoryPatch = $this->getDirectoryPath();
      $directoryPatch->addPatch(new \Fiv\Refactor\Tool\PregReplace([
        '!test-value!' => 'test-value'
      ]));

      $directoryPatch->apply('');
      $lastErrorLevel = $directoryPatch->getLogger()->getLastLogItem()->getLevel();

      $this->assertEquals(Log::LEVEL_ERROR, $lastErrorLevel);

      $directoryPatch->apply(__DIR__ . '/custom_undefined_directory');
      $lastErrorLevel = $directoryPatch->getLogger()->getLastLogItem()->getLevel();

      $this->assertEquals(Log::LEVEL_ERROR, $lastErrorLevel);
    }

    public function testOnEmptyDirectory() {
      $directoryPatch = $this->getDirectoryPath();
      $directoryPatch->addPatch(new \Fiv\Refactor\Tool\PregReplace([
        '!test-value!' => 'test-value'
      ]));

      $directoryPatch->apply($this->getDemoDataDirectoryPath());
      $lastErrorLevel = $directoryPatch->getLogger()->getLastLogItem()->getLevel();

      $this->assertEquals(Log::LEVEL_INFO, $lastErrorLevel);
    }

    public function testFileRegexpReplace() {
      $directoryPatch = $this->getDirectoryPath();
      $directoryPatch->addPatch(new \Fiv\Refactor\Tool\PregReplace([
        '!test-value!' => 'other-value'
      ]));

      $demoDataDirectoryPath = $this->getDemoDataDirectoryPath();

      $filePath = $demoDataDirectoryPath . 'text.php';
      file_put_contents($filePath, '<?php echo "test-value";');

      $directoryPatch->apply($demoDataDirectoryPath);

      $log = $directoryPatch->getLogger();
      $level = $log->getLastLogItem()->getLevel();

      $newFileContent = file_get_contents($filePath);
      $this->assertNotContains('other-value', $newFileContent);
      $this->assertEquals(Log::LEVEL_VERBOSE, $level);

      $level = $log->getLastLogItem()->getLevel();
      $directoryPatch->apply($demoDataDirectoryPath, true);

      $newFileContent = file_get_contents($filePath);
      $this->assertContains('other-value', $newFileContent);


      unlink($filePath);

    }

    public function testLogger() {

      $logger = new Log();

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