<?php
// once
/** 
 * Set or retrieve an object
 *
 * @param $key string entry key to set or get
 * @param $instance optional object. If provided, set the entry
 *  for $key to this object. If not provided, return the entry
 *  for $key. If null, clear the entry for $key.
 *
 * @return object|null
 */
function Registry($key, $instance = -1) {
    if ($instance === -1) {
        return Registry::get($key);
    } else {
        return Registry::set($key, $instance);
    }
}

class Registry {
    /** The map of keys to objects */
    protected static $registry = array();

    /**
     * Retrieve an object, or null if there is no object with
     * the provided key.
     *
     * @param $key string
     * @return object|null
     */
    public static function get($key) {
        return isset(self::$registry[$key]) ? self::$registry[$key] : null;
    }

    /**
     * Put an object, or clear an entry
     *
     * @param $key string
     * @param $instance object|null. If an object, put that object
     *   into the with key $key. If null, clear the entry at key
     *   $key.
     *
     * @return null
     */
    public static function set($key, $instance) {
        if ((! is_object($instance)) && (! is_null($instance))) {
            throw new Exception("Expected object for instance of $key");
        }
        self::$registry[$key] = $instance;
    }
    /**
     * Get all entries
     * @return array
     */
    public static function getAll() {
        return self::$registry;
    }

    /**
     * Overwrite all entries in the with the provided array
     * of keys => objects
     *
     * @param $registry array
     */
    public static function setAll($registry) {
        self::$registry = $registry;
    }
}

class Helper {
    public function help() {
    }
}
// init
Registry::setAll(array()); /* clear */
Registry('helper', new Helper);
// time
for ($j = 0; $j < $___opts['m']; $j++) {
    Registry('helper')->help();
}