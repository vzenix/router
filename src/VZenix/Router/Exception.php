<?php

/**
 * @package    VZenix.Router
 *
 * @copyright  Copyright (C) 2017. vzenix.es
 * @license    GNU General Public License v3.
 */

namespace VZenix\Router;

/**
 * Basic exception class for Router library
 * @author Francisco Muros Espadas <paco@vzenix.es>
 */
class Exception extends \Exception
{

    /**
     * (PHP 5 &gt;= 5.1.0, PHP 7)<br/>
     * Construct the exception
     * @link http://php.net/manual/en/exception.construct.php
     * @param string $message [optional] <p>
     * The Exception message to throw.
     * </p>
     * @param int $code [optional] <p>
     * The Exception code.
     * </p>
     * @param Throwable $previous [optional] <p>
     * The previous exception used for the exception chaining.
     * </p>
     */
    public function __construct(string $message = "", int $code = 500, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
