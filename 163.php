<?php


function curl_get($url)

{

    $refer = "http://music.163.com/";

    $header[] = "Cookie: " . "appver=1.5.0.75771;";

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);

    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);

    curl_setopt($ch, CURLOPT_REFERER, $refer);

    $output = curl_exec($ch);

    curl_close($ch);

    return $output;

}


//搜索
function music_search($word, $type)  //$word歌曲名  $type 1单曲 10专辑 100歌手 1000歌单 1002用户

{

    $url = "http://music.163.com/api/search/pc";

    $post_data = array(

        's' => $word,

        'offset' => '0',  //偏移量

        'limit' => '20',  //最大数量

        'type' => $type,

    );

    $referrer = "http://music.163.com/";

    $URL_Info = parse_url($url);

    $values = array();

    $result = '';

    $request = '';

    foreach ($post_data as $key => $value) {

        $values[] = "$key=" . urlencode($value);

    }

    $data_string = implode("&", $values);

    if (!isset($URL_Info["port"])) {

        $URL_Info["port"] = 80;

    }

    $request .= "POST " . $URL_Info["path"] . " HTTP/1.1\n";

    $request .= "Host: " . $URL_Info["host"] . "\n";

    $request .= "Referer: $referrer\n";

    $request .= "Content-type: application/x-www-form-urlencoded\n";

    $request .= "Content-length: " . strlen($data_string) . "\n";

    $request .= "Connection: close\n";

    $request .= "Cookie: " . "appver=1.5.0.75771;\n";

    $request .= "\n";

    $request .= $data_string . "\n";

    $fp = fsockopen($URL_Info["host"], $URL_Info["port"]);

    fputs($fp, $request);

    $i = 1;

    while (!feof($fp)) {

        if ($i >= 15) {

            $result .= fgets($fp);

        } else {

            fgets($fp);

            $i++;

        }

    }

    fclose($fp);

    return $result;

}


//获取固定歌曲信息一首
function get_music_info($music_id)

{

    $url = "http://music.163.com/api/song/detail/?id=" . $music_id . "&ids=%5B" . $music_id . "%5D";

    return curl_get($url);

}


//获取歌手以及专辑信息
function get_artist_album($artist_id, $limit)

{

    $url = "http://music.163.com/api/artist/albums/" . $artist_id . "?limit=" . $limit;

    return curl_get($url);

}


//获取专辑信息及专辑每首歌曲信息
function get_album_info($album_id)

{

    $url = "http://music.163.com/api/album/" . $album_id;

    return curl_get($url);

}


//获取歌曲列表
function get_playlist_info($playlist_id)

{

    $url = "http://music.163.com/api/playlist/detail?id=" . $playlist_id;

    return curl_get($url);

}


//获取歌词信息
function get_music_lyric($music_id)

{

    $url = "http://music.163.com/api/song/lyric?os=pc&id=" . $music_id . "&lv=-1&kv=-1&tv=-1";

    return curl_get($url);

}


//获取mv信息
function get_mv_info($mvid)

{

    $url = "http://music.163.com/api/mv/detail?id=".$mvid."&type=mp4";  //319104  喜欢你mvid

    return curl_get($url);

}

//echo get_playlist_info('319104');  //39443443 斑马歌曲id
//echo get_music_info('39443443').'<br/>';
//echo get_music_lyric('319104');

$ID = $_REQUEST['id'];

echo get_music_lyric($ID);

?>