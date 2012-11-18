<?php
class CmsMain extends main {
    var $the_msg;
    var $the_success_msg;
    function __construct() {
        $this->connect();   
    }
    
    public function saveReview($video_id, $comments, $user_id) {
        $sql = "INSERT INTO reviews
                SET
                    video_id={$this->ins_string($_REQUEST['video_id'])},
                    comments={$this->ins_string($_REQUEST['comments'])},
                    user_id={$this->ins_string($_REQUEST['user_id'])},
                    time='".time()."'" ;
        $res = mysql_query($sql) or die(mysql_error()); 
        if($res) {
            return 1;
        }
        return '-1';
             
    }
    
    public function getReviews($videoId) {
        $sql = sprintf("SELECT id, r.video_id, comments, time, username 
                        FROM reviews r 
                        INNER JOIN ntvusers u ON(r.user_id = u.user_id)
                        WHERE video_id = {$this->ins_string($videoId)}");
        $res = mysql_query($sql) or die(mysql_error());
        $array = array();
        if($res) {
            while($row = mysql_fetch_assoc($res)) {
                $row['intro'] = substr($row['comments'], 0, 150);
                $row['date_made'] = date('jS, M, y', $row['time']);
                $array[] = $row;
            }
            //print_r('<pre>');
            //print_r($array);
            return $array;    
        }
        return 0;
    }
}
?>