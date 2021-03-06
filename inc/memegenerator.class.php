<?php

/**
 * MemeGenerator.net API
 *
 * This is an API for memegenerator.net
 * 
 * @author Sean Thomas Burke <http://www.seantburke.com/>
 */

class MemeGenerator
{
	private $username; 	//memegenearator.net username
	private $password;	//memegenearator.net password
	public $result;		//array of api call
	
	/**
	 * Constructor that takes in the api token as a parameter
	 *
	 * @author Sean Thomas Burke <http://www.seantburke.com>
	 * @param $username and $password from memegenerator.net
	 * @return JSON
	 */
	function __construct($username, $password)
	{
		$this->username = $username;
		$this->password = $password;
	}
	
	/**
	 * Create the meme
	 *
	 * @author Sean Thomas Burke <http://www.seantburke.com>
	 * @param $top, $bottom, $img // top text, bottom text, name of meme
	 * @return JSON
	 */
	public function meme($top, $bottom, $img)
	{
		if($meme = $this->search($img))
		{
			
			$url = 'http://version1.api.memegenerator.net/Instance_Create';
	
			$fields = array(
			            'username'=>urlencode($this->username),
			            'password'=>urlencode($this->password),
			            'languageCode'=>'en',
			            'generatorID'=>urlencode($meme['generatorID']),
			            'imageId'=>urlencode($meme['imageId']),
			            'text0'=>urlencode(stripslashes(stripcslashes($top))),
			            'text1'=>urlencode(stripslashes(stripcslashes($bottom)))
			        );
			$this->result = $this->api($url, $fields);
			return $this->result;
		}
		else
		{
			$this->result = array('success' => 'false', 'error' => 'No Image Found');
			return $this->result;
		}
	}
	
	/**
	 * Make an API call
	 *
	 * @author Sean Thomas Burke <http://www.seantburke.com>
	 * @param $url, $fields // the url of the call, and the fields as an array
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
		
		//return the MemeGenerator result
		return json_decode($result, true);
	}
	
	
	/**
	 * returns popular memes
	 *
	 * @author Sean Thomas Burke <http://www.seantburke.com>
	 * @param null
	 * @return JSON
	 */
	public function popularMemes()
	{
		$url = 'http://version1.api.memegenerator.net/Generators_Select_ByPopular';
		
		$fields = array(
		            'pageIndex'=>0,
		            'pageSize'=>12,
		            'days'=>7,
		        );
		$array = $this->api($url, $fields);
		$list = array();
		foreach($array['result'] as $selector)
		{
			array_push($list, '#'.$selector['urlName']);
		}
		
		$result = str_split(implode(', ', $list), 160 - 3);
		return $result[0].'...';
	}
	
	/**
	 * returns trending memes
	 *
	 * @author Sean Thomas Burke <http://www.seantburke.com>
	 * @param null
	 * @return JSON
	 */
	public function trendingMemes()
	{
		$url = 'http://version1.api.memegenerator.net/Generators_Select_ByTrending';
		
		$fields = array(
			            'pageIndex'=>0,
			            'pageSize'=>12,
			            'days'=>7,
			        );
			$array = $this->api($url, $fields);
			$list = array();
			foreach($array['result'] as $selector)
			{
				array_push($list, '#'.$selector['urlName']);
			}
			$result = str_split(implode(', ', $list), 160 - 3);
			return $result[0].'...';
		}
		
	/**
	 * returns new memes
	 *
	 * @author Sean Thomas Burke <http://www.seantburke.com>
	 * @param null
	 * @return JSON
	 */
	public function newMemes()
	{
		$url = 'http://version1.api.memegenerator.net/Generators_Select_ByNew';
		
		$fields = array(
			            'pageIndex'=>0,
			            'pageSize'=>12,
			            'days'=>7,
			        );
			$array = $this->api($url, $fields);
			$list = array();
			foreach($array['result'] as $selector)
			{
				array_push($list, '#'.$selector['urlName']);
			}
			
			$result = str_split(implode(', ', $list), 160 - 3);
			return $result[0].'...';
	}
	
	/**
	 * searches memes
	 *
	 * @author Sean Thomas Burke <http://www.seantburke.com>
	 * @param search query
	 * @return JSON
	 */
	public function searchMemes($q)
	{
		$clean_q = $this->clean($q);
		$url = 'http://version1.api.memegenerator.net/Generators_Search';
		
		$fields = array(
						'q'=>urlencode($clean_q),
			            'pageIndex'=>0,
			            'pageSize'=>12,
			            'days'=>7,
			        );
			$array = $this->api($url, $fields);
			$list = array();
			foreach($array['result'] as $selector)
			{
				array_push($list, '#'.$selector['urlName']);
			}
			$result = implode(', ', $list);
			if($result)
			{
				return $result;
			}
			else
			{
				return 'No results for '.$q;
			}			
	}
		
	/**
	 * search for a meme
	 *
	 * @author Sean Thomas Burke <http://www.seantburke.com>
	 * @param $q, // $query to search
	 * @return JSON
	 */
	 
	public function search($q)
	{
		$clean_q = $this->clean($q);
		$url = 'http://version1.api.memegenerator.net/Generators_Search';
		
		$fields = array(
					'q'=>urlencode($clean_q),
		            'pageIndex'=>0,
		            'pageSize'=>1,
		        );
		$result_array = $this->api($url, $fields);
		if(count($result_array['result']) > 0)
		{
			$ra = array('http://cdn.memegenerator.net/images/400x/', '.jpg');
			$result_array['result'][0]['imageId'] = str_ireplace($ra, '', $result_array['result'][0]['imageUrl']);
			return $result_array['result'][0];
		}
		else
		{
			if($q = $this->simplify($q))
			{
				return $this->search($q);
			}
			else
			{
				return 0;
			}
		}
	}
	
	
	
	/**
	 * Cleans query for the user
	 *
	 * @author Sean Thomas Burke <http://www.seantburke.com>
	 * @param $q , query to clean
	 * @return JSON
	 */
	private function clean($q)
	{
		$items = array('-', '_', '+');
		$clean_q = str_replace($items, ' ', $q);
		return $clean_q;
	}
	
	/**
	 * Shortens the query
	 *
	 * @author Sean Thomas Burke <http://www.seantburke.com>
	 * @param $q , query to clean
	 * @return JSON
	 */
	private function simplify($q)
	{
		$clean_q = $this->clean($q);
		$words = explode(' ', $clean_q);
		array_pop($words);
		return implode(' ', $words);
	}
}

?>