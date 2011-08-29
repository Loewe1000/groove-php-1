<?php
session_start();
if ($_POST['settings'] == 'apply') {
$_SESSION['results'] = $_POST['results'];
}
$results = $_SESSION['results'];
if ($results == '') {
$results = 10;
}
$ID = $_POST['SongID'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
 
<head> 
<link rel="icon" href="images/favicon.ico"> 
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=VT323"> 
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Calligraffitti">
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Ubuntu:regular,bold&subset=Latin"> 
<link href="http://fonts.googleapis.com/css?family=Cabin+Sketch:bold" rel="stylesheet" type="text/css" > 
<link href="css/style.css" rel="stylesheet" media="screen" type="text/css" />
<META http-equiv="Content-Type" content="text/html; charset=utf-8"> 
<meta content="yes" name="apple-mobile-web-app-capable" /> 
<meta content="text/html;" http-equiv="Content-Type" /> 
<meta content="minimum-scale=1.0, width=device-width, maximum-scale=0.6667, user-scalable=no" name="viewport" /> 
<title>Groove-PHP</title>
</head>
<body>
<div id="topbar" onclick="self.location.href = 'index.php'">
<?php
if ($_GET['settings'] == '1' or $ID != '' or $_GET['rm'] == '1') {
echo "<div id='leftnav'><a href='index.php'><img alt='home' src='images/home.png' /></a></div>";
}
if ($_GET['rm'] != '1' and $_GET['settings'] != '1' and $ID == '') {
echo "<div id='leftbutton'><a href='index.php?rm=1'>songs</a></div>";
}
if ($_GET['settings'] != '1'){
echo "<div id='rightbutton'><a href='index.php?settings=1'>settings</a></div>";
}
?>
</div>
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
	    $this->newColor=	$this->hexColor[$this->genRandom()].
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



// Get a Token	/* Started here */
    $p["method"] = "getCommunicationToken";
    $p["parameters"]["secretKey"] = $secretKey;
    $p["header"] = $h;
    $p["header"]["client"] = "htmlshark";
    $p["header"]["clientRevision"] = "20110606";



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
function prep_token($method,$token,$with)
{
$obj = new randomColorGenerator();
$hex2 = $obj -> getColor();
return $hex2.sha1($method.":".$token.$with.$hex2);
}


if($_POST['search'] AND $_POST['search'] != NULL)
{
$text = htmlentities(utf8_decode($_POST['searchvalue']));
echo "<div class='searchbox'>
<form action='' method='post'>
<fieldset><input id='search' name='searchvalue' value='$text' placeholder='search' type='text' x-webkit-speech/>
<input id='submit' type='hidden' /></fieldset>
<input type='hidden' name='search' value='1'>
</form>
</div>
<body class='list'>
<div id='content'>
<ul><li class='title'>results</li>";

//get Search Results EX ! /* Starts here */
    $s["header"] = $h;
    $s["header"]["clientRevision"] = "20110606";
    $s["header"]["token"] = prep_token("getSearchResultsEx",$token,":backToTheScienceLab:");
    $s["header"]["client"] = "htmlshark";
    $s["method"] = "getSearchResultsEx";
    $s["parameters"]["type"] = "Songs";
    $s["parameters"]["query"] = "".$_POST['searchvalue']."";



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
while ($i <= $results)
{
$ArtistID = $file2->result->result[$i-1]->ArtistID;
$SongID = $file2->result->result[$i-1]->SongID;
$Cover = $file2->result->result[$i-1]->CoverArtFilename;
$Artist = $file2->result->result[$i-1]->ArtistName;
$Album = $file2->result->result[$i-1]->AlbumName;
$Song = $file2->result->result[$i-1]->SongName;
if ($Song != NULL)
{
if ($Cover != NULL)
{
$Cover = "http://beta.grooveshark.com/static/amazonart/$Cover";
}
else {
$Cover = "images/nocover.jpg";
}
echo"
<li class='withimage'>
<a class='noeffect' href='javascript:document.getElementById(\"$SongID\").style.visibility = \"visible\";document.getElementById(\"$SongID\").style.display = \"block\"'>
<img src='$Cover'/>
<span class='name'>$Song - $Artist</span>
<span class='comment'>$Album</span>Test
<span class='arrow'></span></a></li>
<div id='$SongID' style='visibility:hidden;display:none;'>
<a href='javascript:document.getElementById(\"$SongID\").style.visibility = \"hidden\";document.getElementById(\"$SongID\").style.display = \"none\"'>
<div style='background:rgba(0,0,0,0.9);position:absolute;width:100%;height:91px;margin-top:-91px;z-index:1;'>
<center>
<form action='' method='POST'> 
<input type='hidden' name='download' value='1'>
<input type='hidden' name='DownloadName' value='$Artist - $Album - $Song'>
<input type='hidden' name='SongID' value='$SongID'>			
<input type='hidden' name='Song' value='$Song'>
<input type='submit' style='margin-top:20px;' value='Download'>
</form>
<form action='' method='POST'> 
<input type='hidden' name='ArtistSearch' value='1'>
<input type='hidden' name='ArtistID' value='$ArtistID'>		     
<input type='hidden' name='Artist' value='$Artist'>
<input type='Submit' value='More Songs from $Artist'></form>
</center>
</div>
</a>
</div>
";
}
$i++;
}
echo "</ul></body>";
}
/* Ends Here */



/* Starts here */
elseif($_POST['download'] and $_POST['SongID'] != NULL)
{
    $ps["parameters"]["mobile"] = "false";
    $ps["parameters"]["prefetch"] = "false";
    $ps["parameters"]["songID"] = $_POST['SongID'];
    $ps["parameters"]["country"] = $h["Country"];
    $ps["header"] = $h;
    $ps["header"]["client"] = "jsqueue";
    $ps["header"]["clientRevision"] = "20110606";
    $ps["header"]["token"] = prep_token("getStreamKeyFromSongIDEx",$token,":bewareOfBearsharktopus:");
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

if (is_dir("Songs") == false)
{
mkdir("Songs", 0777);
}
	$Name = "".$_POST['DownloadName']."";
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


$ip = $_SERVER['SERVER_ADDR'];
echo "
<div id='content'>
<ul class='pageitem'>
<li class='textbox'>
<b>$Name</b>
<span class='header'>stream</span>
<audio controls='controls'><source src='Songs/".$Name.".mp3' type='audio/mpeg' /> </audio>
<span class='header'>download</span>
<a href='Songs/".$Name.".mp3'>".$Name.".mp3</a>
</li>
</ul>
</div>";
/* Ends Here */
}
elseif ($_GET['rm'] == 1)
{
$ip = $_SERVER['SERVER_ADDR'];
echo "
<div id='content'>
<ul class='pageitem'>
<li class='textbox'>";
if ($handle = opendir('Songs')) {
while (false !== ($file = readdir($handle))) {
if ($file != "." && $file != "..") {
echo "<b><a style='float:left;' href='Songs/".$file."'>$file</a>
<form action='' method='Post'>
<input type='hidden' value='$file' name='File'>
<input type='hidden' value='1' name='rm'>
<input type='hidden' value='1' name='g'>
<input type='image' style='float:left;margin:0;' name='bubmit' src='images/delete.png'>
</b><br style='both:clear;'/>";
}
}
closedir($handle);
}
echo "</li>
</ul>
</div>";
if($_POST['g'] == 1)
{
$File = $_POST['File'];
$unlink = unlink('Songs/'.$File);
}
}
elseif($_POST['ArtistSearch'] and $_POST['ArtistID'] != NULL)
{
    $p["parameters"]["artistID"] = $_POST['ArtistID'];
    $p["parameters"]["isVerifiedOrPopular"] = 'isVerified';
    $p["header"] = $h;
    $p["header"]["client"] = "htmlshark";
    $p["header"]["clientRevision"] = "20110606";
    $p["header"]["token"] = prep_token("artistGetSongsEx",$token,":backToTheScienceLab:");
    $p["method"] = "artistGetSongsEx";

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

$showartist = $_POST['ArtistSearch'];
echo "<div class='searchbox'>
<form action='' method='post'>
<fieldset><input id='search' name='searchvalue' value='$text' placeholder='search' type='text' x-webkit-speech/>
<input id='submit' type='hidden' /></fieldset>
<input type='hidden' name='search' value='1'>
</form>
</div>
<body class='list'>
<div id='content'>
<ul><li class='title'>More results</li>";
while ($i <= $results)
{
$ArtistID = $decode->result[$i-1]->ArtistID;
$SongID = $decode->result[$i-1]->SongID;
$Cover = $decode->result[$i-1]->CoverArtFilename;
$Artist = $decode->result[$i-1]->ArtistName;
$Album = $decode->result[$i-1]->AlbumName;
$Song = $decode->result[$i-1]->Name;

if ($Song != NULL)
{
if ($Cover != NULL)
{
$Cover = "http://beta.grooveshark.com/static/amazonart/$Cover";
}
else {
$Cover = "images/nocover.jpg";
}
echo"
<li class='withimage'>
<a class='noeffect' href='javascript:document.getElementById(\"$SongID\").style.visibility = \"visible\";document.getElementById(\"$SongID\").style.display = \"block\"'>
<img src='$Cover'/>
<span class='name'>$Song - $Artist</span>
<span class='comment'>$Album</span>Test
<span class='arrow'></span></a></li>
<div id='$SongID' style='visibility:hidden;display:none;'>
<a href='javascript:document.getElementById(\"$SongID\").style.visibility = \"hidden\";document.getElementById(\"$SongID\").style.display = \"none\"'>
<div style='background:rgba(0,0,0,0.9);position:absolute;width:100%;height:91px;margin-top:-91px;z-index:1;'>
<center>
<form action='' method='POST'> 
<input type='hidden' name='download' value='1'>
<input type='hidden' name='DownloadName' value='$Artist - $Album - $Song'>
<input type='hidden' name='SongID' value='$SongID'>			
<input type='hidden' name='Song' value='$Song'>
<input type='submit' style='margin-top:20px;' value='Download'>
</form>
<form action='' method='POST'> 
<input type='hidden' name='ArtistSearch' value='1'>
<input type='hidden' name='ArtistID' value='$ArtistID'>		     
<input type='hidden' name='Artist' value='$Artist'>
<input type='Submit' value='More Songs from $Artist'></form>
</center>
</div>
</a>
</div>
";
}
$i++;
}
echo "</ul></body>";
}
elseif ($_GET['settings'] == '1')
{
echo "<form method='post' action=''>
<span class='graytitle'>settings</span>
<ul class='pageitem'>
<li class='smallfield'><span class='name'>results</span><input name='results' placeholder='$results' type='text'/></li>
<input type='hidden' name='settings' value='apply'/>
<li class='button'><input type='submit' value='apply'/></li>
</ul>
</form>";
}
elseif ($ID != '') {
$Name = $_GET['name'];
echo "<span class='graytitle'>$Name</span>
<ul class='pageitem'>
<li class='menu'><a href=''>
<img alt='list' src='thumbs/plugin.png' />
<span class='name'>Download</span>
<span class='arrow'></span></a></li>
</ul>";
}
elseif ($_POST['search'] == false OR $_POST['search'] == NULL OR $_POST['ArtistSearch'] == false)
{
echo "<div class='searchbox'>
<form action='' method='post'>
<fieldset><input id='search' name='searchvalue' placeholder='search' type='text' x-webkit-speech/>
<input id='submit' type='hidden' /></fieldset>
<input type='hidden' name='search' value='1'>
</form>
</div>";
}
else
{
echo "<pre> Sorry, not found </pre>";
}
?>
</div>
<div id='footer'>
Coded by Check in PHP. Styled by Loewe1000. Created by George Stephanos.
<a href="http://www.groove-dl.co.cc/"> JTR's Grooveshark Downloader </a>
</div>
</body>
</html>