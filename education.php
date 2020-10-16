<?php
// include database and object files
require '../config/database.php';
require '<div class=""></div>/objects/education.php';

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$method = $_SERVER['REQUEST_METHOD'];
//if id is in url
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare edu object
$education = new Education($db);

switch ($method) {
    case 'GET':
        // query edu
        $stmt = $education->read();
        $num = $stmt->rowCount();

        // check if more than 0 record found
        if ($num > 0) {

            // edu array
            $education_arr = array();
            $education_arr["records"] = array();

            // retrieve our table contents
            // fetch() is faster than fetchAll()
            // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // extract row
                // this will make $row['name'] to
                // just $name only
                extract($row);

                $education_item = array(
                    "id" => $id,
                    "university" => $university,
                    "name" => $name,
                    "startedu" => $startedu,
                    "stopedu" => $stopedu,

                );

                array_push($education_arr["records"], $education_item);
            }

            // set response code - 200 OK
            http_response_code(200);

            // show products data in json format
            echo json_encode($education_arr);
        }

        // set ID property of record to read
        $education->id = isset($_GET['id']) ? $_GET['id'] : die();

        // read the details of product to be edited
        $education->readOne();

        if ($education->university != null) {
            // create array
            $education_arr = array(
                "id" =>  $education->id,
                "university" => $education->university,
                "name" => $education->name,
                "startedu" => $education->startedu,
                "stopedu" => $education->stopedu

            );

            // set response code - 200 OK
            http_response_code(200);

            // make it json format
            echo json_encode($education_arr);
        } else {
            // set response code - 404 Not found
            http_response_code(404);

            // tell the user product does not exist
            echo json_encode(array("message" => "education does not exist."));
        }

        break;
    case 'POST':
        $data = json_decode(file_get_contents("php://input"));

        // make sure data is not empty
        if (
            !empty($data->university) &&
            !empty($data->name) &&
            !empty($data->startedu) &&
            !empty($data->stopedu)
        ) {

            // set education property values
            $education->university = $data->university;
            $education->name = $data->name;
            $education->startedu = $data->startedu;
            $education->stopedu = $data->stopedu;

            // create the edu
            if ($education->create()) {
                // set response code - 201 created
                http_response_code(201);
                // tell the user
                echo json_encode(array("message" => "Education was created."));
            }
            // if unable to create the edu, tell the user
            else {
                // set response code - 503 service unavailable
                http_response_code(503);
                // tell the user
                echo json_encode(array("message" => "Unable to create education."));
            }
        }
        // tell the user data is incomplete
        else {
            // set response code - 400 bad request
            http_response_code(400);
            // tell the user
            echo json_encode(array("message" => "Unable to create education. Data is incomplete."));
        }
        break;
    case 'PUT':
        // get id of edu to be edited
$data = json_decode(file_get_contents("php://input")); 
// set ID property of edu to be edited
$education->id = $data->id; 
// set edu property values
$education->university = $data->university;
$education->name = $data->name;
$education->startedu = $data->startedu;
$education->stopedu = $data->stopedu; 
// update the edu
if($education->update()){ 
    // set response code - 200 ok
    http_response_code(200); 
    // tell the user
    echo json_encode(array("message" => "edu was updated."));
} 
// if unable to update the edu, tell the user
else{ 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
    echo json_encode(array("message" => "Unable to update edu."));
}
        break;
    case "DELETE":
        // get edu id
$data = json_decode(file_get_contents("php://input"));
// set edu id to be deleted
$education->id = $data->id; 
// delete the edu
if($education->delete()){ 
    // set response code - 200 ok
    http_response_code(200); 
    // tell the user
    echo json_encode(array("message" => "edu was deleted."));
}
 
// if unable to delete the edu
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
    echo json_encode(array("message" => "Unable to delete edu."));
}
        break;
}
//return result as JSON
//echo json_encode($result);

//close db
$db = $database->close();
