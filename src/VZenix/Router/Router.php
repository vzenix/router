<?php

/**
 * @package    VZenix.Router
 *
 * @copyright  Copyright (C) 2017. vzenix.es
 * @license    GNU General Public License v3.
 */

namespace VZenix\Router;

/**
 * Class for query PATH of URI
 * @author Francisco Muros Espadas <paco@vzenix.es>
 */
class Router
{

    /**
     * 300 Multiple Choices
     * <p>Indicates multiple options for the resource from which the client may choose (via agent-driven content negotiation). For example, this code could be used to present multiple video format options, to list files with different filename extensions, or to suggest word-sense disambiguation.</p>
     */
    const REDIRECT_MULTIPLE_CHOICES = 300;

    /**
     * 301 Moved Permanently
     * <p>This and all future requests should be directed to the given URI.</p>
     */
    const REDIRECT_MOVED_PERMANENTLY = 301;

    /**
     * 302 Found
     * <p>This is an example of industry practice contradicting the standard. The HTTP/1.0 specification (RFC 1945) required the client to perform a temporary redirect (the original describing phrase was "Moved Temporarily"),[21] but popular browsers implemented 302 with the functionality of a 303 See Other. Therefore, HTTP/1.1 added status codes 303 and 307 to distinguish between the two behaviours.[22] However, some Web applications and frameworks use the 302 status code as if it were the 303.</p>
     */
    const REDIRECT_FOUND = 302;

    /**
     * 303 See Other (since HTTP/1.1)
     * <p>The response to the request can be found under another URI using a GET method. When received in response to a POST (or PUT/DELETE), the client should presume that the server has received the data and should issue a redirect with a separate GET message.</p>
     */
    const REDIRECT_SEE_OTHER = 303;

    /**
     * 304 Not Modified (RFC 7232)
     * <p>Indicates that the resource has not been modified since the version specified by the request headers If-Modified-Since or If-None-Match. In such case, there is no need to retransmit the resource since the client still has a previously-downloaded copy.</p>
     */
    const REDIRECT_NOT_MODIFIED = 304;

    /**
     * 305 Use Proxy (since HTTP/1.1)
     * <p>The requested resource is available only through a proxy, the address for which is provided in the response. Many HTTP clients (such as Mozilla[26] and Internet Explorer) do not correctly handle responses with this status code, primarily for security reasons.</p>
     */
    const REDIRECT_USE_PROXY = 305;

    /**
     * 306 Switch Proxy
     * <p>No longer used. </p>
     * <p>Originally meant "Subsequent requests should use the specified proxy."</p>
     */
    const REDIRECT_SWITCH_PROXY = 306;

    /**
     * 307 Temporary Redirect (since HTTP/1.1)
     * In this case, the request should be repeated with another URI; however, future requests should still use the original URI. In contrast to how 302 was historically implemented, the request method is not allowed to be changed when reissuing the original request. For example, a POST request should be repeated using another POST request.</p>
     */
    const REDIRECT_TEMPORARY_REDIRECT = 307;

    /**
     * 308 Permanent Redirect (RFC 7538)
     * <p>The request and all future requests should be repeated using another URI. 307 and 308 parallel the behaviors of 302 and 301, but do not allow the HTTP method to change. So, for example, submitting a form to a permanently redirected resource may continue smoothly.</p>
     */
    const REDIRECT_PERMANENT_REDIRECT = 308;

    /**
     * Static instance for cache
     * @var \VZenix\Router\Router
     */
    private static $_iRouter = null;

    /**
     * Get the instance of the active router
     * @return \VZenix\Router\Router
     */
    public static function GetInstance(): \VZenix\Router\Router
    {
        if (is_null(self::$_iRouter))
        {
            self::$_iRouter = new \VZenix\Router\Router();
            self::$_iRouter->init();
        }

        return self::$_iRouter;
    }

    /**
     * Redirect the page to other URI
     * @param int $t <p>Type of redirection (HTTP standard code)</p>
     * @param string $d [optional] <p>URI to redirect, put into header the "location" instruction</p>
     * @param boolean $die [optional] <p>Finish execution if send true, for example in 301 redirect it's not necessary continue execution</p>
     * @return void
     * @throws \VZenix\Router\Exception If set an invalid http code in the first parameter
     */
    public static function Redirect(int $t, string $d = "", bool $die = false)
    {
        switch ($t)
        {
            default:
                throw new \VZenix\Router\Exception('Invalid redirection code "' . $t . '", use only HTTP standard codes');

            case self::REDIRECT_MULTIPLE_CHOICES:
                header(getenv('SERVER_PROTOCOL') . " 300 Multiple Choices");
                break;

            case self::REDIRECT_MOVED_PERMANENTLY:
                header(getenv('SERVER_PROTOCOL') . " 301 Moved Permanently");
                break;

            case self::REDIRECT_FOUND:
                header(getenv('SERVER_PROTOCOL') . " 302 Found");
                break;

            case self::REDIRECT_SEE_OTHER:
                header(getenv('SERVER_PROTOCOL') . " 303 See Other ");
                break;

            case self::REDIRECT_NOT_MODIFIED:
                header(getenv('SERVER_PROTOCOL') . " 304 Not Modified");
                break;

            case self::REDIRECT_USE_PROXY:
                header(getenv('SERVER_PROTOCOL') . " 305 Use Proxy");
                break;

            case self::REDIRECT_SWITCH_PROXY:
                header(getenv('SERVER_PROTOCOL') . " 306 Switch Proxy");
                break;

            case self::REDIRECT_TEMPORARY_REDIRECT:
                header(getenv('SERVER_PROTOCOL') . " 307 Temporary Redirect");
                break;

            case self::REDIRECT_PERMANENT_REDIRECT:
                header(getenv('SERVER_PROTOCOL') . " 308 Permanent Redirect");
                break;
        }

        if ($d !== "")
        {
            header("location: $d");
        }

        if ($die)
        {
            die();
        }
    }

    /**
     * Array with route in parts
     * @var array
     */
    private $_arrRoutes = array();

    /**
     * Full name path
     * @var String
     */
    private $_pathTxt = "";

    /**
     * Subdomain
     * @var String
     */
    private $_subDomain = "";

    /**
     * Subdomain by level
     * @var array
     */
    private $_subDomainLvl = array();

    /**
     * <p>Position that consider root of the route, default 0 if nothing is specified.</p>
     * <p>This value is used to add to the position passed by position , for example
     * if the content of position 1 of the route and _position worth 1 is requested it be returned
     * really the position 2. This variable is commonly used for websites that multisystem</p>
     * @var Int 
     */
    protected $_position = 0;

    /**
     * Init class
     * @param string $raiz [optional] <p>Path to evade in calc, default none</p>
     * @return \VZenix\Router\Router
     */
    public function init(string $raiz = "/"): \VZenix\Router\Router
    {
        unset($this->_arrRoutes);

        $this->setInitPosition(0);

        $_ruta = substr(getenv("REQUEST_URI"), strlen($raiz));
        if (is_numeric(strpos($_ruta, "?")))
        {
            $_ruta = substr($_ruta, 0, strpos($_ruta, "?"));
        }

        if (is_numeric(strpos($_ruta, "#")))
        {
            $_ruta = substr($_ruta, 0, strpos($_ruta, "#"));
        }

        $this->_arrRoutes = explode("/", $_ruta);
        if (count($this->_arrRoutes) > 0 && $this->_arrRoutes[count($this->_arrRoutes) - 1] == "")
        {
            unset($this->_arrRoutes[count($this->_arrRoutes) - 1]);
        }

        $this->_arrRoutes = array_values($this->_arrRoutes);
        $this->_pathTxt = $_ruta;

        $_dominio = explode(".", getenv("SERVER_NAME"));
        $this->_subDomainLvl = array();

        $this->_subDomain = "";
        for ($i = 0; $i < (count($_dominio) - 2); $i++)
        {
            if ($i > 0 && $i < (count($_dominio) - 3))
            {
                $this->_subDomain .= ".";
            }

            $this->_subDomain .= $_dominio[$i];
            $this->_subDomainLvl[] = $_dominio[$i];
        }

        return $this;
    }

    /**
     * Get the full path uri, sample url http://test.test.es/test/demo/ return "/test/demo/"
     * @param boolean $decode [optional] <p>Set true for return deencode url to real character, false default</p>
     * @return string
     */
    public function getFullPath(bool $decode = false): string
    {
        $_return = "/";
        for ($i = $this->getInitPosition(); $i < count($this->_arrRoutes); $i++)
        {
            $_return .= $this->_arrRoutes[$i] . "/";
        }

        return $decode ? urldecode($_return) : $_return;
    }

    /**
     * Get a specific position striny
     * @param int $pos Position to get
     * @return string empty string it position doesn't exist
     */
    public function getPosition(int $pos)
    {
        $_pos = intval($pos) + $this->getInitPosition();
        if (!isset($this->_arrRoutes[$_pos]))
        {
            return "";
        }

        return $this->_arrRoutes[$_pos];
    }

    /**
     * Subdomain value
     * @return string
     */
    public function getSubdomain(): string
    {
        return $this->_subDomain;
    }

    /**
     * Specific subdomain level
     * @param int $pos
     * @return string empty string it it's not found
     */
    public function getSubdomainPosition(int $pos): string
    {
        if (isset($this->_subDomainLvl[$pos]))
        {
            return $this->_subDomainLvl[$pos];
        }

        return "";
    }

    /**
     * Get init position for calc route
     * @return int
     */
    public function getInitPosition(): int
    {
        return $this->_position;
    }

    /**
     * Set init position for calcs
     * @param int $p
     */
    public function setInitPosition(int $p = 0)
    {
        $this->_position = intval($p);
    }

    /**
     * Get the uri base of website
     * @return string
     */
    public function getUriBase(): string
    {
        static $_sUri = "";
        if ($_sUri != "")
        {
            return $_sUri;
        }

        // REQUEST_SCHEME
        $_scheme = getenv("REQUEST_SCHEME");
        $_scheme = (!is_string($_scheme) || $_scheme !== "https") ? "http" : "https";

        $_sUri = $_scheme . "://" . getenv("HTTP_HOST") . "/";
        for ($i = 0; $i < (int) $this->getInitPosition(); $i++)
        {
            if (isset($this->_arrRoutes[$i]))
            {
                $_sUri .= $this->_arrRoutes[$i] . "/";
            }
        }

        return $_sUri;
    }

    /**
     * Return the real domain for webpage loaded
     * @return String
     */
    public function getDomain(): string
    {
        return getenv("SERVER_NAME");
    }

    /**
     * Returna a hash for URL
     * @link http://php.net/manual/es/function.hash.php
     * @param string $string <p>Name of selected hashing algorithm (e.g. "md5", "sha256", "haval160,4", etc..)</p><p>Valid algorithm in hash PHP definiction function</p>
     * @return string 
     */
    public function getHash(string $string = "md5"): string
    {
        return hash($string, $this->getFullPath());
    }

}
