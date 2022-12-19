<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

$sql_smtp="SELECT * FROM tbl_smtp_settings WHERE `id`='1'";
$smtp_res=mysqli_query($mysqli,$sql_smtp);
$smtp_row=mysqli_fetch_assoc($smtp_res);

if($smtp_row['smtp_type']=='server'){
    define("SMTP_HOST",$smtp_row['smtp_host']);
    define("SMTP_EMAIL",$smtp_row['smtp_email']);
    define("SMTP_PASSWORD",$smtp_row['smtp_password']);
    define("SMTP_SECURE",$smtp_row['smtp_secure']);
    define("PORT_NO",$smtp_row['port_no']);
}
else if($smtp_row['smtp_type']=='gmail'){
    define("SMTP_HOST",$smtp_row['smtp_ghost']);
    define("SMTP_EMAIL",$smtp_row['smtp_gemail']);
    define("SMTP_PASSWORD",$smtp_row['smtp_gpassword']);
    define("SMTP_SECURE",$smtp_row['smtp_gsecure']);
    define("PORT_NO",$smtp_row['gport_no']);
}
else{
    define("SMTP_HOST",$smtp_row['smtp_host']);
    define("SMTP_EMAIL",$smtp_row['smtp_email']);
    define("SMTP_PASSWORD",$smtp_row['smtp_password']);
    define("SMTP_SECURE",$smtp_row['smtp_secure']);
    define("PORT_NO",$smtp_row['port_no']);
}

function send_email($recipient_email,$recipient_name,$subject,$message, $is_test=false)
{  
    $mail = new PHPMailer(false);                              // Passing `true` enables exceptions

    //Server settings
    $mail->SMTPDebug = 0; 
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = SMTP_HOST;  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = SMTP_EMAIL;                 // SMTP username
    $mail->Password = SMTP_PASSWORD;                           // SMTP password
    $mail->SMTPSecure = SMTP_SECURE;                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = PORT_NO;                                      // TCP port to connect to

    $mail->AddReplyTo(SMTP_EMAIL, APP_NAME);
    //Recipients
    $mail->setFrom(SMTP_EMAIL, APP_NAME);
    $mail->addAddress($recipient_email, $recipient_name);     // Add a recipient
     
    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    
    if($is_test){
        if($mail->send()){
        	$_SESSION['msg']="test_email_sent"; 
            echo json_encode(array('status' => 1,'msg' => 'Mail sent success'));
        }
        else{
            echo json_encode(array('status' => 0,'msg' => $mail->ErrorInfo));
        }
    }
    else{
        if($mail->send()){
            return true;
        }
        else{
            return false;
        }
    }
  
}