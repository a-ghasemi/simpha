<?php

namespace Kernel\Abstractions;

interface IErrorHandler
{
    public function addError($title, $message);
    public function throwError();
}