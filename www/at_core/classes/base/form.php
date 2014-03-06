<?php

namespace classes\base;

class ATCore_Form
{
    protected static $_error = array();

    public static function parse_attr(array $params = array())
    {
        $attrs = array();

        foreach ($params as $attr => $value) {
            $attrs[] = $attr . '="' . $value . '"';
        }

        return implode(' ', $attrs);
    }

    public static function text($name, array $params = array())
    {
        static $index = 0;
        $attrs = static::parse_attr($params);

        if (preg_match('/[\[](.*)[\]]/i', $name, $key)) {
            $i = (!empty($key[1]) ? $key[1] : null);

            $real_name = preg_replace('/[\[].*[\]]/i', '', $name);

            if (!isset($_POST[$real_name][$i])) {
                $_POST[$real_name][$i] = 0;
            }

            if (isset($_POST[$real_name])) {
                if (!is_null($i)) {
                    if (isset($_POST[$real_name][$i]) && $_POST[$real_name][$i]) {
                        echo '<input type="text" name="' . $name . '" ' . $attrs . ' value="' . $_POST[$real_name][$i] . '" />';
                    } else {
                        echo '<input type="text" name="' . $name . '" ' . $attrs . ' />';
                    }
                } else {
                    if (isset($_POST[$real_name][$index])) {
                        echo '<input type="text" name="' . $real_name . '[' . $index . ']" ' . $attrs . ' value="' . $_POST[$real_name][$index] . '" />';
                    } else {
                        echo '<input type="text" name="' . $real_name . '[' . $index . ']" ' . $attrs . ' />';
                    }
                }
            } else {
                echo '<input type="text" name="' . $real_name . '[' . $index . ']" ' . $attrs . ' />';
            }

            $index++;
        } else {
            echo '<input type="text" name="' . $name . '" ' . $attrs . ' value="' . (isset($_POST[$name]) ? $_POST[$name] : '') . '"/>';
        }
    }

    public static function password($name, array $params = array())
    {
        $attrs = static::parse_attr($params);
        echo '<input type="password" name="' . $name . '" ' . $attrs . ' value="' . (isset($_POST[$name]) ? $_POST[$name] : '') . '"/>';
    }

    public static function hidden($name, array $params = array())
    {
        $attrs = static::parse_attr($params);
        echo '<input type="hidden" name="' . $name . '" ' . $attrs . ' value="' . (isset($_POST[$name]) ? $_POST[$name] : '') . '"/>';
    }

    public static function textarea($name, array $params = array())
    {
        $attrs = static::parse_attr($params);
        echo '<textarea name="' . $name . '" ' . $attrs . '>' . (isset($_POST[$name]) ? $_POST[$name] : '') . '</textarea>';
    }

    public static function checkbox($name, array $params = array())
    {
        static $index = 0;
        $attrs = static::parse_attr($params);

        if (preg_match('/[\[](.*)[\]]/i', $name, $key)) {
            $i = (!empty($key[1]) ? $key[1] : null);

            $real_name = preg_replace('/[\[].*[\]]/i', '', $name);

            if (!isset($_POST[$real_name][$i])) {
                $_POST[$real_name][$i] = 0;
            }

            if (isset($_POST[$real_name])) {
                if (!is_null($i)) {
                    if (isset($_POST[$real_name][$i]) && $_POST[$real_name][$i]) {
                        echo '<input type="checkbox" value="1" name="' . $name . '" ' . $attrs . ' checked="checked" />';
                    } else {
                        echo '<input type="checkbox" value="1" name="' . $name . '" ' . $attrs . ' />';
                    }
                } else {
                    if (isset($_POST[$real_name][$index]) && $_POST[$real_name][$index]) {
                        echo '<input type="checkbox" value="1" name="' . $real_name . '[' . $index . ']" ' . $attrs . ' checked="checked" />';
                    } else {
                        echo '<input type="checkbox" value="1" name="' . $real_name . '[' . $index . ']" ' . $attrs . ' />';
                    }
                }
            } else {
                echo '<input type="checkbox" value="1" name="' . $real_name . '[' . $index . ']" ' . $attrs . ' />';
            }

            $index++;
        } else {
            echo '<input type="checkbox" value="1" name="' . $name . '" ' . $attrs . ' ' . ((isset($_POST[$name]) && $_POST[$name]) ? 'checked=checked' : '') . '/>';
        }
    }

    public static function select($name, $options = array(), array $params = array())
    {
        $attrs = static::parse_attr($params);
        $select = '<select name="' . $name . '" ' . $attrs . '>';

        if (!empty($options)) {
            foreach ($options as $value => $option) {
                $select .= '<option value="' . $value . '" ' . ((isset($_POST[$name]) && $_POST[$name] == $value && strlen($_POST[$name]) == strlen($value)) ? 'selected' : '') . '>' . $option . '</option>';
            }
        }

        $select .= '</select>';

        echo $select;
    }

    public static function file($name, array $params = array())
    {
        $attrs = static::parse_attr($params);
        echo '<input type="file" name="' . $name . '" ' . $attrs . ' />';
    }

    public static function validation(array $data = array())
    {
        $error = 0;

        if (!empty($data)) {
            foreach ($data as $name => $validation) {
                if (empty($name)) {
                    continue;
                }

                if (empty($validation['validation']) || !is_array($validation['validation'])) {
                    continue;
                }

                if (array_key_exists('empty', $validation['validation'])) {
                    if (array_key_exists('value', $validation)) {
                        if (empty($validation['value'])) {
                            if (!empty($validation['validation']['empty']['message'])) {
                                static::error($name, $validation['validation']['empty']['message']);
                            } else {
                                static::error($name);
                            }

                            $error = 1;
                            continue;
                        }
                    }
                }

                if (array_key_exists('minlength', $validation['validation'])) {
                    if (array_key_exists('value', $validation)) {
                        if (array_key_exists('data', $validation['validation']['minlength'])) {
                            if (mb_strlen($validation['value']) < intval($validation['validation']['minlength']['data'])) {
                                if (!empty($validation['validation']['minlength']['message'])) {
                                    static::error($name, $validation['validation']['minlength']['message']);
                                } else {
                                    static::error($name);
                                }

                                $error = 1;
                                continue;
                            }
                        }
                    }
                }

                if (array_key_exists('maxlength', $validation['validation'])) {
                    if (array_key_exists('value', $validation)) {
                        if (array_key_exists('data', $validation['validation']['maxlength'])) {
                            if (mb_strlen($validation['value']) > intval($validation['validation']['maxlength']['data'])) {
                                if (!empty($validation['validation']['maxlength']['message'])) {
                                    static::error($name, $validation['validation']['maxlength']['message']);
                                } else {
                                    static::error($name);
                                }

                                $error = 1;
                                continue;
                            }
                        }
                    }
                }

                if (array_key_exists('regexp', $validation['validation'])) {
                    if (!empty($validation['validation']['regexp']['data'])) {
                        if (!preg_match($validation['validation']['regexp']['data'], $validation['value'])) {
                            if (!empty($validation['validation']['regexp']['message'])) {
                                static::error($name, $validation['validation']['regexp']['message']);
                            } else {
                                static::error($name);
                            }

                            $error = 1;
                            continue;
                        }
                    }
                }

                if (array_key_exists('match', $validation['validation'])) {
                    if (!empty($validation['validation']['match']['data'])) {
                        if ($validation['validation']['match']['data'] != $validation['value']) {
                            if (!empty($validation['validation']['match']['message'])) {
                                static::error($name, $validation['validation']['match']['message']);
                            } else {
                                static::error($name);
                            }

                            $error = 1;
                            continue;
                        }
                    }
                }
            }
        }

        if (!$error) {
            return true;
        }

        return false;
    }

    public static function error($name, $message = '')
    {
        static::$_error[$name] = $message;
    }

    public static function has_error($name)
    {
        if (isset(static::$_error[$name])) {
            return true;
        }

        return false;
    }

    public static function notice($name)
    {
        if (static::has_error($name)) {
            echo static::$_error[$name];
        }
    }
}