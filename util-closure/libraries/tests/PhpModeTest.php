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
 * @version  SVN: $Id: PhpModeTest.php 596 2009-05-02 18:05:35Z kornel $
 * @link     http://phptal.org/
 */

require_once dirname(__FILE__)."/config.php";

class PhpModeTest extends PHPTAL_TestCase
{
    function testSimple()
    {
        $tpl = $this->newPHPTAL('input/php-mode.01.xml');
        $res = $tpl->execute();
        $exp = trim_file('output/php-mode.01.xml');
        $res = trim_string($res);
        $this->assertEquals($exp, $res);
    }

    function testInContent()
    {
        $tpl = $this->newPHPTAL('input/php-mode.02.xml');
        $res = $tpl->execute();
        $exp = trim_file('output/php-mode.02.xml');
        $res = trim_string($res);
        $this->assertEquals($exp, $res);
    }
}


