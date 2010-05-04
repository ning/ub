--TEST--
Check for clock_gettime() function
--SKIPIF--
<?php if (!extension_loaded("clock")) print "skip"; ?>
--FILE--
<?php 
$a = clock_gettime(0);
print is_array($a) ? 1 : 0;
?>
--EXPECT--
1
