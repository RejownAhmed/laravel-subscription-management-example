<?php

namespace App\Providers;

use Carbon\Laravel\ServiceProvider;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class HelperServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->includeHelper();
    }

    private function includeHelper(): void
    {
        $rdi = new RecursiveDirectoryIterator(app_path("Helpers"));
        $it = new RecursiveIteratorIterator($rdi);
        while ($it->valid()) {
            if (
                ! $it->isDot() &&
                $it->isFile() &&
                $it->isReadable() &&
                $it->current()->getExtension() === 'php' &&
                strpos($it->current()->getFilename(), 'Helper')
            ) {
                require $it->key();
            }
            $it->next();
        }
    }
}
