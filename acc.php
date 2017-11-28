<?php
interface model
{
    public function save();
    public function delete();
}
final class account extends activerecord\model 
{
 public $id;
 public $email;
 public $fname;
 public $lname;
 public $phone;
 public $birthday;
 public $gender;
 public $password;
 public static function tname()
 {
        $tableName='accounts';
        return $tableName;
   }
}

?>