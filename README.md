# JWPlayer-RSS-Generator

PHP- RSS Generator for JWPlayer RSS Playlist
* Reference : http://support.jwplayer.com/customer/portal/articles/1406722-rss-playlist-embed 


## Usage

### HTTP GET Request

* generator=true

* user=USERID

* playlist=PLAYLISTID


### Include in PHP and use the functions

* checkAlive("URL") - will return true if 200 code received

* loadXML("URL") - will return an JSON Array

* YouTubePlaylist("URL") - will return an Array of required data [video_id][video_title]

* YoutubeUserUpload("URL") - will return an Array of required data [video_id][video_title]

* EchoXML(Array()) - will generate JWPlayer RSS Playlist XML