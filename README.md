JWPlayer-RSS-Generator
======================

PHP- RSS Generator for JWPlayer RSS Playlist

======================

Usage
======================

1. HTTP GET Request
generator=true
user=USERID
playlist=PLAYLISTID

2. include in PHP and use the functions
	checkAlive("URL")
		will return true if 200 code received
	loadXML("URL")
		will return an JSON Array
	YouTubePlaylist("URL")
		will return an Array of required data [video_id][video_title]
	YoutubeUserUpload("URL")
		will return an Array of required data [video_id][video_title]
	EchoXML(Array())
		will generate JWPlayer RSS Playlist XML