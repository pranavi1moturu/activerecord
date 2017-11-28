<?php
namespace activerecord;
abstract class collection
 {
public static function create()
 {
    $model = new static::$modelName;
    return $model;
    
    }
    public static function findAll() 
    {
    $db = dbConn::getConnection();
    $tableName = get_called_class();
    $sql = 'SELECT * FROM ' . $tableName;
    $statement = $db->prepare($sql);
    $statement->execute();
    $class = static::$modelName;
    $statement->setFetchMode(\PDO::FETCH_CLASS, $class);
    $recordsSet =  $statement->fetchAll();
    return $recordsSet;
    }
	public static function findOne($id)
   {
        $db = dbConn::getConnection();
	$tableName = get_called_class();
	$sql = 'SELECT * FROM ' . $tableName . ' WHERE id =' . $id;
	$statement = $db->prepare($sql);
	$statement->execute();
	$class = static::$modelName;
	$statement->setFetchMode(\PDO::FETCH_CLASS, $class);
	$recordsSet =  $statement->fetchAll();
	return $recordsSet;
	}
	}
 ?>