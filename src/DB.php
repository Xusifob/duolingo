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