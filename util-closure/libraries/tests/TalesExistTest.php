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
 * @version  SVN: $Id: TalesExistTest.php 657 2009-06-30 16:48:20Z kornel $
 * @link     http://phptal.org/
 */

require_once dirname(__FILE__)."/config.php";

PHPTAL::setIncludePath();
require_once 'PHPTAL/Tales.php';
PHPTAL::restoreIncludePath();

class TalesExistTest extends PHPTAL_TestCase
{
    function testLevel1()
    {
        $tpl = $this->newPHPTAL('input/tales-exist-01.html');
        $tpl->foo = 1;
        $res = $tpl->execute();
        $res = trim_string($res);
        $exp = trim_file('output/tales-exist-01.html');
        $this->assertEquals($exp, $res, $tpl->getCodePath());
    }

    function testLevel2()
    {
        $o = new StdClass();
        $o->foo = 1;
        $tpl = $this->newPHPTAL('input/tales-exist-02.html');
        $tpl->o = $o;
        $res = $tpl->execute();
        $res = trim_string($res);
        $exp = trim_file('output/tales-exist-02.html');
        $this->assertEquals($exp, $res);
    }
}

