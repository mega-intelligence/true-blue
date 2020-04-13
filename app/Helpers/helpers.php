<?php


function activeClassIfActive(string $page, string $activePage = null): string
{
    return $activePage ? ($activePage === $page) ? "active" : "" : "";
}
