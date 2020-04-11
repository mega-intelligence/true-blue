<?php


namespace App\Exceptions;


class NegativeWarehouseQuantityException extends \Exception
{
    protected $message = "product quantity can not be negative, or should be allowed in the trueblue.allow_negative_warehouse_quantities configuration file.";
}
