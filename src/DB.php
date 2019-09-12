<?php

class DB
{

    /**
     * @var string
     */
    protected $dir;

    /**
     * DB constructor.
     */
    public function __construct()
    {
        $this->dir = __DIR__ . "/../data/";
    }


    /**
     * @return bool|mixed
     */
    public function getUserWords()
    {
        return $this->get("users/{$_COOKIE['user']}/words/{$_COOKIE['learning_language']}-{$_COOKIE['user_language']}");

    }

    /**
     *
     * @param $word
     * @return Word|null
     */
    public function getWord($word) : ?Word
    {
        $w = $this->get("words/{$_COOKIE['learning_language']}-{$_COOKIE['user_language']}/{$word}");

        if(!$w) {
            return null;
        }

        return new Word($w);


    }


    /**
     * @return bool|mixed
     */
    public function getSkills()
    {
        return $this->get("skills/{$_COOKIE['learning_language']}-{$_COOKIE['user_language']}");
    }


    /**
     *
     * Get data from database
     *
     * @param $path
     * @return bool|mixed
     */
    public function get($path)
    {
        $file = $this->dir . "$path.json";

        if(!file_exists($file)) {
            return false;
        }

        return json_decode(file_get_contents($file),true);
    }


    /**
     *
     * Write in the database
     *
     * @param $path
     * @param $data
     * @return bool|int
     */
    public function write($path,$data)
    {

        $file = $this->dir . "$path.json";

        $dir = (pathinfo($file,PATHINFO_DIRNAME));

        if(!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        if(file_exists($file) && !is_writable($file)) {
            return false;
        };


        return file_put_contents($file,json_encode($data));
    }


}