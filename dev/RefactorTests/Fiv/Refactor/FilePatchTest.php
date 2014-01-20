<?php

  namespace RefactorTests\Fiv\Refactor;

  /**
   * @author Ivan Shcherbak <dev@funivan.com> 1/20/14
   */
  class FilePatchTest extends \RefactorTests\Main {


    public function testCustomReplace() {
      $directoryPatch = $this->getDirectoryPath();
      $directoryPatch->addPatch(new \RefactorTests\Demo\FileCustomPatch());

      $demoDataDirectoryPath = $this->getDemoDataDirectoryPath();

      $filePath = $demoDataDirectoryPath . 'text.js';
      $this->createFile($filePath, '<?php echo "test-value";');

      $directoryPatch->apply($demoDataDirectoryPath);

      $log = $directoryPatch->getLogger();
      $lastItem = $log->getLastLogItem();
      $level = $lastItem->getLevel();

      $this->assertEquals(\Fiv\Refactor\Log::LEVEL_INFO, $level);

      unlink($filePath);
    }


  }