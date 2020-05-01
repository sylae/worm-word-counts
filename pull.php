<?php

require 'vendor/autoload.php';

$client = new \GuzzleHttp\Client();
$res = $client->request('GET', 'https://parahumans.wordpress.com/table-of-contents/');
$b = $res->getBody();
$qp = html5qp((string) $b, "#post-2404 > .entry-content");
$urls = [];
html5qp($qp, "a")->each(function($index, $item) use (&$urls) {
	$url = html5qp($item)->attr("href");
	if ($url[0] != "h") {
		$url = "https://$url";
	}
	if (strstr($url, "?share=")) {
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
	file_put_contents("data/$id.txt", $text);
	sleep(1);
}
