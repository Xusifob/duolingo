<?php

/**
 * Class Word
 */
class Sentence extends Builder implements JsonSerializable
{


    /**
     * @var string
     */
    public $normal;

    /**
     * @var
     */
    public $translation;

    /**
     * @var
     */
    public $audio;


    /**
     * @var string
     */
    public $word;

    /**
     * @return string
     */
    public function getNormal(): string
    {
        if(!$this->getWord()) {
            return $this->normal;
        }

        return str_replace($this->getWord(),'<span class="highlighted">'. $this->getWord() .'</span>',$this->normal);
    }

    /**
     * @param string $normal
     */
    public function setNormal(string $normal): void
    {
        $this->normal = $normal;
    }

    /**
     * @return mixed
     */
    public function getTranslation()
    {
        return $this->translation;
    }

    /**
     * @return string
     */
    public function getWord() : string
    {
        return $this->word;
    }

    /**
     * @param mixed $word
     */
    public function setWord($word): void
    {
        $this->word = $word;
    }



    /**
     * @param mixed $translation
     */
    public function setTranslation($translation): void
    {
        $this->translation = $translation;
    }

    /**
     * @return mixed
     */
    public function getAudio()
    {
        return $this->audio;
    }

    /**
     * @param mixed $audio
     */
    public function setAudio($audio): void
    {
        $this->audio = $audio;
    }




    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return array(
            'normal' => $this->getNormal(),
            'translation' => $this->getTranslation(),
            'audio' => $this->getAudio(),
        );
    }

}