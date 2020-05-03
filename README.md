# worm-word-counts

This project contains word count totals for the webserial *Worm*, by Wildbow. Also *Ward* by, you guessed it, Wildbow.

* https://parahumans.wordpress.com/
* https://www.parahumans.net/

Also included is the shitty code I used to generate it.

this code is janky af dont judge me

it could probably use some optimization

## usage

```
composer install

mkdir data
php pull.php worm # or ward
# wait like ten minutes

php parse.php worm # or ward
# wait like five minutes
```

if you are running this on linux, you can speed up stuff by using `./batch.sh worm` instead of the parse.php stuff. This will do parallel stuff that actually works, speed it up a little if you have multiple cores bc you arent living in 2003.

## using it on the other serials

Right now it's set up to handle Worm and Ward, I'll probably add the others eventually, but if you want to do it yourself...

just edit pull.php to add the new table of contents url, and then edit arcs.php to provide new arc names. You'll need to edit parse.php as well, you'll see it.

you might need to take a look at the variable `$yeet` in parse.php too, in case there's new "bonus text" that isnt really story.

## License

jesus fuck you want to *use* this? okay uhhhh MIT i guess
