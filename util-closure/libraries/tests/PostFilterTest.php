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
 * @version  SVN: $Id: PostFilterTest.php 579 2009-04-25 23:14:46Z kornel $
 * @link     http://phptal.org/
 */

require_once dirname(__FILE__)."/config.php";

class MyPostFilter implements PHPTAL_Filter
{
    public function filter($str)
    {
        if (preg_match('|<root>(.*?)</root>|s', $str, $m)) {
            return $m[1];
        }
        return $str;
    }
}

class MyPostFilter2 implements PHPTAL_Filter
{
    public function filter($str)
    {
        return str_replace('test','test-filtered',$str);
    }
}

class PostFilterTest extends PHPTAL_TestCase
{
    function testIt()
    {
        $filter = new MyPostFilter();
        $tpl = $this->newPHPTAL('input/postfilter.01.html');
        $tpl->setPostFilter($filter);
        $tpl->value = 'my value';
        $res = trim_string($tpl->execute());
        $exp = trim_file('output/postfilter.01.html');
        $this->assertEquals($exp, $res);
    }

    function testMacro()
    {
        $tpl = $this->newPHPTAL();
        $tpl->setPostFilter(new MyPostFilter2());
        $tpl->setSource('<x><y metal:define-macro="macro">test2</y>
        test1
        <z metal:use-macro="macro" />
        </x>
        ');
        $this->assertEquals(trim_string('<x>test-filtered1<y>test-filtered2</y></x>'),trim_string($tpl->execute()));
    }
}
