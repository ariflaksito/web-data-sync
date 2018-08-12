<?php namespace AppExceptions;

use Illuminate\Contracts\Debug\ExceptionHandler as ExHandler;

class Handler implements ExHandler {
    public function report(\Exception $e) {
        throw $e;
    }

    public function render($request, \Exception $e) {
        throw $e;
    }

    public function renderForConsole($output, \Exception $e) {
        throw $e;
    }
}