<?php

/**
 * MemeBro
 *
 * This is an API for 
 * 
 * @author Sean Thomas Burke <http://www.seantburke.com/>
 */

class MemeBro
{
	public $gvmax;
	public $memegenerator;
	public $yourls;
	private $db;
	public $meme_name;
	public $meme_data;
	public $mogreet;
	public $success;
	public $short_url;
	public $response;
	
	
	public static $MEME_BRO_NUMBER = 	'6146363276';
	private static $FOR_HELP = 			'For help, send "+help"';
	private static $HELP_MESSAGE = 		'Use: "top text, bottom text #meme"';
	private static $FOR_EXAMPLE = 		'To see example, send "+example"';
	private static $EXAMPLE_MESSAGE = 	'Example: "Not sure if trolling, or just stupid #fry"';
	private static $FOR_POPULAR = 		'For popular memes list, send "+popular"';
	private static $FOR_TRENDING = 		'For trending memes list, send "+trend"';
	private static $FOR_NEW = 			'For new memes list, send "+new"';
	private static $NO_IMAGE = 			'No hashtag found';
	private static $NO_MEME = 			'No image found';
	private static $NO_TEXT = 			'No text found';
	private static $ERROR_MESSAGE = 	'An error occurred. Please try again';
	private static $SHOW_LISTS = 		'Try "+popular", "+trending", or "+new" for meme lists';
	private static $PROCESSING_MMS = 	'Processing MMS...';
	
	/**
	 * Constructor that takes in three objects 
	 *
	 * @author Sean Thomas Burke <http://www.seantburke.com>
	 * @param 
	 * @return 
	 */
	function __construct($gvmax, $memegenerator, $yourls, $db, $mogreet)
	{
		
		$this->meme_data = array('success' => false);
		$this->success = 0;
		$this->gvmax = $gvmax;
		$this->memegenerator = $memegenerator;
		$this->yourls = $yourls;
		$this->db = $db;
		$this->mogreet = $mogreet;
	}
	
	/**
	 * Send an SMS from 
	 *
	 * @author Sean Thomas Burke <http://www.seantburke.com>
	 * @param $api //Api from 
	 * @return JSON
	 */
	public function processSMS($text)
	{
		if($text)
		{
			if(strpos($text, '+exa') !== false)
			{
				$this->meme_name = '+exa';
				$output = $this::$EXAMPLE_MESSAGE;
			}
			if(strpos($text, '+hel') !== false)
			{
				$this->meme_name = '+hel';
				$output =  $this::$HELP_MESSAGE.' '.$this::$FOR_EXAMPLE;
			}
			if(strpos($text, '+pop') !== false)
			{
				$this->meme_name = '+pop';
				$output =  $this->memegenerator->popularMemes();
			}
			if(strpos($text, '+tre') !== false)
			{
				$this->meme_name = '+tre';
				$output =  $this->memegenerator->trendingMemes();
			}
			if(strpos($text, '+new') !== false)
			{
				$this->meme_name = '+new';
				$output =  $this->memegenerator->newMemes();
			}
			if($output)
			{
				return $output;
			}
			
			$image_split = explode('#', $text, 2);
			$text_split = explode(',', $image_split[0], 2);
			
			$top = $text_split[0];
			$bottom = $text_split[1];
			$image = $image_split[1];
			
			if(!$image)
			{
				$this->success = 0;
				$this->meme_name = 'fail';
				return $this::$FOR_HELP;
			}
			else
			{	
				$this->top = $top;
				$this->bottom = $bottom;
				$this->image = $image;
				$this->meme_data = $this->memegenerator->meme($this->top, $this->bottom, $this->image);
				if($this->meme_data['success'] == true)
				{
					if($top && $bottom && $image)
					{
						$this->success = 1;
					}
					else
					{
						$this->success = 0;
					}
					$this->meme_name = $this->meme_data['result']['displayName'];
					$img_url = $this->getImage($this->meme_data['result']);
					$message = $this::$PROCESSING_MMS.' '.$img_url;
					return $message;
				}
				else
				{
					$this->success = 0;
					$this->meme_name = 'no_meme';
					return $this::$NO_MEME.': #'.$this->image.'. '.$this::$SHOW_LISTS;
				}
			}
		}
		else
		{
			$this->meme_name = 'error';
			return $this::$FOR_HELP;	
		}
	}
	
	private function insert($text,$response,$from,$success,$meme_name,$status='',$url ='', $img_url ='')
	{
		$sql = 'INSERT 
				INTO 
				seantbur_query(
				`text` , 
				`response` ,
				`from` ,
				`success` ,
				`meme` ,
				`status`,
				`url`,
				`img_url`
				) 
				VALUES
				("'.$this->clean($text).'",
				 "'.$this->clean($response).'", 
				 "'.$this->clean($from).'", 
				 "'.$this->clean($success).'",
				 "'.$this->clean($meme_name).'",
				 "'.$this->clean($status).'",
				 "'.$this->clean($url).'",
				 "'.$this->clean($img_url).'"
				 )';
		return $this->db->query($sql);		
	}
	
	private function shorten($longurl)
	{
		$this->short_url = $this->yourls->shorten($longurl);
		return $this->short_url;
	}
	
	private function sendSMS($text)
	{
		$number = $this->gvmax->number;
		$this->gvmax->sms($number, $text);
		return $text;
	}
	
	private function sendMMS()
	{
		$number = $this->gvmax->number;
		
		if($this->meme_data['success'] == true)
		{
			if((strlen($number) <= 10) && (strlen($number) != 0))
			{
				$message = $this->short_url;
				return $this->mogreet->mms($number, $message, $this->meme_data['result']['instanceImageUrl']);
			}
		}
		return 'MMS not sent';
	}
	
	private function clean($text)
	{
		return mysql_escape_string($text);
	}
	
	public function help()
	{
		return 'help';
	}
	
	private function getImage($meme)
	{
		if($meme['instanceImageUrl'])
		{
			$output = $this->shorten($meme['instanceImageUrl']);
		}
		else
		{
			$this->success = 0;
			$output = $this::$NO_MEME.': #'.$this->image.'. '.$this::$SHOW_LISTS;
		}
		return  $output;
	}
	
	public function process()
	{
		$text = $this->gvmax->text;
		$response = $this->processSMS($text);
		$number = $this->gvmax->number;
		$this->insert($text, $response, $number, $this->success, $this->meme_name, $this->meme_data['success'], $this->short_url, $this->meme_data['result']['instanceImageUrl']);
		$this->sendMMS();
		//$this->insertResponse($this->db->lastInsertedId(),$response,$this::$MEME_BRO_NUMBER,$this->success,$this->meme,'receive');
		return $this->response = $this->sendSMS($response);
	}
}

?>