<?php

declare(strict_types=1);

/**
 * createUniqueId is the try to implement Unique User ID which will be used for accessing user into the site.
 * Here is no guarantee to unique, and string is not UUID format even, but I hope that this mechanism would be
 * enough to determine users within their cookie
 *
 * @return string with 128 length
 */
function createUniqueId(): string
{
    return hash_hmac('SHA512', uniqid((string)rand(), true), (string)rand());
}