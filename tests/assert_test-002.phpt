--TEST--
Test for assertion callbacks and description
--INI--
xdebug.enable=1
xdebug.auto_trace=0
xdebug.collect_params=3
xdebug.collect_return=0
xdebug.collect_assignments=0
xdebug.auto_profile=0
xdebug.profiler_enable=0
xdebug.show_mem_delta=0
xdebug.trace_format=0
--FILE--
<?php
$tf = xdebug_start_trace('/tmp/'. uniqid('xdt', TRUE));

// Active assert and make it quiet
assert_options (ASSERT_ACTIVE, 1);
assert_options (ASSERT_WARNING, 0);
assert_options (ASSERT_QUIET_EVAL, 1);

// Create a handler function
function my_assert_handler ($file, $line, $code, $desc) {
    echo "Assertion Failed:
        File '$file'
        Line '$line'
        Code '$code'
        Desc '$desc'";
}
// Set up the callback
assert_options (ASSERT_CALLBACK, 'my_assert_handler');

// Make an assertion that should fail
assert ('1==2', "One is not two");
echo "\n";
echo file_get_contents($tf);
unlink($tf);
?>
--EXPECTF--
Assertion Failed:
        File '/%s/assert_test-002.php'
        Line '21'
        Code '1==2'
        Desc 'One is not two'
TRACE START [%d-%d-%d %d:%d:%d]
%w%f %w%d     -> assert_options(1, 1) /%s/assert_test-002.php:5
%w%f %w%d     -> assert_options(4, 0) /%s/assert_test-002.php:6
%w%f %w%d     -> assert_options(5, 1) /%s/assert_test-002.php:7
%w%f %w%d     -> assert_options(2, 'my_assert_handler') /%s/assert_test-002.php:18
%w%f %w%d     -> assert('1==2', 'One is not two') /%s/assert_test-002.php:21
%w%f %w%d       -> %r({internal eval}\(\))|(assert\('1==2', 'One is not two'\))%r /%s/assert_test-002.php:21
%w%f %w%d       -> my_assert_handler('/%s/assert_test-002.php', 21, '1==2', 'One is not two') /%s/assert_test-002.php:21
%w%f %w%d     -> file_get_contents('/tmp/%s') /%s/assert_test-002.php:23
