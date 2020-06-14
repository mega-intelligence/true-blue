<?php

/**
 * Sets the active page to the session
 * @param string $page
 */
function activatePage(string $page): void
{
    session()->flash('active_page', $page);
}

/**
 * Returns the 'active' css class if the active page is selected
 * @param string $pageToTest
 * @return string
 */
function activateClass(string $pageToTest): string
{
    return session('active_page') === $pageToTest ? 'active' : '';
}
