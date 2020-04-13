<?php


namespace App\Exceptions;


class NoDefaultVatException extends \Exception
{
    protected $message = "No default VAT has been set, you can set one in the 'vats' table, by putting the 'default' field to true";
}
