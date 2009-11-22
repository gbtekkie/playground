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
 * @version  SVN: $Id: MetalMacroTest.php 750 2009-10-24 22:03:17Z kornel $
 * @link     http://phptal.org/
 */

require_once dirname(__FILE__)."/config.php";

class MetalMacroTest extends PHPTAL_TestCase
{
    function testSimple()
    {
        $tpl = $this->newPHPTAL('input/metal-macro.01.html');
        $res = trim_string($tpl->execute());
        $exp = trim_file('output/metal-macro.01.html');
        $this->assertEquals($exp, $res);
    }

    function testExternalMacro()
    {
        $tpl = $this->newPHPTAL('input/metal-macro.02.html');
        $res = trim_string($tpl->execute());
        $exp = trim_file('output/metal-macro.02.html');
        $this->assertEquals($exp, $res);
    }

    function testBlock()
    {
        $tpl = $this->newPHPTAL('input/metal-macro.03.html');
        $res = trim_string($tpl->execute());
        $exp = trim_file('output/metal-macro.03.html');
        $this->assertEquals($exp, $res);
    }

    function testMacroInsideMacro()
    {
        $tpl = $this->newPHPTAL('input/metal-macro.04.html');
        $res = trim_string($tpl->execute());
        $exp = trim_file('output/metal-macro.04.html');
        $this->assertEquals($exp, $res);
    }

    function testEvaluatedMacroName()
    {
        $call = new StdClass();
        $call->first = 1;
        $call->second = 2;

        $tpl = $this->newPHPTAL('input/metal-macro.05.html');
        $tpl->call = $call;

        $res = trim_string($tpl->execute());
        $exp = trim_file('output/metal-macro.05.html');
        $this->assertEquals($exp, $res);
    }

    function testEvaluatedMacroNameTalesPHP()
    {
        $call = new StdClass();
        $call->first = 1;
        $call->second = 2;

        $tpl = $this->newPHPTAL('input/metal-macro.06.html');
        $tpl->call = $call;

        $res = trim_string($tpl->execute());
        $exp = trim_file('output/metal-macro.06.html');
        $this->assertEquals($exp, $res);
    }

    function testInheritedMacroSlots()
    {
        $tpl = $this->newPHPTAL('input/metal-macro.07.html');
        $res = trim_string($tpl->execute());
        $exp = trim_file('output/metal-macro.07.html');
        $this->assertEquals($exp, $res);
    }

    /**
     * @expectedException PHPTAL_ParserException
     */
    function testBadMacroNameException()
    {
        $tpl = $this->newPHPTAL('input/metal-macro.08.html');
        $res = $tpl->execute();
        $this->fail('Bad macro name exception not thrown');
    }

    /**
     * @expectedException PHPTAL_MacroMissingException
     */
    function testExternalMacroMissingException()
    {
        $tpl = $this->newPHPTAL();
        $tpl->setSource('<tal:block metal:use-macro="input/metal-macro.07.html/this-macro-doesnt-exist"/>');
        $res = $tpl->execute();
        $this->fail('Bad macro name exception not thrown');
        }

    /**
     * @expectedException PHPTAL_MacroMissingException
     */
    function testMacroMissingException()
    {
        $tpl = $this->newPHPTAL();
        $tpl->setSource('<tal:block metal:use-macro="this-macro-doesnt-exist"/>');
        $res = $tpl->execute();
        $this->fail('Bad macro name exception not thrown');
    }

    function testMixedCallerDefiner()
    {
        $tpl = $this->newPHPTAL();
        $tpl->defined_later_var = 'defined_later';
        $tpl->ok_var = '??'; // fallback in case test fails
        $tpl->setSource('<tal:block metal:use-macro="input/metal-macro.09.html/defined_earlier" />');
        $res = $tpl->execute();
        $this->assertEquals('Call OK OK',trim(preg_replace('/\s+/',' ',$res)));
    }
    
    /**
     * @expectedException PHPTAL_Exception
     */
    function testMacroRedefinitionIsGraceful()
    {
        $tpl = $this->newPHPTAL();
        $tpl->setSource(
        '<p>
          <metal:block define-macro=" foo " /> 
              <a metal:define-macro="foo">bar</a>
         </p>');
        $tpl->execute();
        
        $this->fail("Allowed duplicate macro");
    }
    
    function testSameMacroCanBeDefinedInDifferentTemplates()
    {
        $tpl = $this->newPHPTAL();
        $tpl->setSource('<tal:block metal:define-macro=" foo ">1</tal:block>');
        $tpl->execute();

        $tpl = $this->newPHPTAL();
        $tpl->setSource('<tal:block metal:define-macro=" foo ">2</tal:block>');
        $tpl->execute();
    }
    
    /**
     * @expectedException PHPTAL_ParserException
     */
    function testExternalTemplateThrowsError()
    {
        $tpl = $this->newPHPTAL();
        $tpl->setSource('<phptal:block metal:use-macro="input/metal-macro.10.html/throwerr"/>');
        
        $tpl->execute();
    }

    function testOnErrorCapturesErorrInExternalMacro()
    {
        $tpl = $this->newPHPTAL();
        $tpl->setSource('<phptal:block tal:on-error="string:ok"
        metal:use-macro="input/metal-macro.10.html/throwerr"/>');
        
        $this->assertEquals('ok',$tpl->execute());
    }
    
    function testGlobalDefineInExternalMacro()
    {
        $tpl = $this->newPHPTAL();
        $tpl->setSource('<metal:block>
            <phptal:block tal:define="global test string:bad"
            metal:use-macro="input/metal-macro.11.html/redefine"/>
            ${test}
            </metal:block>');
        
        $this->assertEquals('ok',trim($tpl->execute()));
    }
}

