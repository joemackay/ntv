<?php
    //http://stackoverflow.com/questions/9558473/youtube-api-php-json-decode
    //https://developers.google.com/youtube/2.0/developers_guide_protocol#Understanding_Video_Entries
    //https://developers.google.com/youtube/2.0/developers_guide_protocol_understanding_video_feeds  
    //http://ecommercedeveloper.com/articles/2407-add-video-with-youtube-api-and-php-client-library-part-one/
    // set feed URL
    //$feedURL = 'https://gdata.youtube.com/feeds/api/users/UCekTpzKodObpOcmvVCFUvTw/uploads';

    // read feed into SimpleXML object
    //$sxml = simplexml_load_file($feedURL);
    //p//rint_r('<pre>');
    //p//rint_r($sxml);
    // 
    /*$json = file_get_contents($url,0,null,null);
    $json_output = json_decode($json);
    echo '<pre>';
    print_r("query results:");
    print_r($json_output);
    '</pre>';
    foreach ( $json_output->data as $data ){
        echo "{$data->id}";
        echo "{$data->title}";
    }      */
    
    
    //$url="https://gdata.youtube.com/feeds/api/users/UCekTpzKodObpOcmvVCFUvTw/uploads?format=5&max-results=20&v=2&alt=jsonc";
    //$url="https://gdata.youtube.com/feeds/api/playlists/snippets?q=NTVKenya&start-index=11&max-results=10&v=2&alt=jsonc";
    $url="https://gdata.youtube.com/feeds/api/users/UCekTpzKodObpOcmvVCFUvTw/playlists?v=2&alt=jsonc";
    $json = file_get_contents($url,0,null,null);
    $json_output = json_decode($json);    
     
    $arrJ = array(); 
    foreach ( $json_output->data->items as $data ){
        $duration = number_format(($data->duration/60), 2);
        $arrJ["playlists"][] = array(
                                "title"=>(string)$data->title, 
                                "thumbnail"=>(string)$data->thumbnail->sqDefault, 
                                "size"=>(string)$data->size, 
                                "playlistid"=>(string)$data->id 
                            );
    }
    
    $callback = $_REQUEST['callback'];
    if($callback){    
        header('Content-Type: text/javascript, charset=UTF-8');    
        echo $callback . '(' . str_replace('\\/', '/', json_encode($arrJ)) . ');';
    }else{    
        header('Content-Type: application/x-json, charset=UTF-8');    
        echo str_replace('\\/', '/', json_encode($arrJ));       
    }  
    
    /*     
    print_r('<pre>');
    print_r($json_output);    */ 
    ?>