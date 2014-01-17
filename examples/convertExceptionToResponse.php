#!/usr/bin/env php
<?php

  include __DIR__ . '/vendor/autoload.php';


  class ControllerRefactor extends \Fiv\Refactor\FilePatch {

    /**
     * Catch only php files
     *
     * @param string $filePath
     * @return bool
     */
    public function filterByPath($filePath) {
      return preg_match('!.*\.(php)$!', $filePath);
    }

    /**
     * @param \Fiv\Tokenizer\File $file
     */
    protected function process(\Fiv\Tokenizer\File $file) {

      $q = $file->getCollection()->extendedQuery();
      $q->strict()->valueIs('class');
      $q->strict()->typeIs(T_WHITESPACE);
      $q->strict()->valueLike('!^Site_.*_Controller$!');
      $q->section('{', '}');

      $block = $q->getBlock();
      if (count($block) >= 1) {
        foreach ($block->iterate() as $controllerClass) {
          $this->exceptionToResponse($controllerClass);
        }
      }

    }

    /**
     * Pass Controller class tokens collection
     *
     * @param \Fiv\Tokenizer\Collection $controllerClass
     */
    protected function exceptionToResponse(\Fiv\Tokenizer\Collection $controllerClass) {

      # find exception in controller
      $exceptionQuery = $controllerClass->extendedQuery();
      $exceptionQuery->strict()->valueLike('!^throw$!i');
      $exceptionQuery->strict()->valueLike('!^new$!i');
      $exceptionQuery->possible()->valueIs('\\');
      $exceptionQuery->strict()->valueLike('!^Exception$!i');
      $exceptionQuery->strict()->valueIs('(');
      $exceptionQuery->insertWhitespaceQueries();

      $exceptionsList = $exceptionQuery->getBlock();
      $exceptionsList->map(function (\Fiv\Tokenizer\Collection $exception) {
        $exception->map(function (\Fiv\Tokenizer\Token $token) {
          $token->setValue('');
        });


        $exception->getLast()->setValue('$this->response()->error(');
      });
    }

  }

  # ./convertExceptionToResponse.php -s   - save files
  # ./convertExceptionToResponse.php      - run without save

  $directoryPatch = new \Fiv\Refactor\DirectoryPatch();
  $directoryPatch->setLogger(new \Fiv\Refactor\Log());

  $directoryPatch->addPatch(new ControllerRefactor());


  $refactorDir = '/home/ivan/test/projects/custom-site/';

  $saveFlag = (!empty($argv[1]) and $argv[1] == '-s');
  $directoryPatch->apply($refactorDir, $saveFlag);


