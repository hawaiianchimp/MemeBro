<?php

/**
 * Mogreet API
 *
 * This is an API for Mogreet
 * 
 * @author Sean Thomas Burke <http://www.seantburke.com/>
 */

class Mogreet
{
	private $client_id; 	//this is the client_id token from Mogreet
	private $token;
	private $sms;
	private $mms;
	
	public $result;
	
	/**
	 * Constructor that takes in the api token as a parameter
	 * Forward your mogreet HTTP request to a page with this object
	 *
	 * @author Sean Thomas Burke <http://www.seantburke.com>
	 * @param $api //Api from Mogreet
	 * @return JSON
	 */
	function __construct($client_id, $token, $sms, $mms)
	{
		$this->client_id = $client_id;
		$this->token = $token;
		$this->sms = $sms;
		$this->mms = $mms;
	}
	
	/**
	 * Send an SMS
	 *
	 * @author Sean Thomas Burke <http://www.seantburke.com>
	 * @param $number, $text //Api from 
	 * @return JSON
	 */
	public function sms($to, $text)
	{
		//TODO validate number
		
		//if the $number is an array, then send it to the group
		if(is_array($number))
		{
			return $this->groupSMS($number, $text);
		}
		
		//REST URL for sending sms
		$url = 'https://api.mogreet.com/moms/transaction.send';
		
		
		//parameters for call
		$fields = array(
		            'client_id'=>urlencode($this->client_id),
		            'token'=>urlencode($this->token),
		            'campaign_id'=>urlencode($this->sms),
		            'to'=>urlencode($to),
		            'from'=>urlencode($this->from),
		            'message'=>urlencode($text),
		            'content_id'=>urlencode($content_id),
		            'content_url'=>urlencode($content_url),
		            'to_name'=>urlencode($to_name),
		            'from_name'=>urlencode($from_name),
		            'callback'=>urlencode($callback),
		        );
		        
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
		
		$this->result = json_decode($result, true);
		
		//return the GVMax return {$number: ok}
		return $this->result;
	}
	
	
	/**
	 * Send an SMS
	 *
	 * @author Sean Thomas Burke <http://www.seantburke.com>
	 * @param $number, $text //Api from 
	 * @return JSON
	 */
	public function mms($to, $text, $content_url)
	{
		//TODO validate number
		
		//if the $number is an array, then send it to the group
		if(is_array($to))
		{
			return $this->groupMMS($to, $content_url);
		}
		
		//REST URL for sending sms
		$url = 'https://api.mogreet.com/moms/transaction.send';
		
		//parameters for call
		$fields = array(
		            'client_id'=>urlencode($this->client_id),
		            'token'=>urlencode($this->token),
		            'campaign_id'=>urlencode($this->mms),
		            'to'=>urlencode($to),
		            'from'=>urlencode($this->from),
		            'message'=>urlencode($text),
		            'content_id'=>urlencode($content_id),
		            'content_url'=>urlencode($content_url),
		            'to_name'=>urlencode($to_name),
		            'from_name'=>urlencode($from_name),
		            'callback'=>urlencode($callback),
		        );
		        
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
		
		$this->result = 'Sent to '.$to.'. Message: '.$content_url.' <br> '.$result.'<br>'.urlencode($content_url);
		
		//return the GVMax return {$number: ok}
		return $this->result;
	}
	
	
	/**
	 * will send an SMS to a group
	 *
	 * @author Sean Thomas Burke <http://www.seantburke.com>
	 * @param $array_numbers, $text //Api from 
	 * @return JSON
	 */
	private function groupSMS($array_numbers, $text)
	{
		foreach($array_numbers as $number)
		{
			$result[$i++] = $this->sms($number,$text);
		}
		$this->result = $result;
		return $this->result;
	}
	
	/**
	 * call method will place a call to a phone
	 *
	 * @author Sean Thomas Burke <http://www.seantburke.com>
	 * @param $number //
	 * @return JSON
	 */
	 
	 public function call($number)
	 {
	 //TODO Implement this method
	 }
}

?>