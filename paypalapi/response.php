<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once '../vendor/phpmailer/src/Exception.php';
require_once '../vendor/phpmailer/src/PHPMailer.php';
require_once '../vendor/phpmailer/src/SMTP.php';

// passing true in constructor enables exceptions in PHPMailer
$mail = new PHPMailer(true);

//mail->SMTPDebug = SMTP::DEBUG_SERVER; // for detailed debug output
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;

$mail->Username = 'test@gmail.com'; // YOUR gmail email
$mail->Password = 'test'; // YOUR gmail password


use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;

require 'bootstrap.php';


if (empty($_GET['paymentId']) || empty($_GET['PayerID'])) {
    throw new Exception('The response is missing the paymentId and PayerID');
}

$paymentId = $_GET['paymentId'];
$payment = Payment::get($paymentId, $apiContext);

$execution = new PaymentExecution();
$execution->setPayerId($_GET['PayerID']);

try {
    // Take the payment
    $payment->execute($execution, $apiContext);

    try {
        $db = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['name']);

        $payment = Payment::get($paymentId, $apiContext);

        $data = [
            'transaction_id' => $payment->getId(),
            'payment_amount' => $payment->transactions[0]->amount->total,
            'payment_status' => $payment->getState(),
            'invoice_id' => $payment->transactions[0]->invoice_number
        ];
        if (addPayment($data) !== false && $data['payment_status'] === 'approved') {
            // Payment successfully added, redirect to the payment complete page.
            updatePayment($data);

            $order_id = intval($data["invoice_id"]);

            $totalpaid = $data["payment_amount"];

            $useremail = getUserEmail($order_id);

            $receiveremail = getReceiverEmail($order_id);

            //confirmation email to buyer

            $output='<p>Dear user,</p>';
            $output.='<p>Thank you for your order.</p>';
            $output.='<p>The email with the giftcard code(s) will be sent shortly to the provided email address ';
            $output.=$receiveremail;
            $output.= '.</p>';
            $output.='<p>Order number: ';
            $output.=$order_id;
            $output.='</p>';
            $output.='<p>Total paid: &euro;';
            $output.=$totalpaid;
            $output.='</p>';
            $output.='<p>Payment method: Paypal</p>';
            $output.='<p>All the best,</p>';
            $output.='<p>Cardtraders Team</p>';
            $body = $output;
            $subject = "Order confirmation - Cardtraders";

            $email_to = $useremail;

            $mail->setFrom('test@gmail.com', 'Cardtraders');
            $mail->addAddress($email_to, 'Cardtrader User');
            $mail->addReplyTo('test@gmail.com', 'Cardtraders');

            $mail->IsHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;

            $mail->send();


            if (checkAvailable($order_id) == true){

            //send gift card code(s)



                $output2 = getGiftcardCode($order_id);

                $body = $output2;

                $mail->ClearAddresses();

                $email_to = $receiveremail;
                $subject = "Your giftcard(s) - Cardtraders";

                $mail->setFrom('test@gmail.com', 'Cardtraders');
                $mail->addAddress($email_to, 'Cardtrader User');
                $mail->addReplyTo('test@gmail.com', 'Cardtraders');

                $mail->IsHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $body;

                $mail->send();

                //send sell confirmation to selleremail

                sendSellerEmail($order_id);

                //update database (set status gift cards to sold)

                updateDatabase($order_id);

                // remove gift cards from all carts

                emptyCarts($order_id);

                header('location: ../payment-successful.php');
                exit(1);
              }
              else {
                //Problem with order: gift cards sold at same time
                    $output='<p>Dear user,</p>';
                    $output.='<p>Unfortunately, there is a problem with your order.</p>';
                    $output.='<p>We will contact you as soon as possible about a refund or a replacement.</p>';
                    $output.='<p>Order number: ';
                    $output.=$order_id;
                    $output.='</p>';
                    $output.='<p>Total paid: &euro;';
                    $output.=$totalpaid;
                    $output.='</p>';
                    $output.='<p>All the best,</p>';
                    $output.='<p>Cardtraders Team</p>';
                    $body = $output;
                    $subject = "Problem with order - Cardtraders";

                    $mail->ClearAddresses();

                    $email_to = $useremail;

                    $mail->setFrom('test@gmail.com', 'Cardtraders');
                    $mail->addAddress($email_to, 'Cardtrader User');
                    $mail->addReplyTo('test@gmail.com', 'Cardtraders');

                    $mail->IsHTML(true);
                    $mail->Subject = $subject;
                    $mail->Body = $body;

                    $mail->send();

                    header('location: ../order_problem.php');
                    exit(1);



              }


        } else {
            // Payment failed
            header('location: ../paymentfailed.php');
            exit(1);

        }

    } catch (Exception $e) {
        // Failed to retrieve payment from PayPal
        header('location: ../paymentfailed.php');
        exit(1);
    }

} catch (Exception $e) {
    // Failed to take payment
    header('location: ../paymentfailed.php');
    exit(1);
}

/**
 * Add payment to database
 *
 * @param array $data Payment data
 * @return int|bool ID of new payment or false if failed
 */
function addPayment($data)
{
    global $db;

    if (is_array($data)) {
        $stmt = $db->prepare('INSERT INTO `payments` (transaction_id, payment_amount, payment_status, order_id, createdtime) VALUES(?, ?, ?, ?, ?)');
        $stmt->bind_param(
            'sdsss',
            $data['transaction_id'],
            $data['payment_amount'],
            $data['payment_status'],
            $data['invoice_id'],
            date('Y-m-d H:i:s')
        );
        $stmt->execute();
        $stmt->close();

        return $db->insert_id;

    }

    return false;
}

/**
 * Update payment in database
 *
 * @param array $data Payment data
 * @return int|bool ID of new payment or false if failed
 */
function updatePayment($data)
{
    global $db;
    $order_status = "PAID";

    if (is_array($data)) {
        $stmt = $db->prepare('UPDATE tbl_order SET order_status = ? WHERE id= ?');
        $stmt->bind_param(
            'si',
            $order_status,
            $data['invoice_id']
        );
        $stmt->execute();
        $stmt->close();

        return $db->insert_id;

    }

    return false;
}

function getReceiverEmail($order_id)
{
      $servername = "localhost";
      $username = "gcadmin";
      $password = "GCpass@86";
      $dbname = "giftcard";

      // Create connection
      $conn = new mysqli($servername, $username, $password, $dbname);
      // Check connection
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }

      $sql = "SELECT * FROM tbl_order WHERE tbl_order.id = $order_id";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
          //echo "id: " . $row["id"]. " - Customer ID: " . $row["customer_id"]. " " . $row["email"]. "<br>";
          $receiveremail = $row["email"];
        }
      } else {
        echo "0 results";
      }
      $conn->close();
      return $receiveremail;
}


function getUserEmail($order_id)
{
      $servername = "localhost";
      $username = "gcadmin";
      $password = "GCpass@86";
      $dbname = "giftcard";

      // Create connection
      $conn = new mysqli($servername, $username, $password, $dbname);
      // Check connection
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }

      $sql = "SELECT registered_users.email FROM (registered_users INNER JOIN tbl_order ON registered_users.id = tbl_order.customer_id) WHERE tbl_order.id = $order_id";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
          //echo "id: " . $row["id"]. " - Customer ID: " . $row["customer_id"]. " " . $row["email"]. "<br>";
          $useremail = $row["email"];
        }
      } else {
        echo "0 results";
      }
      $conn->close();
      return $useremail;
}

function getGiftcardCode($order_id)
{
      $servername = "localhost";
      $username = "gcadmin";
      $password = "GCpass@86";
      $dbname = "giftcard";

      // Create connection
      $conn = new mysqli($servername, $username, $password, $dbname);
      // Check connection
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }

      $sql = "SELECT giftcard.giftcard_code, giftcard.pincode, giftcard.id, giftcard.brand, giftcard.value, sell_order.price, giftcard.expiration_date
FROM (((giftcard
INNER JOIN tbl_order_item ON giftcard.id = tbl_order_item.product_id)
INNER JOIN tbl_order ON tbl_order_item.order_id = tbl_order.id) INNER JOIN sell_order ON sell_order.giftcard_id = giftcard.id) WHERE tbl_order.id = $order_id";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        // output data of each row
        $output2='<p>Dear user,</p>';
        $output2.='<p>Thank you for choosing Cardtraders, please find your giftcard code(s) below</p>';
        $output2.='<p><table cellpadding="10"><tr><th>Brand</th><th>Value</th><th>Code</th><th>Pincode</th><th>Expiration date</th></tr>';
        while($row = $result->fetch_assoc()) {

          $output2.= '<tr><td>';
          $output2.= $row["brand"];
          $output2.= '</td><td>';
          $output2.= '&euro;';
          $output2.= $row["value"];
          $output2.= '</td><td>';
          $output2.= decrypt($row["giftcard_code"]);
          $output2.= '</td><td>';
          $output2.= $row["pincode"];
          $output2.= '</td><td>';
          $output2.= $row["expiration_date"];
          $output2.= '</td></tr>';
        }
        $output2.= '</table></p><br>';
        $output2.= '<p></p>';
        $output2.= '<p>Best regards,</p>';
        $output2.= '<p>Cardtraders team</p>';

      } else {
        echo "0 results";
      }
      $conn->close();
      return $output2;
}

function sendSellerEmail($order_id)
{
      // passing true in constructor enables exceptions in PHPMailer
      $mail = new PHPMailer(true);

      //mail->SMTPDebug = SMTP::DEBUG_SERVER; // for detailed debug output
      $mail->isSMTP();
      $mail->Host = 'smtp.gmail.com';
      $mail->SMTPAuth = true;
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      $mail->Port = 587;

      $mail->Username = 'test@gmail.com'; // YOUR gmail email
      $mail->Password = 'test'; // YOUR gmail password


      $servername = "localhost";
      $username = "gcadmin";
      $password = "GCpass@86";
      $dbname = "giftcard";

      // Create connection
      $conn = new mysqli($servername, $username, $password, $dbname);
      // Check connection
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }

      $sql = "SELECT registered_users.email, registered_users.username, giftcard.brand, giftcard.value, sell_order.price FROM ((((registered_users INNER JOIN sell_order ON registered_users.id = sell_order.member_id)
      INNER JOIN tbl_order_item ON sell_order.giftcard_id = tbl_order_item.product_id)
      INNER JOIN tbl_order ON tbl_order.id = tbl_order_item.order_id) INNER JOIN giftcard on giftcard.id = tbl_order_item.product_id) where tbl_order.id = $order_id";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {


          $output3= '<p>Dear ';
          $output3.= $row["username"];
          $output3.= ',</p>';
          $output3.= '<p>Congratulations, a gift card you listed for sale on Cardtraders has just been sold!</p>';
          $output3.= '<p>See for details below.</p>';
          $output3.= '<p><table cellpadding="10"><tr><th>Brand</th><th>Value</th><th>Price</th><th>Payout</th></tr>';

          $output3.= '<tr><td>';
          $output3.= $row["brand"];
          $output3.= '</td><td>';
          $output3.= '&euro;';
          $output3.= $row["value"];
          $output3.= '</td><td>';
          $output3.= '&euro;';
          $output3.= $row["price"];
          $output3.= '</td><td>';

          // Calculate payout price

          $price = floatval($row["price"]);
          $payout = 0.95*$price;
          $payoutprice = number_format($payout, 2, '.', '');

          $output3.= '&euro;';
          $output3.= $payoutprice;
          $output3.= '</td></tr>';
          $output3.= '</table></p>';

          $output3.= '<p>You will receive the payout amount in seven days on the PayPal account you provided.';
          $output3.= '<p><Thank you for choosing Cardtraders.</p>';
          $output3.= '<p>Best regards,</p>';
          $output3.= '<p>Cardtraders team</p>';


          $body = $output3;
          $selleremail = $row["email"];

          //echo $selleremail;
          $mail->ClearAddresses();
          $email_to = $selleremail;
          //echo $email_to;
          $subject = "Your giftcard has been sold - Cardtraders";

          $mail->setFrom('test@gmail.com', 'Cardtraders');
          $mail->addAddress($email_to, 'Cardtrader User');
          $mail->addReplyTo('test@gmail.com', 'Cardtraders');

          $mail->IsHTML(true);
          $mail->Subject = $subject;
          $mail->Body = $body;
          //
          $mail->send();

        }
      } else {
        echo "0 results";
      }
      $conn->close();
      return $output3;
}



function updateDatabase($order_id)

{

  $servername = "localhost";
  $username = "gcadmin";
  $password = "GCpass@86";
  $dbname = "giftcard";

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $sql = "SELECT giftcard.id FROM ((tbl_order INNER JOIN tbl_order_item ON tbl_order.id = tbl_order_item.order_id)
  INNER JOIN giftcard ON tbl_order_item.product_id = giftcard.id) WHERE tbl_order.id = $order_id";

  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
      //echo "id: " . $row["id"]. " - Customer ID: " . $row["customer_id"]. " " . $row["email"]. "<br>";
      $giftcard_id = $row["id"];
      $sql2 = "UPDATE giftcard SET sold = 1 WHERE giftcard.id = $giftcard_id";
      $conn->query($sql2);
    }
  } else {
    echo "0 results";
  }
  $conn->close();
  //return $receiveremail;

}

function checkAvailable($order_id)
{

        $servername = "localhost";
        $username = "gcadmin";
        $password = "GCpass@86";
        $dbname = "giftcard";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT giftcard.id, giftcard.sold FROM ((tbl_order INNER JOIN tbl_order_item ON tbl_order.id = tbl_order_item.order_id)
        INNER JOIN giftcard ON tbl_order_item.product_id = giftcard.id) WHERE tbl_order.id = $order_id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          // output data of each row
          while($row = $result->fetch_assoc()) {

            if ($row["sold"] == 1){
              return false;
            }
          }
        } else {
          echo "0 results";
        }
        $conn->close();

        return true;
}


function emptyCarts($order_id)
{

    $servername = "localhost";
    $username = "gcadmin";
    $password = "GCpass@86";
    $dbname = "giftcard";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT giftcard.id FROM ((tbl_order INNER JOIN tbl_order_item ON tbl_order.id = tbl_order_item.order_id)
    INNER JOIN giftcard ON tbl_order_item.product_id = giftcard.id) WHERE tbl_order.id = $order_id";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      // output data of each row
      while($row = $result->fetch_assoc()) {
        //echo "id: " . $row["id"]. " - Customer ID: " . $row["customer_id"]. " " . $row["email"]. "<br>";
        $giftcard_id = $row["id"];
        $sql2 = "DELETE FROM tbl_cart WHERE product_id = $giftcard_id";
        $conn->query($sql2);
      }
    } else {
      echo "0 results";
    }
    $conn->close();
    //return $receiveremail;

}

function encrypt($code){

		// Storing a string into the variable which
		// needs to be Encrypted
		$simple_string = $code;

		// Storingthe cipher method
		$ciphering = "AES-128-CTR";

		// Using OpenSSl Encryption method
		$iv_length = openssl_cipher_iv_length($ciphering);
		$options  = 0;

		// Non-NULL Initialization Vector for encryption
		$encryption_iv = '1234567891011121';

		// Storing the encryption key
		$encryption_key = "fLs6uov1u3lPp5Av";

		// Using openssl_encrypt() function to encrypt the data
		$encryption = openssl_encrypt($simple_string, $ciphering, $encryption_key, $options, $encryption_iv);

		return $encryption;
}


function decrypt($code){

		// Storingthe cipher method
		$ciphering = "AES-128-CTR";

		// Using OpenSSl Encryption method
		$iv_length = openssl_cipher_iv_length($ciphering);
		$options  = 0;

		// Non-NULL Initialization Vector for decryption
		$decryption_iv = '1234567891011121';

		// Storing the decryption key
		$decryption_key = "fLs6uov1u3lPp5Av";

		// Using openssl_decrypt() function to decrypt the data
		$decryption = openssl_decrypt($code, $ciphering, $decryption_key, $options, $decryption_iv);

		return $decryption;

}
