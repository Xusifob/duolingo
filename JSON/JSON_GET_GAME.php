<?php

include 'header.php';

if(!isset($_COOKIE['user']) || !isset($_COOKIE['user_language']) || !isset($_COOKIE['learning_language'])) {
    return_response(array("error" => "User must be defined"),400);
}


$category = null;

if(isset($_GET['category'])) {
    $category = $_GET['category'];
}

$user_words = $db->getUserWords();

if(!$user_words) {
    return_response(array("error" => "No data is found, please sync your values first"),404);
}

$words = array();

foreach ($user_words as $word) {

    $word = $db->getWord($word);

    if(!$word || !$word->hasCategory($category)) {
        continue;
    }

    $words[] = $word;

}

$random = get_array_random($words,10);

foreach ($random as &$word) {
    $w = get_array_random($user_words,3);

    $word->addWord(clone $word);

    foreach($w as $e) {
        $e = $db->getWord($e);
        $word->addWord($e);
    }
}

return_response($random);