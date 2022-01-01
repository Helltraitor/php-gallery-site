<?php

declare(strict_types=1);

namespace Views;

/**
 * Class ABCView is abstract basic class (ABC) for all views in current mvc
 * system. Must be created and used by a controller
 *
 * @package Views contains all views of the site
 */
abstract class ABCView
{
    /**
     * This function is used for render template php file through include
     * operation
     */
    abstract public function render();
}