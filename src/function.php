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