<?php
class Education{
 
    // database connection and table name
    private $conn;
    private $table_name = "education";
 
    // education properties
    public $id;
    public $university;
    public $name;
    public $startedu;
    public $stopedu;
    
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    // read products
function read(){
 
    // select all query
    $query = "SELECT
                id, university,name, startedu, stopedu
            FROM
                 . $this->table_name" ;
 
    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // execute query
    $stmt->execute();
 
    return $stmt;
}
// create edu
function create(){
 
    // query to insert record
    $query = "INSERT INTO
                " . $this->table_name . "
            SET
                university=:university, name=:name, startedu=:startedu, stopedu=:stopedu";
 
    // prepare query
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->university=htmlspecialchars(strip_tags($this->university));
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->startedu=htmlspecialchars(strip_tags($this->startedu));
    $this->stopedu=htmlspecialchars(strip_tags($this->stopedu));
    
 
    // bind values
    $stmt->bindParam(":university", $this->university);
    $stmt->bindParam(":name", $this->name);
    $stmt->bindParam(":startedu", $this->startedu);
    $stmt->bindParam(":stopedu", $this->stopedu);
   
 
    // execute query
    if($stmt->execute()){
        return true;
    }
 
    return false;
     
}
// used when filling up the update product form
function readOne(){
 
    // query to read single record
    $query = "SELECT
                id, university, name, startedu, stopedu 
            FROM
                " . $this->table_name . " 
            WHERE
                id = ?
            LIMIT
                0,1";
 
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
 
    // bind id of education to be updated
    $stmt->bindParam(1, $this->id);
 
    // execute query
    $stmt->execute();
 
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
    // set values to object properties
    $this->university = $row['university'];
    $this->name = $row['name'];
    $this->startedu = $row['startedu'];
    $this->stopedu = $row['stopedu'];
    
    
}
// update the edu

function update(){
 
    // update query
    $query = "UPDATE
                " . $this->table_name . "
            SET
                university = :university,
                name = :name,
                startedu = :startedu,
                stopedu = :stopedu
                
            WHERE
                id = :id";
 
    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->university=htmlspecialchars(strip_tags($this->university));
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->startedu=htmlspecialchars(strip_tags($this->startedu));
    $this->stopedu=htmlspecialchars(strip_tags($this->stopedu));
   
    $this->id=htmlspecialchars(strip_tags($this->id));
 
    // bind new values
    $stmt->bindParam('university', $this->university);
    $stmt->bindParam(':name', $this->name);
    $stmt->bindParam(':startedu', $this->startedu);
    $stmt->bindParam(':stopedu', $this->stopedu);
    $stmt->bindParam(':id', $this->id);
 
    // execute the query
    if($stmt->execute()){
        return true;
    }
 
    return false;
}
// delete the edu
function delete(){
 
    // delete query
    $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
 
    // prepare query
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->id=htmlspecialchars(strip_tags($this->id));
 
    // bind id of record to delete
    $stmt->bindParam(1, $this->id);
 
    // execute query
    if($stmt->execute()){
        return true;
    }
 
    return false;
}
}
?>