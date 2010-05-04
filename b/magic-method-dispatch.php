<?php
// once
class CallMyFunction {
    public function __call($method, $args) {
        if ($method == 'please') {
            return call_user_func_array(array($this, 'doPlease'), $args);
        }
        return 0;
    }

    protected function doPlease() {
        return 0;
    }

}
// init
$c = new CallMyFunction();
// time
$c->please();
// ignore
print "c->please = " . $c->please() . "\n";