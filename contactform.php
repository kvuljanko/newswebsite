<?php
 function strip_crlf($string)
 {
     return str_replace("\r\n", "", $string);
 }
 
$name = $_POST["fname"];
$name = $_POST["lname"];
$email = $_POST["email"];
$country = $_POST["country"];
$subject = $_POST["subject"];
   $toEmail = "kvuljanko@gmail.com";
   $name = strip_crlf($name);
   $email = strip_crlf($email);
if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "The email address is invalid.";
} else {
    // appending \r\n at the end of mailheaders for end
    $mailHeaders = "From: " . $name . "<" . $email . ">\r\n";
    if (mail($toEmail, $subject, $content, $mailHeaders)) {
        $message = "Your contact information is received successfully.";
        $type = "success";
    }
}

?>