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
 * @version  SVN: $Id: ParserTest.php 681 2009-07-22 10:55:43Z kornel $
 * @link     http://phptal.org/
 */


require_once dirname(__FILE__)."/config.php";
 
PHPTAL::setIncludePath();
require_once 'PHPTAL/Dom/DocumentBuilder.php';
PHPTAL::restoreIncludePath();

class ParserTest extends PHPTAL_TestCase
{
    public function testParseSimpleDocument()
    {
        $parser = new PHPTAL_Dom_SaxXmlParser('UTF-8');
        $tree = $parser->parseFile(new PHPTAL_Dom_DocumentBuilder(),'input/parser.01.xml')->getResult();

        if ($tree instanceof DOMNode) $this->markTestSkipped();

        $children = $tree->childNodes;
        $this->assertEquals(3, count($children));
        $this->assertEquals(5, count($children[2]->childNodes));
    }

    public function testByteOrderMark()
    {
        $parser = new PHPTAL_Dom_SaxXmlParser('UTF-8');
        try {
            $tree = $parser->parseFile(new PHPTAL_Dom_DocumentBuilder(),'input/parser.02.xml')->getResult();
            $this->assertTrue(true);
        }
        catch (Exception $e){
            $this->assertTrue(false);
        }
    }

    public function testBadAttribute(){
        try {
            $parser = new PHPTAL_Dom_SaxXmlParser('UTF-8');
            $parser->parseFile(new PHPTAL_Dom_DocumentBuilder(),'input/parser.03.xml')->getResult();
        }
        catch (Exception $e){
            $this->assertContains( 'href', $e->getMessage() );
            $this->assertContains( 'quote', $e->getMessage() );
        }
    }

    public function testLegalElementNames()
    {
        $parser = new PHPTAL_Dom_SaxXmlParser('UTF-8');
        $parser->parseString(new PHPTAL_Dom_DocumentBuilder(),'<?xml version="1.0" encoding="UTF-8"?>
        <t1 xmlns:foo..._-ą="http://foo.example.com"><foo..._-ą:test-element_name /><t---- /><t___ /><oóźżćń /><d.... /></t1>')->getResult();
    }

    public function testXMLNS()
    {
         $parser = new PHPTAL_Dom_SaxXmlParser('UTF-8');
         $parser->parseString(new PHPTAL_Dom_DocumentBuilder(),'<?xml version="1.0" encoding="UTF-8"?>
         <t1 xml:lang="foo" xmlns:bla="xx"></t1>')->getResult();
    }

    public function testIllegalElementNames1()
    {
        $parser = new PHPTAL_Dom_SaxXmlParser('UTF-8');
        try
        {
            $parser->parseString(new PHPTAL_Dom_DocumentBuilder(),'<?xml version="1.0" encoding="UTF-8"?>
            <t><1element /></t>')->getResult();

            $this->fail("Accepted invalid element name starting with a number");
        }
        catch(PHPTAL_Exception $e) {}
    }

    public function testIllegalElementNames2()
    {
        $parser = new PHPTAL_Dom_SaxXmlParser('UTF-8');
        try
        {
            $parser->parseString(new PHPTAL_Dom_DocumentBuilder(),'<t><element~ /></t>');
            $this->fail("Accepted invalid element name")->getResult();
        }
        catch(PHPTAL_Exception $e) {}
    }

}
