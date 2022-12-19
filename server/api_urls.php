<?php $page_title="Api Urls";

include("includes/header.php");
	
$file_path = getBaseUrl().'api.php';
$ios_file_path = getBaseUrl().'ios_api.php';

?>
<div class="row">
      <div class="col-sm-12 col-xs-12">
      	<?php
	      	if(isset($_SERVER['HTTP_REFERER']))
	      	{
	      		echo '<a href="'.$_SERVER['HTTP_REFERER'].'"><h4 class="pull-left" style="font-size: 20px;color: #e91e63"><i class="fa fa-arrow-left"></i> Back</h4></a>';
	      	}
      	 ?>
     	 	<div class="card">
		        <div class="card-header">
		          Example API urls
		        </div>
       			    <div class="card-body no-padding">
         			 <pre>
                <code class="html">
                <?php 
                      if(file_exists('api.php'))
                      {
                        echo '<br><b>Android API URL</b>&nbsp; '.$file_path;    
                      }
                      
                      if(file_exists('ios_api.php'))
                      {
                        echo '<br><b>iOS API URL</b>&nbsp; '.$ios_file_path;    
                      }
                    ?>

			    <br><b>Home</b>(Method: get_home) (Parameter : user_id)
                <br><b>All Places</b>(Method: get_all_places)(Parameter: user_id,page)
                <br><b>Popular Places</b>(Method: popular_place)(Parameters: user_id))
                <br><b>Latest Places</b>(Method: get_latest_places)(Parameter: user_id)
                <br><b>Category List</b>(Method: get_category)
                <br><b>Home Banner List</b> (Method: get_home_banner)
                <br><b>Places list by Cat ID</b>(Method: get_place_by_cat_id)(Parameter: user_id,cat_id,page)
                <br><b>Single Place</b>(Method: get_single_place)(Parameter: user_id,place_id,user_lat,user_long)
                <br><b>Search Place</b>(Method: get_search_place)(Parameter: search_text,user_id)
                <br><b>Nearby Place</b>(Method: get_nearby_place)(Parameter: user_id,cat_id,user_lat,user_long,distance_limit)
                <br><b>Rating</b>(Method: place_ratings)(Parameter: post_id,user_id,rate,ip,message)
                <br><b>User's Rating List</b>(Method: get_user_rating)(Parameter: user_id,post_id)
                <br><b>User Register</b>(Method: user_register)(Parameter: name,email,password,phone, auth_id, type(Normal, Google, Facebook))
                <br><b>User Login</b>(Method: user_login)(Parameter: email,password,auth_id,type[Normal, Google, Facebook])
        		<br><b>User Profile</b>(Method: user_profile)(Parameter: user_id)
                <br><b>User Profile Update</b>(Method: user_profile_update)(Parameter: user_id,name,email,password,phone)
                <br><b>Forgot Password</b>(Method: forgot_pass)(Parameter: user_email)
                <br><b>Favourite/Unfavourite</b>(Method: add_favourite)(Parameters: place_id,user_id)</span>
                <br><b>Get Favorite List</b>(Method: get_favourite_list)(Parameters: user_id)
                <br><b>App Details</b>(Method: get_app_details)
				</code> 
              </pre>
       		 </div>
          	</div>
        </div>
		</div>
    <br/>
    
<div class="clearfix"></div>
        
<?php include("includes/footer.php");?>       
