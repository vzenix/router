<?php

/**
 * @package    VZenix.Router
 *
 * @copyright  Copyright (C) 2017. vzenix.es
 * @license    GNU General Public License v3.
 *
 * Launch sample:
 * 
 *  - Windows: vendor\bin\phpunit.bat --bootstrap "../wallpapers/basic.php" "./test/libraries/RouterTest.php"
 *  - Linux: vendor\bin\phpunit --bootstrap "../wallpapers/basic.php" "./test/libraries/RouterTest.php"
 * 
 * Launch without CMS MWG: 
 * 
 *  - Windows: vendor\bin\phpunit.bat "test/RouterTest.php"
 *  - Linux: vendor\bin\phpunit "test/RouterTest.php"
 * 
 */

/**
 * Class for test "vzenix/router" library
 * @author Francisco Muros Espadas <paco@vzenix.es>
 */
class ConfigurationTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Instance for work
     * @var \VZenix\Router\Router
     */
    private $_iRouter = null;

    /**
     * Get a class file for require, this test is very simple, for this doesn't 
     * use "--bootstrap" and only load the class programmatically
     * @return string
     */
    private function _getPath(): string
    {
        if (defined("___MBASEPATH___"))
        {
            return ___MBASEPATH___ . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'router' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'VZenix' . DIRECTORY_SEPARATOR . 'Router' . DIRECTORY_SEPARATOR . 'Router.php';
        }

        return "./src/VZenix/Router/Router.php";
    }

    /**
     * Load class file for assertion
     * @return boolean
     */
    private function _initClass(): bool
    {
        // If class isn't loaded by --bootstrap phpunit param
        if (!class_exists("\\VZenix\\Router\\Router"))
        {
            // Try load programmatically
            if (!file_exists($this->_getPath()))
            {
                $this->assertEquals(1, 0, "Can't load Router.php, if is necesary can edit test file, function '_getPath'");
                return false;
            }

            require_once $this->_getPath();
        }

        $this->_iRouter = new \VZenix\Router\Router();
        return true;
    }

    /**
     * Basic test for get and set methods
     */
    public function testTestRouter()
    {
        if (!$this->_initClass())
        {
            return;
        }
    }

}
