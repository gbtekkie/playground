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
 * @version  SVN: $Id: I18NDummyTranslator.php 579 2009-04-25 23:14:46Z kornel $
 * @link     http://phptal.org/
 */

PHPTAL::setIncludePath();
require_once 'PHPTAL/TranslationService.php';
PHPTAL::restoreIncludePath();

class DummyTranslator implements PHPTAL_TranslationService
{
    public $vars = array();
    public $translations = array();

    public function setLanguage(){
    }

    public function setEncoding($enc) {}

    public function useDomain($domain){
    }

    public function setVar($key, $value){
        $this->vars[$key] = $value;
    }

    public function setTranslation($key, $translation){
        $this->translations[$key] = $translation;
    }

    public function translate($key, $escape = true){
        if (array_key_exists($key, $this->translations)) {
            $v = $this->translations[$key];
        } else {
            $v = $key;
        }

        if ($escape) $v = htmlspecialchars($v);

        while (preg_match('/\$\{(.*?)\}/sm', $v, $m)){
            list($src, $var) = $m;
            if (!isset($this->vars[$var])) return "!*$var* is not defined!";
            $v = str_replace($src, $this->vars[$var], $v);
        }


        return $v;
    }
}
