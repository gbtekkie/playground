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
 * @version  SVN: $Id: TalOnErrorTest.php 611 2009-05-24 00:40:00Z kornel $
 * @link     http://phptal.org/
 */

if (!class_exists('OnErrorDummyObject',false)) {
    class OnErrorDummyObject
    {
        function throwException()
        {
            throw new Exception('error thrown');
        }
    }
}

require_once dirname(__FILE__)."/config.php";

class TalOnErrorTest extends PHPTAL_TestCase
{
    function testSimple()
    {
        $tpl = $this->newPHPTAL('input/tal-on-error.01.html');
        $tpl->dummy = new OnErrorDummyObject();
        $res = trim_string($tpl->execute());
        $exp = trim_file('output/tal-on-error.01.html');
        $this->assertEquals($exp, $res);
        $errors = $tpl->getErrors();
        $this->assertEquals(1, count($errors));
        $this->assertEquals('error thrown', $errors[0]->getMessage());
    }

    function testEmpty()
    {
        $tpl = $this->newPHPTAL('input/tal-on-error.02.html');
        $tpl->dummy = new OnErrorDummyObject();
        $res = trim_string($tpl->execute());
        $exp = trim_file('output/tal-on-error.02.html');
        $errors = $tpl->getErrors();
        $this->assertEquals(1, count($errors));
        $this->assertEquals('error thrown', $errors[0]->getMessage());
        $this->assertEquals($exp, $res);
    }

    function testReplaceStructure()
    {
        $tpl = $this->newPHPTAL('input/tal-on-error.03.html');
        $tpl->dummy = new OnErrorDummyObject();
        $res = trim_string($tpl->execute());
        $exp = trim_file('output/tal-on-error.03.html');
        $errors = $tpl->getErrors();
        $this->assertEquals(1, count($errors));
        $this->assertEquals('error thrown', $errors[0]->getMessage());
        $this->assertEquals($exp, $res);
    }
}


