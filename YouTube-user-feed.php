<?php
/***********************************************************
Author			: Anais Samuri
Date			: 10 November 2014
Version 		: 1.0
************************************************************
Function
----------------------
checkAlive		: Checking the url provided is available,
				  will return true if the code 200 received
************************************************************/
function checkAlive($url, $timeout = 10) {
  $ch = curl_init($url);
  // Set request options
  curl_setopt_array($ch, array(
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_NOBODY => true,
    CURLOPT_TIMEOUT => $timeout,
    CURLOPT_USERAGENT => "page-check/1.0" 
  ));
  // Execute request
  curl_exec($ch);
  // Check if an error occurred
  if(curl_errno($ch)) {
    curl_close($ch);
    return false;
  }
  // Get HTTP response code
  $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);
  // Page is alive if 200 OK is received
  return $code === 200;
}
/***********************************************************
loadXML			: Read XML,
				  will return required data in JSON Array
************************************************************/
function loadXML($thisURL) {
	// Set
	$array = array();
	// Default data if unsucces to acquire from XML
	$xmlStatus = "NOXML";	
	// XML path	
	//$thisURL="http://gdata.youtube.com/feeds/api/users/myCQTV/uploads?v=2";
	// Check if XML exist
	$isAlive = checkAlive($thisURL);
	// Get channel's info from XML
	if ($isAlive) {
		$xmlStatus = "N";
		$i=0;
		$xml = simplexml_load_file($thisURL);
		//print_r($xml);
		$json = json_encode($xml);
		$array = json_decode($json,TRUE);
	}
	// Return channel's info
	return $array;
}
/***********************************************************
YouTubePlayList	: Extract required data from JSON Array,
				  will return required data in Array
************************************************************/
function YouTubePlayList($thisURL) {
	$getListVideo = loadXML($thisURL);
	$video = array(array(),array());
	for ($i=0;$i<count($getListVideo['entry']);$i++) {
		$ex = explode("/",$getListVideo['entry'][$i]['link'][1]['@attributes']['href']);
		$j = count($ex); 
		if ($j) {
			$video[$i][0] = $ex[$j-2];
		}
		$video[$i][1] = $getListVideo['entry'][$i]['title'];
	}
	return $video;
}
/***********************************************************
YouTubeUserUpload : Extract required data from JSON Array,
				  will return required data in Array
************************************************************/
function YouTubeUserUpload($thisURL) {
	$getListVideo = loadXML($thisURL);
	$video = array(array(),array());
	for ($i=0;$i<count($getListVideo['entry']);$i++) {
		$ex = explode("/",$getListVideo['entry'][$i]['link'][1]['@attributes']['href']);
		$j = count($ex); 
		if ($j) {
			$video[$i][0] = $ex[$j-2];
		}
		$video[$i][1] = $getListVideo['entry'][$i]['title'];
	}
	return $video;
}
/***********************************************************
EchoXML			: Generates the JWPlayer RSS Playlist XML
************************************************************/
function EchoXML ($vid) {
	//print_r($vid);
	echo 
"<?xml version=\"1.0\" encoding=\"UTF-8\" ?>
<rss version=\"2.0\" xmlns:media=\"http://search.yahoo.com/mrss/\" xmlns:jwplayer=\"http://developer.longtailvideo.com/trac/wiki/FlashFormats\">
<channel>
	";
	for ($i=0;$i<count($vid);$i++) {
		if ($vid[$i][1] != "Deleted video") {
			echo 
	"<item>
    	<title>".$vid[$i][1]."</title>
    	<media:group>
        	<media:content url=\"https://www.youtube.com/watch?v=".$vid[$i][0]."\"></media:content>
        	<media:thumbnail url=\"https://i.ytimg.com/vi/".$vid[$i][0]."/0.jpg\" />
    	</media:group>
	</item>
	";
		}
	}
	echo 
"</channel>
</rss>
	";
}
/************************************************************
Set Default
*************************************************************/
$yt_UserID = "";
$yt_PlayListID = "";
$yt_Generator = false;
$thisURL = "";
$thisXML = "";
/************************************************************
HTTP GET for Quick Generation
-----------------------------
yt_user			: YouTube User ID
yt_playlist		: Youtube Playlist ID
yt_generator	: Generate the JWPlayer RSS XML
*************************************************************/
if (isset($_GET['user'])) { $yt_UserID = $_GET['user']; }
if (isset($_GET['playlist'])) { $yt_PlayListID = $_GET['playlist']; }
if (isset($_GET['generator'])) { $yt_Generator = $_GET['generator']; }
/************************************************************
Based on Youtube API 2.0
To request a feed of all videos uploaded by another user, send a GET request to the following URL. This request does not require authentication.
http://gdata.youtube.com/feeds/api/users/userId/uploads

To request a feed of single playlists, send a GET request to the following URL. This request does not require user authorization.
http://gdata.youtube.com/feeds/api/playlists/PlayListID?v=2
*************************************************************/
if ($yt_Generator) {	
	if (!empty($yt_UserID)) {
		$thisURL = "http://gdata.youtube.com/feeds/api/users/".$yt_UserID."/uploads";
		$thisXML = "user";
		$vid = YouTubeUserUpload($thisURL);
		EchoXML ($vid);
	} else if (!empty($yt_PlayListID)) {
		$thisURL = "http://gdata.youtube.com/feeds/api/playlists/".$yt_PlayListID."?v=2";
		$thisXML = "playlist";
		$vid = YouTubePlaylist($thisURL);
		EchoXML ($vid);
	}
}
?>