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
 * @version  SVN: $Id: PhptalIdTest.php 596 2009-05-02 18:05:35Z kornel $
 * @link     http://phptal.org/
 */

require_once dirname(__FILE__)."/config.php";

PHPTAL::setIncludePath();
require_once 'PHPTAL/Trigger.php';
PHPTAL::restoreIncludePath();

class MyTrigger implements PHPTAL_Trigger
{
    public $useCache = false;
    private $_cache  = null;

    public function start($id, $tpl)
    {
        if ($this->_cache !== null) {
            $this->useCache = true;
            return PHPTAL_Trigger::SKIPTAG;
        }

        $this->useCache = false;
        ob_start();
        return PHPTAL_Trigger::PROCEED;
    }

    public function end($id, $tpl)
    {
        if ($this->_cache === null) {
            $this->_cache = ob_get_contents();
            ob_end_clean();
        }
        echo $this->_cache;
    }
}

class PhptalIdTest extends PHPTAL_TestCase
{
    function test01()
    {
        $trigger = new MyTrigger();

        $exp = trim_file('output/phptal.id.01.html');
        $tpl = $this->newPHPTAL('input/phptal.id.01.html');
        $tpl->addTrigger('myTable', $trigger);
        $tpl->result = range(0, 3);

        $res = $tpl->execute();
        $res = trim_string($res);

        $this->assertEquals($exp, $res);
        $this->assertEquals(false, $trigger->useCache);

        $res = $tpl->execute();
        $res = trim_string($res);

        $this->assertEquals($exp, $res);
        $this->assertEquals(true, $trigger->useCache);
    }
}


