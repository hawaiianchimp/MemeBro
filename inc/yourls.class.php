<?php

/**
 * Yourls Your Own URL Shortener API
 *
 * This is an API for Yourls
 * 
 * 
 * @author Sean Thomas Burke <http://www.seantburke.com/>
 */

class Yourls
{
	private $signature;	//signature of yourls user
	public $result;
	
	/**
	 * Constructor that takes in the api token as a parameter
	 *
	 * @author Sean Thomas Burke <http://www.seantburke.com>
	 * @param $site_url, $signature // your site, and it's signature; get from $site_url/admin
	 * 
	 */
	function __construct($site_url, $signature)
	{
		$this->signature = $signature;
		$this->site_url = $site_url;
	}
	
	/**
	 * Shorten a given url
	 *
	 * @author Sean Thomas Burke <http://www.seantburke.com>
	 * @param $longurl, $keyword // a long url to shorten and an optional keyword for the URL
	 * @return JSON
	 */
	public function shorten($longurl, $keyword = '')
	{	
		$url = $this->site_url.'/yourls-api.php';
		
		$fields = array(
		            'signature'=>$this->signature,
		            'action'=>'shorturl',
		            'url'=>urlencode($longurl),
		            'keyword'=>urlencode($keyword),
		            'format'=>'json'
		        );
		$result = $this->api($url, $fields);
		$this->result = $result;
		return $result['shorturl'];
	}
	
	/**
	 * API Call makes a call using curl
	 *
	 * @author Sean Thomas Burke <http://www.seantburke.com>
	 * @param $url, $fields // the API URL and fields as an array
	 * @return JSON
	 */
	private function api($url, $fields)
	{
		if(!$url)
		{
			die('No URL Specified');
		}
		        
		//url-ify the data for the POST
		foreach($fields as $key=>$value) 
		{
		 	$fields_string .= $key.'='.$value.'&'; 
		}
		rtrim($fields_string,'&');
		
		$ch = curl_init();
		
		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_POST,count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
		$result = curl_exec($ch);
		curl_close($ch);
		
		//return the Yourls return
		return json_decode($result, true);
	}
}

?>