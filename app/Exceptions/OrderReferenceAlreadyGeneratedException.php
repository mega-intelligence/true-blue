<?php


namespace App\Exceptions;


class OrderReferenceAlreadyGeneratedException extends \Exception
{
    protected $message = "A reference has already been generated for the selected Order, it can not be updated";
}
