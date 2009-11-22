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
 * @version  SVN: $Id: TalesPhpTest.php 579 2009-04-25 23:14:46Z kornel $
 * @link     http://phptal.org/
 */

require_once dirname(__FILE__)."/config.php";

class TalesPhpTest extends PHPTAL_TestCase {
	
	function testMix()
	{
		$tpl = $this->newPHPTAL('input/php.html');
		$tpl->real = 'real value';
		$tpl->foo = 'real';
		$res = trim_string($tpl->execute());
		$exp = trim_file('output/php.html');
		$this->assertEquals($exp,$res);
	}
	
	function testPHPAttribute()
	{
	    $tpl = $this->newPHPTAL();
	    $tpl->setSource('<foo bar="<?php  echo  \'baz\' ; ?>"/>');
	    $this->assertEquals('<foo bar="baz"></foo>',$tpl->execute());
    }
}

