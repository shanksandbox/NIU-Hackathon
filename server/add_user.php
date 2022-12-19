<?php $page_title=(isset($_GET['user_id'])) ? 'Edit User' : 'Add User'; 

include('includes/header.php');

require_once("thumbnail_images.class.php");
	 
	 // Add user start
	if(isset($_POST['submit']) and isset($_GET['add']))
	{		
			  
			$data = array(
			'user_type'=>'Normal',	
			'name'  =>  cleanInput($_POST['name']),
			'email'  =>  cleanInput($_POST['email']),
			'password'  =>  md5(trim($_POST['password'])),
			'phone'  =>  cleanInput($_POST['phone']),
			'registration_on'  =>  strtotime(date('d-m-Y h:i:s A'))
			);

			$qry = Insert('tbl_users',$data);

			$_SESSION['msg']="10";
			header("location:manage_users.php");	 
			exit;
		
	}
	// Add user start
	
	if(isset($_GET['user_id']))
	{
			 
			$user_qry="SELECT * FROM tbl_users WHERE `id`='".$_GET['user_id']."'";
			$user_result=mysqli_query($mysqli,$user_qry);
			$user_row=mysqli_fetch_assoc($user_result);
		
	}
	
	// Update user start
	if(isset($_POST['submit']) AND isset($_POST['user_id']))
	{
		  
		if($_POST['password']!="")
		{
			$data = array(
			'name'  =>  cleanInput($_POST['name']),
			'email'  =>  cleanInput($_POST['email']),
			'password'  =>  md5(trim($_POST['password'])),
			'phone'  =>  cleanInput($_POST['phone'])
			);
		}
		else
		{
			$data = array(
			'name'  =>  $_POST['name'],
			'email'  =>  cleanInput($_POST['email']),			 
			'phone'  =>  cleanInput($_POST['phone'])
			);
		}
 
	   $user_edit=Update('tbl_users', $data, "WHERE id = '".$_POST['user_id']."'");
		 
	  $_SESSION['msg']="11";
        if(isset($_GET['redirect'])){
          header("Location:".$_GET['redirect']);
        }
        else{
          header( "Location:add_user.php?user_id=".$_POST['user_id']);
        }
        exit;			
	}
// Update user end
	
?>
 	

 <div class="row">
      <div class="col-md-12">
      	<?php
	      	if(isset($_SERVER['HTTP_REFERER']))
	      	{
	      		echo '<a href="'.$_SERVER['HTTP_REFERER'].'"><h4 class="pull-left" style="font-size: 20px;color: #e91e63"><i class="fa fa-arrow-left"></i> Back</h4></a>';
	      	}
      	 ?>
        <div class="card">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title"><?=$page_title?></div>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="card-body mrg_bottom"> 
            <form action="" name="addedituser" method="post" class="form form-horizontal" enctype="multipart/form-data" >
            	<input  type="hidden" name="user_id" value="<?php echo $_GET['user_id'];?>" />

              <div class="section">
                <div class="section-body">
                  <div class="form-group">
                    <label class="col-md-3 control-label">Name :-</label>
                    <div class="col-md-6">
                      <input type="text" name="name" id="name" value="<?php if(isset($_GET['user_id'])){echo $user_row['name'];}?>" class="form-control" required>
                    </div>
                  </div>
                <?php if (!isset($_GET['user_id']) OR ($user_row['user_type']) == 'Normal') { ?>
                  <div class="form-group"> 
                    <label class="col-md-3 control-label">Email :-</label>
                    <div class="col-md-6">
                      <input type="email" name="email" id="email" value="<?php if(isset($_GET['user_id'])){echo $user_row['email'];}?>" class="form-control" required>
                    </div>
                  </div>
                  <?php }else{?>
                  	<div class="form-group">
                    <label class="col-md-3 control-label">Email :-</label>
                    <div class="col-md-6">
                      <input type="email" name="email" id="email" readonly="" value="<?php if(isset($_GET['user_id'])){echo $user_row['email'];}?>" class="form-control" required>
                    </div>
                  </div>
                  	<?php }?>
                  <?php if (!isset($_GET['user_id']) OR ($user_row['user_type']) == 'Normal') { ?>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Password :-</label>
                    <div class="col-md-6">
                      <input type="password" name="password" id="password" value="" class="form-control" <?php if(!isset($_GET['user_id'])){?>required<?php }?>>
                    </div>
                  </div>
                 <?php }?>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Phone :-</label>
                    <div class="col-md-6">
                      <input type="text" name="phone" id="phone" value="<?php if(isset($_GET['user_id'])){echo $user_row['phone'];}?>" class="form-control">
                    </div>
                  </div>
                   
                  <div class="form-group">
                    <div class="col-md-9 col-md-offset-3">
                      <button type="submit" name="submit" class="btn btn-primary">Save</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
   

<?php include('includes/footer.php');?>                  