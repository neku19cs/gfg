<?php
//n[&y-=T8951CmXJ#
class db{
		private $db;
		public function database(){
			$this->db = new mysqli('localhost','id19671654_root','n[&y-=T8951CmXJ#','id19671654_xkcd');
			if(!$this->db->connect_error)
			{
				return $this->db;
			}
            else{
                die('Database Connection Error');
            }
		}
    }
    
?>