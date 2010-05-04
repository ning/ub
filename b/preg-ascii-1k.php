<?php
// once
function gen_string($len) {
    $s = '';
    while ($len--) {
        $s .= chr(mt_rand(33,126));
    }
    return $s;
}
$s = gen_string(1024);
// time
preg_match('/<[^>]+?>/', $s);
