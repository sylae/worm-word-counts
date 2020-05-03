<?php

require 'vendor/autoload.php';
require 'arcs.php';
require 'wordcountcount.php';

$test = false; // true will stop after ten tests

if (!in_array($argv[1] ?? "worm", ['worm', 'ward'])) {
	die("what fic is that?");
}
$fic = $argv[1] ?? "worm";

switch ($fic) {
	case "worm":
		$folder = "data-worm";
		break;
	case "ward":
		$folder = "data-ward";
		break;
}

if ($req = $argv[2] ?? false) {
	$parseAll = false;
	$process = [$req];
} else {
	$parseAll = true;
	$process = glob("$folder/*.txt");
}

if ($test) {
	$process = array_slice($process, 0, 10);
}

$chapters = [];
foreach($process as $file) {
	$index = ltrim(str_replace("$folder/", "", str_replace(".txt", "", $file)), "0");
	if (strlen($index) == 0) { // yeah i know
		$index = "0";
	}
	$f = file_get_contents($file);
	
	if (is_null($arcs[$fic][$index])) {
		echo "Skipping $index due to TOC duplication.\n";
		continue;
	}
	
	// data sanitization
	$yeet = [
		"Next Chapter",
		"Last Chapter",
		"Share this:Click to share on Twitter (Opens in new window)Click to share on Facebook (Opens in new window)",
		"Like this:Like Loading...",
	];
	$f = str_replace($yeet, "", $f);
	
	if (!array_key_exists($arcs[$fic][$index], $chapters)) {
		$chapters[$arcs[$fic][$index]] = [];
	}
	$chapters[$arcs[$fic][$index]][$index] = WordcountCount::count($f);
	
	echo "{$arcs[$fic][$index]} $index complete\n";
}

$x = $parseAll ? "arc,chapter,word,count\n" : "";
foreach ($chapters as $arc => $chaps) {
	foreach ($chaps as $chap => $words) {
		foreach ($words as $word => $count) {
			$x .= "$arc,$chap,$word,$count\n";
		}
	}
}

if ($parseAll) {
	file_put_contents("$folder.out.csv", $x);
} else {
	$fraw = str_replace("$folder/", "", str_replace(".txt", "", $req));
	file_put_contents("out/$fraw.out.csv", $x);
}
