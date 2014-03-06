<?php

namespace classes\base;

class ATCore_String
{
    public static function filter($str)
    {
        $replace = array(
            '"' => '\"',
            '\'' => '\\\'',
            "\t" => ' ',
            "\r" => ' ',
            "\n" => ' '
        );

        $str = strtr($str, $replace);

        return $str;
    }

    public static function translit($str)
    {
        $tr = array(
            "А" => "a", "Б" => "b", "В" => "v", "Г" => "g", "Д" => "d", "Е" => "e", "Ж" => "j", "З" => "z", "И" => "i",
            "Й" => "y", "К" => "k", "Л" => "l", "М" => "m", "Н" => "n", "О" => "o", "П" => "p", "Р" => "r", "С" => "s",
            "Т" => "t", "У" => "u", "Ф" => "f", "Х" => "h", "Ц" => "ts", "Ч" => "ch", "Ш" => "sh", "Щ" => "sch",
            "Ъ" => "", "Ы" => "yi", "Ь" => "", "Э" => "e", "Ю" => "yu", "Я" => "ya", "а" => "a", "б" => "b", "в" => "v",
            "г" => "g", "д" => "d", "е" => "e", "ж" => "j", "з" => "z", "и" => "i", "й" => "y", "к" => "k", "л" => "l",
            "м" => "m", "н" => "n", "о" => "o", "п" => "p", "р" => "r", "с" => "s", "т" => "t", "у" => "u", "ф" => "f",
            "х" => "h", "ц" => "ts", "ч" => "ch", "ш" => "sh", "щ" => "sch", "ъ" => "y", "ы" => "yi", "ь" => "",
            "э" => "e", "ю" => "yu", "я" => "ya", " " => "_", "." => "", "/" => "_"
        );

        $complete = strtr(trim($str), $tr);

        if (preg_match('/[^A-Za-z0-9_\-]/', $complete)) $complete = preg_replace('/[^A-Za-z0-9_\-]/', '', $complete);

        return $complete;
    }

    public static function convert_date(array $date_format = array())
    {
        $default = array(
            'date' => date("Y-m-d H:i:s"),
            'date_reverse' => false,
            'time' => true,
            'month_str' => false,
            'time_before' => false
        );

        $date_format = array_merge($default, $date_format);

        $months = array(
            1 => 'Январь',
            'Февраль',
            'Март',
            'Апрель',
            'Май',
            'Июнь',
            'Июль',
            'Август',
            'Сентябрь',
            'Октябрь',
            'Ноябрь',
            'Декабрь'
        );

        $pattern = "/(\d+)(\D)+(\d+)(\D)+(\d+)(\D)+(\d+)(\D)+(\d+)(\D)+(\d+)/";

        if (isset($date_format['human']) && $date_format['human']) {
            $date = preg_replace('/(\d+)\D(\d+)\D(\d+)\D(\d+)\D(\d+)\D(\d+)/', '$1-$2-$3 $4:$5:$6', $date_format['date']);
            $readable = self::readable_date($date);
            if (!strtotime($readable)) {
                return $readable;
            }
        }

        if (isset($date_format['date_reverse']) && $date_format['date_reverse']) {
            $replacement = (isset($date_format['month_str']) && $date_format['month_str']) ?
                "$5 " . $months[intval(date('m', strtotime($date_format['date'])))] . " $1" :
                "$5$4$3$2$1";
        } else {
            $replacement = (isset($date_format['month_str']) && $date_format['month_str']) ?
                "$1 " . $months[intval(date('m', strtotime($date_format['date'])))] . " $5" :
                "$1$4$3$2$5";
        }

        if (isset($date_format['time']) && $date_format['time']) {
            if (isset($date_format['time_before']) && $date_format['time_before']) {
                $replacement = "$7$8$9$10$11$6" . $replacement;
            } else {
                $replacement .= "$6$7$8$9$10$11";
            }
        }

        $newDate = preg_replace($pattern, $replacement, $date_format['date']);

        return $newDate;
    }

    public static function readable_date($date)
    {
        $date = preg_replace('/(\d+)\D(\d+)\D(\d+)\D(\d+)\D(\d+)\D(\d+)/', '$1-$2-$3 $4:$5:$6', $date);

        $today = (strtotime(date("Y-m-d")) == strtotime(date("Y-m-d", strtotime($date))));
        $tomorrow = (strtotime("+1 day", strtotime(date("Y-m-d"))) == strtotime(date("Y-m-d", strtotime($date))));
        $yesterday = (strtotime("-1 day", strtotime(date("Y-m-d"))) == strtotime(date("Y-m-d", strtotime($date))));

        if ($today) {
            $cur_hour = (strtotime(date("Y-m-d H:00:00")) == strtotime(date("Y-m-d H:00:00", strtotime($date))));

            if ($cur_hour) {
                $sec_cur = strtotime(date('Y-m-d H:i:s'));
                $sec = strtotime(date('Y-m-d H:i:s', strtotime($date)));

                if ($sec_cur == $sec) {
                    $date = 'Только что';
                    return $date;
                }

                if ($sec_cur > $sec) {
                    if (($sec_cur - $sec) < 60) {
                        $date = preg_replace('/\d+\D\d+\D\d+$/', ($sec_cur - $sec) . ' сек. назад', $date);
                    } else {
                        $date = preg_replace('/\d+\D\d+\D\d+$/', intval(($sec_cur - $sec) / 60) . ' мин. назад', $date);
                    }
                } else {
                    if (($sec - $sec_cur) < 60) {
                        $date = preg_replace('/\d+\D\d+\D\d+$/', 'через ' . ($sec - $sec_cur) . ' сек.', $date);
                    } else {
                        $date = preg_replace('/\d+\D\d+\D\d+$/', 'через ' . intval(($sec - $sec_cur) / 60) . ' мин.', $date);
                    }
                }
            }

            $date = preg_replace('/^\d+\D\d+\D\d+/', 'Сегодня,', $date);

            return $date;
        }

        if ($tomorrow) {
            $date = preg_replace('/^\d+\D\d+\D\d+/', 'Завтра,', date("Y-m-d H:i:s", strtotime($date)));
            return $date;
        }

        if ($yesterday) {
            $date = preg_replace('/^\d+\D\d+\D\d+/', 'Вчера,', date("Y-m-d H:i:s", strtotime($date)));
            return $date;
        }

        return $date;
    }

    public static function is_utf8($string)
    {
        for ($i = 0; $i < strlen($string); $i++) {
            if (ord($string[$i]) < 0x80) continue;

            elseif ((ord($string[$i]) & 0xE0) == 0xC0) $n = 1; elseif ((ord($string[$i]) & 0xF0) == 0xE0) $n = 2; elseif ((ord($string[$i]) & 0xF8) == 0xF0) $n = 3; elseif ((ord($string[$i]) & 0xFC) == 0xF8) $n = 4; elseif ((ord($string[$i]) & 0xFE) == 0xFC) $n = 5; else                                        return false;

            for ($j = 0; $j < $n; $j++) {
                if ((++$i == strlen($string)) || ((ord($string[$i]) & 0xC0) != 0x80)) {
                    return false;
                }
            }
        }

        return true;
    }

    public static function autoencode($string, $encoding = 'utf-8')
    {
        if (static::is_utf8($string)) {
            $detect = 'utf-8';
        } else {
            $cp1251 = 0;
            $koi8u = 0;
            $strlen = strlen($string);

            for ($i = 0; $i < $strlen; $i++) {
                $code = ord($string[$i]);
                if (($code > 223 and $code < 256) or ($code == 179) or ($code == 180) or ($code == 186) or ($code == 191)) $cp1251++;
                if (($code > 191 and $code < 224) or ($code == 164) or ($code == 166) or ($code == 167) or ($code == 173)) $koi8u++;
            }
            if ($cp1251 > $koi8u) {
                $detect = 'windows-1251';
            } else {
                $detect = 'koi8-u';
            }
        }

        if ($encoding == $detect) {
            return $string;
        } else {
            return iconv($detect, $encoding, $string);
        }
    }
}

?>