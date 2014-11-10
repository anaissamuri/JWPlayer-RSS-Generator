<?php 
/* checkAlive use for checking the url is available or not */
/* will return true if the code 200 received               */
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
function loadXML() {
	// Set
	$listVideo = array();
	$xmlStatus = "NOXML";
	// Default data if unsucces to acquire from XML
	// XML path
	$thisURL='http://www.youtube.com/rss/user/myCQTV/feed.rss';
	// Check if XML exist
	$isAlive = checkAlive($thisURL);
	// Get channel's info from XML
	if ($isAlive) {
		$xmlStatus = "N";
		$i=0;
		$rss=simplexml_load_file($thisURL);
		if ($rss) {
			foreach($rss->channel->item as $item){
				$xmlStatus = "Y";
				$listVideo[$i][0] = trim($item->guid);
				$listVideo[$i][1] = trim($item->title);
				$listVideo[$i][2] = trim($item->description);
				$listVideo[$i][3] = trim($item->link);
				$listVideo[$i][4] = $xmlStatus;
				$i=$i+1;
			}
		}
	}
	// Return channel's info
	return $listVideo;
}
$getListVideo = loadXML();
echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>
<rss version=\"2.0\" xmlns:media=\"http://search.yahoo.com/mrss/\" xmlns:jwplayer=\"http://developer.longtailvideo.com/trac/wiki/FlashFormats\">
<channel>
";
for ($i=0;$i<count($getListVideo);$i++) {
	$ex = explode(":",$getListVideo[$i][0]);
	$j = count($ex);
	$video_id = $ex[$j-1];
echo "	
	<item>
    	<title>".$getListVideo[$i][1]."</title>
    	<media:group>
        	<media:content url=\"https://www.youtube.com/watch?v=".$video_id."\"></media:content>
        	<media:thumbnail url=\"https://i.ytimg.com/vi/".$video_id."/0.jpg\" />
    	</media:group>
	</item>
	";
}
echo "
</channel>
</rss>
";
?>