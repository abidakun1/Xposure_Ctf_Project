<?php
session_start();
// Change this connection setting to your preference.
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'admin';
$DATABASE_PASS = '---------------';
$DATABASE_NAME = 'myapp';
// Try and connect using the info above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
        // If there is an error with the connection, stop the script and display the error.
        exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}


// Time to check if the login form data was submitted. The isset() function checks if the form data exists.
if ( !isset($_POST['username'], $_POST['password']) ) {
        // Could not fetch  any data from form subbmission
        exit('Please make sure you filled both the username and password form fields!');
}

// We need to Prepare our SQL. This SQL preparation helps prevent SQL injections
if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
        // Bind parameters (s = string, i = int, b = blob, etc). Since a string is the username in our case, we use "s"
        $stmt->bind_param('s', $_POST['username']);
        $stmt->execute();
        // Store or preserve the results. It helps counter-check if the  user account exists within our database.
        $stmt->store_result();
if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password);
        $stmt->fetch();
        // Account exists, now we verify the password.
        // Note: remember to use password_hash in your registration file to store the hashed passwords.
        if (password_verify($_POST['password'], $password)) {
                // Verification success! User has logged-in!
                // Create sessions, so we know the user is logged in, they basically act like cookies but remember the data on the server.
                session_regenerate_id();
                $_SESSION['loggedin'] = TRUE;
                $_SESSION['name'] = $_POST['username'];
                $_SESSION['id'] = $id;
                echo 'Welcome ' . $_SESSION['name'] . '!';
        } else {
                // Incorrect password
                echo 'Incorrect username and/or password!';
        }
} else {
        // Incorrect username
        echo 'Incorrect username and/or password!';
}

        $stmt->close();

}
?> 



