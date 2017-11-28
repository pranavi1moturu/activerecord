<?php
final class todo extends activerecord\model 
{
    public $id;
        public $owneremail;
	    public $ownerid;
	        public $createddate;
		    public $duedate;
		        public $message;
			    public $isdone;
                      public static function tname()
                      {
        $tableName='todos';
        
        return $tableName;
   }
   }
?>