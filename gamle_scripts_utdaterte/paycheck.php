<?php
  //set true if you want to use script for billing reports
  //first you need to enable them in your account
  include 'inc/functions.php';
  $billing_reports_enabled = false;
  // check that the request comes from Fortumo server
  if(!in_array($_SERVER['REMOTE_ADDR'],
      array('81.20.151.38', '81.20.148.122', '79.125.125.1', '209.20.83.207','54.72.6.126','54.72.6.27','54.72.6.17','54.72.6.23','79.125.125.1','79.125.5.95','79.125.5.205'))) {
    header("HTTP/1.0 403 Forbidden");
    die("Error: Unknown IP");
  }
  // check the signature
  $secret = 'ee02668128cf77f0a41c3bdb7a189296'; // insert your secret between ''
  if(empty($secret) || !check_signature($_GET, $secret)) {
    header("HTTP/1.0 404 Not Found");
    die("Error: Invalid signature");
  }
  $sender = $_GET['sender'];
  $amount = $_GET['amount'];
  $message_id = $_GET['payment_id'];//unique id
  $uid = $_GET['cuid'];
  //hint:use message_id to log your messages
  //additional parameters: country, price, currency, operator, keyword, shortcode
  // do something with $sender and $message
  define('THRUTT', "Sperredørp!");
include_once("classes/Database.php");
  $db = new database;
  $db->configure();
  $db->connect();
  $status = $_GET['status'];
  $in = print_r($_GET, true);
  $db->query("INSERT INTO `infoin` VALUES(NULL,'".$db->escape($in)."')");
  //$sel = $db->query("SELECT * FROM `paymentcheck` WHERE `mes_id` = '$message_id'");
  if($db->query("INSERT INTO `paymentcheck`(`id`,`mes_id`,`mobil`,`poeng`,`tilpris`,`uid`,`status`,`time`) VALUES(NULL,'$message_id','$sender','$amount','$price','$uid','$status',UNIX_TIMESTAMP())")){
  if($db->query("UPDATE `users` SET `points` = (`points` + ".$amount.") WHERE `id` = '$uid' LIMIT 1")){
      $reply = "Takk for kjøpet $sender!
      Brukeren skal nå ha mottatt poengene.";
      sysmel(1, "Poengkjøp av spiller--<br>".user($uid)." kjøpte $amount poeng!");
      sysmel(2, "Poengkjøp av spiller--<br>".user($uid)." kjøpte $amount poeng!");
      sysmel(28, "Poengkjøp av spiller--<br>".user($uid)." kjøpte $amount poeng!");
  }
  else{
      $reply = "Kjøpet er notert, men ikke lagt til bruker pga. databasefeil. Ta kontakt med Ledelsen asap for å motta poengene dine!";
  }
  }
  else{
  $reply = 'Kjøpet ble ikke gjennomført riktig. Du kan bli belastet uten å motta poengene. Ta kontakt med Ledelsen SNAREST for å motta dine poeng!';
  }
  // print out the reply
  echo($reply);
 //customize this according to your needs
  if($billing_reports_enabled
    && preg_match("/Failed/i", $_GET['status'])
    && preg_match("/MT/i", $_GET['billing_type'])) {
   // find message by $_GET['message_id'] and suspend it
  }
  function check_signature($params_array, $secret) {
    ksort($params_array);
    $str = '';
    foreach ($params_array as $k=>$v) {
      if($k != 'sig') {
        $str .= "$k=$v";
      }
    }
    $str .= $secret;
    $signature = md5($str);
    return ($params_array['sig'] == $signature);
  }
?>