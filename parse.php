<?php

require 'vendor/autoload.php';
require 'arcs.php';

$chapters = [];
foreach(glob("data/*.txt") as $file) {
	$index = str_replace("data/", "", str_replace(".txt", "", $file));
	$words = [];
	$f = file_get_contents($file);
	$matches = [];
	
	preg_match_all("/\\w+/im", $f, $matches);
	foreach($matches[0] as $word) {
		$w = mb_strtolower($word);
		if (!array_key_exists($arcs[$index], $chapters)) {
			$chapters[$arcs[$index]] = [];
		}
		if (!array_key_exists($w, $chapters[$arcs[$index]])) {
			$chapters[$arcs[$index]][$w] = 0;
		}
		$chapters[$arcs[$index]][$w]++;
	};
}

ksort($chapters);
$x = "chapter,word,count\n";
foreach ($chapters as $chap => $words) {
	arsort($words);
	foreach ($words as $word => $count) {
		$x .= "$chap,$word,$count\n";
	}
}
file_put_contents("results3.csv",$x);