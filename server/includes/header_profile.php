<?php

include('includes/header.php');


error_reporting(0);

$user_id = strip_tags(addslashes(trim($_GET['user_id'])));

$user_qry = "SELECT * FROM tbl_users WHERE id='$user_id'";
$user_result = mysqli_query($mysqli, $user_qry);
$user_row = mysqli_fetch_assoc($user_result);

$user_img = '';

if ($user_row['user_image'] != '' && file_exists('images/' . $user_row['user_image'])) {
	$user_img = 'images/' . $user_row['user_image'];
} else {
	$user_img = 'assets/images/user-icons.jpg';
}


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


<div class="row">
	<div class="col-lg-12">
		<?php
		if (isset($_GET['redirect'])) {
			echo '<a href="' . $_GET['redirect'] . '"><h4 class="pull-left" style="font-size: 20px;color: #e91e63"><i class="fa fa-arrow-left"></i> Back</h4></a>';
		} else {
			echo '<a href="manage_users.php"><h4 class="pull-left" style="font-size: 20px;color: #e91e63"><i class="fa fa-arrow-left"></i> Back</h4></a>';
		}
		?>
		<div class="page_title_block user_dashboard_item" style="background-color: #333;border-radius:6px;0 1px 4px 0 rgba(0, 0, 0, 0.14);border-bottom:0">
			<div class="user_dashboard_mr_bottom">
				<div class="col-md-12 col-xs-12"> <br>
					<span class="badge badge-success badge-icon">
						<div class="user_profile_img">
							<img type="image" src="<?php echo $user_img; ?>" alt="image" style="" />
						</div>
						<span style="font-size: 14px;"><?php echo $user_row['name']; ?>
						</span>
					</span>
					<span class="badge badge-success badge-icon">
						<i class="fa fa-envelope fa-2x" aria-hidden="true"></i>
						<span style="font-size: 14px;text-transform: lowercase;"><?php echo $user_row['email']; ?></span>
					</span>
					<span class="badge badge-success badge-icon">
						<strong style="font-size: 14px;">Registered At:</strong>
						<span style="font-size: 14px;"><?php echo (date('d-m-Y', $user_row['registration_on'])); ?></span>
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
				<ul class="nav nav-tabs">
					<li role="dashboard" <?= ($currentFile == 'user_profile.php') ? 'class="active"' : '' ?>>
						<a href="user_profile.php?user_id=<?= $user_id ?>" name="Edit Profile">Edit Profile</a>
					</li>
					<li role="favourite_place" <?= ($currentFile == 'user_profile.php') ? 'class="active"' : '' ?>>
						<a href="user_profile.php?user_id=<?= $user_id ?>" aria-controls="favourite_place" name="Place">Favourite Place</a>
					</li>
				</ul>
			</div>