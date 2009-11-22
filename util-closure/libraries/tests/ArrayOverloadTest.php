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
 * @version  SVN: $Id: ArrayOverloadTest.php 579 2009-04-25 23:14:46Z kornel $
 * @link     http://phptal.org/
 */

class MyArray implements ArrayAccess
{
    public function push($value) {
        $this->_values[] =  $value;
    }

    public function offsetGet($index){
        return $this->_values[$index];
    }

    public function offsetSet($index, $value){
        $this->_values[$index] = $value;
    }

    public function offsetExists($of){
        return isset($this->_values[$of]);
    }

    public function offsetUnset($of){
        unset($this->_values[$of]);
    }

    private $_values = array();
}

require_once dirname(__FILE__)."/config.php";

class ArrayOverloadTest extends PHPTAL_TestCase
{
    function testIt()
    {
        $arr = new MyArray();
        for ($i=0; $i<20; $i++){
            $val = new StdClass;
            $val->foo = "foo value $i";
            $arr->push($val);
        }

        $tpl = $this->newPHPTAL('input/array-overload.01.html');
        $tpl->myobject = $arr;
        $res = $tpl->execute();
        $exp = trim_file('output/array-overload.01.html');
        $res = trim_string($res);
        $this->assertEquals($exp, $res);
    }
}


