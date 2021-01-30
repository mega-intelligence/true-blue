<?php


namespace App\Exceptions;


class CanNotAssignCategoryAsParentToItSelfException extends \Exception
{
    protected $message = "Can not assign category as parent of it self";
}
