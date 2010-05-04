<?php
// once
class CallMyFunction {
    public function __call($method, $args) {
        return 0;
    }
}
// init
$c = new CallMyFunction();
// time
$c->please();
