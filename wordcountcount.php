<?php

/**
 * this is a shitty edit of sylae/word-count, to make it add up each word's count instead of all of them.
 */

class WordcountCount
{

    public static function split(string $text): array
    {
        // multiple hyphens are to be interpreted as a word break, this is hacky but w/e
        $text = preg_replace("/-{2,}/", " ", $text);

        $last = null;
        $letters = self::getLetters();
        $punct = self::getPunct();
		$words = [];
		$word = "";
        foreach (preg_split('//u', $text." ", null, PREG_SPLIT_NO_EMPTY) as $char) {
            $isLetter = in_array(IntlChar::ord($char), $letters);
            $isPunct = in_array(IntlChar::ord($char), $punct);
			$last = $isLetter;
			if (!$isLetter && !$last && strlen($word) > 0) {
				$words[] = mb_strtolower($word);
				$word = "";
			} elseif ($isLetter && !$isPunct) {
				$word .= IntlChar::foldCase($char);
			}
        }
        return $words;
    }
	
	public static function count(string $text): array
	{
		$words = [];
		foreach (self::split($text) as $word) {
			if (!array_key_exists($word, $words)) {
				$words[$word] = 1;
			} else {
				$words[$word]++;
			}
		}
		return $words;
		
	}

    /**
     * Get the code points that "count" as letters.
     *
     * @return array
     */
    private static function getLetters(): array
    {
        static $letters = [];
        if (count($letters) > 0) {
            return $letters;
        }

        $categories = [
            IntlChar::CHAR_CATEGORY_UPPERCASE_LETTER,
            IntlChar::CHAR_CATEGORY_LOWERCASE_LETTER,
            IntlChar::CHAR_CATEGORY_TITLECASE_LETTER,
            IntlChar::CHAR_CATEGORY_MODIFIER_LETTER,
            IntlChar::CHAR_CATEGORY_OTHER_LETTER,
            IntlChar::CHAR_CATEGORY_LETTER_NUMBER,
            IntlChar::CHAR_CATEGORY_CONNECTOR_PUNCTUATION,
            IntlChar::CHAR_CATEGORY_DECIMAL_DIGIT_NUMBER,
            IntlChar::CHAR_CATEGORY_OTHER_NUMBER,
        ];
        $breaks = [
            IntlChar::WB_MIDNUM,
            IntlChar::WB_MIDNUMLET,
            IntlChar::WB_SINGLE_QUOTE,
        ];

        $x = 0x0;
        while ($x < 0x10ffff) {
            $cp = $x;
            $x++;

            if (!IntlChar::isdefined($cp)) {
                continue;
            }

            $hyphenMatch = ($cp == 0x00ad || $cp == 0x002d);
            $catMatch = in_array(IntlChar::charType($cp), $categories, true);
            $wbMatch = in_array(IntlChar::getIntPropertyValue($cp, IntlChar::PROPERTY_WORD_BREAK), $breaks, true);

            if ($hyphenMatch || $catMatch || $wbMatch) {
                $letters[] = $cp;
            }
        }
        return $letters;
    }

    /**
     * hack bc i dont wanna see stupidass punctuation
     *
     * @return array
     */
    private static function getPunct(): array
    {
        static $letters = [];
        if (count($letters) > 0) {
            return $letters;
        }

        $breaks = [
            IntlChar::WB_MIDNUM,
            IntlChar::WB_MIDNUMLET,
            IntlChar::WB_SINGLE_QUOTE,
        ];

        $x = 0x0;
        while ($x < 0x10ffff) {
            $cp = $x;
            $x++;

            if (!IntlChar::isdefined($cp)) {
                continue;
            }
            if (in_array(IntlChar::getIntPropertyValue($cp, IntlChar::PROPERTY_WORD_BREAK), $breaks, true)) {
                $letters[] = $cp;
            }
        }
        return $letters;
    }

    /**
     * the list of valid letters is normally generated when wordcount() is called for the first time. If you'd like it
     * to load earlier (since it takes a hot moment), use this handy method!
     */
    public static function loadLetters(): void
    {
        self::getLetters();
    }
}