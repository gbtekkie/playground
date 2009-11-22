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
 * @version  SVN: $Id: ContentEncodingTest.php 579 2009-04-25 23:14:46Z kornel $
 * @link     http://phptal.org/
 */

require_once dirname(__FILE__)."/config.php";

class ContentEncodingTest extends PHPTAL_TestCase
{
    function testSimple()
    {
        $tpl = $this->newPHPTAL('input/content-encoding.xml');
        $res = $tpl->execute();
        $exp = trim_file('output/content-encoding.xml');
        $res = trim_string($res);
        $this->assertEquals($exp, $res);
    }

    function testEchoArray()
    {
        $p = $this->newPHPTAL();
        $p->setSource('<p tal:content="foo"/>');
        $p->foo = array('bar'=>'a&aa','<bbb>',null,-1);
        $this->assertEquals('<p>a&amp;aa, &lt;bbb&gt;, , -1</p>',$p->execute());
    }
}

