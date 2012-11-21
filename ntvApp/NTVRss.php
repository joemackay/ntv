<?php
    //http://stackoverflow.com/questions/9558473/youtube-api-php-json-decode
    //https://developers.google.com/youtube/2.0/developers_guide_protocol#Understanding_Video_Entries
    //https://developers.google.com/youtube/2.0/developers_guide_protocol_understanding_video_feeds  
    //http://ecommercedeveloper.com/articles/2407-add-video-with-youtube-api-and-php-client-library-part-one/
    // set feed URL
    //$feedURL = 'https://gdata.youtube.com/feeds/api/users/UCekTpzKodObpOcmvVCFUvTw/uploads';
    $start = (isset($_REQUEST['start']) && ($_REQUEST['start'] >0)) ? '&start-index='.($_REQUEST['start'] + 1) : '' ;
    $limit = isset($_REQUEST['limit']) ? '&max-results='.$_REQUEST['limit'] : '&max-results=20' ;
    
    $url="https://gdata.youtube.com/feeds/api/users/UCekTpzKodObpOcmvVCFUvTw/uploads?format=5{$limit}{$start}&v=2&alt=jsonc";
    $json = file_get_contents($url,0,null,null);
    $json_output = json_decode($json);
    //print_r('<pre>');
    //print_r($json_output);
     
    $arrJ = array(); 
    foreach ( $json_output->data->items as $data ){
        $duration = number_format(($data->duration/60), 2);
        $arrJ["videos"][] = array(
                                "url"=>(string)$data->player->default, 
                                "title"=>(string)$data->title, 
                                "intro"=>substr((string)$data->description, 0, 180).'...', 
                                "description"=>(string)$data->description, 
                                "thumbnail"=>(string)$data->thumbnail->sqDefault, 
                                "author"=>(string)$data->uploader, 
                                "views"=>(string)$data->viewCount, 
                                "rating"=>(string)$data->rating, 
                                "duration"=>(string)$duration,
                                "videoid"=>(string)$data->id 
                            );
    }
 
    
	
	$callback = $_REQUEST['callback'];
    //echo "<script type=\"text/javascript\">document.domain = \"http://www.briteskills.com\"</script>";
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