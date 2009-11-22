<?php
/**
 * PHPTAL templating engine
 *
 * PHP Version 5
 *
 * @category HTML
 * @package  PHPTAL
 * @author   Laurent Bedubourg <lbedubourg@motion-twin.com>
 * @author   Kornel Lesiński <kornel@aardvarkmedia.co.uk>
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @version  SVN: $Id: config.php 714 2009-09-12 00:26:42Z kornel $
 * @link     http://phptal.org/
 */


// This is needed to run tests ran individually without run-tests.php script
if (!class_exists('PHPTAL'))
{
    ob_start();
    
    // try local copy of PHPTAL first, otherwise it might be testing
    // PEAR version (or another in include path) causing serious WTF!?s.
    if (file_exists(dirname(__FILE__).'/../classes/PHPTAL.php')) {
        require_once dirname(__FILE__).'/../classes/PHPTAL.php';        
    } elseif (file_exists(dirname(__FILE__).'/../PHPTAL.php')) {
        require_once dirname(__FILE__).'/../PHPTAL.php';        
    } else {
        require_once "PHPTAL.php";
    }
    $out = ob_get_clean();
    if (strlen($out)) {
        throw new Exception("Inclusion of PHPTAL causes output: '$out'");
    }
}

abstract class PHPTAL_TestCase extends PHPUnit_Framework_TestCase
{
    private $cwd_backup;
    function setUp()
    {        
        // tests rely on cwd being in tests/
        $this->cwd_backup = getcwd();
        chdir(dirname(__FILE__));
        
        parent::setUp();
    }
    
    function tearDown()
    {
        chdir($this->cwd_backup);
    }

    /**
     * backupGlobals is the worst idea ever.
     */
    protected $backupGlobals = FALSE;

    protected function newPHPTAL($tpl = false)
    {
        $p = new PHPTAL($tpl);
        $p->setForceReparse(true);
        return $p;
    }    
}

if (function_exists('date_default_timezone_set')) {
    date_default_timezone_set(@date_default_timezone_get());
}

function trim_file( $src ){
    return trim_string( join('', file($src) ) );
}

function trim_string( $src ){
    $src = trim($src);
    $src = preg_replace('/\s+/usm', ' ', $src);
    $src = str_replace('\n', ' ', $src);
    $src = preg_replace('/(?<!]])&gt;/', '>', $src); // > may or may not be escaped, except ]]>
    $src = str_replace('> ', '>', $src);
    $src = str_replace(' <', '<', $src);
    $src = str_replace(' />', '/>', $src);
    return $src;
}

// Old versions of PHPUnit seemed to need it
// OTOH PHP5-incompatible PEAR throws lots of errors, causing Phing to fail.

// function exception_error_handler($errno, $errstr, $errfile, $errline )
// {
//     throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
// }
// set_error_handler("exception_error_handler", E_ALL | E_STRICT);


