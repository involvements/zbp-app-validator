<?php
/**
 * Created by PhpStorm.
 * User: sx
 * Date: 2017/10/22
 * Time: 11:17
 */

namespace Zsxsoft\AppValidator\Helpers;


class PathHelper
{
    public static function getAbsoluteFilename($filename)
    {
        $path = [];
        $regEx = DIRECTORY_SEPARATOR == '\\'
            ? '/\/|' . preg_quote(DIRECTORY_SEPARATOR) . '/'
            : '/\/|' . '\\'. preg_quote(DIRECTORY_SEPARATOR) . '/';

        foreach (preg_split($regEx, $filename) as $part) {
            // ignore parts that have no value
            if (empty($part) || $part === '.') continue;

            if ($part !== '..') {
                // cool, we found a new part
                array_push($path, $part);
            } else if (count($path) > 0) {
                // going back up? sure
                array_pop($path);
            } else {
                // now, here we don't like
                throw new \Exception('Climbing above the root is not permitted.');
            }
	    }

        if (DIRECTORY_SEPARATOR !== '\\') {
            array_unshift($path, '');
        }

        return join(DIRECTORY_SEPARATOR, $path);
    }

    public static function rrmdir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . DIRECTORY_SEPARATOR . $object) == "dir") {
                        self::rrmdir($dir . DIRECTORY_SEPARATOR . $object);
                    } else {
                        unlink($dir . DIRECTORY_SEPARATOR . $object);
                    }
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    static public function rcopy($source, $dest)
    {
        if (is_dir($source)) {
            $dir_handle = opendir($source);
            while ($file = readdir($dir_handle)) {
                if ($file[0] !== '.') {
                    if (is_dir($source . DIRECTORY_SEPARATOR . $file)) {
                        @mkdir($dest . DIRECTORY_SEPARATOR . $file);
                        self::rcopy($source . DIRECTORY_SEPARATOR . $file, $dest . DIRECTORY_SEPARATOR . $file);
                    } else {
                        copy($source . DIRECTORY_SEPARATOR . $file, $dest . DIRECTORY_SEPARATOR . $file);
                    }
                }
            }
            closedir($dir_handle);
        } else {
            copy($source, $dest);
        }
    }

    public static function scanDirectory($path, $recursive = true, $returnType = "getPathName")
    {
        $ret = [];

        if ($recursive) {
            $dir = new \RecursiveDirectoryIterator($path);
            $iterator = new \RecursiveIteratorIterator($dir, \RecursiveIteratorIterator::SELF_FIRST);
        } else {
            $iterator = new \DirectoryIterator($path);
        }

        foreach ($iterator as $name => $object) {
            $fileName = $object->getFilename();
            if ($fileName == "." || $fileName == "..") {
                continue;
            }

            if (!$object->isDir()) {
                array_push($ret, $object->$returnType());
            }
        }

        return $ret;
    }
}
