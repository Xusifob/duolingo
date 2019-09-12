<?php



/**
 * @param mixed ...$var
 */
function dump(...$var)
{

    echo '<pre>';
    if(count($var) <= 1) {
        var_dump($var[0]);
    }else {
        var_dump($var);
    }

    echo '</pre>';
}




/**
 * @param $data
 * @param int $status
 */
function return_response($data,$status = 200)
{
    header("Content-type:application/json");
    http_response_code($status);

    if(is_array($data)) {
        $data['status'] = $status;
    }

    die(json_encode($data,JSON_PRETTY_PRINT));
}


/**
 * @param $key
 * @param $value
 */
function addCookie($key,$value)
{
    setcookie($key,$value,time()+365*60*60*24,"/");

}


/**
 * @param $array
 * @param int $nb
 * @return mixed
 */
function get_array_random($array,$nb = 10)
{
    $r = array_rand($array,$nb);
    shuffle($r);
    $random = array();

    foreach ($r as $item) {
        $random[] = $array[$item];
    }

    return $random;
}