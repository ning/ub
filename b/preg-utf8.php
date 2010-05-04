<?php
// init
$s = "this is some text to match against. it's not short but not incredibly long";
// time
preg_match('/<[^>]+?>/u', $s);
