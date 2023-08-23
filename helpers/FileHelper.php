<?php

namespace Siyaludeio\IPAccessGuard\Helpers;

class FileHelper
{
    public static function readFromFile($filePath): bool|string|null
    {
        if (file_exists($filePath)) {
            return file_get_contents($filePath);
        }
        return null;
    }

    public static function writeToFile($filePath, $content): void
    {
        file_put_contents($filePath, $content);
    }
}
