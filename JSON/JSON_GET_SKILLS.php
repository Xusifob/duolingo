<?php

include 'header.php';

if(!isset($_COOKIE['user'])) {
    return_response(array("error" => "User must be defined"),400);
}

if(!isset($_COOKIE['user_language']) || !isset($_COOKIE['learning_language'])) {
    return_response(array('error' => 'Please set your studying language'),500);
}

$skills = $db->getSkills();

if(!$skills) {
    return_response(array("error" => sprintf("The language %s is not yet configured in the app",$_GET['learning_language'])),500);
}

$user_words = $db->getUserWords();

$user_cats = array();

foreach ($user_words as $word) {
    $word = $db->getWord($word);
    if(!$word) {
        // The word is not in the database, you need to sync it
        continue;
    }

    $word = new Word($word);

    $cat = $word->getCategory()['slug'];
    if(!in_array($cat,$user_cats)) {
        $user_cats[] = $cat;
    }

}


$categories = array();

foreach ($skills as $key => $skill) {

    $skill = new Course($skill);

    if(!in_array($skill->getCategorySlug(),$user_cats)) {
        unset($skills[$key]);
        continue;
    } else {
        $categories[$skill->getCategorySlug()] = $skill->getCategory();
    }

}

return_response(array(
    'skills' => $skills,
    'categories' => $categories
));