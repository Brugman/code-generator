<?php

$config = [
    'number'     => 1000,
    'length'     => 4,
    'chars'      => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
    // 'chars'      => '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ',
    // 'chars'      => '0123456789',
    // 'chars'      => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
    'prefix'     => '',
    'postfix'    => '',
    'unique'     => true,
    'autel'      => false, // autel: also unique to existing list
    'autel_file' => 'existing-codes.json', // txt or json file
];

