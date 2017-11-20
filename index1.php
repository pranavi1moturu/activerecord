<?php
define('DATABASE', 'pm487');
define('USERNAME', 'pm487');
define('PASSWORD', 'kKmilXOt');
define('CONNECTION', 'sql2.njit.edu');
class dbConn{
    //variable to hold connection object.
    protected static $db;
    //private construct - class cannot be instatiated externally.
    private function __construct() {
        try {
            // assign PDO object to db variable
            self::$db = new PDO( 'mysql:host=' . CONNECTION .';dbname=' . DATABASE, USERNAME, PASSWORD );
            self::$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        }
        catch (PDOException $e) {
            //Output error - would normally log this to error file rather than output to user.
            echo "Connection Error: " . $e->getMessage();
        }
    }
    // get connection function. Static method - accessible without instantiation
    public static function getConnection()
     {
        //Guarantees single instance, if no connection object exists then create one.
        if (!self::$db) 
        {
            //new connection object.
            new dbConn();
        }
        //return connection.
        return self::$db;
    }
}
class collection
{
protected $html;
static public function create() 
{
$model = new static::$modelName;
return $model;
}
static public function findAll()
{
$db = dbConn::getConnection();
$tableName = get_called_class();
$sql = 'SELECT * FROM ' . $tableName;
$statement = $db->prepare($sql);
$statement->execute();
$class = static::$modelName;
$statement->setFetchMode(PDO::FETCH_CLASS, $class);
$recordsSet =  $statement->fetchAll();
return $recordsSet;
}
static public function findOne($id)
{
$db = dbConn::getConnection();
$tableName = get_called_class();
$sql = 'SELECT * FROM ' . $tableName . ' WHERE id =' . $id;
$statement = $db->prepare($sql);
$statement->execute();
$class = static::$modelName;
$statement->setFetchMode(PDO::FETCH_CLASS, $class);
$recordsSet =  $statement->fetchAll();
return $recordsSet;
}
}
class accounts extends collection
 {
    protected static $modelName = 'account';
}
class todos extends collection
 {
    protected static $modelName = 'todo';
}
class model
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
 $array = get_object_vars($this);
 foreach (array_flip($array) as $key=>$value)
 {
 $statement->bindParam(":$value", $this->$value);
 }
 $statement->execute();
 }
 private function insert()
 {
 $modelName=get_called_class();
 $tableName = $modelName::getTablename();
 $array = get_object_vars($this);
 $data = implode(',', array_flip($array));
 $values = ':'.implode(',:', array_flip($array));
  print_r($data);
  $sql =  'INSERT INTO '.$tableName.' ('.$data.') VALUES ('.$values.')';
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
    

class account extends model 
{
public $id;
public $email;
public $fname;
public $lname;
public $phone;
public $birthday;
public $gender;
public $password;
public static function getTablename()
{
$tableName='accounts';
return $tableName;
    }
}

class todo extends model
 {
 public $id;
 public $owneremail;
 public $ownerid;
 public $createddate;
public $duedate;
 public $message;
public $isdone;
public static function getTablename()
{
        $tableName='todos';
        return $tableName;
    }
}
//Todos table
echo "<div align = 'right'> </div>";
 echo  "<font size = 4> Search for all records in  todo table </font>";
 $records = todos::findAll();  
  $d = '<table border =5>';
  
  $d .= '<tr>';
    foreach($records[0] as $key=>$value)
        {
            $d .= '<th>' . htmlspecialchars($key) . '</th>';
        }
       
    $d .= '</tr>';
    
    foreach($records as $key=>$value)
    {
        $d .= '<tr>';
        
        foreach($value as $key1=>$value1)
        {
            $d .= '<td>' . htmlspecialchars($value1) . '<br></td>';
        }
        $d .= '</tr>';
      
  
    }
    $d .= '</table>';

    print_r($d);
   
//finding one record in todos table
    echo "<font size= 4 >Search for one record by id</font>";
 $record = todos::findOne(4);
  
  
  $d = '<table border =5>';
  $d .= '<tr>';
    foreach($record[0]as $key=>$value)
        {
            $d .= '<th>' . htmlspecialchars($key) . '</th>';
        }
    $d.= '</tr>';
    foreach($record as $key=>$value)
    {
       $d .= '<tr>';
        
       foreach($value as $key1=>$value1)
        {
            $d .= '<td>' . htmlspecialchars($value1) . '<br></td>';
        }
        $d.= '</tr>';
    }
    $d .= '</table>';
    
    print_r($d);
    
   //delete one record in todo table
echo  "<font size=5 >deleting one record</font>";
$record= new todo();
$id=42;
$record->delete($id);
echo '<h2>Record with id: '.$id.' is deleted</h2>';
//'<h3>Todos table After the record is Deleted</h3>';
$record = todos::findAll();
$d = '<table border = 5>';
  
  $d .= '<tr>';
    
    foreach($record[0] as $key=>$value)
        {
            $d .= '<th>' . htmlspecialchars($key) . '</th>';
        }
       
    $d .= '</tr>';
    foreach($record as $key=>$value)
    {
        $d .= '<tr>';
        
        foreach($value as $key1=>$value1)
        {
            $d .= '<td>' . htmlspecialchars($value1) . '<br></td>';
        }
        $d .= '</tr>';
    }
    $d .= '</table>';
echo "<h2>Todos table after the record with specified id is deleted</h2>";
print_r($d);

//update one record in todos table
echo "<font size=5 >Update one record in todos table</font>";
$id=4;
$record = new todo();
$record->id=$id;
$record->owneremail="nav@hotmail.com";
$record->ownerid="20";
$record->createddate="01-02-1995";
$record->duedate="02-01-1995";
$record->message="updated record";
$record->isdone="1";
$record->save();
$record = todos::findAll();
echo "<h2>Record update with id: ".$id."</h2>";
        
$d = '<table border = 5>';
  
  $d.= '<tr>';
    
    foreach($record[0] as $key=>$value)
        {
            $html .= '<th>' . htmlspecialchars($key) . '</th>';
        }
       
    $d .= '</tr>';
    foreach($record as $key=>$value)
    {
        $d .= '<tr>';
        
        foreach($value as $key1=>$value1)
        {
            $d .= '<td>' . htmlspecialchars($value1) . '<br></td>';
        }
        $d .= '</tr>';
    }
    $d .= '</table>';
 echo  "<font size=4> Todos table with specified id is updated </font>";
 print_r($d);

//insert record
   echo "<font size=4> Insert One Record in Todos table</font>";
$record = new todo();
$record->owneremail="ght@njit.edu";
$record->ownerid=4;
$record->createddate="09-10-1994";
$record->duedate="10-13-1994";
$record->message="inserted record";
$record->isdone=1;
$record->save();
$records = todos::findAll();
$d = '<table border = 5>';
$d .= '<tr>';
foreach($records[0] as $key=>$value)
{
$d.= '<th>' . htmlspecialchars($key) . '</th>';
}
       
$d .= '</tr>';
foreach($records as $key=>$value)
{
$d .= '<tr>';
        
foreach($value as $key1=>$value1)
{
$d .= '<td>' . htmlspecialchars($value1) . '<br></td>';
}
$d.= '</tr>';
}
echo "<h3> After Inserting one record</h3>";
$d .= '</table>';
print_r($d);

echo "<h1>Search for all records in accounts table</h1>";
$records = accounts::findAll();
  
  $d = '<table border = 5>';
  
  $d .= '<tr>';
    foreach($records[0] as $key=>$value)
        {
            $d .= '<th>' . htmlspecialchars($key) . '</th>';
        }
       
    $d.= '</tr>';
    foreach($records as $key=>$value)
    {
        $d .= '<tr>';
        
        foreach($value as $key1=>$value1)
        {
            $d .= '<td>' . htmlspecialchars($value1) . '<br></td>';
        }
        $d .= '</tr>';
    }
    $d .= '</table>';
    print_r($d);
    
    //finding one record in accounts table
    echo"<h1>Search for one record in accounts table by id</h1>";
$record = accounts::findOne(2);
  
  $d = '<table border = 6>';
  $d .= '<tr>';
    
    foreach($record[0]as $key=>$value)
        {
            $html .= '<th>' . htmlspecialchars($key) . '</th>';
        }
       
    $d .= '</tr>';

    
    foreach($record as $key=>$value)
    {
       $d .= '<tr>';
        
       foreach($value as $key1=>$value1)
        {
            $d .= '<td>' . htmlspecialchars($value1) . '<br></td>';
        }
        $d.= '</tr>';
    }
    $d .= '</table>';
    
    print_r($d);

//deleting one record in accounts table
echo "<h1>Deleting  One Record in accounts table</h1>";
$record= new account();
$id=9;
$record->delete($id);
echo '<h3>Record with id: '.$id.' is deleted</h3>';
$record = accounts::findAll();

$d= '<table border = 5>';
 
  
  $d .= '<tr>';
    
    foreach($record[0] as $key=>$value)
        {
            $d .= '<th>' . htmlspecialchars($key) . '</th>';
        }
       
    $d .= '</tr>';
    foreach($record as $key=>$value)
    {
        $d.= '<tr>';
        
        foreach($value as $key1=>$value1)
        {
            $d.= '<td>' . htmlspecialchars($value1) . '<br></td>';
        }
        $d .= '</tr>';
    }
    $d .= '</table>';
echo "<h2>Accounts table after deleting one record</h2>";
print_r($d);

//updating record in accounts table
echo "<h2>Updating one record in accounts table</h1>";
$id=4;
$record = new account();
$record->id=$id;
$record->fname="sample";
$record->lname="name";
$record->gender="female";
$record->save();
$record = accounts::findAll();
echo "<h2>Record is updated with id: ".$id."</h3>";
        
$d = '<table border = 5>';

  $d .= '<tr>';
    
    foreach($record[0] as $key=>$value)
        {
            $d .= '<th>' . htmlspecialchars($key) . '</th>';
        }
       
    $d .= '</tr>';
    foreach($record as $key=>$value)
    {
        $d.= '<tr>';
        
        foreach($value as $key1=>$value1)
        {
            $d .= '<td>' . htmlspecialchars($value1) . '<br></td>';
        }
        $d .= '</tr>';
    }
    $d .= '</table>';
 
 print_r($d);
 
 //Inserting one record in accounts table
 echo "<h2> Inserting one record in accounts table</h2>";
$record = new account();
$record->email="janedoe@hotmail.com";
$record->fname="jane";
$record->lname="doe";
$record->phone="565-854-7812";
$record->birthday="02-01-1993";
$record->gender="male";
$record->password="158";
$record->save();
$records = accounts::findAll();
$d = '<table border = 5>';
  $d .= '<tr>';
    foreach($records[0] as $key=>$value)
        {
            $d .= '<th>' . htmlspecialchars($key) . '</th>';
        }
    $d .= '</tr>';
    foreach($records as $key=>$value)
    {
        $d .= '<tr>';
        
        foreach($value as $key1=>$value1)
        {
            $d .= '<td>' . htmlspecialchars($value1) . '<br></td>';
        }
        $d .= '</tr>';
    }
    $d .= '</table>';
echo "<h2>After Inserting one record in accounts table </h2>";
print_r($d);

?>