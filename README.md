# worm-word-counts

This project contains word count totals for the webserial *Worm*, by Wildbow. 

https://parahumans.wordpress.com/

Also included is the shitty code I used to generate it.

this code is janky af dont judge me

it could probably use some optimization

## usage

```
composer install

mkdir data
php pull.php
# wait like ten minutes

php parse.php
# wait like five minutes
```

## using it on the other serials

just edit pull.php to point to the new table of contents url, and then edit arcs.php to provide new arc names.

you might need to take a look at the variable `$yeet` in parse.php too, in case there's new "bonus text" that isnt really story.

## License

jesus fuck you want to *use* this? okay uhhhh MIT i guess
