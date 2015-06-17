<?php
/*
 * Copyright (C) 2015 uClass Developers Daniel Holm & Adam Jacobs Feldstein
 *
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */

//Stop cors error
header("Access-Control-Allow-Origin: *");

if ($_GET['apikey'] == "APIKEY") {

// If api key is correct connect to database and make sure it is coded in utf8
header("Content-type: text/html; charset=utf-8");
$servername = "localhost";
$username = "username";
$password = "password";
$database = "database";
mysqli_set_charset("utf8");
$con=mysqli_connect("$servername",$username, $password, $database);
  
   
  // Check connection
  if (mysqli_connect_errno())
  {
   echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

  $query = "SELECT * FROM `table`"; //Change table to yours
  mysqli_query("SET names utf8;");
  mysqli_set_charset("utf8");
  mysqli_set_charset('utf8');
  $rs = mysqli_query($con,$query);

if (mysqli_num_rows($rs) > 0) {
$response["startpage"]= array();

//Construct the Startpage array
while($obj = mysqli_fetch_array($rs)) {
	$info = array(
        "id" => $obj['id'], "title" => utf8_encode($obj['title']), "content" => utf8_encode($obj['content']), "image_url" => utf8_encode($obj['image_url']), "on_link" => utf8_encode($obj['on_link']), "row" => $obj['row'], "position" => $obj['position'], "is_dyn" => $obj['is_dyn'], "dyn_content" => utf8_encode($obj['dyn_content'])

);
 array_push($response["startpage"], $info);
}
    $response["success"] = 1;
	header("Content-type: application/json; charset=utf-8");
	 header("Access-Control-Allow-Origin: *");
    echo json_encode($response);
} 
else {
    $response["success"] = 0;
    $response["message"] = "Inget hittades";
    header("Content-type: application/json; charset=utf-8");
    header("Access-Control-Allow-Origin: *");
    echo json_encode($response);
}
} else {
    $response["success"] = 0;
    $response["message"] = "Felaktiga paramterar";
    header("Content-type: application/json; charset=utf-8");
    header("Access-Control-Allow-Origin: *");
    echo json_encode($response);
}
?>