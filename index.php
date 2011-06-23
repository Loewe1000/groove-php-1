<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
 
<head> 
<link rel="icon" href="images/favicon.ico"> 
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=VT323"> 
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Calligraffitti">
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Ubuntu:regular,bold&subset=Latin"> 
<link href="http://fonts.googleapis.com/css?family=Cabin+Sketch:bold" rel="stylesheet" type="text/css" > 
<META http-equiv="Content-Type" content="text/html; charset=utf-8"> 
<meta content="yes" name="apple-mobile-web-app-capable" /> 
<meta content="text/html;" http-equiv="Content-Type" /> 
<meta content="minimum-scale=1.0, width=device-width, maximum-scale=0.6667, user-scalable=no" name="viewport" /> 
<style type="text/css">
footer {
border-bottom-left-radius: 10px;
border-bottom-right-radius: 10px;
border: thin black solid;
}
#haupt {
border-left: thin black solid;
border-right: thin black solid;
}
header {
text-align:center;
border-top-left-radius: 10px;
border-top-right-radius: 10px;
border: thin black solid;
}
#title {
font-size:20 pt;
font-family: 'Cabin Sketch';font-family:VT323;
}
</style>
</head>
<body>
<header onclick="self.location.href = 'index.php'">
<img src="images/Grooveshark.png" height="128px" width="128px" /><br />
<div id="title">We got the groove, you got the shark!</div>
</header>
<section id="haupt"> 
<?php
/*
Ported Python Version to PHP by Check
<check96@gmail.com>
I've not Included my pictures this would be illegal.
To Setup read the README File!
Orginal:
A Grooveshark song downloader in python
by George Stephanos <gaf.stephanos@gmail.com>
*/


// HEX
class randomColorGenerator
    {
        private $hexColor = array("0","1","2","3","4","5","6","7","8","9","A","B","C","D","E","F");
        private $newColor = "";
        private $colorBag = array();
        function getColor()
        {
            $this->newColor=    $this->hexColor[$this->genRandom()].
                                $this->hexColor[$this->genRandom()].
                                $this->hexColor[$this->genRandom()].
                                $this->hexColor[$this->genRandom()].
                                $this->hexColor[$this->genRandom()].
                                $this->hexColor[$this->genRandom()];
                                
            if(!in_array($this->newColor,$this->colorBag))
            {
                $this->colorBag[] = $this->newColor;
                return $this->newColor;
            }
        }
        function genRandom()
        {
            srand((float) microtime() * 10000000);
            $random_col_keys = array_rand($this->hexColor, 2);
            return $random_col_keys[0];
        }
    }

// Generate UUID
function gen_uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}



 $header = get_headers("http://www.grooveshark.com");
 $id = substr($header[24],22,32);
 $secretKey = md5($id);


 // Strings 
 $Useragent = "Mozilla/5.0 (Windows NT 6.1; rv:2.0.1) Gecko/20100101 Firefox/4.0.1";
 $Referer = "http://grooveshark.com/JSQueue.swf?20110216.04";

// Header !
$h["session"] = $id;
$h["uuid"] = gen_uuid();
$h["privacy"] = 0;
$h["Country"]["CC1"] = "0";
$h["Country"]["CC2"] = "0";
$h["Country"]["CC3"] = "0";
$h["Country"]["CC4"] = "0";
$h["Country"]["ID"] = "1";



// Get a Token  /* Started here */
    $p["method"] = "getCommunicationToken";
    $p["parameters"]["secretKey"] = $secretKey;
    $p["header"] = $h;
    $p["header"]["client"] = "htmlshark";
    $p["header"]["clientRevision"] = "20101222.35";



$opts = array(
  'http'=>array(
    'method'=>"POST",
    'header'=>"User-Agent: $Useragent\r\n" .
              "Referer: $Referer\r\n".
	      "Cookie: PHPSESSID=$id\r\n".
	      "Content-Type: application/json\r\n",
   'content'=> "".json_encode($p).""
  )
);

$context = stream_context_create($opts);
$file = file_get_contents('http://grooveshark.com/more.php', false, $context);
$decode = json_decode($file);
// Got TOKEN
$token = $decode->result;
/* Ends here! */


//prepToken! fixed :)
function prep_token($method,$token)
{
$obj = new randomColorGenerator();
$hex2 = $obj -> getColor();
return $hex2.sha1($method.":".$token.":quitStealinMahShit:".$hex2);
}


if($_POST['search'] AND $_POST['search'] != NULL)
{
//get Search Results EX ! /* Starts here */
    $s["header"] = $h;
    $s["header"]["clientRevision"] = "20101222";
    $s["header"]["token"] = prep_token("getSearchResultsEx",$token);
    $s["header"]["client"] = "htmlshark";
    $s["method"] = "getSearchResultsEx";
    $s["parameters"]["type"] = "Songs";
    $s["parameters"]["query"] = $_POST['searchvalue'];



$option = array(
  'http'=>array(
    'method'=>"POST",
    'header'=>"User-Agent: $Useragent\r\n" .
              "Referer: http://grooveshark.com/\r\n".
	      "Cookie: PHPSESSID=$id\r\n".
	      "Content-Type: application/json\r\n",
   'content'=> "".json_encode($s).""
  )
);

$contexts = stream_context_create($option);
$file2 = file_get_contents('http://grooveshark.com/more.php?getSearchResultsEx', false, $contexts);
$file2 = json_decode($file2);

while ($i < 11)
{
$ArtistID = $file2->result->result[$i-1]->ArtistID;
$SongID = $file2->result->result[$i-1]->SongID;
$Cover = $file2->result->result[$i-1]->CoverArtFilename;
$Artist = $file2->result->result[$i-1]->ArtistName;
$Album = $file2->result->result[$i-1]->AlbumName;
$Song = $file2->result->result[$i-1]->SongName;
if ($Cover != NULL)
{
echo "<img style='float:left' src='http://beta.grooveshark.com/static/amazonart/$Cover' align='top' height='70px' width='70px' /><div style='padding:10px;'>Artist: $Artist <br/>
			     Album: $Album <br/>
			     Song: $Song
			     <form action='' method='POST'> 
			     <input type='hidden' name='download' value='1'>
			     <input type='hidden' name='SongID' value='$SongID'>		     
			     <input type='hidden' name='Song' value='$Song'>
			     <div style='align:right'><input type='Submit' value='Download'></div></div>";
}
elseif ($Song != NULL)
{
echo "<br /><div style='padding-left:10px;'><form action='' method='POST'>Artist: $Artist - Album: $Album - Song: $Song 
			     <input type='hidden' name='download' value='1'>
			     <input type='hidden' name='SongID' value='$SongID'>
			     <input type='hidden' name='DownloadName' value='$Artist - $Album - $Song'>
			     <div style=';align:right'><input  type='Submit' value='Download'></div></form></div><br />";
}
$i++;
}

}
/* Ends Here */



/* Starts here */
elseif($_POST['download'])
{
    $ps["parameters"]["mobile"] = "false";
    $ps["parameters"]["prefetch"] = "false";
    $ps["parameters"]["songID"] = $_POST['SongID'];
    $ps["parameters"]["country"] = $h["Country"];
    $ps["header"] = $h;
    $ps["header"]["client"] = "jsqueue";
    $ps["header"]["clientRevision"] = "20101012.37";
    $ps["header"]["token"] = prep_token("getStreamKeyFromSongIDEx",$token);
    $ps["method"] = "getStreamKeyFromSongIDEx";

$optst = array(
  'http'=>array(
    'method'=>"POST",
    'header'=>"User-Agent: $Useragent\r\n" .
              "Referer: $Referer\r\n".
	      "Cookie: PHPSESSID=$id\r\n".
	      "Content-Type: application/json\r\n",
   'content'=> "".json_encode($ps).""
  )
);

$contextss = stream_context_create($optst);
$file4 = file_get_contents('http://grooveshark.com/more.php?getStreamKeyFromSongIDEx', false, $contextss);

mkdir("Songs", 0777);
$Name = $_POST['DownloadName'];
$ip = $_SERVER['SERVER_ADDR'];
// This must not be Curl, but it's easier and cooler with curl :)
	$keys = json_decode($file4);
	$streamKey = $keys->result->streamKey;
	$ip = $keys->result->ip;

  	$ch = curl_init();
	$fp = fopen('Songs/'.$Name.'.mp3', "w");
	curl_setopt($ch, CURLOPT_FILE, $fp);  	
	curl_setopt($ch,CURLOPT_URL,'http://'.$ip.'/stream.php');
  	curl_setopt($ch,CURLOPT_POST, true);
  	curl_setopt($ch,CURLOPT_POSTFIELDS, "streamKey=$streamKey");
  	curl_exec($ch);
  	curl_close($ch);
	fclose($fp);

// Ok, Download Complete, let's creat a simple m3u
$file = 'Songs/my.m3u';
$current = file_get_contents($file);
$current .= "http://".$ip."/Songs/".$Name.".mp3 \n";
file_put_contents($file, $current);
echo "<center>
<div style='padding-bottom:10px;font-family:Ubuntu;font-size:20pt;'>
How to Download?
<br />
<div style='font-family:Ubuntu; font-size:12pt;'>
As a Stream:<br />
<audio controls='controls'>
<source src='Songs/".$Name.".mp3' type='audio/mpeg' /> 
</audio>
</div>

<div style='font-family:Ubuntu; font-size:12pt;'>
As a Link: <br />
<a href='http://".$ip."/Songs/".$Name.".mp3'>Download</a>
</div>

<div style='font-family:Ubuntu; font-size:12pt;'>
Or a m3u with all Songs, you've downloaded: <br />
<a href='http://".$ip."/Songs/my.m3u'>Download</a>
</div>

</div>
</center>";
/* Ends Here */
}
elseif ($_GET['rm'] == 1)
{
echo "<div>
<div style='font-family:Ubuntu;font-size:30pt;'><center>Delete:</center></div>
";
if ($handle = opendir('Songs')) {
while (false !== ($file = readdir($handle))) {
if ($file != "." && $file != "..") {
$gro = filesize('Songs/'.$file);
echo "$file, $gro<br />";
}
}
closedir($handle);
}
if($_GET['g'] == 1)
{
$File = $_GET['File'];
$unlink = unlink('Songs/'.$File);
}
else
{
echo '
<form action="" method="GET">
File ? <input type="text" name="File">
<input type="hidden" value="1" name="rm">
<input type="hidden" value="1" name="g"> <br />
<input type="Submit" value="Submit">
';
}
echo "</div>";
}
elseif ($_POST['search'] == false OR $_POST['search'] == NULL)
{
echo '<form method="POST" action="">
<!-- Just for fun -->
<center style="padding:40px;"><div style="padding-bottom:5px;"><input type="text" name="searchvalue" x-webkit-speech />
<input type="hidden" name="search" value="1">
<input type="Submit" value="Search !"></div><br />
<a href="?rm=1">DELETE:</a>
</center>';
}
?>
</section>
<footer>
<p><small style="padding-left:3px;font-family:Calligraffitti; font-size:12pt;">
Coded by Check in PHP. Created by George Stephanos.
<a href="http://www.groove-dl.co.cc/"> JTR's Grooveshark Downloader </a>
</small>
</p>
</footer>
</body>
</html>
