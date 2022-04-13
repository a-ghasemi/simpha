<?php

namespace Kernel\Abstractions;

interface IErrorHandler
{
    public function addError($title, $message = null);
    public function throwError();
}