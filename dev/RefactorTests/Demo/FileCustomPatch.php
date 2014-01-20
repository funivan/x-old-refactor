<?php

  namespace RefactorTests\Demo;

  /**
   * @author Ivan Shcherbak <dev@funivan.com> 1/20/14
   */
  class FileCustomPatch extends \Fiv\Refactor\FilePatch {

    /**
     * @param \Fiv\Tokenizer\File $file
     * @return void
     */
    protected function process(\Fiv\Tokenizer\File $file) {

    }

    public function filterByPath($filePath) {
      if (!preg_match('!\.php$!', $filePath)) {
        return false;
      }
      return parent::filterByPath($filePath);
    }

  }