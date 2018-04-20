<?php

use Carbon\Carbon;

/**
 * Website General Functions
 *
 */

function getMonths()
{
    return
    [
         1 => "1 Month",
         3 => "3 Months",
         6 => "6 Months",
         12 => "1 Year",
         60 => "5 Years",
         -1 => "YTD",
    ];
}

function getLastUpdateDate()
{
    $file = public_path().DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR."last-updated-date.json";

    if(is_file($file))
    {
        $data = file_get_contents($file);
        $data = json_decode($data,1);
        return isset($data[0]) ? date("Y-m-d H:i:s",strtotime($data[0])):date("Y-m-d H:i:s");
    }

    return date("Y-m-d H:i:s");
}

function getMarketUrls($marketID)
{
    if($marketID == 1)
    {
        return '/markets/equities';
    }
    else if($marketID == 2)
    {
        return '/markets/currencies';
    }
    else if($marketID == 3)
    {
        return '/markets/commodities';
    }
    else if($marketID == 4)
    {
        return '/markets/rates';
    }
    else if($marketID == 5)
    {
        return '/markets/credit';
    }
    else
    {
        return "/";
    }
}


function getFilename($fullpath, $uploaded_filename) {
    $count = 1;
    $new_filename = $uploaded_filename;
    $firstinfo = pathinfo($fullpath);

    while (file_exists($fullpath)) {
        $info = pathinfo($fullpath);
        $count++;
        $new_filename = $firstinfo['filename'] . '(' . $count . ')' . '.' . $info['extension'];
        $fullpath = $info['dirname'] . '/' . $new_filename;
    }

    return $new_filename;
}

function getLatestTweets()
{
    $settings = array(
        'oauth_access_token' => env('TWITTER_ACCESS_TOKEN'),
        'oauth_access_token_secret' => env('TWITTER_ACCESS_TOKEN_SECRET'),
        'consumer_key' => env('TWITTER_CONSUMER_KEY'),
        'consumer_secret' => env('TWITTER_CONSUMER_SECRET')
    );

    $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
    $getfield = "?screen_name=";
    $requestMethod = 'GET';

    $twitter = new \App\TwitterAPIExchange($settings);

    $tweet =  $twitter->setGetfield($getfield)
                 ->buildOauth($url, $requestMethod)
                 ->performRequest();

    $tweets = json_decode($tweet,1);
    $data = [];
    $i = 0;

    if(!empty($tweets))
    {
        foreach($tweets as $tweet)
        {
            $data[$i]['comment'] = $tweet['text'];
            $data[$i]['date'] = date("d M, Y",strtotime($tweet['created_at']));
            $i++;
        }
    }

    return $data;
}

function getSearchTweets($search)
{
    $settings = array(
        'oauth_access_token' => env('TWITTER_ACCESS_TOKEN'),
        'oauth_access_token_secret' => env('TWITTER_ACCESS_TOKEN_SECRET'),
        'consumer_key' => env('TWITTER_CONSUMER_KEY'),
        'consumer_secret' => env('TWITTER_CONSUMER_SECRET')
    );
    $url = 'https://api.twitter.com/1.1/search/tweets.json';
    $from = "@EmfiSecurities";
    $getfield = '?q=#'.$search.' from:'.$from.'&count=20';
    $requestMethod = 'GET';

    $twitter = new \App\TwitterAPIExchange($settings);

    $tweet =  $twitter->setGetfield($getfield)
                 ->buildOauth($url, $requestMethod)
                 ->performRequest();

    $tweets = json_decode($tweet,1);
	// if (empty($tweets['statuses'])) {
	// 	$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
	//     $screen_name = '@EmfiSecurities';
	//     $getfield = "?screen_name=".$screen_name;
	//     $requestMethod = 'GET';

	//     $twitter = new \App\TwitterAPIExchange($settings);

	//     $tweet =  $twitter->setGetfield($getfield)
	//                  ->buildOauth($url, $requestMethod)
	//                  ->performRequest();

	//     $tweets = json_decode($tweet,1);
	// 	$data = [];
	//     $i = 0;

	//     if(!empty($tweets))
	//     {
	//         foreach($tweets as $tweet)
	//         {
	// 			$data[$i]['link'] = "https://twitter.com/itdoesnotmatter/status/".$tweet['id_str'];
	//             $data[$i]['comment'] = linkify_twitter_status($tweet['text']);
	//             $data[$i]['date'] = date("d M, Y",strtotime($tweet['created_at']));
	//             $i++;
	//         }
	//     }

	//     return $data;
	// }

    $data = [];
    $i = 0;

    if(!empty($tweets))
    {
        foreach($tweets['statuses'] as $tweet)
        {
            $data[$i]['link'] = "https://twitter.com/search?src=typd&".$getfield;
            $data[$i]['comment'] = linkify_twitter_status($tweet['text']);
            $data[$i]['date'] = date("d M, Y",strtotime($tweet['created_at']));
            $i++;
        }
    }

    return $data;
}


function getPeopleTweets($from)
{
    $settings = array(
        'oauth_access_token' => env('TWITTER_ACCESS_TOKEN'),
        'oauth_access_token_secret' => env('TWITTER_ACCESS_TOKEN_SECRET'),
        'consumer_key' => env('TWITTER_CONSUMER_KEY'),
        'consumer_secret' => env('TWITTER_CONSUMER_SECRET')
    );

    $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
    $screen_name = '@EmfiSecurities';
    $getfield = '?screen_name='.$screen_name;
    $requestMethod = 'GET';

    $twitter = new \App\TwitterAPIExchange($settings);

    $tweet =  $twitter->setGetfield($getfield)
                 ->buildOauth($url, $requestMethod)
                 ->performRequest();



    $tweets = json_decode($tweet,1);
    $data = [];
    $i = 0;

    if(!empty($tweets))
    {
        foreach($tweets as $tweet)
        {
            $data[$i]['link'] = "https://twitter.com/itdoesnotmatter/status/".$tweet['id_str'];
            $data[$i]['comment'] = linkify_twitter_status($tweet['text']);
            $data[$i]['date'] = date("d M, Y",strtotime($tweet['created_at']));
            $i++;
        }
    }

    return $data;
}

function linkify_twitter_status($status_text)
{
  // linkify URLs
  $status_text = preg_replace(
    '/(https?:\/\/\S+)/',
    '<a href="\1" target="_blank">\1</a>',
    $status_text
  );

  // linkify twitter users
  $status_text = preg_replace(
    '/(^|\s)@(\w+)/',
    '\1@<a href="http://twitter.com/\2" target="_blank">\2</a>',
    $status_text
  );

  return $status_text;
}


function humanTiming($time) {

    $time = time() - $time; // to get the time since that moment
    $time = ($time < 1) ? 1 : $time;

    $tokens = array(
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit)
            continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '');
    }
}

function removeDir($dir) {
    foreach (glob($dir . "/*.*") as $filename) {
        if (is_file($filename)) {
            unlink($filename);
        }
    }

    if (is_dir($dir . "feature")) {

        foreach (glob($dir . "feature/*.*") as $filename) {
            if (is_file($filename)) {
                unlink($filename);
            }
        }

        rmdir($dir . "feature");
    }

    rmdir($dir);
}

function sendHtmlMail($params) {
    $files = isset($params['files']) ? $params['files'] : array();

    \Mail::send('emails.index', $params, function($message) use ($params, $files) {
        $message->from($params['from'], '');
        $message->to($params['to'], '')->subject($params['subject']);

        if (count($files) > 0) {
            foreach ($files as $file) {
                $message->attach($file['path']);
            }
        }
    });
}

// to generate random string
function getRandomString($len = 30) {
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

    //$chars = "0123456789";
    $r_str = "";
    for ($i = 0; $i < $len; $i++)
        $r_str .= substr($chars, rand(0, strlen($chars)), 1);

    if (strlen($r_str) != $len) {
        $r_str .= getRandomString($len - strlen($r_str));
    }

    return $r_str;
}

// to generate random string number
function getRandomStringNumber($len = 30) {
    // $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $chars = "0123456789";
    $r_str = "";
    for ($i = 0; $i < $len; $i++)
        $r_str .= substr($chars, rand(0, strlen($chars)), 1);

    if (strlen($r_str) != $len) {
        $r_str .= getRandomStringNumber($len - strlen($r_str));
    }

    return $r_str;
}

// for table heading sorting link
function getSortingLink($link, $heading, $field, $curSortBy = '', $curSortOrder = 'asc', $search_field = '', $search_val = '', $extra_params = '') {

    $qs = '?';
    if (strpos($link, '?') != false) {
        $qs = '&';
    }



    if ($field != $curSortBy) {
        $link .= $qs . 'sortBy=' . $field . '&sortOrd=asc';
    } elseif ($field == $curSortBy) {
        if ($curSortOrder == "asc") {
            $link .= $qs . 'sortBy=' . $field . '&sortOrd=desc';
        } elseif ($curSortOrder == "desc") {
            $link .= $qs . 'sortBy=' . $field . '&sortOrd=asc';
        } else {
            $link .= $qs . 'sortBy=' . $field . '&sortOrd=asc';
        }
    }

    if ($search_field != "" && $search_val != "") {
        $link .= '&search_field=' . $search_field . "&search_text=" . $search_val;
    }

    if ($extra_params != "") {
        $link .= "&" . $extra_params;
    }


    return '<a href="' . $link . '">' . $heading . '</a>';
}

function dateFormat($date, $format = '', $withTime = false) {


    if ($date == "0000-00-00 00:00:00" || $date == "0000-00-00" || $date == "0000-00-00 00:00:00000000" || $date == "" || is_null($date)) {
        return '-';
    }

    $temp = '';
    if ($withTime) {
        $temp = ' H:i a';
    }

    if ($format == '') {
        return date(env('APP_DATE_FORMAT', 'Y-m-d') . $temp, strtotime($date));
    } else {
        return date($format, strtotime($date));
    }
}

function downloadFile($filename, $filepath) {
    $fsize = filesize($filepath);
    header('Pragma: public');
    header('Cache-Control: public, no-cache');
    header('Content-Type: ' . mime_content_type($filepath));
    header('Content-Length: ' . $fsize);
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Transfer-Encoding: binary');
    readfile($filepath);
    exit;
}

function isDigits($quantity) {
    return preg_match("/[^0-9]/", $quantity);
}

function displayPrice($price, $with_symbol = 1) {
    if ($with_symbol == 1)
        return "$" . number_format($price, 2);
    else
        return number_format($price, 2);
}

function makeDir($path) {
    if (!is_dir($path)) {
        mkdir($path);
        chmod($path, 0777);
    }
}

function get_month_name($month) {
    $months = array(
        1 => 'January',
        2 => 'February',
        3 => 'March',
        4 => 'April',
        5 => 'May',
        6 => 'June',
        7 => 'July',
        8 => 'August',
        9 => 'September',
        10 => 'October',
        11 => 'November',
        12 => 'December'
    );

    return $months[$month];
}

function NVPToArray($NVPString) {
    $proArray = array();

    while (strlen($NVPString)) {
        // name
        $keypos = strpos($NVPString, '=');
        $keyval = substr($NVPString, 0, $keypos);
        // value
        $valuepos = strpos($NVPString, '&') ? strpos($NVPString, '&') : strlen($NVPString);
        $valval = substr($NVPString, $keypos + 1, $valuepos - $keypos - 1);
        // decoding the respose
        $proArray[$keyval] = urldecode($valval);
        $NVPString = substr($NVPString, $valuepos + 1, strlen($NVPString));
    }

    return $proArray;
}

/**
 * Website General Model Functions
 *
 */
function getRecordsFromSQL($sql, $returnType = "array") {
    $result = \DB::select($sql);

    if ($returnType == "array") {
        $result = json_decode(json_encode($result), true);
    } else {
        return $result;
    }
}

function getRecords($table, $whereArr, $returnType = "array") {
    $result = \DB::table($table)->from($table);

    if (is_array($whereArr) && count($whereArr) > 0) {
        foreach ($whereArr as $field => $value) {
            $result->where($field, $value);
        }
    }

    $result = $result->get();


    if ($returnType == "array") {
        $result = json_decode(json_encode($result), true);
    } else {
        return $result;
    }
}

function GetUserIp() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if (isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';

    /* If Local IP */
    if ($ipaddress == "UNKNOWN" || $ipaddress == "127.0.0.1")
        $ipaddress = '72.229.28.185'; //NY

        /* if($ipaddress == '203.88.138.46') { //GJ
          $ipaddress = '128.101.101.101'; //MN
          $ipaddress = '24.128.151.64'; //Adrian
          $ipaddress = '66.249.69.245'; //CA
          $ipaddress = '72.229.28.185'; //NY
          $ipaddress = '127.0.0.1'; //UNKNOWN
          $ipaddress = '2603:300a:f05:a000:2970:d9ff:9da:ccd6'; //Patrick mobile IPv6
          } */

    return $ipaddress;
}

function getAdminUserTypes()
{
    $array = array();

    $rows = \DB::table("admin_user_types")->get();

    foreach($rows as $row)
    {
        $array[$row->id] = $row->title;
    }

    return $array;
}

function callCustomSP($str)
{
    $db = new \PDO('mysql:host='.env('DB_HOST').';dbname='.env('DB_DATABASE').';charset=utf8',env("DB_USERNAME"), env('DB_PASSWORD'));
    $db->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);
    // $pdo = $db->getPdo();
    $statement = $db->prepare($str);
    $statement->execute();
    return $statement->fetchAll(\PDO::FETCH_ASSOC);
}

function WriteJsonInFile($message=null, $path_file_name= null){

    if(!empty($path_file_name )){
        $file_name = public_path().DIRECTORY_SEPARATOR.$path_file_name;
        $Write_message = json_encode($message);
        fopen($file_name, 'w');
        file_put_contents($file_name, $Write_message, FILE_APPEND);
    }

}

 function WriteLogsInFile($message=null, $file_names=null, $path_file_name= null, $json_encode = false){

    $Current_Date = date("Y-m-d");

    if(!empty($path_file_name )){
        $file_name = public_path().DIRECTORY_SEPARATOR.$path_file_name;
    } else {
        if(!empty($file_names)){
            $file_name = public_path().DIRECTORY_SEPARATOR."logfile/".$file_names."_".$Current_Date.".log";
        } else {
            $file_name = public_path().DIRECTORY_SEPARATOR."logfile/Custom_Log_".$Current_Date.".log";
        }
    }
    if(is_array($message)){
        $Write_message   = print_r($message, true);
    } else {
        $Write_message   = $message. "\r\n " . PHP_EOL;
    }

    if($json_encode){
        $Write_message = json_encode($Write_message);
    }

    if(file_exists($file_name)){
        file_put_contents($file_name, $Write_message, FILE_APPEND);
    } else {
        fopen($file_name, 'w');
        file_put_contents($file_name, $Write_message, FILE_APPEND);
    }
}

function GetDateUsingTimeZone($date, $timezone_to,$format)
{
    // return Carbon::createFromFormat('Y-m-d H:i:s',$date, $timezone_to)->setTimezone('UTC')->format($format);
    return Carbon::createFromFormat($format, $date, $timezone_to)->format($format);
}
function convertDateFromTimezone($date,$timezone,$timezone_to,$format){
     $date = new DateTime($date,new DateTimeZone($timezone));
     $date->setTimezone( new DateTimeZone($timezone_to) );
     return $date->format($format);
}

function adjustBrightness($hex, $steps) {
    // Steps should be between -255 and 255. Negative = darker, positive = lighter
    $steps = max(-255, min(255, $steps));

    // Normalize into a six character long hex string
    $hex = str_replace('#', '', $hex);
    if (strlen($hex) == 3) {
        $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
    }

    // Split into three parts: R, G and B
    $color_parts = str_split($hex, 2);
    $return = '#';

    foreach ($color_parts as $color) {
        $color   = hexdec($color); // Convert to decimal
        $color   = max(0,min(255,$color + $steps)); // Adjust color
        $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
    }

    return $return;
}

function prd($arr){
    echo "<pre>";
    print_r($arr);
    exit();
}