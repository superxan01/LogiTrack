<?php

// The plain-text password provided by the user
$password = "password";

// Hash the password using the password_hash() function
// PASSWORD_DEFAULT uses the currently recommended hashing algorithm (e.g., bcrypt)
// The function automatically generates a salt and includes it within the resulting hash
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Output the generated hash (for demonstration purposes)
echo "Original Password: " . $password . "\n";
echo "Hashed Password: " . $hashed_password . "\n";

// When a user attempts to log in, you would retrieve the stored hashed password from your database
// and then use password_verify() to check if the provided password matches the hash.
// For example:


?>