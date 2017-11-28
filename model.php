<?php

namespace activerecord;
abstract class model
 {
    protected $tableName;
    public function save()
	    {
	if ($this->id != '')
   {
	$sql = $this->update($this->id);
 	        } 
           else 
           {
		            $sql = $this->insert();
	 }
$db = dbConn::getConnection();
        $statement = $db->prepare($sql);
  //echo '<h2>I just saved record: </h2>';
	echo '<hr/>';
	}
 public static function getTablename()
{
        $tableName='todos';
        return $tableName;
    }


	private function insert()
   {
 $modelName=get_called_class();
 $tableName = $modelName::getTablename();
 $array = get_object_vars($this);
 $data = implode(',', array_flip($array));
 $values = ':'.implode(',:', array_flip($array));
  print_r($data);
  $sql =  'INSERT INTO '.$tableName.' ('.$data.') VALUES (1,"pm@njit.edu","xiu","cde",456,"abc","t","result")';
 
        return $sql;
    }
        private function update($id)
         {
   $modelName=get_called_class();
   $tableName = $modelName::getTablename();
   $array = get_object_vars($this);
   $comma = " ";
   $sql = 'UPDATE '.$tableName.' SET ';
   foreach ($array as $key=>$value)
   {
     if( ! empty($value)) 
   {
   $sql .= $comma . $key . ' = "'. $value .'"';
  $comma = ", ";
  }
}
$sql .= ' WHERE id='.$id;
return $sql;
}
	 public function delete($id)
    {
 $db = dbConn::getConnection();
 $modelName=get_called_class();
 $tableName = $modelName::getTablename();
$sql = 'DELETE FROM '.$tableName.' WHERE id='.$id;
$statement = $db->prepare($sql);
$statement->execute();
    }
}

?>