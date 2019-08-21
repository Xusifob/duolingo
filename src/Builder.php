<?php

abstract class Builder
{

    /**
     * Builder constructor.
     *
     * @param $data
     */
    public function __construct($data = array())
    {

        foreach ($data as $key => $datum) {

           $setter = "set" . $this->dashesToCamelCase($key,true);

           if(method_exists($this,$setter)) {
               $this->$setter($datum);
           }
        }
    }


    /**
     * @param $string
     * @param bool $capitalizeFirstCharacter
     * @return mixed|string
     */
    protected function dashesToCamelCase($string, $capitalizeFirstCharacter = false)
    {

        $str = str_replace('_', '', ucwords($string, '-'));

        if (!$capitalizeFirstCharacter) {
            $str = lcfirst($str);
        }

        return $str;
    }



}