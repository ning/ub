<?php
// once
class Library {
    function doSomething($arg) {
        $a = 1 + 2;
        $a *= $arg;
        return $a;
    }    
}

class Component {
    private $libs = array();
    
    public function lib($lib) {
        if (! isset($this->libs[$lib])) {
            $this->libs[$lib] = new Library;
            $this->libs[$lib]->name = "library=$lib";
        }
        return $this->libs[$lib];
    }
    
    public function clearLibs() {
        $this->libs = array();
    }
}

class Controller {

    public function __construct() {
        $this->C = new Component();
    }

    public function withLocal() {
        $lib = $this->C->lib('photo');
        $value1 = $lib->doSomething(12);
        $value2 = $lib->doSomething(24);
    }

    public function withCalls() {
        $value1 = $this->C->lib('photo')->doSomething(12);
        $value2 = $this->C->lib('photo')->doSomething(24);
    }
}

$c = new Controller;

// init
$c->C->clearLibs();
// time
$c->withLocal();
