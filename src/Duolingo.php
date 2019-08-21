<?php


use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

class Duolingo
{

    public $client;


    public $cookieJar;


    public $headers;

    /**
     * Duolingo constructor.
     */
    public function __construct()
    {
        /** @var CookieJar cookieJar */
        $this->cookieJar =  new CookieJar();

        $this->headers = array(
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/36.0.1985.143 Safari/537.36',
        );

        $this->client = new Client(array(
            'base_uri' => 'https://www.duolingo.com/',
            'headers' => $this->headers,
            'cookies' => $this->cookieJar,
        ));
    }


    /**
     * @return mixed
     *
     * @param $login
     * @param $password
     * @return array
     */
    public function login($login,$password)
    {

        return json_decode($this->client->post('/login',array(
                'json' => array(
                    "login" => $login,
                    "password" => $password,
                ))
        )->getBody()->getContents(),true);
    }


    /**
     * @param $login
     * @return string
     */
    public function getUserData($login)
    {
        return $this->getJSON('/users/' . $login);
    }


    public function getUserCourses($user_id)
    {

        return $this->getJSON("2017-06-30/users/$user_id?fields=bio,courses,currentCourse");

    }


    /**
     * @return mixed
     */
    public function getOverview()
    {
        return $this->getJSON('/vocabulary/overview');
    }

    /**
     * @param $string
     * @param $from
     * @param $to
     *
     * @return array|mixed
     */
    public function getElementData($string,$from,$to)
    {
        $string = urlencode($string);

        $from = $this->transformApiPrefix($from);
        $to  = $this->transformApiPrefix($to);

        $url = ('https://duolingo-lexicon-prod.duolingo.com/api/1/complete?languageId='. $from .'&query='. $string .'&uiLanguageId='. $to .'');

        $data = $this->getJSON($url);

        if(!isset($data[$from][0])) {
            return array();
        }

        return $this->getDictionnaryInfos($data[$from][0]['lexemeId']);

    }


    /**
     * @param $lexem_id
     * @return mixed
     */
    public function getDictionnaryInfos($lexem_id)
    {

        return  $this->getJSON('/api/1/dictionary_page?lexeme_id=' . $lexem_id);
    }


    /**
     * @param $prefix
     * @return mixed
     */
    public function transformApiPrefix($prefix)
    {

        $transform = array('zs' => 'zh');

        if(isset($transform[$prefix])) {
            return $transform[$prefix];
        }

        return $prefix;

    }


    /**
     * @param $url
     * @return mixed
     */
    protected function getJSON($url)
    {
        return json_decode($this->client->get($url)->getBody()->getContents(),true);

    }



    /**
     * @param $string
     * @param $language
     * @return mixed
     */
    public function getPhonetic($string,$language)
    {
        switch ($language) {
            case 'zs' :
                $string = urlencode($string);

                $data = file_get_contents('https://glosbe.com/transliteration/api?from=Han&dest=Latin&text='. $string .'&format=json');

                return json_decode($data, true)['text'];
                break;

            default :
                return "";
        }
    }


}