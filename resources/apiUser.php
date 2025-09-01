<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

// connect to the mysql database
$conn = mysqli_connect('feenix-mariadb.swin.edu.au', 's104101431', '211104', 's104101431_db');
mysqli_set_charset($conn, 'utf8');

$table = "users";
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    // --- Registration Logic ---
    if (isset($input['action']) && $input['action'] === 'register') {
        // Validate required fields
        $required_fields = ['username', 'email', 'password', 'firstName', 'lastName'];
        foreach ($required_fields as $field) {
            if (empty($input[$field])) {
                echo json_encode(['success' => false, 'error' => 'All fields are required']);
                exit;
            }
        }

        // Backend validation
        if (!preg_match('/^[A-Za-z]+$/', $input['firstName'])) {
            echo json_encode(['success' => false, 'error' => 'First name required (letters only)']);
            exit;
        }
        if (!preg_match('/^[A-Za-z]+$/', $input['lastName'])) {
            echo json_encode(['success' => false, 'error' => 'Last name required (letters only)']);
            exit;
        }
        if (strlen($input['username']) < 3) {
            echo json_encode(['success' => false, 'error' => 'Username must be at least 3 characters']);
            exit;
        }
        if (strlen($input['password']) < 8 || !preg_match('/[\\$\\%\\^\\&\\*]/', $input['password'])) {
            echo json_encode(['success' => false, 'error' => 'Password must be at least 8 characters long and contain a special character']);
            exit;
        }
        if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'error' => 'Invalid email format']);
            exit;
        }

        // Check if username or email already exists
        $username = mysqli_real_escape_string($conn, $input['username']);
        $email = mysqli_real_escape_string($conn, $input['email']);

        $checkSql = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
        $checkResult = mysqli_query($conn, $checkSql);

        if (!$checkResult) {
            echo json_encode(['success' => false, 'error' => 'Database query failed']);
            exit;
        }

        if (mysqli_num_rows($checkResult) > 0) {
            echo json_encode(['success' => false, 'error' => 'Username or email already exists']);
            exit;
        }

        $password = mysqli_real_escape_string($conn, $input['password']);
        $firstName = mysqli_real_escape_string($conn, $input['firstName']);
        $lastName = mysqli_real_escape_string($conn, $input['lastName']);

        // Prepare the insert query
        $sql = "INSERT INTO users (username, email, password, firstName, lastName)
                VALUES ('$username', '$email', '$password', '$firstName', '$lastName')";

        // Execute the registration query
        if (mysqli_query($conn, $sql)) {
            echo json_encode(['success' => true, 'message' => 'Registration successful']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Registration failed: ' . mysqli_error($conn)]);
        }
        exit;
    }

    // --- Login Logic ---
    if (isset($input['username']) && isset($input['password'])) {
        $username = mysqli_real_escape_string($conn, $input['username']);
        $password = mysqli_real_escape_string($conn, $input['password']);

        $sql = "SELECT * FROM `$table` WHERE username = '$username' AND password = '$password'";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            $user = mysqli_fetch_object($result);
            echo json_encode($user); // Will be null if no matching user is found
        } else {
            // Error in the SQL query
            http_response_code(500); // Internal Server Error
            echo json_encode(['error' => 'Database query failed']);
        }
        exit;
    }

    // If neither login nor registration parameters are present
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Invalid request']);

} else {
    // Handle other HTTP methods if needed, otherwise return an error
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Method not allowed']);
}

mysqli_close($conn);
?>