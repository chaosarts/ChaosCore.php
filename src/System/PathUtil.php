<?php 
namespace Chaos\Core\System;

class PathUtil {

    /**
     * Shortcut for DIRECTORY_SEPARATOR
     * @var string
     */
    const DS = DIRECTORY_SEPARATOR;

    /**
     * Returns the list of arguments as path string
     * @param string ... 
     * @return string
     */
    public static function join () {
        $arguments = func_get_args();
        $path = implode(self::DS, $arguments);
        if (count($arguments) > 0 && empty($path))
            return self::DS;
        return $path;
    }


    /**
     * Cleans up a path string
     * @param string $path
     * @return string
     */
    public static function clean ($path) {
        $parts = explode(self::DS, preg_replace('/\\' . self::DS .'+/', self::DS, $path));
        $output = array();
        while (!empty($parts)) {
            $part = array_shift($parts);
            switch ($part) {
                case '.':
                    continue;
                case '..':
                    if (!empty($output)) array_pop($output);
                    else array_push($output, $part);
                    break;
                default:
                    array_push($output, $part);
                    break;
            }
        }

        if (preg_match('/^\\' . self::DS . '/', $path) && (empty($output) || $output[0] != '')) {
            array_unshift($output, '');
        }

        return preg_replace('/(.+)\\' . self::DS . '$/', '$1', self::join(self::DS, $output));
    }
}