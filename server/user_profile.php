<?php $page_title="Users Profile";
	
	include('includes/header.php'); 

	error_reporting(0);

	$user_id=strip_tags(addslashes(trim($_GET['user_id'])));

	if(!isset($_GET['user_id']) OR $user_id==''){
		header("Location: manage_users.php");
	}

    $user_qry="SELECT * FROM tbl_users WHERE id='$user_id'";
    $user_result=mysqli_query($mysqli,$user_qry);

    if(mysqli_num_rows($user_result)==0){
    	header("Location: manage_users.php");
    }

    $user_row=mysqli_fetch_assoc($user_result);
	
	$user_img='assets/images/user-icons.jpg';
	

	// User profile update
    if(isset($_POST['btn_submit']))
    {

        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) 
        {
            $_SESSION['class']="warn";
            $_SESSION['msg']="invalid_email_format";
        }
        else{

            $email=cleanInput($_POST['email']);

            $sql="SELECT * FROM tbl_users WHERE `email` = '$email' AND `id` <> '".$user_id."'";

            $res=mysqli_query($mysqli, $sql);

            if(mysqli_num_rows($res) == 0){
                $data = array(
                    'name'  =>  cleanInput($_POST['name']),
                    'email'  =>  cleanInput($_POST['email']),
                    'phone'  =>  cleanInput($_POST['phone']),
                );

                if($_POST['password']!="")
                {

                    $password=md5(trim($_POST['password']));

                    $data = array_merge($data, array("password"=>$password));
                }

                $user_edit=Update('tbl_users', $data, "WHERE id = '".$user_id."'");

                $_SESSION['class']="success";

                $_SESSION['msg']="11";
            }
            else{
                $_SESSION['class']="warn";
                $_SESSION['msg']="email_exist";
            }
        }

        header("Location:user_profile.php?user_id=".$user_id);
        exit;
    }

  // Get category information start
  function get_cat_info($p_cat_id,$field_name) 
    {
      global $mysqli;

      $qry_cat="SELECT * FROM tbl_category WHERE cid='".$p_cat_id."'";
      $query1=mysqli_query($mysqli,$qry_cat);
      $row_cat = mysqli_fetch_array($query1);

      $num_rows1 = mysqli_num_rows($query1);

      if ($num_rows1 > 0)
      {     
        return $row_cat[$field_name];
      }
      else
      {
        return "";
      }
    } 

 // Get last active log   
function getLastActiveLog($user_id){
    	global $mysqli;

    	$sql="SELECT * FROM tbl_active_log WHERE `user_id`='$user_id'";
        $res=mysqli_query($mysqli, $sql);

        if(mysqli_num_rows($res) == 0){
        	echo 'no available';
        }
        else{

        	$row=mysqli_fetch_assoc($res);
			return calculate_time_span($row['date_time'],true);	
        }
    }
   
?>

<link rel="stylesheet" type="text/css" href="assets/css/stylish-tooltip.css">
<style>
#applied_user .dataTables_wrapper .top{
	position: relative;
	width: 100%;
}	
</style>
  
<div class="row">
	<div class="col-lg-12">
		<?php
	      	if(isset($_SERVER['HTTP_REFERER']))
	      	{
	      		echo '<a href="'.$_SERVER['HTTP_REFERER'].'"><h4 class="pull-left" style="font-size: 20px;color: #e91e63"><i class="fa fa-arrow-left"></i> Back</h4></a>';
	      	}
      	 ?>
		<div class="page_title_block user_dashboard_item" style="background-color: #333;border-radius:6px;0 1px 4px 0 rgba(0, 0, 0, 0.14);border-bottom:0">
			<div class="user_dashboard_mr_bottom">
			  <div class="col-md-12 col-xs-12"> <br>
				<span class="badge badge-success badge-icon">
				  <div class="user_profile_img">
				  	<?php 
		                if($user_row['user_type']=='Google'){
		                  echo '<img src="assets/images/google-logo.png" style="width: 16px;height: 16px;position: absolute;top: 24px;z-index: 1;left: 62px;">';
		                }
		                else if($user_row['user_type']=='Facebook'){
		                  echo '<img src="assets/images/facebook-icon.png" style="width: 16px;height: 16px;position: absolute;top: 24px;z-index: 1;left: 62px;">';
		                }
		              ?>
					<img type="image" src="<?php echo $user_img;?>" alt="image" style=""/>
				  </div>
				  <span style="font-size: 14px;"><?php echo $user_row['name'];?>				
				  </span>
				</span>  
				<span class="badge badge-success badge-icon">
					<i class="fa fa-envelope fa-2x" aria-hidden="true"></i>
					<span style="font-size: 14px;text-transform: lowercase;"><?php echo $user_row['email'];?></span>
				</span> 
				<span class="badge badge-success badge-icon">
				  <strong style="font-size: 14px;">Registered At:</strong>
				  <span style="font-size: 14px;"><?php echo (date('d-m-Y',$user_row['registration_on']));?></span>
				</span>
				<span class="badge badge-success badge-icon">
					  <strong style="font-size: 14px;">Last Activity On:</strong>
					  <span style="font-size: 14px;text-transform: lowercase;"><?php echo getLastActiveLog($user_id)?></span>
					</span>
					<br><br/>
			  </div>
			</div>
		</div>

		  <div class="card card-tab">
			<div class="card-header" style="overflow-x: auto;overflow-y: hidden;">
				<ul class="nav nav-tabs" role="tablist">
		            <li role="dashboard" class="active"><a href="#edit_profile" aria-controls="edit_profile" role="tab" data-toggle="tab">Edit Profile</a></li>
		            <li role="favourite_place"><a href="#favourite_place" aria-controls="favourite_place" role="tab" data-toggle="tab">Favourite Place</a></li>
		        </ul>
			</div>
			<div class="card-body no-padding tab-content">
				<div role="tabpanel" class="tab-pane active" id="edit_profile">
					<div class="row">
						<div class="col-md-12">
							<form action="" method="post" class="form form-horizontal" enctype="multipart/form-data">
					          <div class="section">
					            <div class="section-body">
					              <div class="form-group">
					                <label class="col-md-3 control-label">Name :-</label>
					                <div class="col-md-6">
					                  <input type="text" name="name" id="name" value="<?=$user_row['name']?>" class="form-control" required>
					                </div>
					              </div>
					              <?php if($user_row['user_type']=='Normal'){?>
					              <div class="form-group">
					                <label class="col-md-3 control-label">Email :-</label>
					                <div class="col-md-6">
					                  <input type="email" name="email" id="email" value="<?=$user_row['email']?>" class="form-control" required>
					                </div>
					              </div>
					           	<?php }else{?>
					          	 <div class="form-group">
					                <label class="col-md-3 control-label">Email :-</label>
					                <div class="col-md-6">
					                  <input type="email" name="email" id="email" readonly="" value="<?=$user_row['email']?>" class="form-control" required>
					                </div>
					              </div>
					           	<?php }?>
					              <?php if($user_row['user_type']=='Normal'){?>
					              <div class="form-group">
					                <label class="col-md-3 control-label">Password :-</label>
					                <div class="col-md-6">
					                  <input type="password" name="password" id="password" value="" class="form-control">
					                </div>
					              </div>
					              <?php }?>
					              <div class="form-group">
					                <label class="col-md-3 control-label">Phone :-</label>
					                <div class="col-md-6">
					                  <input type="text" name="phone" id="phone" value="<?=$user_row['phone']?>" class="form-control">
					                </div>
					              </div>
					     			<div class="form-group">
					                <div class="col-md-9 col-md-offset-3">
					                  <button type="submit" name="btn_submit" class="btn btn-primary">Save</button>
					                </div>
					              </div>
					            </div>
					          </div>
					        </form>
						</div>
					  </div>
					</div>
					
					<div role="tabpanel" class="tab-pane" id="favourite_place">
							<div class="row">
							 <div class="col-md-12">
						      	<?php
						      	  $user_id=$_GET['user_id'];

								  //Favourite list    
								  $tableName="tbl_places";   
								  $targetpage = "user_profile.php";   
								  $limit = 12; 
								  
								  $query = "SELECT COUNT(*) as num FROM tbl_favourite WHERE tbl_favourite.`user_id` = '$user_id'";
								  $total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query));
								  $total_pages = $total_pages['num'];
								  
								  $stages = 1;
								  $page=0;
								  if(isset($_GET['page'])){
								  $page = mysqli_real_escape_string($mysqli,$_GET['page']);
								  }
								  if($page){
								    $start = ($page - 1) * $limit; 
								  }else{
								    $start = 0; 
								  }

								 $place_qry="SELECT * FROM tbl_favourite
											 LEFT JOIN tbl_users ON tbl_favourite.`user_id`= tbl_users.`id`
											 LEFT JOIN tbl_places ON tbl_favourite.`place_id`= tbl_places.`p_id`
											 WHERE tbl_favourite.`user_id` = '$user_id'
											 ORDER BY tbl_favourite.`id` DESC LIMIT $start, $limit";

	  							 $place_result = mysqli_query($mysqli,$place_qry)or die(mysqli_error($mysqli));

					      		$i=0;
					      		while ($place_row=mysqli_fetch_assoc($place_result)){
					      		 ?>
					      		 <div class="col-lg-3 col-sm-6 col-xs-12">
					            <div class="block_wallpaper" style="box-shadow:2px 3px 5px #333;">
					              <div class="wall_category_block">
					               <h2><?=ucwords(get_cat_info($place_row['p_cat_id'],'category_name'))?></h2> 
				                   </div>   
					              <div class="wall_image_title">
					              	 <h2><?php echo $place_row['place_name'];?></h2>  
					                <ul>
					              	<li><a href="javascript:void(0)" data-toggle="tooltip" data-tooltip="<?php echo $place_row['place_total_rate'];?> Rating"><i class="fa fa-star"></i></a></li>
					                </ul>
					              </div>
					               <span><img src="images/<?php echo $place_row['place_image'];?>" /></span>
					             </div>
					          </div>
						      <?php $i++; } ?>
							</div>
						</div>
					</div>									 
			</div>
		</div>
	</div>
</div>

<?php include('includes/footer.php');?>

<script type="text/javascript">

 $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
    localStorage.setItem('activeTab', $(e.target).attr('href'));
    document.title = $(this).text()+" | <?=APP_NAME?>";
  });

  var activeTab = localStorage.getItem('activeTab');
  if(activeTab){
    $('.nav-tabs a[href="' + activeTab + '"]').tab('show');
  }
  
</script>


