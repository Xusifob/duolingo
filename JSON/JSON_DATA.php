<?php

include 'header.php';


$offset = 0;

if(isset($_GET['offset'])) {
    $offset = $_GET['offset'];
}

$category = null;


if(isset($_GET['category'])) {
    $category = $_GET['category'];
}
$search = null;

if(isset($_GET['search'])) {
    $search = $_GET['search'];
}

$length = 15;

if(isset($_GET['length'])) {
    $length = $_GET['length'];
}


$user_words = $db->getUserWords();

if(!$user_words) {
    return_response(array("error" => "No data is found, please sync your values first"),404);
}

$words = array();

foreach ($user_words as $word) {

    $word = $db->getWord($word);
    if(!$word) {
        // The word is not in the database, you need to sync it
        continue;
    }

    $word = new Word($word);

    if(!$word->match($search)) {
        continue;
    }

    if(!$word->hasCategory($category)) {
        continue;
    }

    $words[] = $word;

}


$count = count($words);

$words = array_splice($words,$offset,$length);

return_response(array(
    'total' => $count,
    'data' => $words,
    'offset' => $offset,
    'length' => $length,
    'request' => $_GET,
));