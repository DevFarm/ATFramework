<?php

namespace classes\base;

class ATCore_Stem
{
    const ENCODING = 'UTF-8';
    const CHAR_LENGTH = 1;

    public static function russian($word)
    {
        $word = mb_strtolower($word, static::ENCODING);
        $a = static::rv($word);

        $start = $a[0];
        $rv = $a[1];
        $rv = static::step_1($rv);
        $rv = static::step_2($rv);
        $rv = static::step_3($rv);
        $rv = static::step_4($rv);

        return $start . $rv;
    }

    public static function rv($word)
    {
        $vowels = array('а', 'е', 'и', 'о', 'у', 'ы', 'э', 'ю', 'я');
        $flag = 0;
        $rv = '';
        $start = '';

        for ($i = 0; $i < mb_strlen($word, static::ENCODING); $i += static::CHAR_LENGTH) {
            if ($flag) {
                $rv .= mb_substr($word, $i, static::CHAR_LENGTH, static::ENCODING);
            } else {
                $start .= mb_substr($word, $i, static::CHAR_LENGTH, static::ENCODING);
            }

            if (array_search(mb_substr($word, $i, static::CHAR_LENGTH, static::ENCODING), $vowels) !== false) {
                $flag = 1;
            }
        }

        return array($start, $rv);
    }

    public static function step_1($word)
    {
        $perfective1 = array('в', 'вши', 'вшись');

        foreach ($perfective1 as $suffix) {
            if (mb_substr($word, -(strlen($suffix)), null, static::ENCODING) == $suffix && (mb_substr($word, -strlen($suffix) - static::CHAR_LENGTH, static::CHAR_LENGTH, static::ENCODING) == 'а' || mb_substr($word, -strlen($suffix) - static::CHAR_LENGTH, static::CHAR_LENGTH, static::ENCODING) == 'я')) {
                return mb_substr($word, 0, mb_strlen($word, static::ENCODING) - mb_strlen($suffix, static::ENCODING), static::ENCODING);
            }
        }

        $perfective2 = array('ив', 'ивши', 'ившись', 'ывши', 'ывшись');

        foreach ($perfective2 as $suffix) {
            if (mb_substr($word, -(mb_strlen($suffix, static::ENCODING)), null, static::ENCODING) == $suffix) {
                return mb_substr($word, 0, mb_strlen($word, static::ENCODING) - mb_strlen($suffix, static::ENCODING));
            }
        }

        $reflexive = array('ся', 'сь');

        foreach ($reflexive as $suffix) {
            if (mb_substr($word, -(mb_strlen($suffix, static::ENCODING)), null, static::ENCODING) == $suffix) {
                $word = mb_substr($word, 0, mb_strlen($word, static::ENCODING) - mb_strlen($suffix, static::ENCODING), static::ENCODING);
            }
        }

        $adjective = array(
            'ее', 'ие', 'ые', 'ое', 'ими', 'ыми', 'ей', 'ий', 'ый', 'ой', 'ем', 'им', 'ым', 'ом', 'его', 'ого', 'ему',
            'ому', 'их', 'ых', 'ую', 'юю', 'ая', 'яя', 'ою', 'ею'
        );

        $participle2 = array('ем', 'нн', 'вш', 'ющ', 'щ');
        $participle1 = array('ивш', 'ывш', 'ующ');

        foreach ($adjective as $suffix) {
            if (mb_substr($word, -(mb_strlen($suffix, static::ENCODING)), null, static::ENCODING) == $suffix) {
                $word = mb_substr($word, 0, mb_strlen($word, static::ENCODING) - mb_strlen($suffix, static::ENCODING), static::ENCODING);
                foreach ($participle1 as $suffix) {
                    if (mb_substr($word, -(mb_strlen($suffix, static::ENCODING)), null, static::ENCODING) == $suffix &&
                        (mb_substr($word, -mb_strlen($suffix, static::ENCODING) - static::CHAR_LENGTH, static::CHAR_LENGTH, static::ENCODING) == 'а' ||
                            mb_substr($word, -mb_strlen($suffix, static::ENCODING) - static::CHAR_LENGTH, static::CHAR_LENGTH, static::ENCODING) == 'я')
                    ) {
                        $word = mb_substr($word, 0, mb_strlen($word, static::ENCODING) - mb_strlen($suffix, static::ENCODING), static::ENCODING);
                    }
                }
                foreach ($participle2 as $suffix) {
                    if (mb_substr($word, -(mb_strlen($suffix, static::ENCODING)), null, static::ENCODING) == $suffix) {
                        $word = mb_substr($word, 0, mb_strlen($word, static::ENCODING) - mb_strlen($suffix, static::ENCODING), static::ENCODING);
                    }
                }

                return $word;
            }
        }

        $verb1 = array(
            'ла', 'на', 'ете', 'йте', 'ли', 'й', 'л', 'ем', 'н', 'ло', 'но', 'ет', 'ют', 'ны', 'ть', 'ешь', 'нно'
        );

        foreach ($verb1 as $suffix) {
            if (mb_substr($word, -(mb_strlen($suffix, static::ENCODING)), null, static::ENCODING) == $suffix &&
                (mb_substr($word, -mb_strlen($suffix, static::ENCODING) - static::CHAR_LENGTH, static::CHAR_LENGTH, static::ENCODING) == 'а' ||
                    mb_substr($word, -mb_strlen($suffix, static::ENCODING) - static::CHAR_LENGTH, static::CHAR_LENGTH, static::ENCODING) == 'я')
            ) {
                return mb_substr($word, 0, mb_strlen($word, static::ENCODING) - mb_strlen($suffix, static::ENCODING), static::ENCODING);
            }
        }

        $verb2 = array(
            'ила', 'ыла', 'ена', 'ейте', 'уйте', 'ите', 'или', 'ыли', 'ей', 'уй', 'ил', 'ыл', 'им', 'ым', 'ен', 'ило',
            'ыло', 'ено', 'ят', 'ует', 'уют', 'ит', 'ыт', 'ены', 'ить', 'ыть', 'ишь', 'ую', 'ю'
        );
        foreach ($verb2 as $suffix) {
            if (mb_substr($word, -(mb_strlen($suffix, static::ENCODING)), null, static::ENCODING) == $suffix) {
                return mb_substr($word, 0, mb_strlen($word, static::ENCODING) - mb_strlen($suffix, static::ENCODING), static::ENCODING);
            }
        }

        $noun = array(
            'а', 'ев', 'ов', 'ие', 'ье', 'е', 'иями', 'ями', 'ами', 'еи', 'ии', 'и', 'ией', 'ей', 'ой', 'ий', 'й',
            'иям', 'ям', 'ием', 'ем', 'ам', 'ом', 'о', 'у', 'ах', 'иях', 'ях', 'ы', 'ь', 'ию', 'ью', 'ю', 'ия', 'ья',
            'я'
        );
        foreach ($noun as $suffix) {
            if (mb_substr($word, -(mb_strlen($suffix, static::ENCODING)), null, static::ENCODING) == $suffix) {
                return mb_substr($word, 0, mb_strlen($word, static::ENCODING) - mb_strlen($suffix, static::ENCODING), static::ENCODING);
            }
        }

        return $word;
    }

    public static function step_2($word)
    {
        if (mb_substr($word, -static::CHAR_LENGTH, static::CHAR_LENGTH, static::ENCODING) == 'и') {
            $word = mb_substr($word, 0, mb_strlen($word, static::ENCODING) - static::CHAR_LENGTH, static::ENCODING);
        }

        return $word;
    }

    public static function step_3($word)
    {
        $vowels = array('а', 'е', 'и', 'о', 'у', 'ы', 'э', 'ю', 'я');
        $flag = 0;
        $r1 = '';
        $r2 = '';

        for ($i = 0; $i < mb_strlen($word, static::ENCODING); $i += static::CHAR_LENGTH) {
            if ($flag == 2) {
                $r1 .= mb_substr($word, $i, static::CHAR_LENGTH, static::ENCODING);
            }
            if (array_search(mb_substr($word, $i, static::CHAR_LENGTH, static::ENCODING), $vowels) !== false) {
                $flag = 1;
            }
            if ($flag = 1 && array_search(mb_substr($word, $i, static::CHAR_LENGTH, static::ENCODING), $vowels) === false) {
                $flag = 2;
            }
        }

        $flag = 0;

        for ($i = 0; $i < mb_strlen($r1, static::ENCODING); $i += static::CHAR_LENGTH) {
            if ($flag == 2) {
                $r2 .= mb_substr($r1, $i, static::CHAR_LENGTH, static::ENCODING);
            }
            if (array_search(mb_substr($r1, $i, static::CHAR_LENGTH, static::ENCODING), $vowels) !== false) {
                $flag = 1;
            }
            if ($flag = 1 && array_search(mb_substr($r1, $i, static::CHAR_LENGTH, static::ENCODING), $vowels) === false) {
                $flag = 2;
            }
        }

        $derivational = array('ост', 'ость');

        foreach ($derivational as $suffix) {
            if (mb_substr($r2, -(mb_strlen($suffix, static::ENCODING)), null, static::ENCODING) == $suffix) {
                $word = mb_substr($word, 0, mb_strlen($r2, static::ENCODING) - mb_strlen($suffix, static::ENCODING), static::ENCODING);
            }
        }

        return $word;
    }

    public static function step_4($word)
    {
        if (mb_substr($word, -static::CHAR_LENGTH * 2, null, static::ENCODING) == 'нн') {
            $word = mb_substr($word, 0, mb_strlen($word, static::ENCODING) - static::CHAR_LENGTH, static::ENCODING);
        } else {
            $superlative = array('ейш', 'ейше');
            foreach ($superlative as $suffix) {
                if (mb_substr($word, -(mb_strlen($suffix, static::ENCODING)), null, static::ENCODING) == $suffix) {
                    $word = mb_substr($word, 0, mb_strlen($word, static::ENCODING) - mb_strlen($suffix, static::ENCODING), static::ENCODING);
                }
            }
            if (mb_substr($word, -static::CHAR_LENGTH * 2, null, static::ENCODING) == 'нн') {
                $word = mb_substr($word, 0, mb_strlen($word, static::ENCODING) - static::CHAR_LENGTH, static::ENCODING);
            }
        }

        if (mb_substr($word, -static::CHAR_LENGTH, static::CHAR_LENGTH, static::ENCODING) == 'ь') {
            $word = mb_substr($word, 0, mb_strlen($word, static::ENCODING) - static::CHAR_LENGTH, static::ENCODING);
        }

        return $word;
    }
}

?>