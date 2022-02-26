<?php

namespace App\Infrastructure\Helpers;

class FilePathHelper
{
    public static function validatePath(string|null $path, string $acceptablePathExt) : string|null {
        if (empty($path)) {
            return null;
        }
        if (!file_exists($path)) {
            return "Invalid file path. Path: $path";
        }
        if (pathinfo($path, PATHINFO_EXTENSION) !== $acceptablePathExt) {
            return 'Only files with ' . $acceptablePathExt . ' extension are allowed.';
        }
        return null;
    }
}
