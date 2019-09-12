<?php

include 'header.php';


if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    return_response(array("error" => "Method not allowed"),400);
}

$duolingo = new Duolingo();

if(!isset($_POST['login']) || !isset($_POST['password'])) {
    return_response(array("error" => "Id or password is missing",400));
}

$login = $duolingo->login($_POST['login'],$_POST['password']);

if(isset($login['failure'])) {
    return_response(array("error" => "Invalid credentials"),400);
}

$courses = $duolingo->getUserCourses($login['user_id']);

$overview = $duolingo->getOverview();

$skills = array();

$learning = $courses['currentCourse']['trackingProperties']['learning_language'];
$userLanguage = $courses['currentCourse']['trackingProperties']['ui_language'];

foreach ($courses['currentCourse']['skills'] as $course) {

    foreach ($course as $cours) {
        $course = new Course();

        $course->setName($cours['name']);
        $course->setExplanations($cours['tipsAndNotes']);

        $skills[$course->getSlug()] = $course;
    }
}

$db->write("skills/$learning-$userLanguage",$skills);

$words = array();
foreach($overview['vocab_overview'] as $vocab) {

    $word = new Word();

    $word->setWord($vocab['word_string']);
    $word->setLearningLanguage($learning);
    $word->setUserLanguage($userLanguage);

    $words[] = $word->getWord();

    if($data = $db->get($word->getDBName())) {
        continue;
    }


    $infos = $duolingo->getDictionnaryInfos($vocab['lexeme_id']);
    $word->setTranslations($infos['translations']);

    $word->setPhonetic($duolingo->getPhonetic($word->getWord(),$word->getLearningLanguage()));

    if($infos['has_tts']) {
        $word->setAudio($infos['tts']);
    }

    $word->setCourse($vocab['skill']);

    foreach($infos['alternative_forms'] as $sent) {
        $sentence = new Sentence();
        $sentence->setWord($sent['word']);
        $sentence->setNormal($sent['text']);
        $sentence->setAudio($sent['tts']);
        $sentence->setTranslation($sent['translation']);
        $word->addSentence($sentence);
    }

    $db->write($word->getDBName(),$word);

}

$db->write("users/{$login['user_id']}/words/{$learning}-{$userLanguage}",$words);

addCookie('user_language',$userLanguage);
addCookie('learning_language',$learning);
addCookie('user',$login['user_id']);

return_response(array(
    'words' => $words,
    'login' => $login,
    'user_language' => $userLanguage,
    'learning_language' => $learning,
));