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
 * @version  SVN: $Id: NamespacesTest.php 677 2009-07-20 11:58:26Z kornel $
 * @link     http://phptal.org/
 */

require_once dirname(__FILE__)."/config.php";

PHPTAL::setIncludePath();
require_once 'PHPTAL/Namespace.php';
require_once 'PHPTAL/Namespace/Builtin.php';
PHPTAL::restoreIncludePath();

if (!class_exists('Test_PHPTAL_Namespace',false))
{
    class Test_PHPTAL_Namespace extends PHPTAL_Namespace_Builtin
    {
    }
}

class NamespacesTest extends PHPTAL_TestCase
{
    function testTalAlias()
    {
        $exp = trim_file('output/namespaces.01.html');
        $tpl = $this->newPHPTAL('input/namespaces.01.html');
        $res = $tpl->execute();
        $res = trim_string($res);
        $this->assertEquals($exp, $res);
    }

    function testInherit()
    {
        $exp = trim_file('output/namespaces.02.html');
        $tpl = $this->newPHPTAL('input/namespaces.02.html');
        $res = $tpl->execute();
        $res = trim_string($res);
        $this->assertEquals($exp, $res);
    }

    function testOverwrite()
    {
        $exp = trim_file('output/namespaces.03.html');
        $tpl = $this->newPHPTAL('input/namespaces.03.html');
        $res = $tpl->execute();
        $res = trim_string($res);
        $this->assertEquals($exp, $res);
    }

    function testOverwriteBuiltinNamespace()
    {
        $tpl = $this->newPHPTAL();
        $tpl->setSource($src='<metal:block xmlns:metal="non-zope" metal:use-macro="just kidding">ok</metal:block>');
        $this->assertEquals($src, $tpl->execute());
    }

    function testNamespaceWithoutPrefix()
    {
        $tpl = $this->newPHPTAL();
        $tpl->setSource('<metal:block xmlns:metal="non-zope">
                           <block xmlns="http://xml.zope.org/namespaces/tal" content="string:works" />
                         </metal:block>');
        $this->assertEquals(trim_string('<metal:block xmlns:metal="non-zope"> works </metal:block>'),
                            trim_string($tpl->execute()));
    }

    function testRedefineBuiltinNamespace()
    {
        $tpl = $this->newPHPTAL();
        $tpl->setSource('<metal:block xmlns:metal="non-zope">
                           <foo:block xmlns="x" xmlns:foo="http://xml.zope.org/namespaces/tal" content="string:works" />
                           <metal:block xmlns="http://xml.zope.org/namespaces/i18n" xmlns:metal="http://xml.zope.org/namespaces/tal" metal:content="string:properly" />
                         </metal:block>');
        $this->assertEquals(trim_string('<metal:block xmlns:metal="non-zope"> works properly </metal:block>'),
                            trim_string($tpl->execute()));
    }
    
    // different kind of namespace
    
    /**
     * @expectedException PHPTAL_ConfigurationException
     */
    function testPHPTALNamespaceClassRejectsEmptyNS() 
    {
        new Test_PHPTAL_Namespace('test','');
    }
    
    /**
     * @expectedException PHPTAL_ConfigurationException
     */
    function testPHPTALNamespaceClassRejectsEmptyPrefix() 
    {
        new Test_PHPTAL_Namespace('','urn:test');
    }
}
