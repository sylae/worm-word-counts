<?php

require 'vendor/autoload.php';

if (!in_array($argv[1] ?? "worm", ['worm', 'ward'])) {
	die("what fic is that?");
}
$fic = $argv[1] ?? "worm";

switch ($fic) {
	case "worm":
		$baseURL = 'https://parahumans.wordpress.com/table-of-contents/';
		$class = "#post-2404 > .entry-content";
		$folder = "data-worm";
		break;
	case "ward":
		$baseURL = 'https://www.parahumans.net/table-of-contents/';
		$class = "#post-48 > .entry-content";
		$folder = "data-ward";
		break;
}

$client = new \GuzzleHttp\Client();
$res = $client->request('GET', $baseURL);
$b = $res->getBody();
$qp = html5qp((string) $b, $class);
$urls = [];
html5qp($qp, "a")->each(function($index, $item) use (&$urls, $fic) {
	$url = html5qp($item)->attr("href");
	if ($url[0] != "h") {
		$url = "https://$url";
	}
	if (strstr($url, "?share=")) {
		return;
	}
	if ($fic == "ward" && stripos($url, "glow-worm") !== false) {
		return;
	}
	$urls[] = $url;
	
	if ($url == "https://parahumans.wordpress.com/2013/11/02/teneral-e-1/") { // wildbow pls
		$urls[] = "https://parahumans.wordpress.com/2013/11/05/teneral-e-2/";
	}
});
print_r($urls);
foreach($urls as $id=>$url) {
	$id = str_pad($id, 3, "0", STR_PAD_LEFT);
	echo "FETCHING $id /// $url".PHP_EOL;
	$chapter = $client->request('GET', $url)->getBody();
	
	$text = html5qp((string) $chapter, ".entry-content")->text();
	file_put_contents("$folder/$id.txt", $text);
	sleep(1);
}
