<?php
// no  direct access
defined('_ACCESS') or die;

class wordsChangerHelper
{
    private static $instance;

    public static function instance()
    {
        if (!self::$instance)
            self::$instance = new self();

        return self::$instance;
    }

    //Counting word # inside the fraze should start with 0
    public function getFraze($fraze, $word_number, $case)
    {
        //separating fraze into words
        $arrFraze = explode(" ", $fraze);
        //getting the word we want to change out of the array by the word number (i.e. $key)
        $word_to_change = mb_strtolower($arrFraze [$word_number]);

        //checking the word, getting the ending of this word (1 letter)
        if (preg_match('/[^A-Za-z0-9_\-]/', $word_to_change))
            $ending_to_change = mb_substr($word_to_change, -1);
        //getting another ending according to the case
        $new_ending = self:: getEnding($ending_to_change, $case);
        //forming new word woth new ending
        $new_word = mb_substr($word_to_change,0,-1).$new_ending;
        // rebuilding the fraze: replacing old word with new one
        $new_fraze= str_replace ($arrFraze [$word_number], $new_word, $fraze);

        return $new_fraze;
    }

    // $case => 1-и, 2-р, 3-д, 4-в, 5-т, 6-п
    private function getEnding($ending, $case)
    {
        $padeg = array (
            2 => array("н"=>"на","з"=>"за","ь"=>"я", "о" => "а"),
            3 => array("н"=>"ну","з"=>"зу","ь"=>"ю", "о"=>"у"),
            4 => array("н"=>"н","з"=>"з","ь"=>"ь","о"=>"о"),
            5 => array("н"=>"ном","з"=>"зом","ь"=>"ем","о"=>"ом"),
            6 => array("н"=>"не","з"=>"зу","ь"=>"е","о"=>"е")
        );

        return $padeg[$case][$ending];
    }
}
