<?php

include __DIR__ . '/../ub.php';

assert(parse_opts('ab',array()) === array('_' => array(), 'a' => false, 'b' => false));
assert(parse_opts('a:b',array()) === array('_' => array(), 'a' => null, 'b' => false));
assert(parse_opts('a:b',array('-a','alice')) === array('_' => array(), 'a' => 'alice', 'b' => false));
assert(parse_opts('a:b',array('-b','-a','alice')) === array('_' => array(), 'a' => 'alice', 'b' => true));
assert(parse_opts('a:b',array('-b','-a','-c','foo')) === array('_' => array('-c','foo'), 'a' => null, 'b'=>true));
assert(parse_opts('a:',array('-a','alice','bob')) === array('_' => array('bob'), 'a' =>'alice'));
assert(parse_opts('a:',array('-a','alice','-a','bob')) === array('_' => array(), 'a' => array('alice','bob')));
