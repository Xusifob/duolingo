<?php

/**
 * Class Word
 */
class Course extends Builder implements JsonSerializable
{


    /**
     * @var string
     */
    public $name;

    /**
     * @var
     */
    public $category;


    /**
     * @var string
     */
    public $explanations;

    /**
     * @return string
     */
    public function getSlug(): string
    {

        $slugify = new Cocur\Slugify\Slugify();

        return $slugify->slugify($this->name);
    }


  /**
     * @return string
     */
    public function getCategorySlug(): string
    {

        $slugify = new Cocur\Slugify\Slugify();

        return $slugify->slugify($this->category);
    }



    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category): void
    {
        $this->category = trim($category, '123456789 ');
    }

    /**
     * @return string
     */
    public function getExplanations(): ?string
    {
        return $this->explanations;
    }

    /**
     * @param string $explanations
     */
    public function setExplanations(?string $explanations): void
    {
        $this->explanations = $explanations;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
        $this->setCategory($name);
    }




    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return array(
            'name' => $this->getName(),
            'slug' => $this->getSlug(),
            'category' => $this->getCategory(),
            'explanation' => $this->getExplanations(),
        );
    }

}