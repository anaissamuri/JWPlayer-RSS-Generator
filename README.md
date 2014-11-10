JWPlayer-RSS-Generator
======================

PHP- RSS Generator for JWPlayer RSS Playlist

======================

Usage
======================

1. HTTP GET Request

======================

1.1 generator=true

1.2 user=USERID

1.3 playlist=PLAYLISTID

======================

2. include in PHP and use the functions

======================

2.1 checkAlive("URL") - will return true if 200 code received

2.2 loadXML("URL") - will return an JSON Array

2.3 YouTubePlaylist("URL") - will return an Array of required data [video_id][video_title]

2.4 YoutubeUserUpload("URL") - will return an Array of required data [video_id][video_title]

2.5 EchoXML(Array()) - will generate JWPlayer RSS Playlist XML