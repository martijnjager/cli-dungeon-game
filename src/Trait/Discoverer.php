<?php

namespace CliGame\Trait;

trait Discoverer
{
    /**
     * Discover and return class names from a specified directory and namespace.
     *
     * @param string $directory The directory to scan for classes.
     * @param string $namespace The namespace corresponding to the directory.
     * @param string $suffix Optional suffix to filter class names (e.g., 'Command', 'Monster').
     * @return array<string> An array of fully qualified class names.
     */
    public function discoverClasses(string $directory, string $namespace, string $suffix = ''): array
    {
        $classes = [];
        $files = glob($directory . DIRECTORY_SEPARATOR . '*' . $suffix . '.php') ?: [];

        foreach ($files as $file) {
            $className = $namespace . '\\' . basename($file, '.php');

            if (class_exists($className)) {
                $classes[] = $className;
            }
        }

        return $classes;
    }
}