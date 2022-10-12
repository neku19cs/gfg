<?php

require_once __DIR__.'/database-connection.php';




class cron_job
{
    private $db;
    private $query;
    private $response;
    private $data;
    private $user_mail;
    private $send_mail;
    private $count;
    private $header;
    private $comic_desc;
    private $message;
    private $header_array;
    private $url_location;
    private $url_content;
    private $boundary;
    private $msg_body;




    public function send_mail_fun($user_mail){
        $this->send_mail = $user_mail;
      
      
        $url = "https://c.xkcd.com/random/comic/";
	    try {
	        	$head       = get_headers( $url );
                
	        	$comic_link = $head[8];
               
		        preg_match( '/[0-9]+/', $comic_link, $matches );
                print_r($comic_link);
		        $rand_comic = $matches[0];
                
        	}    catch (\Throwable$th) {
	            	print($th->getMessage());
	        }
            // echo $matches ;
           

	$url    = "https://xkcd.com/" .$rand_comic. "/info.0.json";
	$result = json_decode( file_get_contents( $url ), true );
    // echo $url;
    // print_r ($result);


       $title = 'Your New Comic' . $result['safe_title'];

	    $file    = file_get_contents( $result['img'] );
	    $encoded_file = chunk_split( base64_encode( $file ) );   //Embed image in base64 to send with email

	      $attachments[] = array(
	        	'name'     => $result['title'] . '.jpg',
		        'data'     => $encoded_file,
		        'type'     => 'application/pdf',
		        'encoding' => 'base64',
	      );
        //   print_r($attachments);
        
        $Body = '
        <p >Hello Subscriber</p>
        Here is your Comic for the day
        <h3>' . $result['safe_title'] . "</h3>
        <img src='" . $result['img'] . "' alt='some comic hehe'/>
        <br />
        To read the comic head to <a target='_blank' href='https://xkcd.com/" . $result['num'] . "'>Here</a><br /></a>
        ";
		$headers   = array();
	    $headers[] = "To: {$this->send_mail}";
	    $headers[] = 'From: Team Comic <comics@vishal.com>';
    	$headers[] = 'Reply-To: Vishal Jha <vishaljha1006@gmail.com>';
    	$headers[] = 'X-Mailer: PHP/' . phpversion();
        $headers[] = 'MIME-Version: 1.0';
        if ( ! empty( $attachments )) {
            $boundary  = md5( time() );
            $headers[] = 'Content-type: multipart/mixed;boundary="' . $boundary . '"';
        } else {
            $headers[] = 'Content-type: text/html; charset=UTF-8';
        }
            $output   = array();
            $output[] = '--' . $boundary;
            $output[] = 'Content-type: text/html; charset="utf-8"';
            $output[] = 'Content-Transfer-Encoding: 8bit';
            $output[] = '';
            $output[] = $Body;
            $output[] = '';
        foreach ($attachments as $attachment) {
            $output[] = '--' . $boundary;
            $output[] = 'Content-Type: ' . $attachment['type'] . '; name="' . $attachment['name'] . '";';
            if (isset( $attachment['encoding'] )) {
                $output[] = 'Content-Transfer-Encoding: ' . $attachment['encoding'];
            }
            $output[] = 'Content-Disposition: attachment;';
            $output[] = '';
            $output[] = $attachment['data'];
            $output[] = '';
        }
           if( mail( $this->send_mail, $title, implode( "\r\n", $output ), implode( "\r\n", $headers ) )){
                echo "echo";
           }else{
            echo"jg";
           }



	}
	
        
      

    public function __construct()
    {
        $this->db = new db();
        $this->db = $this->db->database();
        $this->query = 'SELECT * FROM user_data WHERE otp=1';
        $this->response = $this->db->query($this->query);
        if ($this->response->num_rows != 0) {
            while($this->data = $this->response->fetch_assoc()){
                $this->count = $this->data['count']+1;
                $this->user_mail= $this->data['email'];
                $this->db->query("UPDATE user_data SET count='$this->count' WHERE email='$this->user_mail'");
                $this->send_mail_fun($this->user_mail);
                
            }
        }
        // $this->db->close();
    }

    public function __destruct()
    {
    
        unset($this->db);
        unset($this->query);
        unset($this->response);
        unset($this->data);
        unset($this->user_mail);
        unset($this->send_mail);
        unset($this->count);
        unset($this->header);
        unset($this->comic_desc);
        unset($this->message);
        unset($this->header_array);
        unset($this->url_location);
        unset($this->url_content);
        unset($this->boundary);
        unset($this->msg_body);
        unset($this->img_content);
        unset($this->img_encoded_content);
    }

}

new cron_job();

?>