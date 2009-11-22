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
 * @version  SVN: $Id: TalConditionTest.php 611 2009-05-24 00:40:00Z kornel $
 * @link     http://phptal.org/
 */

require_once dirname(__FILE__)."/config.php";

PHPTAL::setIncludePath();
require_once 'PHPTAL/Dom/DocumentBuilder.php';
PHPTAL::restoreIncludePath();

if (!class_exists('DummyTag',false)) {
    class DummyTag {}
}

class TalConditionTest extends PHPTAL_TestCase
{
    function testSimple()
    {
        $tpl = $this->newPHPTAL('input/tal-condition.01.html');
        $res = trim_string($tpl->execute());
        $exp = trim_file('output/tal-condition.01.html');
        $this->assertEquals($exp, $res);
    }

    function testNot()
    {
        $tpl = $this->newPHPTAL('input/tal-condition.02.html');
        $res = trim_string($tpl->execute());
        $exp = trim_file('output/tal-condition.02.html');
        $this->assertEquals($exp, $res);
    }

    function testExists()
    {
        $tpl = $this->newPHPTAL('input/tal-condition.03.html');
        $tpl->somevar = true;
        $res = trim_string($tpl->execute());
        $exp = trim_file('output/tal-condition.03.html');
        $this->assertEquals($exp, $res);
    }

    function testException()
    {
        $tpl = $this->newPHPTAL('input/tal-condition.04.html');
        $tpl->somevar = true;
        try {
            $tpl->execute();
        }
        catch (Exception $e){
        }
        $this->assertEquals(true, isset($e));
        // $exp = trim_file('output/tal-condition.04.html');
        // $this->assertEquals($exp, $res);
    }

    function testChainedFalse()
    {
        $tpl = $this->newPHPTAL();
        $tpl->setSource('<tal:block tal:condition="foo | bar | baz | nothing">fail!</tal:block>');
        $res = $tpl->execute();
        $this->assertEquals($res,'');
    }

    function testChainedTrue()
    {
        $tpl = $this->newPHPTAL();
        $tpl->setSource('<tal:block tal:condition="foo | bar | baz | \'ok!\'">ok</tal:block>');
        $res = $tpl->execute();
        $this->assertEquals($res,'ok');
    }

    function testChainedShortCircuit()
    {
        $tpl = $this->newPHPTAL();
        $tpl->setSource('<tal:block tal:condition="foo | \'ok!\' | bar | nothing">ok</tal:block>');
        $res = $tpl->execute();
        $this->assertEquals($res,'ok');
    }
}
