<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

// connect to the mysql database
$conn = mysqli_connect('feenix-mariadb.swin.edu.au', 's104101431', '211104', 's104101431_db');
mysqli_set_charset($conn, 'utf8');

// get the HTTP method, path and body of the request
$method = $_SERVER['REQUEST_METHOD'];

$request = isset($_SERVER['PATH_INFO']) ? explode('/', trim($_SERVER['PATH_INFO'], '/')) : [];
$input = json_decode(file_get_contents('php://input'),true);


// initialise the table name accordingly
$table = ""; 

// retrieve the main entity type from the path (e.g., /restaurants or /reviews)
$entity = isset($request[0]) ? preg_replace('/[^a-z0-9_]+/i','',array_shift($request)): '';
if (empty($entity)) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Entity is required in the URL path']);
    mysqli_close($conn);
    exit();
}
// retrieve the search key field name and value from the path (if any)
$fld = preg_replace('/[^a-z0-9_]+/i','',array_shift($request));
$key = array_shift($request);

// determine the table based on the entity
if ($entity === 'restaurants') {
    $table = "restaurants";
    $primaryKeyField = "restaurant_id";
} elseif ($entity === 'reviews') {
    $table = "reviews";
    $primaryKeyField = "review_id";
} else {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Invalid entity']);
    mysqli_close($conn);
    exit();
}

// retrieve the data to prepare set values for POST and PUT
if (isset ($input))  {
    $columns = preg_replace('/[^a-z0-9_]+/i','',array_keys($input));
    $values = array_map(function ($value) use ($conn) {
        if ($value===null) return null;
        return mysqli_real_escape_string($conn,(string)$value);
    },array_values($input));

    $set = '';
    for ($i=0;$i<count($columns);$i++) {
        $set.=($i>0?',':'').'`'.$columns[$i].'`=';
        $set.=($values[$i]===null?'NULL':'"'.$values[$i].'"');
    }
}
//create sql
$sql = "";
switch ($method) {
    case 'GET':
        // $sql = "SELECT * FROM `$table`".($key ? " WHERE `$fld`='$key'" : '');
        // break;
        $restaurant_id = isset($_GET['restaurant_id']) ? mysqli_real_escape_string($conn, $_GET['restaurant_id']) : null;
        if ($entity === 'reviews') {
            // Join reviews with users to fetch username
            $sql = "SELECT 
                        reviews.review_id,
                        reviews.restaurant_id,
                        reviews.comment,
                        reviews.rating,
                     
                        users.username
                    FROM 
                        reviews
                    JOIN 
                        users
                    ON 
                        reviews.user_id = users.id";
                    // . ($key ? " WHERE `$fld`='$key'" : '');
            if ($restaurant_id) {
                 $sql .= " WHERE reviews.restaurant_id = '$restaurant_id'";
            }
        } else {
            
            $sql = "SELECT * FROM `$table`" . ($key ? " WHERE `$fld`='$key'" : '');
        }
        break;
    case 'PUT':
        $sql = "UPDATE `$table` SET $set WHERE ".($key ? "`$fld`='$key'" : '0=1');
        break;
    case 'POST':
        if ($entity === 'reviews') {
            $input = json_decode(file_get_contents('php://input'), true);

                // Validate required fields
                if (!isset($input['restaurant_id'], $input['user'], $input['rating'], $input['comment'])) {
                    http_response_code(400); // Bad Request
                    echo json_encode(['error' => 'Missing required fields']);
                    exit();
                }

                // Get user_id from username
                $username = mysqli_real_escape_string($conn, $input['user']);
                $userQuery = "SELECT id FROM users WHERE username = '$username'";
                $userResult = mysqli_query($conn, $userQuery);
                if ($userResult && $userRow = mysqli_fetch_assoc($userResult)) {
                    $user_id = $userRow['id'];
                } else {
                    http_response_code(400); // Bad Request
                    echo json_encode(['error' => 'Invalid username']);
                    exit();
                }

                // Insert the review
                $restaurant_id = mysqli_real_escape_string($conn, $input['restaurant_id']);
                $rating = mysqli_real_escape_string($conn, $input['rating']);
                $comment = mysqli_real_escape_string($conn, $input['comment']);
                

                $sql = "INSERT INTO reviews (restaurant_id, user_id, comment, rating) 
                        VALUES ('$restaurant_id', '$user_id', '$comment', '$rating')";

                if (mysqli_query($conn, $sql)) {
                    echo json_encode(['success' => true, 'review' => [
                        'review_id' => mysqli_insert_id($conn),
                        'restaurant_id' => $restaurant_id,
                        'user' => $username,
                        'comment' => $comment,
                        'rating' => $rating,
                        
                    ]]);
                    exit();
                } else {
                    http_response_code(500); // Internal Server Error
                    echo json_encode(['error' => 'Failed to add review']);
                    exit();
                }
            }    
        break;
    case 'DELETE':
        $fld = $primaryKeyField; // Use the primary key field for deletion
        $key = array_shift($request);
        $sql = "DELETE FROM `$table` WHERE ".($key ? "`$fld`='$key'" : '0=1');
        break;
}

// execute SQL statement
$result = mysqli_query($conn,$sql);

if ($result) {
    header('Content-Type: application/json');
    if ($method == 'GET') {
        echo '[';
        for ($i=0;$i<mysqli_num_rows($result);$i++) {
            echo ($i>0?',':'').json_encode(mysqli_fetch_object($result));
        }
        echo ']';
    } elseif ($method == 'POST') {
        echo json_encode(['id' => mysqli_insert_id($conn)]); // Return the ID of the newly inserted record
    } else {
        if ($method == 'DELETE') {
            echo json_encode([
                'success' => true,
                'deleted_id' => $key
            ]);
        } else {
            echo json_encode(['affected_rows' => mysqli_affected_rows($conn)]);
        }
    }
} else {
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => mysqli_error($conn)]);
}

// close mysql connection
mysqli_close($conn);
?>