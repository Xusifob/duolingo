<?php

include '../header.php';

if(!isset($_GET['from']) || !isset($_GET['to'])) {
    return_response(array('error' => 'Please set your studying language'),500);
}

$skills = $db->get("skills/{$_GET['from']}-{$_GET['to']}");

if(!$skills) {
    return_response(array("error" => sprintf("The language %s is not yet configured in the app",$_GET['from'])),500);
}

$categories = array();

foreach ($skills as $skill) {

    $skill = new Course($skill);

    $categories[$skill->getCategorySlug()] = $skill->getCategory();
}

return_response(array(
    'skills' => $skills,
    'categories' => $categories
));