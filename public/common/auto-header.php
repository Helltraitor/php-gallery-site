<?php

declare(strict_types=1);

if (array_key_exists('AUTH', $_SESSION) && $_SESSION['AUTH']) {
    include_once __DIR__ . '/../../public/common/user-header.php';
} else {
    include_once __DIR__ . '/../../public/common/guest-header.html';
}