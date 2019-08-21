<?php

/**
 * Class Word
 */
class Word extends Builder implements JsonSerializable
{


    /**
     *
     * The word you're learning
     *
     * @var string
     */
    protected $word;


    /**
     * The language you are learning
     *
     * @var string
     */
    protected $learning_language;


    /**
     *
     * The language you're learning it from
     *
     * @var string
     */
    protected $user_language;


    /**
     *
     * The phonetic of the word
     *
     * @var string
     */
    protected $phonetic;


    /**
     * @var string
     */
    protected $category;


    /**
     *
     * The list of courses the word is in
     *
     * @var string
     */
    protected $course;


    /**
     * @var Sentence[]
     */
    protected $sentences = array();


    /**
     *
     * The audio file
     *
     * @var string
     */
    protected $audio;

    /**
     * @var \Cocur\Slugify\Slugify
     */
    protected $slugify;


    /**
     * @var array
     */
    protected $translations = array();


    /**
     * Word constructor.
     * @param array $data
     */
    public function __construct($data = array())
    {
        parent::__construct($data);

        $this->slugify = new \Cocur\Slugify\Slugify();
    }


    /**
     * @return string
     */
    public function getWord(): string
    {
        return $this->word;
    }

    /**
     * @param string $word
     */
    public function setWord(string $word): void
    {
        $this->word = $word;
    }

    /**
     * @return string
     */
    public function getLearningLanguage(): string
    {
        return $this->learning_language;
    }

    /**
     * @param string $learning_language
     */
    public function setLearningLanguage(string $learning_language): void
    {
        $this->learning_language = $learning_language;
    }

    /**
     * @return string
     */
    public function getUserLanguage(): string
    {
        return $this->user_language;
    }

    /**
     * @param string $user_language
     */
    public function setUserLanguage(string $user_language): void
    {
        $this->user_language = $user_language;
    }

    /**
     * @return array
     */
    public function getTranslations(): array
    {
        return $this->translations;
    }

    /**
     * @param array $translations
     */
    public function setTranslations($translations): void
    {
        if(is_string($translations)) {
            $translations = explode(', ',$translations);
        }
        $this->translations = $translations;
    }



    /**
     * @return string
     */
    public function getPhonetic(): string
    {
        return $this->phonetic;
    }

    /**
     * @param string $phonetic
     */
    public function setPhonetic(string $phonetic): void
    {
        $this->phonetic = $phonetic;
    }

    /**
     * @return array
     */
    public function getCategory(): array
    {
        return array(
            'normal' => $this->category,
            'slug' => $this->slugify->slugify($this->category),
        );
    }

    /**
     * @param string|array $category
     */
    public function setCategory($category): void
    {
        if(is_array($category)) {
            $category = $category['normal'];
        }
        $this->category = trim($category, '123456789 ');
    }

    /**
     * @return array
     */
    public function getCourse(): array
    {
        return array(
            'normal' => $this->course,
            'slug' => $this->slugify->slugify($this->course),
        );
    }

    /**
     * @param string|array $course
     */
    public function setCourse($course): void
    {
        if(is_array($course)) {
            $course = $course['normal'];
        }
        $this->course = $course;
        $this->setCategory($course);
    }

    /**
     * @return Sentence[]
     */
    public function getSentences(): array
    {
        return $this->sentences;
    }


    /**
     * @param Sentence $sentence
     */
    public function addSentence(Sentence $sentence)
    {
        $this->sentences[] = $sentence;
    }

    /**
     * @param array $sentences
     */
    public function setSentences(array $sentences): void
    {
        $this->sentences = $sentences;
    }

    /**
     * @return string
     */
    public function getAudio(): string
    {
        return $this->audio;
    }

    /**
     * @param string $audio
     */
    public function setAudio(string $audio): void
    {
        $this->audio = $audio;
    }


    public function getSlug()
    {
        return $this->slugify->slugify($this->getPhonetic());
    }


    /**
     * @param string $category
     * @return bool
     */
    public function hasCategory(string  $category)
    {

        if(!$category) {
            return  true;
        }

        if(!$this->getCategory()) {
            return false;
        }

        return in_array($category,$this->getCategory());
    }

    /**
     * @param $regex
     * @return bool
     */
    public function match($regex)
    {

        if(!$regex) {
            return  true;
        }

        $string = implode(" ",array(
            $this->getSlug(),
            $this->getWord(),
            $this->getPhonetic(),
            implode(" ",$this->getTranslations()),
        ));

        return preg_match("/$regex/i",$string) === 1;
    }


    public function getDBName()
    {
        return "words/{$this->getLearningLanguage()}-{$this->getUserLanguage()}/{$this->getWord()}";
    }


    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return array(
            'word' => $this->getWord(),
            'learning_language' => $this->getLearningLanguage(),
            'user_language' => $this->getUserLanguage(),
            'translations' => $this->getTranslations(),
            'phonetic' => $this->getPhonetic(),
            'audio' => $this->getAudio(),
            'sentences' => $this->getSentences(),
            'slug' => $this->getSlug(),
            'category' => $this->getCategory(),
            'course' => $this->getCourse()
        );
    }

}