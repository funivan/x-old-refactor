<?php

  namespace Fiv\Refactor\Tool;

  /**
   * Simple preg replace file content
   *
   * @author Ivan Shcherbak <dev@funivan.com> 1/16/14
   */
  class PregReplace extends \Fiv\Refactor\FilePatch {

    /**
     * @var array
     */
    protected $fromTo = [];

    /**
     * key value array
     *
     * @param array $fromToRegexp
     * @throws \Fiv\Refactor\Exception
     */
    public function __construct($fromToRegexp) {
      foreach ($fromToRegexp as $from => $to) {
        if (!\is_string($from)) {
          throw new \Fiv\Refactor\Exception("Invalid from regexp pattern. Expect string");
        }
        if (!\is_string($to)) {
          throw new \Fiv\Refactor\Exception("Invalid to regexp pattern. Expect string");
        }
      }

      $this->fromTo = $fromToRegexp;
    }

    /**
     * @param \Fiv\Tokenizer\File $file
     * @return void
     */
    protected function process(\Fiv\Tokenizer\File $file) {
      if (empty($this->fromTo)) {
        return;
      }
      $collection = $file->getCollection();
      $newCode = $collection->assemble();

      foreach ($this->fromTo as $from => $to) {
        $newCode = preg_replace($from, $to, $newCode);
      }

      $newCollection = \Fiv\Tokenizer\Collection::createFromString($newCode);
      $collection->setItems($newCollection);
    }

  }