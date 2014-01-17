<?php

  namespace Fiv\Refactor;

  /**
   * @author Ivan Shcherbak <dev@funivan.com> 1/16/14
   */
  abstract class FilePatch extends Base {

    /**
     * @var null|\Fiv\Refactor\Validator
     */
    protected $validator = null;

    public function __construct() {
      $this->init();
    }

    /**
     * @param \Fiv\Tokenizer\File $file
     * @return void
     */
    protected abstract function process(\Fiv\Tokenizer\File $file);

    /**
     * Set default validator
     */
    protected function init() {
      $this->setValidator(new Validator());
    }

    /**
     * Apply patch to file
     *
     * @param \Fiv\Tokenizer\File $file
     * @param bool $save
     */
    public function apply(\Fiv\Tokenizer\File $file, $save = false) {
      $this->log('Start apply:' . \get_class($this), Log::LEVEL_INFO);

      $this->process($file);

      $this->log('File process successful', Log::LEVEL_VERBOSE);

      if (!$file->isChanged()) {
        $this->log('No changes', Log::LEVEL_VERBOSE);
        return;
      }

      if (!$save) {
        $this->log('Skip file save', Log::LEVEL_VERBOSE);
        return;
      }

      if ($this->validator !== null) {
        $code = $file->getCollection()->assemble();
        $this->log('Start file validation', Log::LEVEL_VERBOSE);
        $fileIsValid = $this->validator->isValid($code);
        if (!$fileIsValid) {
          $this->log('Code in file is invalid', Log::LEVEL_ERROR);
          return;
        }
      }

      $this->log('Save file', Log::LEVEL_INFO);
      $file->save();

    }

    /**
     * @param string $filePath
     * @return bool
     */
    public function filterByPath($filePath) {
      return true;
    }

    /**
     * @param \Fiv\Refactor\Validator $validator
     * @return $this
     */
    public function setValidator(Validator $validator) {
      $this->validator = $validator;
      return $this;
    }

    /**
     * @return \Fiv\Refactor\Validator
     */
    public function getValidator() {
      return $this->validator;
    }

  }