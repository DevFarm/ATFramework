<?php

namespace classes\base;

class ATCore_Crypt
{
    const AES_KEY = '\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0';

    public static function aes_decrypt($val, $key)
    {
        if (!function_exists('mcrypt_decrypt')) return $val;

        $private_key = static::AES_KEY;

        for ($a = 0; $a < strlen($key); $a++) {
            $private_key[$a % 16] = chr(ord($private_key[$a % 16]) ^ ord($key[$a]));
        }

        $mode = MCRYPT_MODE_ECB;
        $cipher = MCRYPT_RIJNDAEL_128;
        $init_vector = mcrypt_create_iv(mcrypt_get_iv_size($cipher, $mode), MCRYPT_DEV_URANDOM);
        $decrypt = mcrypt_decrypt($cipher, $private_key, $val, $mode, $init_vector);

        return rtrim($decrypt, ((ord(substr($decrypt, strlen($decrypt) - 1, 1)) >= 0 and ord(substr($decrypt, strlen($decrypt) - 1, 1)) <= 16) ? chr(ord(substr($decrypt, strlen($decrypt) - 1, 1))) : null));
    }

    public static function aes_encrypt($val, $key)
    {
        if (!function_exists('mcrypt_encrypt')) return $val;

        $private_key = static::AES_KEY;

        for ($a = 0; $a < strlen($key); $a++) {
            $private_key[$a % 16] = chr(ord($private_key[$a % 16]) ^ ord($key[$a]));
        }

        $mode = MCRYPT_MODE_ECB;
        $cipher = MCRYPT_RIJNDAEL_128;
        $value = str_pad($val, (16 * (floor(strlen($val) / 16) + (strlen($val) % 16 == 0 ? 2 : 1))), chr(16 - (strlen($val) % 16)));
        $init_vector = mcrypt_create_iv(mcrypt_get_iv_size($cipher, $mode), MCRYPT_DEV_URANDOM);

        return mcrypt_encrypt($cipher, $private_key, $value, $mode, $init_vector);
    }

    public static function rc4($key, $data)
    {
        // Store the vectors "S" has calculated
        static $SC;
        // Function to swaps values of the vector "S"
        $swap = create_function('&$v1, &$v2', '
			$v1 = $v1 ^ $v2;
			$v2 = $v1 ^ $v2;
			$v1 = $v1 ^ $v2;
		');
        $ikey = crc32($key);
        if (!isset($SC[$ikey])) {
            // Make the vector "S", basead in the key
            $S = range(0, 255);
            $j = 0;
            $n = strlen($key);

            for ($i = 0; $i < 255; $i++) {
                $char = ord($key{$i % $n});
                $j = ($j + $S[$i] + $char) % 256;
                $swap($S[$i], $S[$j]);
            }

            $SC[$ikey] = $S;
        } else {
            $S = $SC[$ikey];
        }

        // Crypt/decrypt the data
        $n = strlen($data);
        $data = str_split($data, 1);
        $i = $j = 0;

        for ($m = 0; $m < $n; $m++) {
            $i = ($i + 1) % 256;
            $j = ($j + $S[$i]) % 256;
            $swap($S[$i], $S[$j]);
            $char = ord($data[$m]);
            $char = $S[($S[$i] + $S[$j]) % 256] ^ $char;
            $data[$m] = chr($char);
        }

        return implode('', $data);
    }

    public static function lm_hash($string)
    {
        $string = strtoupper(substr($string, 0, 14));

        $p1 = static::lm_hash_des_encrypt(substr($string, 0, 7));
        $p2 = static::lm_hash_des_encrypt(substr($string, 7, 7));

        return strtoupper($p1 . $p2);
    }

    public static function lm_hash_des_encrypt($string)
    {
        $key = array();
        $tmp = array();
        $len = strlen($string);

        for ($i = 0; $i < 7; ++$i) {
            $tmp[] = $i < $len ? ord($string[$i]) : 0;
        }

        $key[] = $tmp[0] & 254;
        $key[] = ($tmp[0] << 7) | ($tmp[1] >> 1);
        $key[] = ($tmp[1] << 6) | ($tmp[2] >> 2);
        $key[] = ($tmp[2] << 5) | ($tmp[3] >> 3);
        $key[] = ($tmp[3] << 4) | ($tmp[4] >> 4);
        $key[] = ($tmp[4] << 3) | ($tmp[5] >> 5);
        $key[] = ($tmp[5] << 2) | ($tmp[6] >> 6);
        $key[] = $tmp[6] << 1;

        $is = mcrypt_get_iv_size(MCRYPT_DES, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($is, MCRYPT_RAND);
        $key0 = "";

        foreach ($key as $k) {
            $key0 .= chr($k);
        }

        $crypt = mcrypt_encrypt(MCRYPT_DES, $key0, "KGS!@#$%", MCRYPT_MODE_ECB, $iv);

        return bin2hex($crypt);
    }
}