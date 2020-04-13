<?php


namespace App\Helpers;


class ViewContext
{
    /**
     * @var string|null
     */
    public $activePage = null;

    /**
     * ViewContext constructor.
     * @param string|null $activePage
     */
    public function __construct(string $activePage = null)
    {
        $this->activePage = $activePage;
    }
}
