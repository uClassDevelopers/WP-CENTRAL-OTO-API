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

// Stop/fix cors error
header("Access-Control-Allow-Origin: *");

// If api key is correct connect to database and make sure it is coded in utf8
if ($_GET['apikey'] == "enter new key here please") {
   
    header("Content-type: application/json; charset=utf-8");
    $servername = "";
    $username = "";
    $password = "";
    $database = "";

    mysqli_set_charset("utf8");
    $con=mysqli_connect("$servername",$username, $password, $database);

   
  // Check connection
  if (mysqli_connect_errno())
  {
   echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
    if ($_GET['courses-slider'] == "1") {
      $query = "SELECT * FROM `wp_eter_courses_slider` ORDER BY `id` ASC";
      mysqli_query("SET names utf8;");
      mysqli_set_charset("utf8");
      mysqli_set_charset('utf8');
      $rs = mysqli_query($con,$query);

    if (mysqli_num_rows($rs) > 0) {
        $response["courses-slider"]= array();

        //Construct the Startpage array    
        while($obj = mysqli_fetch_array($rs)) {
          $info = array(
                "id" => $obj['id'], "title" => utf8_encode($obj['title']), "course" => utf8_encode($obj['course']), "image_url" => utf8_encode($obj['image_url']), "on_link" => utf8_encode($obj['on_link']), "row" => $obj['row'], "position" => $obj['position'],"description" => utf8_encode($obj['content'])

        );
         array_push($response["courses-slider"], $info);
        }

        $response["success"] = 1;
        header("Content-type: application/json; charset=utf-8");
        header("Access-Control-Allow-Origin: *");
        echo json_encode($response);
    } else {
        $response["success"] = 0;
        $response["message"] = "Inga slides hittades";
        header("Content-type: application/json; charset=utf-8");
        header("Access-Control-Allow-Origin: *");
        echo json_encode($response);
    }
} else if ($_GET['list-all-courses'] == "1") {
      $query = "SELECT * FROM `wp_term_taxonomy` WHERE parent=".$_GET['parent']." ORDER BY `term_id` ASC";
      mysqli_query("SET names utf8;");
      mysqli_set_charset("utf8");
      mysqli_set_charset('utf8');
      $rs = mysqli_query($con,$query);
      
      $all_courses_posts_url= "http://eter.rudbeck.info/category/kurser/?json=1&count=10&apikey=ErtYnDsKATCzmuf6";
      $json_elements = file_get_contents($all_courses_posts_url);
      $elements_arr = json_decode($json_elements, TRUE);
      //print_r($elements_arr);
        
    if (mysqli_num_rows($rs) > 0) {
    $response["list_all_courses"]= array();

    while($obj = mysqli_fetch_array($rs)) {
         $s_query = "SELECT * FROM `wp_terms` WHERE term_id=".$obj['term_id']." ORDER BY `term_id` ASC";
         mysqli_query("SET names utf8;");
         mysqli_set_charset("utf8");
         mysqli_set_charset('utf8');
         $rs2 = mysqli_query($con,$s_query);
        while($objs = mysqli_fetch_array($rs2)) {
            $info = array(
            "id" => $obj['term_id'], "description" => utf8_encode($obj['description']), "name" => utf8_encode($objs['name']), "parent" => utf8_encode($obj['parent']), "post-count" => utf8_encode($obj['count']), "slug" => utf8_encode($objs['slug'])

        );
        array_push($response["list_all_courses"], $info);  
        }    
    }
    $response["success"] = 1;
    header("Content-type: application/json; charset=utf-8");
    header("Access-Control-Allow-Origin: *");
    //print_r($response);

    //Loop through all courses     
    foreach($response['list_all_courses'] as $index => $course) {
        //Loop through all posts in the wordpress api for courses category
        foreach($elements_arr[posts] as $p => $arr) {
            //Loop through the posts categories
            foreach($arr[categories] as $arr2 => $cat) {
                // If the category id matches the course id push the posts in to elments array ´
                if($course['id'] == $cat['id']) {               
                    array_push($response['list_all_courses'][$index]['elements'][] = $arr);
                }
            }
        }
    }
    //encode the respone in json format
    echo json_encode($response);
    }
    else {
        $response["success"] = 0;
        $response["message"] = "Inga kurser hittades";
        header("Content-type: application/json; charset=utf-8");
        header("Access-Control-Allow-Origin: *");
        echo json_encode($response);
    }   
    } else if($_GET['list-courses-name'] == "1"){
        $query = "SELECT * FROM `wp_term_taxonomy` WHERE parent=".$_GET['parent']." ORDER BY `term_id` ASC";
        mysqli_query("SET names utf8;");
        mysqli_set_charset("utf8");
        mysqli_set_charset('utf8');
        $rs = mysqli_query($con,$query);
        
        if (mysqli_num_rows($rs) > 0) {
            $response["list_courses"]= array();
            
            while($obj = mysqli_fetch_array($rs)) {
                $s_query = "SELECT * FROM `wp_terms` WHERE term_id=".$obj['term_id']." ORDER BY `term_id` ASC";
                mysqli_query("SET names utf8;");
                mysqli_set_charset("utf8");
                mysqli_set_charset('utf8');
                $rs2 = mysqli_query($con,$s_query);
                while($objs = mysqli_fetch_array($rs2)) {
                    $info = array("id" => $obj['term_id'], "description" => utf8_encode($obj['description']), "name" => utf8_encode($objs['name']), "parent" => utf8_encode($obj['parent']), "post-count" => utf8_encode($obj['count']), "slug" => utf8_encode($objs['slug'])
                         );
                array_push($response["list_courses"], $info);  
                }    
            }
            $response["success"] = 1;
            header("Content-type: application/json; charset=utf-8");
            header("Access-Control-Allow-Origin: *");
            echo json_encode($response);   
        }
    } else if($_GET['startpage']='1') {
          $query = "SELECT * FROM `wp_eter_start` ORDER BY id ASC";
          mysqli_query("SET names utf8;");
          mysqli_set_charset("utf8");
          mysqli_set_charset('utf8');
          $rs = mysqli_query($con,$query);

        if (mysqli_num_rows($rs) > 0) {
        $response["startpage"]= array();

        while($obj = mysqli_fetch_array($rs)) {
            $info = array(
                "id" => $obj['id'], "title" => utf8_encode($obj['title']), "content" => utf8_encode($obj['content']), "image_url" => utf8_encode($obj['image_url']), "on_link" => utf8_encode($obj['on_link']), "on_link_to_post" => utf8_encode($obj['on_link_to_post']), "on_link_outbound" => utf8_encode($obj['on_link_outbound']), "row" => $obj['row'], "position" => $obj['position'], "is_dyn" => $obj['is_dyn'], "dyn_content" => utf8_encode($obj['dyn_content'])

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
            $response["message"] = "Inga boxar/slides hittades";
            header("Content-type: application/json; charset=utf-8");
            header("Access-Control-Allow-Origin: *");
            echo json_encode($response);
        }
        }
    }
    else {
        $response["success"] = 0;
        $response["message"] = "Felaktiga paramterar";
        header("Content-type: application/json; charset=utf-8");
        header("Access-Control-Allow-Origin: *");
        echo json_encode($response);
    }
?>