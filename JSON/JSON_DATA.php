<?php

include '../header.php';

if(!isset($_GET['user']) || !isset($_GET['from']) || !isset($_GET['to'])) {
    return_response(array("error" => "User must be defined"),400);
}

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


$user_words = $db->get("users/{$_GET['user']}/words/{$_GET['from']}-{$_GET['to']}");

if(!$user_words) {
    return_response(array("error" => "No data is found, please sync your values first"),404);
}

$words = array();

foreach ($user_words as $word) {

    $word = $db->get("words/{$_GET['from']}-{$_GET['to']}/{$word}");
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