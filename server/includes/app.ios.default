<?php 

	include("includes/connection.php");
	include("includes/function.php"); 
	include("language/app_language.php");
	include("smtp_email.php");

	error_reporting(E_ALL);

	$file_path = getBaseUrl();

	date_default_timezone_set("Asia/Kolkata");

	$mysqli->set_charset('utf8mb4');

	define("PACKAGE_NAME",$settings_details['package_name']);
	define("API_PAGE_LIMIT",$settings_details['api_page_limit']);

	// Create a random code start
	function generateRandomPassword($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	// Create a random code end

	//Get thumb image start
	function get_thumb($filename,$thumb_size)
	{	
		$file_path = getBaseUrl();

		return $thumb_path=$file_path.'thumb.php?src='.$filename.'&size='.$thumb_size;
	}
	//Get thumb image end

	// Get user name start
	function get_user_name($user_id)
	{
		global $mysqli;

		$user_qry="SELECT * FROM tbl_users where id='".$user_id."'";
		$user_result=mysqli_query($mysqli,$user_qry);
		$user_row=mysqli_fetch_assoc($user_result);

		return $user_row['name'];
	}
	// Get user name end

	// Get total item start
	function get_total_item($cat_id)
	{ 
		global $mysqli;   

		$qry_places="SELECT COUNT(*) as num FROM tbl_places WHERE p_cat_id='".$cat_id."'";

		$total_places = mysqli_fetch_array(mysqli_query($mysqli,$qry_places));
		$total_places = $total_places['num'];

		return $total_places;
	}
	// Get total item end

	// Get total rate start
	function get_total_rate($post_id)
	{	
		global $mysqli;

		$qry_rate="SELECT COUNT(*) as num FROM tbl_rating WHERE `post_id` ='$post_id'";
		$total_rate = mysqli_fetch_array(mysqli_query($mysqli,$qry_rate));
		$total_rate = $total_rate['num'];

		return $total_rate;
	}
	// Get total rate end

	// Get favourite info start
	function get_favourite_info($place_id,$user_id)
	{
		global $mysqli;

		$sql="SELECT * FROM tbl_favourite WHERE `place_id`='$place_id' AND `user_id`='$user_id'";
		$res=mysqli_query($mysqli,$sql);

		return ($res->num_rows == 1) ? 'true' : 'false';

	}
	// Get favourite info end
	
	$get_method = checkSignSalt($_POST['data']);

	// Get home place start
	if($get_method['method_name']=="get_home")	
	{			
		$jsonObj_1= array();

		$user_id=$get_method['user_id'];

		$query_1="SELECT * FROM tbl_places
		LEFT JOIN tbl_category ON tbl_places.`p_cat_id`= tbl_category.`cid`
		WHERE tbl_places.`place_status`=1 AND tbl_category.`status`=1 AND tbl_places.`place_featured`=1 ORDER BY tbl_places.`p_id` DESC LIMIT 5";

		$sql_1 = mysqli_query($mysqli,$query_1)or die(mysqli_error($mysqli_error));

		while($data_1 = mysqli_fetch_assoc($sql_1))
		{
			$row_1['p_id'] = $data_1['p_id'];
			$row_1['p_cat_id'] = $data_1['p_cat_id'];
			$row_1['place_name'] = $data_1['place_name'];
			$row_1['place_image'] = $file_path.'images/'.$data_1['place_image'];
			$row_1['place_thumb_image'] = get_thumb('images/'.$data_1['place_image'],'500*400');
			$row_1['place_video'] = $data_1['place_video'];
			$row_1['place_description'] = stripslashes($data_1['place_description']);
			$row_1['place_address'] = $data_1['place_address'];
			$row_1['place_email'] = $data_1['place_email'];
			$row_1['place_phone'] = $data_1['place_phone'];
			$row_1['place_website'] = $data_1['place_website'];
			$row_1['place_map_latitude'] = $data_1['place_map_latitude'];
			$row_1['place_map_longitude'] = $data_1['place_map_longitude'];
			$row_1['place_status'] = $data_1['place_status'];
			$row_1['place_rate_avg'] = $data_1['place_rate_avg'];
			$row_1['place_total_rate'] = $data_1['place_total_rate'];
			$row_1['total_views'] = $data_1['total_views'];

			$row_1['cid'] = $data_1['cid'];
			$row_1['category_name'] = $data_1['category_name'];
			$row_1['category_image'] = $file_path.'images/'.$data_1['category_image'];

			$row_1['is_favourite']=get_favourite_info($data_1['p_id'],$user_id);

			array_push($jsonObj_1,$row_1);

		}

		$row['featured_property']=$jsonObj_1;

		$jsonObj_2= array();

		$query_2="SELECT * FROM tbl_places
		LEFT JOIN tbl_category ON tbl_places.`p_cat_id`= tbl_category.`cid`
		WHERE tbl_places.`place_status`=1 AND tbl_category.`status`=1 ORDER BY tbl_places.`total_views` DESC LIMIT 6";

		$sql_2 = mysqli_query($mysqli,$query_2)or die(mysqli_error($mysqli));

		while($data_2 = mysqli_fetch_assoc($sql_2))
		{
			$row_2['p_id'] = $data_2['p_id'];
			$row_2['p_cat_id'] = $data_2['p_cat_id'];
			$row_2['place_name'] = $data_2['place_name'];
			$row_2['place_image'] = $file_path.'images/'.$data_2['place_image'];
			$row_2['place_thumb_image'] = get_thumb('images/'.$data_2['place_image'],'500*400');
			$row_2['place_video'] = $data_2['place_video'];
			$row_2['place_description'] = stripslashes($data_2['place_description']);
			$row_2['place_address'] = $data_2['place_address'];
			$row_2['place_email'] = $data_2['place_email'];
			$row_2['place_phone'] = $data_2['place_phone'];
			$row_2['place_website'] = $data_2['place_website'];
			$row_2['place_map_latitude'] = $data_2['place_map_latitude'];
			$row_2['place_map_longitude'] = $data_2['place_map_longitude'];
			$row_2['place_status'] = $data_2['place_status'];
			$row_2['place_rate_avg'] = $data_2['place_rate_avg'];
			$row_2['place_total_rate'] = $data_2['place_total_rate'];
			$row_2['total_views'] = $data_2['total_views'];

			$row_2['cid'] = $data_2['cid'];
			$row_2['category_name'] = $data_2['category_name'];
			$row_2['category_image'] = $file_path.'images/'.$data_2['category_image'];

			$row_2['is_favourite']=get_favourite_info($data_2['p_id'],$user_id);

			array_push($jsonObj_2,$row_2);

		}

		$row['popular_property']=$jsonObj_2;

		$jsonObj_3= array();	

		$cid=API_CAT_ORDER_BY;

		$query3="SELECT * FROM tbl_category WHERE tbl_category.`status`=1 ORDER BY tbl_category.".$cid." LIMIT 6";
		$sql3 = mysqli_query($mysqli,$query3)or die(mysqli_error($mysqli));

		while($data3 = mysqli_fetch_assoc($sql3))
		{
			
			$row3['cid'] = $data3['cid'];
			$row3['category_name'] = $data3['category_name'];
			$row3['category_image'] = $file_path.'images/'.$data3['category_image'];
			$row3['category_image_thumb'] = get_thumb('images/'.$data3['category_image'],'300*300');
			$row3['total_places'] = get_total_item($row3['cid']);

			array_push($jsonObj_3,$row3);

		}

		$row['cat_list']=$jsonObj_3; 

		$set['PLACE_APP'] = $row;

		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE));
		die();
	}	 
	// Get home place end

	// Get home banner start
	else if($get_method['method_name']=="get_home_banner")
	{

		$jsonObj_2= array();

		$query_all="SELECT * FROM tbl_slider WHERE `status`='1' ORDER BY `id` DESC";

		$sql_all = mysqli_query($mysqli,$query_all) or die(mysqli_error($mysqli));

		while($data = mysqli_fetch_assoc($sql_all))
		{
			$total_views=0;

			$place_id=$data['place_id'];

			switch ($data['slider_type']) {
				case 'Place':

				$query="SELECT tbl_places.`place_name`, tbl_places.`place_image`, tbl_places.`place_total_rate`,tbl_places.`place_rate_avg`,tbl_places.`total_views` FROM tbl_places
				WHERE tbl_places.`place_status`='1' AND tbl_places.`p_id`='$place_id' ORDER BY tbl_places.`p_id` DESC";	

				$sql_res=mysqli_query($mysqli,$query) or die(mysqli_error($mysqli));

				$row_data=mysqli_fetch_assoc($sql_res);

				$slider_title=$row_data['place_name'];
				$image=$data['external_image'];
				$total_views=$row_data['total_views'];
				$place_rate_avg=$row_data['place_rate_avg'];
				$place_total_rate=$row_data['place_total_rate'];

				break;
				
				default:
				$slider_title=$data['slider_title'];
				$image=$data['external_image'];
				break;

			}
			
			if($sql_res->num_rows == 0 AND $data['slider_type']!='external'){
				continue;
			}

			$row2['place_id'] = $data['place_id'];
			$row2['place_type'] = $data['slider_type'];
			$row2['place_name'] = $slider_title;
			$row2['external_image'] = $file_path.'images/'.$image;
			$row2['place_total_rate'] = ($place_total_rate!='') ? $place_total_rate : '';
			$row2['place_rate_avg'] = ($place_rate_avg!='') ? $place_rate_avg : '';
			$row2['external_link'] = ($data['external_url']!='') ? $data['external_url'] : '';
			$row2['total_views'] = $total_views;

			array_push($jsonObj_2,$row2);
			
		}

		$row['PLACE_APP']=$jsonObj_2;
		$set= $row;

		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	}	 
	// Get home banner end

	// Get featured place start	
	else if($get_method['method_name']=='featured_place')   
	{

		$jsonObj= array();   

		$query="SELECT * FROM tbl_places
		LEFT JOIN tbl_category ON tbl_places.`p_cat_id`= tbl_category.`cid`
		WHERE tbl_places.`place_status`=1 AND tbl_category.`status`=1 AND tbl_places.`place_featured`=1 ORDER BY tbl_places.`p_id` DESC";

		$sql = mysqli_query($mysqli,$query)or die(mysqli_error($mysqli_error));

		while($data = mysqli_fetch_assoc($sql))
		{
			$row['p_id'] = $data['p_id'];
			$row['p_cat_id'] = $data['p_cat_id'];
			$row['place_name'] = $data['place_name'];
			$row['place_image'] = $file_path.'images/'.$data['place_image'];
			$row['place_thumb_image'] = get_thumb('images/'.$data['place_image'],'500*400');
			$row['place_video'] = $data['place_video'];
			$row['place_description'] = stripslashes($data['place_description']);
			$row['place_address'] = $data['place_address'];
			$row['place_email'] = $data['place_email'];
			$row['place_phone'] = $data['place_phone'];
			$row['place_website'] = $data['place_website'];
			$row['place_map_latitude'] = $data['place_map_latitude'];
			$row['place_map_longitude'] = $data['place_map_longitude'];
			$row['place_status'] = $data['place_status'];
			$row['place_rate_avg'] = $data['place_rate_avg'];
			$row['place_total_rate'] = $data['place_total_rate'];
			$row['total_views'] = $data['total_views'];

			$row['cid'] = $data['cid'];
			$row['category_name'] = $data['category_name'];
			$row['category_image'] = $file_path.'images/'.$data['category_image'];

			$row['is_favourite']=get_favourite_info($data['p_id'],$get_method['user_id']);

			array_push($jsonObj,$row);

		}
		$set['PLACE_APP'] = $jsonObj;

		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	}
	// Get featured place end	

	// Get popular place start	
	else if($get_method['method_name']=='popular_place')   
	{

		$jsonObj= array();   

		$query="SELECT * FROM tbl_places
		LEFT JOIN tbl_category ON tbl_places.`p_cat_id`= tbl_category.`cid`
		WHERE tbl_places.`place_status`=1 AND tbl_category.`status`=1 ORDER BY tbl_places.`total_views` DESC";

		$sql = mysqli_query($mysqli,$query)or die(mysqli_error($mysqli));

		while($data = mysqli_fetch_assoc($sql))
		{
			$row['p_id'] = $data['p_id'];
			$row['p_cat_id'] = $data['p_cat_id'];
			$row['place_name'] = $data['place_name'];
			$row['place_image'] = $file_path.'images/'.$data['place_image'];
			$row['place_thumb_image'] = get_thumb('images/'.$data['place_image'],'500*400');
			$row['place_video'] = $data['place_video'];
			$row['place_description'] = stripslashes($data['place_description']);
			$row['place_address'] = $data['place_address'];
			$row['place_email'] = $data['place_email'];
			$row['place_phone'] = $data['place_phone'];
			$row['place_website'] = $data['place_website'];
			$row['place_map_latitude'] = $data['place_map_latitude'];
			$row['place_map_longitude'] = $data['place_map_longitude'];
			$row['place_status'] = $data['place_status'];
			$row['place_rate_avg'] = $data['place_rate_avg'];
			$row['place_total_rate'] = $data['place_total_rate'];
			$row['total_views'] = $data['total_views'];

			$row['cid'] = $data['cid'];
			$row['category_name'] = $data['category_name'];
			$row['category_image'] = $file_path.'images/'.$data['category_image'];

			$row['is_favourite']=get_favourite_info($data['p_id'],$get_method['user_id']);

			array_push($jsonObj,$row);

		}

		$set['PLACE_APP'] = $jsonObj;

		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
	}
	// Get popular place end

	// Get all place start
	else if($get_method['method_name']=="get_all_places")		
	{

		$page_limit=API_PAGE_LIMIT;

		$limit=($get_method['page']-1) * $page_limit;
		$jsonObj= array();	

		$query="SELECT * FROM tbl_places
		LEFT JOIN tbl_category ON tbl_places.`p_cat_id`= tbl_category.`cid` 
		where tbl_places.`place_status`='1' AND tbl_category.`status`=1 ORDER BY tbl_places.`p_id` DESC LIMIT $limit,$page_limit";

		$sql = mysqli_query($mysqli,$query);

		while($data = mysqli_fetch_assoc($sql))
		{	

			$row['p_id'] = $data['p_id'];
			$row['p_cat_id'] = $data['p_cat_id'];
			$row['place_name'] = $data['place_name'];
			$row['place_image'] = $file_path.'images/'.$data['place_image'];
			$row['place_thumb_image'] = get_thumb('images/'.$data['place_image'],'500*400');
			$row['place_video'] = $data['place_video'];
			$row['place_description'] = stripslashes($data['place_description']);
			$row['place_address'] = $data['place_address'];
			$row['place_email'] = $data['place_email'];
			$row['place_phone'] = $data['place_phone'];
			$row['place_website'] = $data['place_website'];
			$row['place_map_latitude'] = $data['place_map_latitude'];
			$row['place_map_longitude'] = $data['place_map_longitude'];
			$row['place_status'] = $data['place_status'];
			$row['place_rate_avg'] = $data['place_rate_avg'];
			$row['place_total_rate'] = $data['place_total_rate'];

			$row['cid'] = $data['cid'];
			$row['category_name'] = $data['category_name'];
			$row['category_image'] = $file_path.'images/'.$data['category_image'];

			$row['is_favourite']=get_favourite_info($data['p_id'],$get_method['user_id']);

			array_push($jsonObj,$row);

		}

		$set['PLACE_APP'] = $jsonObj;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE));
		die();

	}
	// Get all place end

	// Get latest place start
	else if($get_method['method_name']=="get_latest_places")		
	{

		$jsonObj= array();	

		$query="SELECT * FROM tbl_places
		LEFT JOIN tbl_category ON tbl_places.`p_cat_id`= tbl_category.`cid`
		WHERE tbl_places.`place_status`='1' AND tbl_category.`status`=1 ORDER BY tbl_places.`p_id` DESC LIMIT 15";

		$sql = mysqli_query($mysqli,$query);

		while($data = mysqli_fetch_assoc($sql))
		{ 
			$row['p_id'] = $data['p_id'];
			$row['p_cat_id'] = $data['p_cat_id'];
			$row['place_name'] = $data['place_name'];
			$row['place_image'] = $file_path.'images/'.$data['place_image'];
			$row['place_thumb_image'] = get_thumb('images/'.$data['place_image'],'500*400');
			$row['place_video'] = $data['place_video'];
			$row['place_description'] = stripslashes($data['place_description']);
			$row['place_address'] = $data['place_address'];
			$row['place_email'] = $data['place_email'];
			$row['place_phone'] = $data['place_phone'];
			$row['place_website'] = $data['place_website'];
			$row['place_map_latitude'] = $data['place_map_latitude'];
			$row['place_map_longitude'] = $data['place_map_longitude'];
			$row['place_status'] = $data['place_status'];
			$row['place_rate_avg'] = $data['place_rate_avg'];
			$row['place_total_rate'] = $data['place_total_rate'];

			$row['cid'] = $data['cid'];
			$row['category_name'] = $data['category_name'];
			$row['category_image'] = $file_path.'images/'.$data['category_image'];

			$row['is_favourite']=get_favourite_info($data['p_id'],$get_method['user_id']);

			array_push($jsonObj,$row);

		}

		$set['PLACE_APP'] = $jsonObj;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE));
		die();

	} 
	// Get latest place end

	// Get nearby place start
	else if($get_method['method_name']=="get_nearby_place")		
	{

		$post_order_by=API_CAT_POST_ORDER_BY;

		$cat_id=$get_method['cat_id'];		

		$jsonObj= array();
		
		$latitude = $get_method['user_lat'];
		$longitude = $get_method['user_long'];

		$distance_limit = $get_method['distance_limit'];

	        $earthRadius = '6371.0'; // In miles(3959)  
	        

	        $sql = mysqli_query($mysqli,"
	        	SELECT p.*,c.*,
	        	ROUND(
	        	$earthRadius * ACOS(  
	        	SIN( $latitude*PI()/180 ) * SIN( place_map_latitude*PI()/180 )
	        	+ COS( $latitude*PI()/180 ) * COS( place_map_latitude*PI()/180 )  *  COS( (place_map_longitude*PI()/180) - ($longitude*PI()/180) )   ) 
	        	, 1)
	        	AS distance                              

	        	FROM
	        	tbl_places p,tbl_category c
	        	WHERE p.p_cat_id= c.cid AND  p.place_status='1' AND c.status='1' AND p.p_cat_id IN (".$cat_id.")      
	        	ORDER BY
	        	distance");

	        while($data = mysqli_fetch_assoc($sql))
	        {

	        	if(round($data['distance']) <=$distance_limit)
	        	{
	        		$row['p_id'] = $data['p_id'];
	        		$row['p_cat_id'] = $data['p_cat_id'];
	        		$row['place_name'] = $data['place_name'];
	        		$row['place_image'] = $file_path.'images/'.$data['place_image'];
	        		$row['place_thumb_image'] = get_thumb('images/'.$data['place_image'],'500*400');
	        		$row['place_video'] = $data['place_video'];
	        		$row['place_description'] = stripslashes($data['place_description']);
	        		$row['place_address'] = $data['place_address'];
	        		$row['place_email'] = $data['place_email'];
	        		$row['place_phone'] = $data['place_phone'];
	        		$row['place_website'] = $data['place_website'];
	        		$row['place_map_latitude'] = $data['place_map_latitude'];
	        		$row['place_map_longitude'] = $data['place_map_longitude'];
	        		$row['place_status'] = $data['place_status'];
	        		$row['place_rate_avg'] = $data['place_rate_avg'];
	        		$row['place_total_rate'] = $data['place_total_rate'];

	        		$row['place_distance'] = $data['distance'].'Km'; 

    				$m  = $row['place_distance']*1000; // amount of "full" meters"
    				//$cm = $rkm % 100; // rest

    				$row['place_distance_m'] = $m.' MT'; 

    				$row['cid'] = $data['cid'];
    				$row['category_name'] = $data['category_name'];
    				$row['category_image'] = $file_path.'images/'.$data['category_image'];	

    				$row['is_favourite']=get_favourite_info($data['p_id'],$get_method['user_id']);			     
    				array_push($jsonObj,$row);
    			}

    		}

    		$set['PLACE_APP'] = $jsonObj;
    		header( 'Content-Type: application/json; charset=utf-8' );
    		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE));
    		die();

    	}	
    	// Get nearby place end

    	// Get place by category start
    	else if($get_method['method_name']=="get_place_by_cat_id")
    	{

    		$page_limit=API_PAGE_LIMIT;

    		$limit=($get_method['page']-1) * $page_limit;

    		$post_order_by=API_CAT_POST_ORDER_BY;

    		$cat_id=$get_method['cat_id'];		


    		$jsonObj= array();

    		$query="SELECT * FROM tbl_places,tbl_category WHERE tbl_places.`p_cat_id`= tbl_category.`cid` and tbl_places.`p_cat_id` ='".$cat_id."' AND tbl_places.`place_status`= 1 AND tbl_category.`status`= 1 ORDER BY tbl_places.`p_id` ".$post_order_by." LIMIT $limit,$page_limit";

    		$sql = mysqli_query($mysqli,$query);

    		while($data = mysqli_fetch_assoc($sql))
    		{
    			$row['p_id'] = $data['p_id'];
    			$row['p_cat_id'] = $data['p_cat_id'];
    			$row['place_name'] = $data['place_name'];
    			$row['place_image'] = $file_path.'images/'.$data['place_image'];
    			$row['place_thumb_image'] = get_thumb('images/'.$data['place_image'],'500*400');
    			$row['place_video'] = $data['place_video'];
    			$row['place_description'] = stripslashes($data['place_description']);
    			$row['place_address'] = $data['place_address'];
    			$row['place_email'] = $data['place_email'];
    			$row['place_phone'] = $data['place_phone'];
    			$row['place_website'] = $data['place_website'];
    			$row['place_map_latitude'] = $data['place_map_latitude'];
    			$row['place_map_longitude'] = $data['place_map_longitude'];
    			$row['place_status'] = $data['place_status'];
    			$row['place_rate_avg'] = $data['place_rate_avg'];
    			$row['place_total_rate'] = $data['place_total_rate'];

    			$row['cid'] = $data['cid'];
    			$row['category_name'] = $data['category_name'];
    			$row['category_image'] = $file_path.'images/'.$data['category_image'];

    			$row['is_favourite']=get_favourite_info($data['p_id'],$get_method['user_id']);	

    			array_push($jsonObj,$row);

    		}

    		$set['PLACE_APP'] = $jsonObj;
    		header( 'Content-Type: application/json; charset=utf-8' );
    		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE));
    		die();
    	}	
    	// Get place by category end

    	// Get place by category start
    	else if($get_method['method_name']=="get_search_place")
    	{

    		$search_text=addslashes(trim($get_method['search_text']));	

    		$jsonObj= array();	

    		$query="SELECT * FROM tbl_places
    		LEFT JOIN tbl_category ON tbl_places.`p_cat_id`= tbl_category.`cid`
    		WHERE tbl_places.`place_status`='1' AND tbl_places.`place_name` LIKE '%$search_text%'  AND tbl_category.`status`=1 ORDER BY tbl_places.`place_name` DESC";

    		$sql = mysqli_query($mysqli,$query)or die(mysqli_error($mysqli));

    		while($data = mysqli_fetch_assoc($sql))
    		{
    			$row['p_id'] = $data['p_id'];
    			$row['p_cat_id'] = $data['p_cat_id'];
    			$row['place_name'] = $data['place_name'];
    			$row['place_image'] = $file_path.'images/'.$data['place_image'];
    			$row['place_thumb_image'] = get_thumb('images/'.$data['place_image'],'500*400');
    			$row['place_video'] = $data['place_video'];
    			$row['place_description'] = stripslashes($data['place_description']);
    			$row['place_address'] = $data['place_address'];
    			$row['place_email'] = $data['place_email'];
    			$row['place_phone'] = $data['place_phone'];
    			$row['place_website'] = $data['place_website'];
    			$row['place_map_latitude'] = $data['place_map_latitude'];
    			$row['place_map_longitude'] = $data['place_map_longitude'];
    			$row['place_status'] = $data['place_status'];
    			$row['place_rate_avg'] = $data['place_rate_avg'];
    			$row['place_total_rate'] = $data['place_total_rate'];

    			$row['cid'] = $data['cid'];
    			$row['category_name'] = $data['category_name'];
    			$row['category_image'] = $file_path.'images/'.$data['category_image'];

    			$row['is_favourite']=get_favourite_info($data['p_id'],$get_method['user_id']);	

    			array_push($jsonObj,$row);
    		}

    		$set['PLACE_APP'] = $jsonObj;

    		header( 'Content-Type: application/json; charset=utf-8' );
    		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    		die();
    	}	
    	// Get place by category end

    	// Get single place start
    	else if($get_method['method_name']=="get_single_place")
    	{

    		$jsonObj= array();

    		$latitude = $get_method['user_lat'];
    		$longitude = $get_method['user_long'];

	        $earthRadius = '6371.0'; // In miles(3959)  
	        

	        $sql = mysqli_query($mysqli,"
	        	SELECT p.*,c.*,
	        	ROUND(
	        	$earthRadius * ACOS(  
	        	SIN( $latitude*PI()/180 ) * SIN( place_map_latitude*PI()/180 )
	        	+ COS( $latitude*PI()/180 ) * COS( place_map_latitude*PI()/180 )  *  COS( (place_map_longitude*PI()/180) - ($longitude*PI()/180) )   ) 
	        	, 1)
	        	AS distance                              

	        	FROM
	        	tbl_places p,tbl_category c
	        	WHERE p.p_cat_id= c.cid AND  p.place_status='1' AND c.status='1' AND	p.p_id='".$get_method['place_id']."'                
	        	ORDER BY
	        	distance");

	        while($data = mysqli_fetch_assoc($sql))
	        {

	        	$row['p_id'] = $data['p_id'];
	        	$row['p_cat_id'] = $data['p_cat_id'];
	        	$row['place_name'] = $data['place_name'];
	        	$row['place_image'] = $file_path.'images/'.$data['place_image'];
	        	$row['place_thumb_image'] = get_thumb('images/'.$data['place_image'],'500*400');
	        	$row['place_video'] = $data['place_video'];
	        	$row['place_description'] = stripslashes($data['place_description']);
	        	$row['place_address'] = $data['place_address'];
	        	$row['place_email'] = $data['place_email'];
	        	$row['place_phone'] = $data['place_phone'];
	        	$row['place_website'] = $data['place_website'];
	        	$row['place_map_latitude'] = $data['place_map_latitude'];
	        	$row['place_map_longitude'] = $data['place_map_longitude'];
	        	$row['place_status'] = $data['place_status'];
	        	$row['place_rate_avg'] = $data['place_rate_avg'];
	        	$row['place_total_rate'] = $data['place_total_rate'];
	        	$row['total_users'] = get_total_rate($data['p_id']);
	        	$row['total_views'] = $data['total_views'];

	        	$row['place_distance'] = $data['distance'].'Km'; 

				$m  = $row['place_distance']*1000; // amount of "full" meters"

				$row['place_distance_m'] = $m.' MT'; 

				$row['category_map_icon'] = $file_path.'images/'.$data['category_map_icon'];
				$row['cid'] = $data['cid'];
				$row['category_name'] = $data['category_name'];
				$row['category_image'] = $file_path.'images/'.$data['category_image'];

				$row['is_favourite']=get_favourite_info($data['p_id'],$get_method['user_id']);


				$wall_query="SELECT * FROM tbl_place_gallery WHERE `place_id`='".$get_method['place_id']."'";

				$wall_sql = mysqli_query($mysqli,$wall_query);

				if($wall_sql->num_rows > 0)
				{	
					while($wall_data = mysqli_fetch_assoc($wall_sql))
					{
						$row1['image_name'] = $file_path.'images/gallery/'.$wall_data['image_name'];

						$row['gallery'][]=$row1;
					}

				}
				else
				{
					$row['gallery']=array();
				}
			  //Rating
				$qry1="SELECT * FROM tbl_rating WHERE `post_id` ='".$get_method['place_id']."'";
				$result1=mysqli_query($mysqli,$qry1); 

				if($result1->num_rows > 0)
				{
					while ($user_rating=mysqli_fetch_array($result1)) {

						$row3['id'] = $user_rating['id'];
		 		      	//$row3['place_id'] = $user_rating['place_id'];
		 		      	//$row3['user_id'] = $user_rating['user_id'];
						$row3['user_name'] = get_user_name($user_rating['user_id']);
 		 		      	//$row3['ip'] =$user_rating['ip'];
						$row3['rate'] =$user_rating['rate'];
		 		      	//$row3['dt_rate'] = date('d M Y',strtotime($user_rating['dt_rate']));
						$row3['message'] = $user_rating['message'];

						$row['Ratings'][]= $row3;
					}
				}
				else
				{	

					$row['Ratings'][]= '';
				}

				array_push($jsonObj,$row);

			}

			$view_qry=mysqli_query($mysqli,"UPDATE tbl_places SET `total_views` = total_views + 1 WHERE `p_id` = '".$get_method['place_id']."'");

			$set['PLACE_APP'] = $jsonObj;

			header( 'Content-Type: application/json; charset=utf-8' );
			echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE));
			die();

		}  
		// Get single place end

		// Get user register start
		else if($get_method['method_name']=="user_register")
		{

		$user_type=trim($get_method['type']); //Google, Normal, Facebook

		$email=addslashes(trim($get_method['email']));
		$auth_id=addslashes(trim($get_method['auth_id']));

		$to = $get_method['email'];
		$recipient_name=$get_method['name'];
		// subject
		$subject = '[IMPORTANT] '.APP_NAME.'Registration completed';

		if($user_type=='Google' || $user_type=='google'){
			// register with google

			$sql="SELECT * FROM tbl_users WHERE (`email` = '$email' OR `auth_id`='$auth_id') AND `user_type`='Google'";
			$res=mysqli_query($mysqli,$sql);
			$num_rows = mysqli_num_rows($res);
			$row = mysqli_fetch_assoc($res);

			if($num_rows == 0)
			{
				// data is not available
				$data = array(
					'user_type'=>'Google',
					'auth_id'  =>  $auth_id,
					'name'  => addslashes(trim($get_method['name'])),				    
					'email'  =>  addslashes(trim($get_method['email'])),
					'password'  =>  trim($get_method['password']),
					'phone'  =>  addslashes(trim($get_method['phone'])),
					'registration_on'  =>  strtotime(date('d-m-Y h:i:s A')), 
					'status'  =>  '1'
				);		

				$qry = Insert('tbl_users',$data);

				$user_id=mysqli_insert_id($mysqli);

				$sql="SELECT * FROM tbl_active_log WHERE `user_id`='$user_id'";
				$res=mysqli_query($mysqli, $sql);

				if(mysqli_num_rows($res) == 0){
	                // insert active log

					$data_log = array(
						'user_id'  =>  $user_id,
						'date_time'  =>  strtotime(date('d-m-Y h:i:s A'))
					);

					$qry = Insert('tbl_active_log',$data_log);

				}
				else{
	                // update active log
					$data_log = array(
						'date_time'  =>  strtotime(date('d-m-Y h:i:s A'))
					);

					$update=Update('tbl_active_log', $data_log, "WHERE user_id = '$user_id'");  
				}

				mysqli_free_result($res);

				$set['PLACE_APP'][]=array('user_id' => strval($user_id),'name'=>$get_method['name'],'email'=>$get_method['email'], 'success'=>'1', 'msg' =>'', 'auth_id' => $auth_id);
			}
			else{
				$data = array(
					'auth_id'  =>  $auth_id,
				); 

				$update=Update('tbl_users', $data, "WHERE id = '".$row['id']."'");

				if($row['status']==0)
				{
					$set['PLACE_APP'][]=array('msg' =>$app_lang['account_deactive'],'success'=>'0');
				}	
				else
				{
					$user_id=$row['id'];

					$sql="SELECT * FROM tbl_active_log WHERE `user_id`='$user_id'";
					$res=mysqli_query($mysqli, $sql);

					if(mysqli_num_rows($res) == 0){
	                    // insert active log

						$data_log = array(
							'user_id'  =>  $user_id,
							'date_time'  =>  strtotime(date('d-m-Y h:i:s A'))
						);

						$qry = Insert('tbl_active_log',$data_log);

					}
					else{
	                    // update active log
						$data_log = array(
							'date_time'  =>  strtotime(date('d-m-Y h:i:s A'))
						);

						$update=Update('tbl_active_log', $data_log, "WHERE user_id = '$user_id'");  
					}

					mysqli_free_result($res);

					$message='<div style="background-color: #eee;" align="center"><br />
					<table style="font-family: OpenSans,sans-serif; color: #666666;" border="0" width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="#FFFFFF">
					<tbody>
					<tr>
					<td colspan="2" bgcolor="#FFFFFF" align="center" ><img src="'.$file_path.'images/'.APP_LOGO.'" alt="logo" /></td>
					</tr>
					<br>
					<br>
					<tr>
					<td colspan="2" bgcolor="#FFFFFF" align="center" style="padding-top:25px;">
					<img src="'.$file_path.'assets/images/thankyoudribble.gif" alt="header" auto-height="100" width="50%"/>
					</td>
					</tr>
					<tr>
					<td width="600" valign="top" bgcolor="#FFFFFF">
					<table style="font-family:OpenSans,sans-serif; color: #666666; font-size: 10px; padding: 15px;" border="0" width="100%" cellspacing="0" cellpadding="0" align="left">
					<tbody>
					<tr>
					<td valign="top">
					<table border="0" align="left" cellpadding="0" cellspacing="0" style="font-family:OpenSans,sans-serif; color: #666666; font-size: 10px; width:100%;">
					<tbody>
					<tr>
					<td>
					<p style="color: #717171; font-size: 28px; margin-top:0px; margin:0 auto; text-align:center;"><strong>Welcome, '.cleanInput($get_method['name']).'</strong></p>
					<br>
					<p style="color:#15791c; font-size:20px; line-height:32px;font-weight:500;margin-bottom:30px; margin:0 auto; text-align:center;">You have sucsessfully registration with google<br /></p>
					<br/>
					<p style="color:#999; font-size:18px; line-height:32px;font-weight:500;">Thank you for using '.APP_NAME.'</p>
					</td>
					</tr>
					</tbody>
					</table>
					</td>
					</tr>
					</tbody>
					</table>
					</td>
					</tr>
					<tr>
					<td style="color: #262626; padding: 20px 0; font-size: 20px; border-top:5px solid #52bfd3;" colspan="2" align="center" bgcolor="#ffffff">Copyright © '.APP_NAME.'.</td>
					</tr>
					</tbody>
					</table>
					</div>';

					send_email($to,$recipient_name,$subject,$message);

					$set['PLACE_APP'][]=array('user_id' => $row['id'], 'name'=>$row['name'], 'email'=>$row['email'], 'success'=>'1', 'msg' =>'', 'auth_id' => $auth_id);
				}
			}

		}
		else if($user_type=='Facebook' || $user_type=='facebook'){
			// register with facebook

			$sql="SELECT * FROM tbl_users WHERE (`email` = '$email' OR `auth_id`='$auth_id') AND `user_type`='Facebook'";
			$res=mysqli_query($mysqli,$sql);
			$num_rows = mysqli_num_rows($res);
			$row = mysqli_fetch_assoc($res);

			if($num_rows == 0)
			{

    			// data is not available
				$data = array(
					'user_type'=>'Facebook',
					'name'  => addslashes(trim($get_method['name'])),				    
					'email'  =>  addslashes(trim($get_method['email'])),
					'password'  =>  trim($get_method['password']),
					'phone'  =>  addslashes(trim($get_method['phone'])),
					'registration_on'  =>  strtotime(date('d-m-Y h:i:s A')),
					'auth_id'  =>  $auth_id, 
					'status'  =>  '1'
				);		

				$qry = Insert('tbl_users',$data);

				$user_id=mysqli_insert_id($mysqli);

				$sql="SELECT * FROM tbl_active_log WHERE `user_id`='$user_id'";
				$res=mysqli_query($mysqli, $sql);

				if(mysqli_num_rows($res) == 0){
                    // insert active log

					$data_log = array(
						'user_id'  =>  $user_id,
						'date_time'  =>  strtotime(date('d-m-Y h:i:s A'))
					);

					$qry = Insert('tbl_active_log',$data_log);

				}
				else{
                    // update active log
					$data_log = array(
						'date_time'  =>  strtotime(date('d-m-Y h:i:s A'))
					);

					$update=Update('tbl_active_log', $data_log, "WHERE user_id = '$user_id'");  
				}

				mysqli_free_result($res);

				$set['PLACE_APP'][]=array('user_id' => strval($user_id),'name'=>$get_method['name'],'email'=>$get_method['email'], 'success'=>'1', 'msg' =>'', 'auth_id' => $auth_id);
			}
			else{
				$data = array(
					'auth_id'  =>  $auth_id,
				); 

				$update=Update('tbl_users', $data, "WHERE id = '".$row['id']."'");

				if($row['status']==0)
				{
					$set['PLACE_APP'][]=array('msg' =>$app_lang['account_deactive'],'success'=>'0');
				}	
				else
				{
					$user_id=$row['id'];

					$sql="SELECT * FROM tbl_active_log WHERE `user_id`='$user_id'";
					$res=mysqli_query($mysqli, $sql);

					if(mysqli_num_rows($res) == 0){
                        // insert active log

						$data_log = array(
							'user_id'  =>  $user_id,
							'date_time'  =>  strtotime(date('d-m-Y h:i:s A'))
						);

						$qry = Insert('tbl_active_log',$data_log);

					}
					else{
                        // update active log
						$data_log = array(
							'date_time'  =>  strtotime(date('d-m-Y h:i:s A'))
						);

						$update=Update('tbl_active_log', $data_log, "WHERE user_id = '$user_id'");  
					}

					mysqli_free_result($res);

					$message='<div style="background-color: #eee;" align="center"><br />
					<table style="font-family: OpenSans,sans-serif; color: #666666;" border="0" width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="#FFFFFF">
					<tbody>
					<tr>
					<td colspan="2" bgcolor="#FFFFFF" align="center" ><img src="'.$file_path.'images/'.APP_LOGO.'" alt="logo" /></td>
					</tr>
					<br>
					<br>
					<tr>
					<td colspan="2" bgcolor="#FFFFFF" align="center" style="padding-top:25px;">
					<img src="'.$file_path.'assets/images/thankyoudribble.gif" alt="header" auto-height="100" width="50%"/>
					</td>
					</tr>
					<tr>
					<td width="600" valign="top" bgcolor="#FFFFFF">
					<table style="font-family:OpenSans,sans-serif; color: #666666; font-size: 10px; padding: 15px;" border="0" width="100%" cellspacing="0" cellpadding="0" align="left">
					<tbody>
					<tr>
					<td valign="top">
					<table border="0" align="left" cellpadding="0" cellspacing="0" style="font-family:OpenSans,sans-serif; color: #666666; font-size: 10px; width:100%;">
					<tbody>
					<tr>
					<td>
					<p style="color: #717171; font-size: 28px; margin-top:0px; margin:0 auto; text-align:center;"><strong>Welcome, '.cleanInput($get_method['name']).'</strong></p>
					<br>
					<p style="color:#15791c; font-size:20px; line-height:32px;font-weight:500;margin-bottom:30px; margin:0 auto; text-align:center;">You have sucsessfully registration with google<br /></p>
					<br/>
					<p style="color:#999; font-size:18px; line-height:32px;font-weight:500;">Thank you for using '.APP_NAME.'</p>
					</td>
					</tr>
					</tbody>
					</table>
					</td>
					</tr>
					</tbody>
					</table>
					</td>
					</tr>
					<tr>
					<td style="color: #262626; padding: 20px 0; font-size: 20px; border-top:5px solid #52bfd3;" colspan="2" align="center" bgcolor="#ffffff">Copyright © '.APP_NAME.'.</td>
					</tr>
					</tbody>
					</table>
					</div>';

					send_email($to,$recipient_name,$subject,$message);
					
					$set['PLACE_APP'][]=array('user_id' => $row['id'], 'name'=>$row['name'], 'email'=>$row['email'], 'success'=>'1', 'msg' =>'', 'auth_id' => $auth_id);
				}
			}

		}
		else if($user_type=='Normal' || $user_type=='normal'){
			// for normal registration

			$qry = "SELECT * FROM tbl_users WHERE email = '".$get_method['email']."' AND `user_type`='Normal'"; 
			$result = mysqli_query($mysqli, $sql);
			$row = mysqli_fetch_assoc($result);
			
			if (!filter_var($get_method['email'], FILTER_VALIDATE_EMAIL)) 
			{
				$set['PLACE_APP'][]=array('msg' => $app_lang['invalid_email_format'],'success'=>'0');
			}
			else if($row['email']!="")
			{
				$set['PLACE_APP'][]=array('msg' => $app_lang['email_exist'],'success'=>'0');
			}
			else
			{	
				$data = array(
					'user_type'=>'Normal',											 
					'name'  => addslashes(trim($get_method['name'])),				    
					'email'  =>  addslashes(trim($get_method['email'])),
					'password'  =>  md5(trim($get_method['password'])),
					'phone'  =>  addslashes(trim($get_method['phone'])),
					'registration_on'  =>  strtotime(date('d-m-Y h:i:s A')), 
					'status'  =>  '1'
				);		

				$qry = Insert('tbl_users',$data);

				$message='<div style="background-color: #eee;" align="center"><br />
				<table style="font-family: OpenSans,sans-serif; color: #666666;" border="0" width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="#FFFFFF">
				<tbody>
				<tr>
				<td colspan="2" bgcolor="#FFFFFF" align="center" ><img src="'.$file_path.'images/'.APP_LOGO.'" alt="logo" /></td>
				</tr>
				<br>
				<br>
				<tr>
				<td colspan="2" bgcolor="#FFFFFF" align="center" style="padding-top:25px;">
				<img src="'.$file_path.'assets/images/thankyoudribble.gif" alt="header" auto-height="100" width="50%"/>
				</td>
				</tr>
				<tr>
				<td width="600" valign="top" bgcolor="#FFFFFF">
				<table style="font-family:OpenSans,sans-serif; color: #666666; font-size: 10px; padding: 15px;" border="0" width="100%" cellspacing="0" cellpadding="0" align="left">
				<tbody>
				<tr>
				<td valign="top">
				<table border="0" align="left" cellpadding="0" cellspacing="0" style="font-family:OpenSans,sans-serif; color: #666666; font-size: 10px; width:100%;">
				<tbody>
				<tr>
				<td>
				<p style="color: #717171; font-size: 28px; margin-top:0px; margin:0 auto; text-align:center;"><strong>Welcome, '.cleanInput($get_method['name']).'</strong></p>
				<br>
				<p style="color:#15791c; font-size:20px; line-height:32px;font-weight:500;margin-bottom:30px; margin:0 auto; text-align:center;">You have sucsessfully registration with google<br /></p>
				<br/>
				<p style="color:#999; font-size:18px; line-height:32px;font-weight:500;">Thank you for using '.APP_NAME.'</p>
				</td>
				</tr>
				</tbody>
				</table>
				</td>
				</tr>
				</tbody>
				</table>
				</td>
				</tr>
				<tr>
				<td style="color: #262626; padding: 20px 0; font-size: 20px; border-top:5px solid #52bfd3;" colspan="2" align="center" bgcolor="#ffffff">Copyright © '.APP_NAME.'.</td>
				</tr>
				</tbody>
				</table>
				</div>';

				send_email($to,$recipient_name,$subject,$message);

				$set['PLACE_APP'][]=array('msg' => $app_lang['register_success'],'success'=>'1');
			}

		}

		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
	}
	// Get user register end

	// Get user login start
	else if($get_method['method_name']=="user_login")
	{
		
		$email= htmlentities(trim($get_method['email']));
		$password = htmlentities(trim($get_method['password']));

		$auth_id = htmlentities(trim($get_method['auth_id']));

		$user_type = htmlentities(trim($get_method['type']));

		if($user_type=='normal' OR $user_type=='Normal'){

			// simple login
			$qry = "SELECT * FROM tbl_users WHERE email = '$email' AND (`user_type`='Normal' OR `user_type`='normal') AND `id` <> 0"; 
			$result = mysqli_query($mysqli,$qry);
			$num_rows = mysqli_num_rows($result);
			
			if($num_rows > 0){
				$row = mysqli_fetch_assoc($result);

				if($row['status']==1){
					if($row['password']==md5($password)){

						$user_id=$row['id'];

						$sql="SELECT * FROM tbl_active_log WHERE `user_id`='$user_id'";
						$res=mysqli_query($mysqli, $sql);

						if(mysqli_num_rows($res) == 0){
                            // insert active log

							$data_log = array(
								'user_id'  =>  $user_id,
								'date_time'  =>  strtotime(date('d-m-Y h:i:s A'))
							);

							$qry = Insert('tbl_active_log',$data_log);

						}
						else{
                            // update active log
							$data_log = array(
								'date_time'  =>  strtotime(date('d-m-Y h:i:s A'))
							);

							$update=Update('tbl_active_log', $data_log, "WHERE user_id = '$user_id'");  
						}

						mysqli_free_result($res);

						$set['PLACE_APP'][]=array('user_id' => $row['id'], 'name'=>$row['name'], 'email'=>$row['email'], 'msg' => $app_lang['login_success'], 'auth_id' => '', 'success'=>'1');
					}
					else{
						// invalid password
						$set['PLACE_APP'][]=array('msg' =>$app_lang['invalid_password'],'success'=>'0');
					}
				}
				else{
					// account is deactivated
					$set['PLACE_APP'][]=array('msg' =>$app_lang['account_deactive'],'success'=>'0');
				}

			}
			else{
				// email not found
				$set['PLACE_APP'][]=array('msg' =>$app_lang['email_not_found'],'success'=>'0');	
			}
		}
		else if($user_type=='google' OR $user_type=='Google'){

			// login with google

			$sql = "SELECT * FROM tbl_users WHERE (`email` = '$email' OR `auth_id`='$auth_id') AND (`user_type`='Google' OR `user_type`='google')";

			$res=mysqli_query($mysqli, $sql);

			if(mysqli_num_rows($res) > 0){
				$row = mysqli_fetch_assoc($res);

				if($row['status']==0){
					$set['PLACE_APP'][]=array('msg' => $app_lang['account_deactive'],'success'=>'0');
				}	
				else
				{
					$user_id=$row['id'];

					$sql="SELECT * FROM tbl_active_log WHERE `user_id`='$user_id'";
					$res=mysqli_query($mysqli, $sql);

					if(mysqli_num_rows($res) == 0){
                        // insert active log

						$data_log = array(
							'user_id'  =>  $user_id,
							'date_time'  =>  strtotime(date('d-m-Y h:i:s A'))
						);

						$qry = Insert('tbl_active_log',$data_log);

					}
					else{
                        // update active log
						$data_log = array(
							'date_time'  =>  strtotime(date('d-m-Y h:i:s A'))
						);

						$update=Update('tbl_active_log', $data_log, "WHERE user_id = '$user_id'");  
					}

					mysqli_free_result($res);


					$set['PLACE_APP'][]=array('user_id' => $row['id'], 'name' => $row['name'], 'email'=>$row['email'], 'msg' => $app_lang['login_success'], 'auth_id' => $auth_id, 'success'=>'1');

					$data = array(
						'auth_id'  =>  $auth_id
					);  

					$updatePlayerID=Update('tbl_users', $data, "WHERE `id` = '".$row['id']."'");
				}

			}
			else{
				$set['PLACE_APP'][]=array('msg' => $app_lang['email_not_found'],'success'=>'0');
			}
		}
		else if($user_type=='facebook' OR $user_type=='Facebook'){
			
			// login with google

			$sql = "SELECT * FROM tbl_users WHERE (`email` = '$email' OR `auth_id`='$auth_id') AND (`user_type`='Facebook' OR `user_type`='facebook')";

			$res=mysqli_query($mysqli, $sql);

			if(mysqli_num_rows($res) > 0){
				$row = mysqli_fetch_assoc($res);

				if($row['status']==0){
					$set['PLACE_APP'][]=array('msg' => $app_lang['account_deactive'],'success'=>'0');
				}	
				else
				{

					$user_id=$row['id'];

					$sql="SELECT * FROM tbl_active_log WHERE `user_id`='$user_id'";
					$res=mysqli_query($mysqli, $sql);

					if(mysqli_num_rows($res) == 0){
                        // insert active log

						$data_log = array(
							'user_id'  =>  $user_id,
							'date_time'  =>  strtotime(date('d-m-Y h:i:s A'))
						);

						$qry = Insert('tbl_active_log',$data_log);

					}
					else{
                        // update active log
						$data_log = array(
							'date_time'  =>  strtotime(date('d-m-Y h:i:s A'))
						);

						$update=Update('tbl_active_log', $data_log, "WHERE user_id = '$user_id'");  
					}

					mysqli_free_result($res);

					$set['PLACE_APP'][]=array('user_id' => $row['id'], 'name' => $row['name'], 'email'=>$row['email'], 'msg' => $app_lang['login_success'], 'auth_id' => $auth_id, 'success'=>'1');

					$data = array(
						'auth_id'  =>  $auth_id
					);  

					$updatePlayerID=Update('tbl_users', $data, "WHERE `id` = '".$row['id']."'");
				}

			}
			else{
				$set['PLACE_APP'][]=array('msg' => $app_lang['email_not_found'],'success'=>'0');
			}

		}

		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
	}
	// Get user login end

	// Get user profile start
	else if($get_method['method_name']=="user_profile")
	{
		$qry = "SELECT * FROM tbl_users WHERE `id` = '".$get_method['user_id']."'"; 
		$result = mysqli_query($mysqli,$qry);

		$row = mysqli_fetch_assoc($result);

		$set['PLACE_APP'][]=array('user_id' => $row['id'],'name'=> $row['name'],'email'=> $row['email'],'phone'=> $row['phone'],'success'=> '1');

		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
	}
	// Get user profile end

	// Get user profile update start
	else if($get_method['method_name']=="user_profile_update")
	{
		$jsonObj= array();	
		
		$sql = "SELECT * FROM tbl_users WHERE `id` = '".$get_method['user_id']."'"; 
		$result = mysqli_query($mysqli,$sql);
		$row = mysqli_fetch_assoc($result);

		if (!filter_var($get_method['email'], FILTER_VALIDATE_EMAIL)) 
		{
			$set['PLACE_APP'][]=array('msg' => $app_lang['invalid_email_format'],'success'=>'0');

			header( 'Content-Type: application/json; charset=utf-8' );
			$json = json_encode($set);
			echo $json;
			exit;
		}
		else if($row['email']==$get_method['email'] AND $row['id']!=$get_method['user_id'])
		{
			$set['PLACE_APP'][]=array('msg' => $app_lang['email_exist'],'success'=>'0');

			
		}else{

			$data = array(
				'name'  =>  cleanInput($get_method['name']),
				'email'  =>  trim($get_method['email']),
				'phone'  =>  cleanInput($get_method['phone']),
			);

			if($get_method['password']!=""){
				$data = array_merge($data, array("password" => md5(trim($get_method['password']))));
			}

			$user_edit=Update('tbl_users', $data, "WHERE id = '".$get_method['user_id']."'");

			$set['PLACE_APP'][] = array('msg' => $app_lang['update_success'], 'success' => '1');
		}

		header('Content-Type: application/json; charset=utf-8');
		echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
	}	
	// Get user profile end

	// Get forgot password start
	else if($get_method['method_name']=="forgot_pass")
	{	 	
		$email=trim($get_method['user_email']);

		$user_qry = "SELECT * FROM tbl_users WHERE tbl_users.`email` = '$email' AND tbl_users.`user_type`='Normal'"; 
		$user_result = mysqli_query($mysqli,$user_qry);
		$user_row = mysqli_fetch_assoc($user_result);

		if($user_result->num_rows > 0)
		{
			$password=generateRandomPassword(7);

			$new_password=md5($password);

			$to = $row['email'];
			$recipient_name=$row['name'];
			// subject
			$subject = '[IMPORTANT] '.APP_NAME.' Forgot Password Information';

			$message='<div style="background-color: #f9f9f9;" align="center"><br />
			<table style="font-family: OpenSans,sans-serif; color: #666666;" border="0" width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="#FFFFFF">
			<tbody>
			<tr>
			<td colspan="2" bgcolor="#FFFFFF" align="center"><img src="'.$file_path.'images/'.APP_LOGO.'" alt="header" /></td>
			</tr>
			<tr>
			<td width="600" valign="top" bgcolor="#FFFFFF"><br>
			<table style="font-family:OpenSans,sans-serif; color: #666666; font-size: 10px; padding: 15px;" border="0" width="100%" cellspacing="0" cellpadding="0" align="left">
			<tbody>
			<tr>
			<td valign="top"><table border="0" align="left" cellpadding="0" cellspacing="0" style="font-family:OpenSans,sans-serif; color: #666666; font-size: 10px; width:100%;">
			<tbody>
			<tr>
			<td><p style="color: #262626; font-size: 28px; margin-top:0px;"><strong>Dear '.$row['name'].'</strong></p>
			<p style="color:#262626; font-size:20px; line-height:32px;font-weight:500;">Thank you for using '.APP_NAME.',<br>
			Your password is: '.$password.'</p>
			<p style="color:#262626; font-size:20px; line-height:32px;font-weight:500;margin-bottom:30px;">Thanks you,<br />
			'.APP_NAME.'.</p></td>
			</tr>
			</tbody>
			</table></td>
			</tr>

			</tbody>
			</table></td>
			</tr>
			<tr>
			<td style="color: #262626; padding: 20px 0; font-size: 20px; border-top:5px solid #52bfd3;" colspan="2" align="center" bgcolor="#ffffff">Copyright © '.APP_NAME.'.</td>
			</tr>
			</tbody>
			</table>
			</div>';


			send_email($to,$recipient_name,$subject,$message);

			$sql="UPDATE tbl_users SET `password`='$new_password' WHERE `id`='".$row['id']."'";
			mysqli_query($mysqli,$sql);


			$set['PLACE_APP'][]=array('msg' => $app_lang['password_sent_mail'],'success'=>'1');
		}
		else
		{  	 

			$set['PLACE_APP'][]=array('msg' => $app_lang['email_not_found'],'success'=>'0');

		}

		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
	}
	// Get forgot password end

	// Get add favourite start
	else if($get_method['method_name']=="add_favourite")
	{
		$user_id =$get_method['user_id'];
		$place_id =$get_method['place_id'];

		$sql="SELECT * FROM tbl_favourite WHERE `user_id`='$user_id' AND `place_id`='$place_id'";
		$res=mysqli_query($mysqli, $sql);

		if($res->num_rows == 0){
			// add to favourite list

			$data = array( 
				'place_id'  =>  $place_id,
				'user_id'  =>  $user_id,
				'created_at'  =>  strtotime(date('d-m-Y h:i:s A'))
			);      

			$qry = Insert('tbl_favourite',$data);

			$set = array('status' => '1','message' => '','msg'=>$app_lang['add_favourite'],'success'=>1,'is_favourite'=>true);

		}
		else{

			// remove to favourite list
			$deleteSql="DELETE FROM tbl_favourite WHERE `user_id`='$user_id' AND `place_id`='$place_id'";

			if(mysqli_query($mysqli, $deleteSql)){
				$set = array('status' => '1','message' => '','msg'=>$app_lang['remove_favourite'],'success'=>1,'is_favourite'=>false);
			}
			else{

				$set = array('status' => '1','message' => '','msg'=>$app_lang['error_msg'],'success'=>0,'is_favourite'=>false);
			}
		}

		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	}
	// Get add favourite end

	// Get favourite list start
	else if($get_method['method_name']=="get_favourite_list")		
	{

		$post_order_by=API_CAT_POST_ORDER_BY;

		$user_id=$get_method['user_id'];

		$jsonObj= array();

		$query="SELECT tbl_places.*,tbl_category.`cid`,tbl_category.`category_name`,tbl_category.`category_image` FROM tbl_places
		LEFT JOIN tbl_category ON tbl_places.`p_cat_id`= tbl_category.`cid` 
		LEFT JOIN tbl_favourite ON tbl_places.`p_id`= tbl_favourite.`place_id` 
		WHERE tbl_places.`place_status`='1' AND tbl_category.`status`='1' AND tbl_favourite.`user_id`='$user_id' ORDER BY tbl_favourite.`id` DESC";

		$sql = mysqli_query($mysqli,$query)or die(mysqli_error($mysqli));

		while($data = mysqli_fetch_assoc($sql))
		{	

			$row['p_id'] = $data['p_id'];
			$row['p_cat_id'] = $data['p_cat_id'];
			$row['place_name'] = $data['place_name'];
			$row['place_image'] = $file_path.'images/'.$data['place_image'];
			$row['place_thumb_image'] = get_thumb('images/'.$data['place_image'],'500*400');
			$row['place_video'] = $data['place_video'];
			$row['place_description'] = stripslashes($data['place_description']);
			$row['place_address'] = $data['place_address'];
			$row['place_email'] = $data['place_email'];
			$row['place_phone'] = $data['place_phone'];
			$row['place_website'] = $data['place_website'];
			$row['place_map_latitude'] = $data['place_map_latitude'];
			$row['place_map_longitude'] = $data['place_map_longitude'];
			$row['place_status'] = $data['place_status'];
			$row['place_rate_avg'] = $data['place_rate_avg'];
			$row['place_total_rate'] = $data['place_total_rate'];

			$row['cid'] = $data['cid'];
			$row['category_name'] = $data['category_name'];
			$row['category_image'] = $file_path.'images/'.$data['category_image'];

			$row['is_favourite']=get_favourite_info($data['p_id'],$user_id);

			array_push($jsonObj,$row);	

		}

		$set['PLACE_APP'] = $jsonObj;

		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
	}
	// Get favourite list end

	// Get add ratings start
	else if($get_method['method_name']=="place_ratings")
	{
		error_reporting(0);
		$flag=0;

		$ip = filter_var($get_method['ip'], FILTER_SANITIZE_STRING);
		if (filter_var($get_method['post_id'], FILTER_VALIDATE_INT) === 0 || !filter_var($get_method['post_id'], FILTER_VALIDATE_INT) === false) {
			$post_id = filter_var($get_method['post_id']);
		} else {
			$flag++;
		}

		if (filter_var($get_method['user_id'], FILTER_VALIDATE_INT) === 0 || !filter_var($get_method['user_id'], FILTER_VALIDATE_INT) === false) {
			$user_id = filter_var($get_method['user_id']);
		} else {
			$flag++;
		}

		$therate = cleanInput($get_method['rate']);

		$message = filter_var($get_method['message'], FILTER_SANITIZE_STRING);

		if($flag==0){

			$result = mysqli_query($mysqli,"SELECT * FROM tbl_rating WHERE `post_id`='$post_id' AND `user_id`='$user_id'"); 

			while($data1 = mysqli_fetch_assoc($result)){
				$rate_db1[] = $data1;
			}
			if(@count($rate_db1) == 0 ){

				$data = array(            
					'post_id'  =>$post_id,
					'user_id'  =>$user_id,
					'rate'  =>  $therate,
					'message'  =>  $message,
					'ip'  => $ip,
				);  
				$qry = Insert('tbl_rating',$data); 

				//Total rate result

				$query = mysqli_query($mysqli,"SELECT * FROM tbl_rating WHERE `post_id`='$post_id'");

				while($data = mysqli_fetch_assoc($query)){
					$rate_db[] = $data;
					$sum_rates[] = $data['rate'];

				}

				if(@count($rate_db)){
					$rate_times = count($rate_db);
					$sum_rates = array_sum($sum_rates);
					$rate_value = $sum_rates/$rate_times;
					$rate_bg = (($rate_value)/5)*100;
				}else{
					$rate_times = 0;
					$rate_value = 0;
					$rate_bg = 0;
				}

				$rate_avg=round($rate_value); 

				$sql="UPDATE tbl_places SET place_total_rate=place_total_rate + 1,place_rate_avg='$rate_avg' WHERE `p_id`='".$post_id."'";

				mysqli_query($mysqli,$sql);

				$qry_users="SELECT COUNT(*) as num FROM tbl_rating WHERE tbl_rating.`post_id`='$post_id'";
				$total_users = mysqli_fetch_array(mysqli_query($mysqli,$qry_users));

				$total_rat_sql="SELECT * FROM tbl_places WHERE `p_id` ='$post_id'";
				$total_rat_res=mysqli_query($mysqli,$total_rat_sql);
				$total_rat_row=mysqli_fetch_assoc($total_rat_res);

				$set['PLACE_APP'][] = array('total_rate' =>$total_rat_row['place_total_rate'],'rate_avg' => $total_rat_row['place_rate_avg'],'msg' => $app_lang['rate_success'],'total_users' => $total_users['num'] ,'success'=>"1");	

			}else{  

				$sql="UPDATE tbl_rating SET ip='$ip',rate='$therate',message='$message' WHERE user_id='$user_id' AND post_id='$post_id'";

				mysqli_query($mysqli,$sql);

          //Total rate result
				$query = mysqli_query($mysqli,"SELECT * FROM tbl_rating WHERE post_id  = '$post_id' ");

				while($data = mysqli_fetch_assoc($query)){
					$rate_db[] = $data;
					$sum_rates[] = $data['rate'];

				}

				if(@count($rate_db)){
					$rate_times = count($rate_db);
					$sum_rates = array_sum($sum_rates);
					$rate_value = $sum_rates/$rate_times;
					$rate_bg = (($rate_value)/5)*100;
				}else{
					$rate_times = 0;
					$rate_value = 0;
					$rate_bg = 0;
				}

				$rate_avg=round($rate_value); 

				$sql="UPDATE tbl_places SET place_rate_avg='$rate_avg' WHERE p_id='$post_id'";
				mysqli_query($mysqli,$sql);

				$qry_users="SELECT COUNT(*) as num FROM tbl_rating WHERE tbl_rating.`post_id`='$post_id'";
				$total_users = mysqli_fetch_array(mysqli_query($mysqli,$qry_users));

				$total_rat_sql="SELECT * FROM tbl_places WHERE `p_id` ='$post_id'";
				$total_rat_res=mysqli_query($mysqli,$total_rat_sql);
				$total_rat_row=mysqli_fetch_assoc($total_rat_res);

				$set['PLACE_APP'][] = array('total_rate' =>$total_rat_row['place_total_rate'],'rate_avg' => $total_rat_row['place_rate_avg'],'msg' => $app_lang['rate_success'],'total_users' => $total_users['num'] ,'success'=>"1");	
			}
		}	
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
	}
	// Get add ratings end

	// Get user ratings list start
	else if($get_method['method_name']=="get_user_rating")
	{
		
		$user_id=$get_method['user_id'];	
		$post_id=$get_method['post_id'];	

		$jsonObj= array();	

		$query="SELECT * FROM tbl_rating
		WHERE tbl_rating.`user_id`='$user_id' AND tbl_rating.`post_id`='$post_id' ORDER BY tbl_rating.`id` DESC";		 

		$res = mysqli_query($mysqli,$query)or die(mysqli_error($mysqli));

		if(mysqli_num_rows($res) > 0){

			$user_rate=mysqli_fetch_assoc($res);

			$set['PLACE_APP'][]=array('user_rate' => $user_rate['rate'],'user_msg' => $user_rate['message'],'msg' =>'User Rated','success'=>"1");

		}else{

			$set['PLACE_APP'][]=array('user_rate' => "0",'user_msg' => "",'msg' =>'User Not Rated','success'=>"1");
		}

		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
	}
	// Get user ratings list end

	// Get category list start		
	else if($get_method['method_name']=="get_category")
	{
		$jsonObj= array();
		
		$cid=API_CAT_ORDER_BY;

		$query="SELECT * FROM tbl_category WHERE status=1 ORDER BY tbl_category.$cid";
		$sql = mysqli_query($mysqli,$query);

		while($data = mysqli_fetch_assoc($sql))
		{
			
			$row['cid'] = $data['cid'];
			$row['category_name'] = $data['category_name'];
			$row['category_image'] = $file_path.'images/'.$data['category_image'];
			$row['category_image_thumb'] = get_thumb('images/'.$data['category_image'],'300*300');

			array_push($jsonObj,$row);

		}
		$set['PLACE_APP'] = $jsonObj;

		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE));
		die();
	}
	// Get category list end

	// Get app details start
	else if($get_method['method_name']=="get_app_details"){

		$jsonObj= array();	

		$query="SELECT * FROM tbl_settings WHERE `id`='1'";
		$sql = mysqli_query($mysqli,$query)or die(mysqli_error($mysqli));

		while($data = mysqli_fetch_assoc($sql))
		{

			$row['package_name'] = $data['package_name'];
			$row['ios_bundle_identifier'] = $data['ios_bundle_identifier'];
			
			$row['app_name'] = $data['app_name'];
			$row['app_logo'] = $data['app_logo'];
			$row['app_version'] = $data['app_version'];
			$row['app_author'] = $data['app_author'];
			$row['app_contact'] = $data['app_contact'];
			$row['app_email'] = $data['app_email'];
			$row['app_website'] = $data['app_website'];
			$row['app_description'] = stripslashes($data['app_description']);
			$row['app_developed_by'] = $data['app_developed_by'];

			$row['app_privacy_policy'] = stripslashes($data['app_privacy_policy']);

			$row['publisher_id_ios'] = $data['publisher_id_ios'];
			$row['app_id_ios'] = $data['app_id_ios'];
			$row['interstital_ad_ios'] = $data['interstital_ad_ios'];
			$row['interstital_ad_id_ios'] = $data['interstital_ad_id_ios'];
			$row['interstital_ad_click_ios'] = $data['interstital_ad_click_ios'];
			$row['banner_ad_ios'] = $data['banner_ad_ios'];
			$row['banner_ad_id_ios'] = $data['banner_ad_id_ios'];

			$row['app_update_status_ios'] = $data['app_update_status_ios'];
			$row['app_new_version_ios'] = $data['app_new_version_ios'];
			$row['app_update_desc_ios'] = stripslashes($data['app_update_desc_ios']);
			$row['app_redirect_url_ios'] = $data['app_redirect_url_ios'];
			$row['cancel_update_status_ios'] = $data['cancel_update_status_ios'];

			array_push($jsonObj,$row);

		}

		$set['PLACE_APP'] = $jsonObj;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE));
		die();

	}
	// Get app details end
	else{

		$get_method = checkSignSalt($_POST['data']);
	}

?>