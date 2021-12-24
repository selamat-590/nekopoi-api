<?php

/**
* NekoPoi Grabbing
* NekoPoi Hentai List
* ---------------------------------
* API Name  : NekoPoi Hentai List
* API Ver 	: v1
* Author  	: MuhBayu
* Project 	: http://github.com/MuhBayu
*----------------------------------
*/
namespace PoiPoi;
require_once('lib/err_handling.php'); // handling error...
require_once('lib/simple_html_dom.php'); //library untuk grabNya

class NekoPoi_
{
	protected $_NEKO_HENTAI = '/category/hentai/';
	function __construct() {
		# code...
	}
	public function setNekoDomain($domain) {
		(!filter_var($domain, FILTER_VALIDATE_URL)) ? $domain =  rtrim('http://'.$domain, '/') : $domain = rtrim($domain, '/');
		$this->_NEKO_HENTAI = $domain.$this->_NEKO_HENTAI;
	}
	private function cURL($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_REFERER, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		$result 	= curl_exec($ch);
		$http_status 	= curl_getinfo($ch, CURLINFO_HTTP_CODE);
  		$curl_errno  	= curl_errno($ch);
		curl_close($ch);
  		if($curl_errno) {
  			return Self::show_err($curl_errno);
  		} elseif($http_status == 404) {
  			return false;
  		}
		return $result;
	}
	public function hentai_list($page = 1) {
		$startScriptTime = microtime(TRUE);
		if($page !== NULL && $page > 1) {
			$this->_NEKO_HENTAI = $this->_NEKO_HENTAI . 'page/' . $page . '/';
		}
		$output['success'] 	= true;
		$output['load_time'] 	= 0;
		$output['page']	 	= $page;
		$output['category'] 	= "hentai";

		if($data = Self::cURL($this->_NEKO_HENTAI)) {
			$html 	= str_get_html($data);
			$result = $html->find('div[class=result]', 0);
			$hen 	= $result->find('ul li');
			foreach ($hen as $key => $value) {
				if ($key >= 10) break;
				$json['title'] = $value->find('h2 a', 0)->plaintext;
				$json['link'] = $value->find('h2 a', 0)->href;
				$json['img'] = $value->find('div[class=limitnjg] img', 0)->src;
				$json['sinopsis'] = $value->find('div[class=desc] p', 1)->plaintext;
				$json['size'] = str_replace('Size : ', '', $value->find('div[class=desc] p', 6)->plaintext);
				$output['data'][] = $json;
			}
			$endScriptTime		= microtime(TRUE);
			$output['load_time'] 	= round(($endScriptTime - $startScriptTime), 4);
		} else {
			return Self::show_err('404 Not Found');
		}
			return $output;
	}
	public function show_err($err_no = '') {
		$json['success'] = false;
		$json['message'] = "Error [".$err_no."]";
		return $json;
	}
}
?>
