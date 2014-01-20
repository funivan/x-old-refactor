<?php

  namespace Fiv\Refactor;

  /**
   * By default use standard validator
   *
   * @author Ivan Shcherbak <dev@funivan.com> 1/16/14
   */
  class DirectoryPatch extends Base {

    /**
     * @var FilePatch[]
     */
    protected $patch = [];

    /**
     * @param FilePatch $path
     * @return $this
     */
    public function addPatch(FilePatch $path) {
      $this->patch[] = $path;
      return $this;
    }

    /**
     * @param string $dir
     * @param bool $save
     * @return null|void
     */
    public function apply($dir, $save = false) {

      if (empty($this->patch)) {
        $this->log("Empty patch list", Log::LEVEL_ERROR);
        return;
      }

      if (empty($dir)) {
        $this->log("Empty directory path", Log::LEVEL_ERROR);
        return;
      }

      if (!is_dir($dir)) {
        $this->log("Directory does not exist", Log::LEVEL_ERROR);
        return;
      }

      $this->log("Find files in directory: " . $dir, Log::LEVEL_VERBOSE);

      $files = $this->getFilesList($dir);

      $filesNum = count($files);
      if (empty($filesNum)) {
        $this->log("Empty files list: #" . count($files), Log::LEVEL_INFO);
        return;
      }

      $this->setLoggerToPatches();

      $this->log("number of files: " . $filesNum, Log::LEVEL_VERBOSE);
      $this->log("Start iterate", Log::LEVEL_VERBOSE);

      foreach ($files as $k => $filePath) {

        if (!is_file($filePath)) {
          unset($files[$k]);
          continue;
        }

        $this->applyPatches($save, $filePath);
      }
    }

    /**
     * @param boolean $save
     * @param string $filePath
     */
    protected function applyPatches($save, $filePath) {
      $this->log("Process file:" . $filePath, Log::LEVEL_VERBOSE);
      foreach ($this->patch as $patch) {
        if (!$patch->filterByPath($filePath)) {
          $this->log('File ignored by path condition', Log::LEVEL_INFO);
          continue;
        }
        $patch->apply(new \Fiv\Tokenizer\File($filePath), $save);
      }
    }

    /**
     * @param string $dir
     * @return array
     */
    protected function getFilesList($dir) {
      $rawList = shell_exec("find " . $dir . " -type f");
      if (empty($rawList)) {
        return [];
      }
      $files = explode("\n", $rawList);
      return $files;
    }

    protected function setLoggerToPatches() {
      if ($this->logger !== null) {
        $this->log("Connect logger to all patches", Log::LEVEL_VERBOSE);
        foreach ($this->patch as $patch) {
          $patch->setLogger($this->logger);
        }
      }
    }

  }
