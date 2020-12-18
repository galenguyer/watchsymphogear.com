<?php
if (getenv("PROTOCOL") && getenv("PROTOCOL") != "") {
    $proto = getenv("PROTOCOL");
}
else {
    $proto = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
}
$host = $proto."://$_SERVER[HTTP_HOST]/";

$uri = $_SERVER['REQUEST_URI'];
$base = explode('/', trim($uri, " /"))[0];

# Root url
if ($uri == "/"){
	$files = glob("./frames/*");
	$file = substr($files[array_rand($files)], 2);
	
	if (getenv("PROTOCOL")) {
		$proto = getenv("PROTOCOL");
	}
	else {
		$proto = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
	}
	$host = $proto . "://$_SERVER[HTTP_HOST]/";
	
	echo <<<EOF
	<html lang='en'>
		<head>
			<title>Watch Symphogear!</title>
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<meta name="author" content="Galen Guyer">
			<meta name="description" content="You really should watch Symphogear!" />
			<style>
				body {
					font-family: Arial, Helvetica, sans-serif;
					text-align: center;
				}
				img {
					max-width: 90vw;
					max-height: 70vh;
				}
			</style>
		</head>
	
		<body>
			<header>
				<h1>Watch Symphogear!</h1>
			</header>
			<img src="$host$file"></img>
			<p>Permalink: <a href="$host$file">$host$file</a></p>
	</body>
	</html>
	EOF;	
	die();
}
# Raw endpoint
/* else if (trim($uri, "/") == ($base . "/raw")) {
	$files = glob("./animals/$base/*");
	$file = str_replace(" ", "%20", substr($files[array_rand($files)], 2));
	header("Content-Type: text/plain");
	echo $host.$file;
	die();
}
# JSON endpoint
else if (trim($uri, "/") == ($base . "/json")) {
	$files = glob("./animals/$base/*");
	$file = str_replace(" ", "%20", substr($files[array_rand($files)], 2));
	header("Content-Type: application/json");
	echo "{\"link\": \"$host$file\"}";
	die();
} */
header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
include('404.php');
die();
?>
