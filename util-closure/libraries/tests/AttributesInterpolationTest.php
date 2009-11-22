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
 * @version  SVN: $Id: AttributesInterpolationTest.php 579 2009-04-25 23:14:46Z kornel $
 * @link     http://phptal.org/
 */

require_once dirname(__FILE__)."/config.php";

class AttributesInterpolationTest extends PHPTAL_TestCase
{
    public function testInterpol()
    {
        $src = <<<EOT
<span title="\${foo}"></span>
EOT;
        $exp = <<<EOT
<span title="foo value"></span>
EOT;
        $tpl = $this->newPHPTAL();
        $tpl->setSource($src);
        $tpl->foo = 'foo value';
        $res = $tpl->execute();
        $this->assertEquals($exp, $res);
    }

    public function testInterpol2()
    {
        $src = <<<EOT
<span title="\${foo2} x \${structure foo} y \${foo}\${structure foo2}"></span><img/>
EOT;
        $exp = <<<EOT
<span title="{foo2 &lt;img /&gt;} x foo value y foo value{foo2 <img />}"></span><img/>
EOT;
        $tpl = $this->newPHPTAL();
        $tpl->setSource($src);
        $tpl->foo = 'foo value';
        $tpl->foo2 = '{foo2 <img />}';
        $res = $tpl->execute();
        $this->assertEquals($exp, $res);
    }

    public function testInterpol3()
    {
        $src = <<<EOT
<span title="\${foo}\${foo}1"></span>
<span tal:attributes="title string:\${foo}\${foo}2"></span>
<span tal:attributes="title '\${foo}\${foo}3'"></span>
EOT;
        $exp = <<<EOT
<span title="foo valuefoo value1"></span>
<span title="foo valuefoo value2"></span>
<span title="foo valuefoo value3"></span>
EOT;
        $tpl = $this->newPHPTAL();
        $tpl->setSource($src);
        $tpl->foo = 'foo value';
        $res = $tpl->execute();
        $this->assertEquals($exp, $res);
    }

    public function testInterpol3a()
    {
        $src = <<<EOT
<span tal:attributes="title php:'\${foo}\${foo}'"></span>
<span title="<?php echo '\${foo}\${foo}' ?>"></span>
EOT;
    $exp = <<<EOT
<span title="\${foo}\${foo}"></span>
<span title="\${foo}\${foo}"></span>
EOT;
        $tpl = $this->newPHPTAL();
        $tpl->setSource($src);
        $tpl->foo = 'foo value';
        $res = $tpl->execute();
        $this->assertEquals($exp, $res);
    }

    public function testNoInterpol()
    {
        $src = <<<EOT
<span title="$\${foo}"></span>
EOT;
        $exp = <<<EOT
<span title="\${foo}"></span>
EOT;
        $tpl = $this->newPHPTAL();
        $tpl->setSource($src);
        $tpl->foo = 'foo value';
        $res = $tpl->execute();
        $this->assertEquals($exp, $res);
    }

    public function testNoInterpolAdv()
    {
        $src = <<<EOT
<span title="$$\${foo}"></span>
EOT;
        $exp = <<<EOT
<span title="$\${foo}"></span>
EOT;
        $tpl = $this->newPHPTAL();
        $tpl->setSource($src);
        $tpl->foo = 'foo value';
        $res = $tpl->execute();
        $this->assertEquals($exp, $res);
    }


    public function testPHPBlock()
    {
        $tpl = $this->newPHPTAL();
        $tpl->setSource('<p test=\'te&amp;st<?php echo "<x>"; ?>test<?php print("&amp;") ?>test\'/>');
        $this->assertEquals('<p test="te&amp;st<x>test&amp;test"></p>', $tpl->execute());
}

    public function testPHPBlockShort()
    {
        ini_set('short_open_tag',1);
        if (!ini_get('short_open_tag')) $this->markTestSkipped("PHP is buggy");

        $tpl = $this->newPHPTAL();
        $tpl->setSource('<p test=\'te&amp;st<? print("<x>"); ?>test<?= "&amp;" ?>test\'/>');
        $this->assertEquals('<p test="te&amp;st<x>test&amp;test"></p>', $tpl->execute());
        ini_restore('short_open_tag');
    }

    public function testPHPBlockNoShort()
    {
        ini_set('short_open_tag', 0);
        if (ini_get('short_open_tag')) $this->markTestSkipped("PHP is buggy");

        $tpl = $this->newPHPTAL();
        $tpl->setSource('<p test=\'te&amp;st<? print("<x>"); ?>test<?= "&amp;" ?>test\'/>');
        try
        {
            $this->assertEquals(trim_string('<p test="te&amp;st&lt;? print("&lt;x&gt;"); ?&gt;test&lt;?= "&amp;" ?&gt;test"></p>'), trim_string($tpl->execute()));
        }
        catch(PHPTAL_ParserException $e) {/* xml ill-formedness error is ok too */}
        ini_restore('short_open_tag');
    }
}

