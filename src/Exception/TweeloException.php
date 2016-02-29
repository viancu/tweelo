<?php

namespace Tweelo\Exception;


use MongoDB\Driver\Exception\Exception;

class TweeloException extends \Exception
{
    /**
     * TweeloException constructor.
     * @param null $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($message = null, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}