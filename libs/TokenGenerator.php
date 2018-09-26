<?php

/**
 * 

 */
debug_backtrace() || die ("Direct access not permitted");

class TokenGenerator 
{
    function __construct()
    {

    }
    public static function generate($length){
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));
        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }
        return $key;
    }
}