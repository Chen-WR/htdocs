<?php

date_default_timezone_set('America/New_York');
// session_start();

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'password');
define('DB_NAME', 'sns');
 
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
// function fetch_user_last_activity($user_id, $link)
// {
//  $query = "
//  SELECT * FROM login_details 
//  WHERE user_id = '$user_id' 
//  ORDER BY last_activity DESC 
//  LIMIT 1
//  ";
// $result = mysqli_query($link, $query);
//  foreach($result as $row)
//  {
//   return $row['last_activity'];
//  }
// }

// function fetch_user_chat_history($from_user_id, $to_user_id, $link)
// {
//  $query = "
//  SELECT * FROM chat_message 
//  WHERE (from_user_id = '".$from_user_id."' 
//  AND to_user_id = '".$to_user_id."') 
//  OR (from_user_id = '".$to_user_id."' 
//  AND to_user_id = '".$from_user_id."') 
//  ORDER BY time_stamp DESC
//  ";
//  $result = mysqli_query($link, $query);
//  $output = '<ul class="list-unstyled">';
//  foreach($result as $row)
//  {
//   $user_name = '';
//   if($row["from_user_id"] == $from_user_id)
//   {
//    $user_name = '<b class="text-success">You</b>';
//   }
//   else
//   {
//    $user_name = '<b class="text-danger">'.get_user_name($row['from_user_id'], $link).'</b>';
//   }
//   $output .= '
//   <li style="border-bottom:1px dotted #ccc">
//    <p>'.$user_name.' - '.$row["chat_message"].'
//     <div align="right">
//      <small><em>'.date("F j, Y, g:i a", $row['time_stamp']).'</em></small>
//     </div>
//    </p>
//   </li>
//   ';
//  }
//  $output .= '</ul>';
//  return $output;
// }

// function get_user_name($user_id, $link)
// {
//  $query = "SELECT username FROM users WHERE id = '$user_id'";
// $result = mysqli_query($link, $query);
//  foreach($result as $row)
//  {
//   return $row['username'];
//  }
// }

// function fetch_group_chat_history($link)
// {
//  $query = "
//  SELECT * FROM chat_message 
//  WHERE to_user_id IS NULL  
//  ORDER BY time_stamp DESC
//  ";


//  $result = mysqli_query($link, $query);



//  $output = '<ul class="list-unstyled">';
//  foreach($result as $row)
//  {
//   $user_name = '';
//   if($row["from_user_id"] == $_SESSION["id"])
//   {
//    $user_name = '<b class="text-success">You</b>';
//   }
//   else
//   {
//    $user_name = '<b class="text-danger">'.get_user_name($row['from_user_id'], $link).'</b>';
//   }

//   $output .= '

//   <li style="border-bottom:1px dotted #ccc">
//    <p>'.$user_name.' - '.$row['chat_message'].' 
//     <div align="right">
//      <small><em>'.date("F j, Y, g:i a", $row['time_stamp']).'</em></small>
//     </div>
//    </p>
//   </li>
//   ';
//  }
//  $output .= '</ul>';
//  return $output;
// }
?>