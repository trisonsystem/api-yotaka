<?php
//3DES util class
class TripleDES {
    private static function pkcs5Pad($text, $blocksize) {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    private static function pkcs5Unpad($text) {
        $pad = ord($text{strlen($text)-1});
        if ($pad > strlen($text)) return false;
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) return false;
        return substr($text, 0, -1 * $pad);
    }

    public static function encryptText($plain_text, $key) {
        $padded = TripleDES::pkcs5Pad($plain_text, mcrypt_get_block_size(MCRYPT_TRIPLEDES, MCRYPT_MODE_CBC));
        return mcrypt_encrypt(MCRYPT_TRIPLEDES, base64_decode($key), $padded, MCRYPT_MODE_CBC, base64_decode("AAAAAAAAAAA="));
    }

    public static function decryptText($cipher_text, $key) {
        $plain_text = mcrypt_decrypt(MCRYPT_TRIPLEDES, base64_decode($key), $cipher_text, MCRYPT_MODE_CBC, base64_decode("AAAAAAAAAAA="));
        return TripleDES::pkcs5Unpad($plain_text);
    }
};

