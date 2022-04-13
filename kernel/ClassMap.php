<?php

namespace Kernel;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class ClassMap
{
    public static function map(string $namespace, string $path): array
    {
        $classes = [];

        foreach ($iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        ) as $item) {
            if ($item->isDir()) {
                $classes = self::fetchDirectory($iterator, $namespace, $item, $classes);
            }
            elseif ($item->isFile() && $item->getExtension() === 'php') {
                $class = $namespace . "\\" . $item->getBasename('.php');

                // excludes not-class files
                if (!class_exists($class)) {
                    continue;
                }

                $methods = self::fetchMethods($class);

                $class = str_replace($namespace . "\\" ,'',$class);
                $classes[$class] = array_values($methods);
            }
        }

        return $classes;
    }

    protected static function fetchDirectory(RecursiveIteratorIterator $iterator, string $namespace, $item, $classes): array
    {
        $nextPath = $iterator->current()->getPathname();
        $nextNamespace = $namespace . '\\' . $item->getFilename();
        $classes = array_merge($classes, self::map($nextNamespace, $nextPath));
        return $classes;
    }

    protected static function fetchMethods(string $class): array
    {
        $methods = get_class_methods($class);
        foreach ($methods as $i => $method) {
            if (str_starts_with($method, '__')) unset($methods[$i]);
            elseif (in_array($method, ['run'])) unset($methods[$i]);
            elseif (in_array($method, ['index'])) $methods[$i] = '';
            else $methods[$i] = str_replace('_', ':', $method);
        }
        return $methods;
    }
}