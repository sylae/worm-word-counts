<?php

require 'vendor/autoload.php';
require 'arcs.php';
require 'wordcountcount.php';


WordcountCount::loadLetters();

$tests = [
	"The",
	"The quick brown fox jumps. over the lazy dog.",
	"Don’t  do   that!!!!!"
];

foreach ($tests as $test) {
	$x = WordcountCount::count($test);
	echo "$test\n";
	var_dump($x);
	echo "\n\n";
}