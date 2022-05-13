<?php

if (!function_exists('base64UrlEncode')) {
     /**
     * @param string $string
     * 
     * @return string
     */
    function base64UrlEncode(string $string)
    {
        // return str_replace('=', '', strtr(base64_encode($hash), '+/', '-_'));

        return strtr(
            base64_encode($string),
            [
                '=' => '',
                '+' => '-',
                '/' => '_',
            ]
        );
    }
}