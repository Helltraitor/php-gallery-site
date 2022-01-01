<?php

declare(strict_types=1);

namespace Controllers;

/**
 * Class ImageController abstract basic class for all image controllers.
 * As you can see controller classes have no common but that is different
 * for view classes: these have many common. That's why I decided
 * to link controllers as I did it for views
 */
abstract class ImageController extends ABCController {}