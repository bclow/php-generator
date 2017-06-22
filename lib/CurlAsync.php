<?php

class FutureCurl {
    protected $name;
    protected $curlAsync;

    // TODO(bclow): support time-out
    public function __construct($name, $curlAsync) {
        $this->name         = $name;
        $this->curlAsync    = $curlAsync;
    }

    public function get() {
        return $this->curlAsync->getResponse($this->name);
    }
}



/** Convenient API for asynchronous HTTP connections using CURL in PHP 5+
* @link http://github.com/vrana/curl-async
* @author Jakub Vrana, http://www.vrana.cz/
* @copyright 2010 Jakub Vrana
* @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
*/
class CurlAsync {
    protected $select_timeout = 1.0;
	/** @var float number of seconds to wait in retrieving data */
	//protected $multi;
	protected $multi;
	protected $curl = array();
	protected $done = array();

    protected $id   = 0;
	
	/** Initialize CURL
	*/
	function __construct() {
		$this->multi    = curl_multi_init();
	}
	
	/** Close CURL
	*/
	function __destruct() {
		curl_multi_close($this->multi);
	}

    function getID() {
        $curId          = $this->id;
        ++$this->id;
        return "mcurl_{$curId}";
    }
	
    function submitURL($url, $timeout=null, $proxy=null) {
        $name               = $this->getID();

        $curl               = curl_init($url);
        $this->curl[$name]  = $curl;
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,      true);
        if($timeout !== null) {
            curl_setopt($curl, CURLOPT_TIMEOUT,         $timeout);
        }

        curl_setopt($curl, CURLOPT_USERAGENT,           'Mozilla/5.0 (compatible; Feebeebot/1.0; +http://feebee.com.tw)');
        curl_setopt($curl, CURLOPT_HTTPHEADER,          ['Connection: close']);

        curl_setopt($curl, CURLOPT_FOLLOWLOCATION,      1);
        curl_setopt($curl, CURLOPT_MAXREDIRS,           7);
        curl_setopt($curl, CURLOPT_AUTOREFERER,         true);

        if($proxy != null) {
            curl_setopt($curl, CURLOPT_PROXY, $proxy);
        }

        $return             = curl_multi_add_handle($this->multi, $curl);
        while (curl_multi_exec($this->multi, $running) == CURLM_CALL_MULTI_PERFORM) ;  

        return new FutureCurl($name, $this);
    }

    function getResponse($name) {
		// get response
		if (!isset($this->curl[$name])) { // wrong identifier
			return false;
		}

		$curl = $this->curl[$name];
		while (!isset($this->done[(int) $curl])) {
			curl_multi_select($this->multi, $this->select_timeout);
			while (curl_multi_exec($this->multi, $running) == CURLM_CALL_MULTI_PERFORM) ; 

			while ($info = curl_multi_info_read($this->multi)) {
				if ($info["msg"] == CURLMSG_DONE) {
					$this->done[(int) $info["handle"]] = true;
				}
			}
		}
		
		return curl_multi_getcontent($curl);
    }


	/** Execute request or get its response
	* @param string request identifier
	* @param array array(string $url) for executing request, array() for getting response
	* @return mixed
	*/
    /*
	function __call($name, array $args) {
		if ($args) { // execute request
			list($url)          = $args;
			$curl               = curl_init($url);
			$this->curl[$name]  = $curl;
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			$return             = curl_multi_add_handle($this->multi, $curl);
			while (curl_multi_exec($this->multi, $running) == CURLM_CALL_MULTI_PERFORM) ;  
			return $return;
		}
		

		// get response
		if (!isset($this->curl[$name])) { // wrong identifier
			return false;
		}
		$curl = $this->curl[$name];
		while (!isset($this->done[(int) $curl])) {
			curl_multi_select($this->multi, $this->timeout);
			while (curl_multi_exec($this->multi, $running) == CURLM_CALL_MULTI_PERFORM) ; 

			while ($info = curl_multi_info_read($this->multi)) {
				if ($info["msg"] == CURLMSG_DONE) {
					$this->done[(int) $info["handle"]] = true;
				}
			}
		}
		
		return curl_multi_getcontent($curl);
	}
     */

}
