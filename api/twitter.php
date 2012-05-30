<?php
	class Twitter {
 
	/**
	 * Method to make twitter api call for the users timeline in XML
	 *
	 * @access	private
	 * @param	$twitter_id, $num_of_tweets
	 * @return 	$xml
	 */
	private function api_call($twitter_id, $num_of_tweets) {
		$c	=	curl_init();
 
		curl_setopt($c, CURLOPT_URL, "http://twitter.com/statuses/user_timeline/$twitter_id.xml?count=$num_of_tweets");
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 3);
		curl_setopt($c, CURLOPT_TIMEOUT, 5);
 
		$response		=	curl_exec($c);
		$response_info	=	curl_getinfo($c);
 
		curl_close($c);
 
		if (intval($response_info['http_code']) == 200) {
			$xml	=	new SimpleXMLElement($response);
 
			return $xml;
		} else {
			return false;
		}
	}
 
	/**
	 * Method to add hyperlink html tags to any urls, twitter ids or hashtags in tweet
	 *
	 * @access	private
	 * @param	$text
	 * @return 	$text
	 */
	private function process_links($text) {
		$text	=	utf8_decode($text);
		$text	=	preg_replace('@(https?://([-\w\.]+)+(d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', '<a href="$1">$1</a>',  $text);
		$text	=	preg_replace("#(^|[\n ])@([^ \"\t\n\r<]*)#ise", "'\\1<a href=\"http://www.twitter.com/\\2\" >@\\2</a>'", $text);  
		$text	=	preg_replace("#(^|[\n ])\#([^ \"\t\n\r<]*)#ise", "'\\1<a href=\"http://hashtags.org/search?query=\\2\" >#\\2</a>'", $text);
 
		return $text;
	}
 
	/**
	 * Main method to retrieve the tweets and return html for display
	 *
	 * @access	public
	 * @param	$twitter_id, $num_of_tweets, $timezone
	 * @return 	$result
	 */
	public function get_tweets($twitter_id, $num_of_tweets = 3, $timezone = "America/Denver") {
		$include_replies	=	false;
 
		date_default_timezone_set($timezone);
 
		// the html markup
		$cont_o		=	"<div id=\"tweets\">\n";
		$tweet_o	=	"<div class=\"status\">\n";
		$tweet_c	=	"</div>\n\n";
		$detail_o	=	"<div class=\"details\">\n";
		$detail_c	=	"</div>\n\n";
		$cont_c		=	"</div>\n";
 
		if ($twitter_xml = $this->api_call($twitter_id, $num_of_tweets)) {
			$result		=	$cont_o;
			foreach ($twitter_xml->status as $key => $status) {
				if ($include_replies == true | substr_count($status->text, "@") == 0 | strpos($status->text, "@") != 0) {
					$tweet	=	$this->process_links($status->text);
					$result	.=	$tweet_o . $tweet . $tweet_c . $detail_o . date('D jS M y H:i', strtotime($status->created_at)) . $detail_c;
				}
			}
			$result		.=	$cont_c;
		} else {
			$result		.=	$cont_o . $tweet_o . "Twitter seems to be unavailable at the moment." . $tweet_c . $cont_c;
		}
		return $result;
	}
}