--TEST--
Test with showing local variables on errors (< PHP 7.0)
--SKIPIF--
<?php if (!version_compare(phpversion(), "7.0", '<')) echo "skip < PHP 7.0 needed\n"; ?>
--INI--
xdebug.default_enable=1
xdebug.auto_trace=0
xdebug.collect_params=1
xdebug.auto_profile=0
xdebug.profiler_enable=0
xdebug.dump_globals=0
xdebug.show_local_vars=1
--FILE--
<?php
	function a($a,$b) {
		$c = array($a, $b * $b);
		$d = new stdClass;
		do_f();
	}

	a(5, 6);
?>
--EXPECTF--
Fatal error: Call to undefined function do_f() in /%s/local_vars_in_error-php5.php on line 5

Call Stack:
%w%f %w%d   1. {main}() /%s/local_vars_in_error-php5.php:0
%w%f %w%d   2. a(long, long) /%s/local_vars_in_error-php5.php:8


Variables in local scope (#2):
  $a = 5
  $b = 6
  $c = array (0 => 5, 1 => 36)
  $d = class stdClass {  }
