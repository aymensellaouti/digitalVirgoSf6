<?php

namespace App\Twig\Runtime;

use Twig\Extension\RuntimeExtensionInterface;

class MyCustomRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    public function defaultImage(string $path)
    {
        if(!strlen(trim($path))) {
            return 'default.png';
        }
        return $path;
    }
}
