<?php


function autoload($class)
{
    if (mb_strstr($class, '\ATCore')) {
        $class = mb_substr($class, mb_strpos($class, '\ATCore') + 1);
    }

    if ($class == 'ATCore') {
        $dir = dirname(__FILE__) . '/at_core/core.php';
        require_once $dir;
        class_alias('ATCore', 'classes\base\ATCore');
        return true;
    }

    $file = $class . '.php';

    if ($env = find($file)) {
        if (!mb_strstr($class, 'Controller_') && !mb_strstr($class, 'Model_') && !mb_strstr($class, 'ATCore_') && $env['env'] != 'module') {
            if (class_exists('classes\base\ATCore_' . $class) && !class_exists($class)) {
                class_alias('classes\base\ATCore_' . $class, $class);
            }
        }
    }

    return true;
}

function find($file)
{
    $file = strtolower($file);

    /*
     * Find class in application
     */
    try {
        $dir = dirname(__FILE__) . '/' . ATCore::$app . '/' . $file;
        if (file_exists($dir)) {
            require_once $dir;
            $env = array(
                'file' => $file,
                'dir' => $dir,
                'env' => 'application'
            );
            return $env;
        } else {
            throw new Exception();
        }
    } catch (Exception $e) {
        /*
         * Find extension for base class
         */
        try {
            $dir = dirname(__FILE__) . '/at_core/classes/' . $file;
            if (file_exists($dir) && !mb_strstr($file, 'atcore_')) {
                require_once $dir;
                $env = array(
                    'file' => $file,
                    'dir' => $dir,
                    'env' => 'extend_class'
                );
                return $env;
            } else {
                throw new Exception();
            }
        } catch (Exception $e) {
            /*
             * Find base class
             */
            try {
                $dir = dirname(__FILE__) . '/at_core/classes/base/' . str_ireplace('atcore_', '', $file);
                if (file_exists($dir)) {
                    require_once $dir;
                    $env = array(
                        'file' => $file,
                        'dir' => $dir,
                        'env' => 'base_class'
                    );
                    return $env;
                } else {
                    throw new Exception();
                }
            } catch (Exception $e) {
                /*
                 * Find module
                 */
                try {
                    $dir = dirname(__FILE__) . '/module/' . $file;
                    if (file_exists($dir)) {
                        require_once $dir;
                        $env = array(
                            'file' => $file,
                            'dir' => $dir,
                            'env' => 'module'
                        );
                        return $env;
                    } else {
                        throw new Exception();
                    }
                } catch (Exception $e) {
                    /*
                     * Find controller
                     */
                    try {
                        if (!mb_strstr($file, '_')) {
                            throw new Exception();
                        }

                        $arr_path = explode('_', $file, 2);
                        $dir = dirname(__FILE__) . '/' . ATCore::$app . '/' . mb_strtolower($arr_path[0]) . '/' . mb_strtolower($arr_path[1]);
                        if (file_exists($dir)) {
                            require_once $dir;
                            $env = array(
                                'file' => $file,
                                'dir' => $dir,
                                'env' => 'controller'
                            );
                            return $env;
                        } else {
                            throw new Exception();
                        }
                    } catch (Exception $e) {
                        echo $e;
                        return false;
                    }
                }
            }
        }
    }
}