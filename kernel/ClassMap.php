<?php

namespace Kernel;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class ClassMap
{
    public static function map(string $namespace, string $path): array
    {
        return self::searchClasses($namespace, $path);
    }

    private static function searchClasses(string $namespace, string $path): array
    {
        $classes = [];

        /**
         * @var \RecursiveDirectoryIterator $iterator
         * @var \SplFileInfo $item
         */
        foreach ($iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        ) as $item) {
            if ($item->isDir()) {
                $nextPath = $iterator->current()->getPathname();
                $nextNamespace = $namespace . '\\' . $item->getFilename();
                $classes = array_merge($classes, self::searchClasses($nextNamespace, $nextPath));
                continue;
            }
            if ($item->isFile() && $item->getExtension() === 'php') {
                $class = $namespace . '\\' . $item->getBasename('.php');
                if (!class_exists($class)) {
                    continue;
                }
                $methods = get_class_methods($class);
                foreach($methods as $i => $method) {
                    if(str_starts_with($method,'__')) unset($methods[$i]);
                    elseif(in_array($method,['run'])) unset($methods[$i]);
                    elseif(in_array($method,['index'])) $methods[$i] = '';
                    else $methods[$i] = str_replace('_',':',$method);
                }
                $class = str_replace($namespace.'\\','',$class);
                $classes[$class] = array_values($methods);
            }
        }

        return $classes;
    }
}