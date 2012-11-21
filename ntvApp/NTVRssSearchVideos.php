<?php
    //  http://stackoverflow.com/questions/12608231/youtubeapi-search-video-from-a-specific-channel
    
    $query = trim($_GET['sc']);
    
    /**
    *
    */
    $start = (isset($_REQUEST['start']) && ($_REQUEST['start'] >0)) ? '&start-index='.($_REQUEST['start'] + 1) : '' ;
    $limit = isset($_REQUEST['limit']) ? '&max-results='.$_REQUEST['limit'] : '&max-results=20' ;
    
    $url="https://gdata.youtube.com/feeds/api/videos?author=UCekTpzKodObpOcmvVCFUvTw{$limit}{$start}&v=2&alt=jsonc&q=".$query;
    $json = file_get_contents($url,0,null,null);
    $json_output = json_decode($json);
    
    $arrJ = array(); 
    foreach ( $json_output->data->items as $data ){
        $duration = format_time($data->duration);
        $arrJ["videos"][] = array(
                                "url"=>(string)$data->player->default, 
                                "title"=>(string)$data->title, 
                                "intro"=>substr((string)$data->description, 0, 150).'...', 
                                "description"=>(string)$data->description, 
                                "thumbnail"=>(string)$data->thumbnail->sqDefault, 
                                "author"=>(string)$data->uploader, 
                                "views"=>(string)$data->viewCount, 
                                "rating"=>(string)$data->rating, 
                                "duration"=>(string)$duration,
                                "videoid"=>(string)$data->id 
                            );
    }
                                                                     
    function format_time($t,$f=':') { // t = seconds, f = separator 
        $init = $t;
        $hours = floor($init / 3600);
        $minutes = floor(($init / 60) % 60);
        $seconds = $init % 60;
        $str = '';
        if ($hours > abs(0))
            $str .= $hours . ':';
        if ($minutes > abs(0))
            $str .= $minutes .':';
        if ($seconds > abs(0))
            $str .= $seconds;
        return $str;
        //return sprintf("%02d%s%02d%s%02d", floor($t/3600), $f, ($t/60)%60, $f, $t%60);
    }

    
    $callback = $_REQUEST['callback'];
    if($callback){    
        header('Content-Type: text/javascript, charset=UTF-8');
        header('Content-Location: http://www.briteskills.com');        
        echo $callback . '(' . str_replace('\\/', '/', json_encode($arrJ)) . ');';
    }else{    
        header('Content-Type: application/x-json, charset=UTF-8');
        header('Content-Location: http://www.briteskills.com');        
        echo str_replace('\\/', '/', json_encode($arrJ));
    
    }        
    ?>