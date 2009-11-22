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
 * @version  SVN: $Id: TalReplaceTest.php 610 2009-05-24 00:32:13Z kornel $
 * @link     http://phptal.org/
 */

require_once dirname(__FILE__)."/config.php";

PHPTAL::setIncludePath();
require_once 'PHPTAL/Tales.php';
PHPTAL::restoreIncludePath();

class TalReplaceTest extends PHPTAL_TestCase
{
    function testSimple()
    {
        $tpl = $this->newPHPTAL('input/tal-replace.01.html');
        $res = trim_string($tpl->execute());
        $exp = trim_file('output/tal-replace.01.html');
        $this->assertEquals($exp, $res);
    }

    function testVar()
    {
        $tpl = $this->newPHPTAL('input/tal-replace.02.html');
        $tpl->replace = 'my replace';
        $res = trim_string($tpl->execute());
        $exp = trim_file('output/tal-replace.02.html');
        $this->assertEquals($exp, $res);
    }

    function testStructure()
    {
        $tpl = $this->newPHPTAL('input/tal-replace.03.html');
        $tpl->replace = '<foo><bar/></foo>';
        $res = trim_string($tpl->execute());
        $exp = trim_file('output/tal-replace.03.html');
        $this->assertEquals($exp, $res);
    }

    function testNothing()
    {
        $tpl = $this->newPHPTAL('input/tal-replace.04.html');
        $res = trim_string($tpl->execute());
        $exp = trim_file('output/tal-replace.04.html');
        $this->assertEquals($exp, $res);
    }

    function testDefault()
    {
        $tpl = $this->newPHPTAL('input/tal-replace.05.html');
        $res = trim_string($tpl->execute());
        $exp = trim_file('output/tal-replace.05.html');
        $this->assertEquals($exp, $res);
    }

    function testChain()
    {
        $tpl = $this->newPHPTAL('input/tal-replace.06.html');
        $res = trim_string($tpl->execute());
        $exp = trim_file('output/tal-replace.06.html');
        $this->assertEquals($exp, $res);
    }

    function testBlock()
    {
        $tpl = $this->newPHPTAL('input/tal-replace.07.html');
        $res = trim_string($tpl->execute());
        $exp = trim_file('output/tal-replace.07.html');
        $this->assertEquals($exp, $res);
    }

    function testEmpty()
    {
        $src = <<<EOT
<root>
<span tal:replace="nullv | falsev | emptystrv | zerov | default">default</span>
<span tal:replace="nullv | falsev | emptystrv | default">default</span>
</root>
EOT;
        $exp = <<<EOT
<root>
0
<span>default</span>
</root>
EOT;
        $tpl = $this->newPHPTAL();
        $tpl->setSource($src, __FILE__);
        $tpl->nullv = null;
        $tpl->falsev = false;
        $tpl->emptystrv = '';
        $tpl->zerov = 0;
        $res = $tpl->execute();
        $this->assertEquals(trim_string($exp), trim_string($res));
    }
}


