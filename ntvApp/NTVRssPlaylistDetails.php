<?php
    //http://stackoverflow.com/questions/9558473/youtube-api-php-json-decode
    //https://developers.google.com/youtube/2.0/developers_guide_protocol#Understanding_Video_Entries
    //https://developers.google.com/youtube/2.0/developers_guide_protocol_understanding_video_feeds  
    //http://ecommercedeveloper.com/articles/2407-add-video-with-youtube-api-and-php-client-library-part-one/
    
    //understanding playlists https://developers.google.com/youtube/2.0/developers_guide_protocol_playlists
    // set feed URL
    //$feedURL = 'https://gdata.youtube.com/feeds/api/users/UCekTpzKodObpOcmvVCFUvTw/uploads';
    
    
    
    //$playlistId = isset($_GET['playlistId']) ? trim($_GET['playlistId']) : 'PL9A0CFA9ACBBC8BA8';
    $playlistId = trim($_GET['playlistId']);
    
    $start = (isset($_REQUEST['start']) && ($_REQUEST['start'] >0)) ? '&start-index='.($_REQUEST['start'] + 1) : '' ;
    $limit = isset($_REQUEST['limit']) ? '&max-results='.$_REQUEST['limit'] : '&max-results=20' ;
    
    
    $url="https://gdata.youtube.com/feeds/api/playlists/{$playlistId}?v=2{$limit}{$start}&alt=jsonc";
    $json = file_get_contents($url,0,null,null);
    $json_output = json_decode($json);
     
    $arrJ = array(); 
    $arrOuter = array();
    foreach ( $json_output->data->items as $data ){
        $duration = number_format(($data->video->duration/60), 2);
        $arrJ["videos"][] = array(
                                "url"=>(string)$data->video->player->default, 
                                "title"=>(string)$data->video->title, 
                                "intro"=>substr((string)$data->video->description, 0, 150).'...', 
                                "description"=>(string)$data->video->description, 
                                "thumbnail"=>(string)$data->video->thumbnail->sqDefault, 
                                "views"=>(string)$data->video->viewCount, 
                                "rating"=>(string)$data->video->ratingCount, 
                                "duration"=>(string)$duration,
                                "videoid"=>(string)$data->video->id 
                            );
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