<?php
    /**
     *	base include file for SimpleTest
     *	@package	SimpleTest
     *	@subpackage	UnitTester
     *	@version	$Id: runner.php 457 2004-02-08 08:38:19Z jsweat $
     */
    
    /**
     *    Can recieve test events and display them. Display
     *    is achieved by making display methods available
     *    and visiting the incoming event.
	 *	  @package SimpleTest
	 *	  @subpackage UnitTester
     *    @abstract
     */
    class SimpleRunner {
        var $_passes;
        var $_fails;
        var $_exceptions;
        var $_is_dry_run;
        
        /**
         *    Starts the test run with no results.
         *    @access public
         */
        function SimpleRunner() {
            $this->_passes = 0;
            $this->_fails = 0;
            $this->_exceptions = 0;
            $this->_is_dry_run = false;
        }
        
        /**
         *    Signals that the next evaluation will be a dry
         *    run. That is, the structure events will be
         *    recorded, but no tests will be run.
         */
        function makeDry($is_dry = true) {
            $this->_is_dry_run = $is_dry;
        }
        
        /**
         *    Invokes a single test method on the test case.
         *    This call back allows the reporter to decide if
         *    it actually wants to run the test.
         *    @param SimpleTestCase $test_case    Test case to run test on.
         *    @param string $method               Name of test method.
         *    @access public
         */
        function invoke(&$test_case, $method) {
            if (! $this->_is_dry_run) {
                $test_case->invoke($method);
            }
        }

        /**
         *    Accessor for current status. Will be false
         *    if there have been any failures or exceptions.
         *    Used for command line tools.
         *    @return boolean        True if no failures.
         *    @access public
         */
        function getStatus() {
            if ($this->_exceptions + $this->_fails > 0) {
                return false;
            }
            return true;
        }
        
        /**
         *    Paints the start of a test method.
         *    @param string $test_name     Name of test or other label.
         *    @access public
         */
        function paintMethodStart($test_name) {
        }
        
        /**
         *    Paints the end of a test method.
         *    @param string $test_name     Name of test or other label.
         *    @access public
         */
        function paintMethodEnd($test_name) {
        }
         
        /**
         *    Paints the start of a test case.
         *    @param string $test_name     Name of test or other label.
         *    @access public
         */
        function paintCaseStart($test_name) {
        }
        
        /**
         *    Paints the end of a test case.
         *    @param string $test_name     Name of test or other label.
         *    @access public
         */
        function paintCaseEnd($test_name) {
        }
       
        /**
         *    Paints the start of a group test.
         *    @param string $test_name     Name of test or other label.
         *    @param integer $size         Number of test cases starting.
         *    @access public
         */
        function paintGroupStart($test_name, $size) {
        }
        
        /**
         *    Paints the end of a group test.
         *    @param string $test_name     Name of test or other label.
         *    @access public
         */
        function paintGroupEnd($test_name) {
        }
        
        /**
         *    Increments the pass count.
         *    @param string $message        Message is ignored.
         *    @access public
         */
        function paintPass($message) {
            $this->_passes++;
        }
        
        /**
         *    Increments the fail count.
         *    @param string $message        Message is ignored.
         *    @access public
         */
        function paintFail($message) {
            $this->_fails++;
        }
        
        /**
         *    Deals with PHP 4 throwing an error.
         *    @param string $message    Text of error formatted by
         *                              the test case.
         *    @access public
         */
        function paintError($message) {
            $this->paintException($message);
        }
        
        /**
         *    Deals with PHP 5 throwing an exception
         *    This isn't really implemented yet.
         *    @param Exception $exception     Object thrown.
         *    @access public
         */
        function paintException($exception) {
            $this->_exceptions++;
        }
        
        /**
         *    Accessor for the number of passes so far.
         *    @return integer       Number of passes.
         *    @access public
         */
        function getPassCount() {
            return $this->_passes;
        }
        
        /**
         *    Accessor for the number of fails so far.
         *    @return integer       Number of fails.
         *    @access public
         */
        function getFailCount() {
            return $this->_fails;
        }
        
        /**
         *    Accessor for the number of untrapped errors
         *    so far.
         *    @return integer       Number of exceptions.
         *    @access public
         */
        function getExceptionCount() {
            return $this->_exceptions;
        }
        
        /**
         *    Paints a simple supplementary message.
         *    @param string $message        Text to display.
         *    @access public
         */
        function paintMessage($message) {
        }
        
        /**
         *    Paints a formatted ASCII message such as a
         *    variable dump.
         *    @param string $message        Text to display.
         *    @access public
         */
        function paintFormattedMessage($message) {
        }
        
        /**
         *    By default just ignores user generated events.
         *    @param string $type        Event type as text.
         *    @param mixed $payload      Message or object.
         *    @access public
         */
        function paintSignal($type, &$payload) {
        }
    }
    
    /**
     *    Modifies the test running behaviour of the standard
     *    runner by wrapping it. This is a do nothing version.
     *    Subclass this for soak testers and statistical
     *    testers.
	 *	  @package SimpleTest
	 *	  @subpackage UnitTester
     */
    class SimpleRunnerDecorator {
        var $_runner;
        
        /**
         *    Takes in the reporter to wrap.
         *    @param SimpleRunner $runner
         */
        function SimpleRunnerDecorator(&$runner) {
            $this->_runner = &$runner;
        }
        
        /**
         *    Runs the method once on the test case.
         *    @param SimpleTest $test_case    Test case to run test on.
         *    @param string $method           Name of test method.
         *    @access public
         */
        function invoke(&$test_case, $method) {
            $test_case->invoke($method);
        }
        
        /**
         *    Chains to the wrapped runner.
         *    @param string $message        Message is ignored.
         *    @access public
         */
        function paintPass($message) {
            $this->_runner->paintPass($message);
        }
        
        /**
         *    Chains to the wrapped runner.
         *    @param string $message        Message is ignored.
         *    @access public
         */
        function paintFail($message) {
            $this->_runner->paintFail($message);
        }
        
        /**
         *    Chains to the wrapped runner.
         *    @param string $message    Text of error formatted by
         *                              the test case.
         *    @access public
         */
        function paintError($message) {
            $this->_runner->paintError($message);
        }
        
        /**
         *    Chains to the wrapped runner.
         *    @param Exception $exception     Object thrown.
         *    @access public
         */
        function paintException($exception) {
            $this->_runner->paintException($exception);
        }
        
        /**
         *    Chains to the wrapped runner.
         *    @param string $message        Text to display.
         *    @access public
         */
        function paintMessage($message) {
            $this->_runner->paintMessage($message);
        }
        
        /**
         *    Chains to the wrapped runner.
         *    @param string $message        Text to display.
         *    @access public
         */
        function paintFormattedMessage($message) {
            $this->_runner->paintFormattedMessage($message);
        }
        
        /**
         *    Chains to the wrapped runner.
         *    @param string $type        Event type as text.
         *    @param mixed $payload      Message or object.
         *    @return boolean            Should return false if this
         *                               type of signal should fail the
         *                               test suite.
         *    @access public
         */
        function paintSignal($type, &$payload) {
            $this->_runner->paintSignal($type, $payload);
        }
    }
    
    /**
     *    Recipient of generated test messages that can display
     *    page footers and headers. Also keeps track of the
     *    test nesting. This is the main base class on which
     *    to build the finished test (page based) displays.
	 *	  @package SimpleTest
	 *	  @subpackage UnitTester
     */
    class SimpleReporter extends SimpleRunner {
        var $_test_stack;
        var $_size;
        var $_progress;
        
        /**
         *    Starts the display with no results in.
         *    @access public
         */
        function SimpleReporter() {
            $this->SimpleRunner();
            $this->_test_stack = array();
            $this->_size = null;
            $this->_progress = 0;
        }
        
        /**
         *    Paints the start of a group test. Will also paint
         *    the page header and footer if this is the
         *    first test. Will stash the size if the first
         *    start.
         *    @param string $test_name   Name of test that is starting.
         *    @param integer $size       Number of test cases starting.
         *    @access public
         */
        function paintGroupStart($test_name, $size) {
            if (!isset($this->_size)) {
                $this->_size = $size;
            }
            if (count($this->_test_stack) == 0) {
                $this->paintHeader($test_name);
            }
            $this->_test_stack[] = $test_name;
        }
        
        /**
         *    Paints the end of a group test. Will paint the page
         *    footer if the stack of tests has unwound.
         *    @param string $test_name   Name of test that is ending.
         *    @param integer $progress   Number of test cases ending.
         *    @access public
         */
        function paintGroupEnd($test_name) {
            array_pop($this->_test_stack);
            if (count($this->_test_stack) == 0) {
                $this->paintFooter($test_name);
            }
        }
        
        /**
         *    Paints the start of a test case. Will also paint
         *    the page header and footer if this is the
         *    first test. Will stash the size if the first
         *    start.
         *    @param string $test_name   Name of test that is starting.
         *    @access public
         */
        function paintCaseStart($test_name) {
            if (! isset($this->_size)) {
                $this->_size = 1;
            }
            if (count($this->_test_stack) == 0) {
                $this->paintHeader($test_name);
            }
            $this->_test_stack[] = $test_name;
        }
        
        /**
         *    Paints the end of a test case. Will paint the page
         *    footer if the stack of tests has unwound.
         *    @param string $test_name   Name of test that is ending.
         *    @access public
         */
        function paintCaseEnd($test_name) {
            $this->_progress++;
            array_pop($this->_test_stack);
            if (count($this->_test_stack) == 0) {
                $this->paintFooter($test_name);
            }
        }
        
        /**
         *    Paints the start of a test method.
         *    @param string $test_name   Name of test that is starting.
         *    @access public
         */
        function paintMethodStart($test_name) {
            $this->_test_stack[] = $test_name;
        }
        
        /**
         *    Paints the end of a test method. Will paint the page
         *    footer if the stack of tests has unwound.
         *    @param string $test_name   Name of test that is ending.
         *    @access public
         */
        function paintMethodEnd($test_name) {
            array_pop($this->_test_stack);
        }
        
        /**
         *    Paints the test document header.
         *    @param string $test_name     First test top level
         *                                 to start.
         *    @access public
         *    @abstract
         */
        function paintHeader($test_name) {
        }
        
        /**
         *    Paints the test document footer.
         *    @param string $test_name        The top level test.
         *    @access public
         *    @abstract
         */
        function paintFooter($test_name) {
        }
        
        /**
         *    Accessor for internal test stack. For
         *    subclasses that need to see the whole test
         *    history for display purposes.
         *    @return array     List of methods in nesting order.
         *    @access public
         */
        function getTestList() {
            return $this->_test_stack;
        }
        
        /**
         *    Accessor for total test size in number
         *    of test cases. Null until the first
         *    test is started.
         *    @return integer   Total number of cases at start.
         *    @access public
         */
        function getTestCaseCount() {
            return $this->_size;
        }
        
        /**
         *    Accessor for the number of test cases
         *    completed so far.
         *    @return integer   Number of ended cases.
         *    @access public
         */
        function getTestCaseProgress() {
            return $this->_progress;
        }
        
        /**
         *    Static check for running in the comand line.
         *    @return boolean        True if CLI.
         *    @access public
         *    @static
         */
        function inCli() {
            return php_sapi_name() == 'cli';
        }
    }
    
    /**
     *    @deprecated
     *    @ignore
     *    @package      SimpleTest
     *    @subpackage   UnitTester
     */
    class TestDisplay extends SimpleReporter {
        /**
         *    @deprecated
         */
        function TestDisplay() {
            $this->SimpleReporter();
        }
    }
?>