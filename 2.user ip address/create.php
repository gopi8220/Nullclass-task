<?php
// Connect to the database
$servername = "your_database_server";
$username = "your_username";
$password = "your_password";
$dbname = "login_tracking";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to get user IP address
function get_client_ip() {
    $ip_address = '';

    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ip_address = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ip_address = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ip_address = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ip_address = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ip_address = $_SERVER['REMOTE_ADDR'];
    else
        $ip_address = 'UNKNOWN';

    return $ip_address;
}

// Function to get user agent details
function get_user_agent() {
    $user_agent = $_SERVER['HTTP_USER_AGENT'];

    $browser = '';
    $os = '';
    $device_type = '';

    // Detect browser
    if (strpos($user_agent, 'MSIE') !== FALSE || strpos($user_agent, 'Trident') !== FALSE) {
        $browser = 'Internet Explorer';
    } elseif (strpos($user_agent, 'Edge') !== FALSE) {
        $browser = 'Microsoft Edge';
    } elseif (strpos($user_agent, 'Chrome') !== FALSE) {
        $browser = 'Google Chrome';
    } elseif (strpos($user_agent, 'Firefox') !== FALSE) {
        $browser = 'Mozilla Firefox';
    } elseif (strpos($user_agent, 'Safari') !== FALSE) {
        $browser = 'Safari';
    } else {
        $browser = 'Other';
    }

    // Detect operating system
    if (strpos($user_agent, 'Windows') !== FALSE) {
        $os = 'Windows';
    } elseif (strpos($user_agent, 'Macintosh') !== FALSE) {
        $os = 'Macintosh';
    } elseif (strpos($user_agent, 'Linux') !== FALSE) {
        $os = 'Linux';
    } elseif (strpos($user_agent, 'Android') !== FALSE) {
        $os = 'Android';
    } elseif (strpos($user_agent, 'iOS') !== FALSE) {
        $os = 'iOS';
    } else {
        $os = 'Other';
    }

    // Detect device type
    if (strpos($user_agent, 'Mobile') !== FALSE || strpos($user_agent, 'Android') !== FALSE || strpos($user_agent, 'iPhone') !== FALSE) {
        $device_type = 'Mobile';
    } else {
        $device_type = 'Desktop';
    }

    return array('browser' => $browser, 'os' => $os, 'device_type' => $device_type);
}

// Function to log user login
function log_user_login($user_id) {
    global $conn;

    $ip_address = get_client_ip();
    $user_agent_details = get_user_agent();

    $browser = $user_agent_details['browser'];
    $os = $user_agent_details['os'];
    $device_type = $user_agent_details['device_type'];

    $sql = "INSERT INTO login_history (user_id, ip_address, browser, os, device_type) VALUES ('$user_id', '$ip_address', '$browser', '$os', '$device_type')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Login successful";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Example usage
// Assume you have a user ID stored in a session or retrieved from a database
$user_id = 1;

// Log user login
log_user_login($user_id);

// Display login history
$sql = "SELECT * FROM login_history WHERE user_id = '$user_id' ORDER BY login_time DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>Login History:</h2>";
    echo "<table><tr><th>ID</th><th>IP Address</th><th>Browser</th><th>OS</th><th>Device Type</th><th>Login Time</th></tr>";
    
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["id"]. "</td><td>" . $row["ip_address"]. "</td><td>" . $row["browser"]. "</td><td>" . $row["os"]. "</td><td>" . $row["device_type"]. "</td><td>" . $row["login_time"]. "</td></tr>";
    }
    
    echo "</table>";
} else {
    echo "No login history.";
}

$conn->close();
?>
