<?php

require 'vendor/autoload.php';
require 'arcs.php';
require 'wordcountcount.php';

$chapters = [];
foreach(glob("data/*.txt") as $file) {
	$index = ltrim(str_replace("data/", "", str_replace(".txt", "", $file)), "0");
	if (strlen($index) == 0) { // yeah i know
		$index = "0";
	}
	$f = file_get_contents($file);
	
	// data sanitization
	$yeet = [
		"Next Chapter",
		"Last Chapter",
		"Share this:Click to share on Twitter (Opens in new window)Click to share on Facebook (Opens in new window)",
	];
	$f = str_replace($yeet, "", $f);
	
	if (!array_key_exists($arcs[$index], $chapters)) {
		$chapters[$arcs[$index]] = [];
	}
	$chapters[$arcs[$index]][$index] = WordcountCount::count($f);
	
	echo "{$arcs[$index]} $index complete\n";
}

$x = "arc,chapter,word,count\n";
foreach ($chapters as $arc => $chaps) {
	foreach ($chaps as $chap => $words) {
		foreach ($words as $word => $count) {
			$x .= "$arc,$chap,$word,$count\n";
		}
	}
}
file_put_contents("data.out.csv",$x);
