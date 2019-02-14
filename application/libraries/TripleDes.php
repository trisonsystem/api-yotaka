<?php
//3DES util class
class TripleDES {

    private static function des_crypt( $string, $action = 'e',$sckey) {

        $secret_key = $sckey;
        $secret_iv  = $sckey;

        $output = false;

        $encrypt_method = "AES-256-CBC";

        $key = hash( 'sha256', $secret_key );

        $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );

        if( $action == 'e' ) {

            $output = base64_encode(openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ));

        }else if( $action == 'd' ){
            $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );

        }

        // debug($output);

        return $output;
    }

    public static function encryptText($string,$key){

     $salting = substr(md5(microtime()),-1) . $string;

     return TripleDES::des_crypt( $salting, 'e' ,$key);

    }

    public static function decryptText($string,$key){

     $encode = TripleDES::des_crypt( $string, 'd' ,$key);

     return substr($encode, 1);

    }
}

