<?php

namespace Utils;

/**
 * PHP autoloader
 * https://www.php.net/manual/en/language.oop5.autoload.php
 */
class ClassLoader
{
    private $prefix;

    public function __construct()
    {
        $this->prefix = '.';
    }

    public function register()
    {
        spl_autoload_register(array($this, 'loadClass'));
    }

    public function loadClass(string $classname)
    {
        $fileName = $this->getFilename($classname);
        $path = $this->makePath($fileName);
        if (file_exists($path)) {
            require_once($path);
        }
    }

    public function getFilename(string $classname): string
    {
        return str_replace("\\", '/', $classname) . ".php";
    }

    public function makePath(string $filename): string
    {
        return $this->prefix . DIRECTORY_SEPARATOR . $filename;
    }
}
