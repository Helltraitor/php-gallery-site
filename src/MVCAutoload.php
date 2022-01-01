<?php

declare(strict_types=1);

/**
 * Custom simple mvc autoload which was used while testing the site
 * on the php web-server
 */

// CONTROLLERS
require_once __DIR__ . '/../mvc/controllers/abc.php';
require_once __DIR__ . '/../mvc/controllers/error.php';
require_once __DIR__ . '/../mvc/controllers/home.php';
require_once __DIR__ . '/../mvc/controllers/login.php';
require_once __DIR__ . '/../mvc/controllers/logout.php';
require_once __DIR__ . '/../mvc/controllers/signup.php';
// CONTROLLERS - IMAGE
require_once __DIR__ . '/../mvc/controllers/image/abc.php';
require_once __DIR__ . '/../mvc/controllers/image/best.php';
require_once __DIR__ . '/../mvc/controllers/image/get.php';
require_once __DIR__ . '/../mvc/controllers/image/latest.php';
require_once __DIR__ . '/../mvc/controllers/image/other.php';
require_once __DIR__ . '/../mvc/controllers/image/self.php';

// MODELS
require_once __DIR__ . '/../mvc/models/connection.php';
require_once __DIR__ . '/../mvc/models/image.php';
require_once __DIR__ . '/../mvc/models/session.php';
require_once __DIR__ . '/../mvc/models/user.php';

// VIEWS
require_once __DIR__ . '/../mvc/views/abc.php';
require_once __DIR__ . '/../mvc/views/error.php';
require_once __DIR__ . '/../mvc/views/home.php';
require_once __DIR__ . '/../mvc/views/login.php';
require_once __DIR__ . '/../mvc/views/signup.php';
// VIEWS - IMAGE
require_once __DIR__ . '/../mvc/views/image/abc.php';
require_once __DIR__ . '/../mvc/views/image/best.php';
require_once __DIR__ . '/../mvc/views/image/latest.php';
require_once __DIR__ . '/../mvc/views/image/other.php';
require_once __DIR__ . '/../mvc/views/image/self.php';