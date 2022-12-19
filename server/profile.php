<?php  $page_title="Admin Profile";

include("includes/header.php");
require("language/language.php");
 
// Get admin profile
if(isset($_SESSION['id']))
{
		 
	$qry="SELECT * FROM tbl_admin WHERE id='".$_SESSION['id']."'";
	 
	$result=mysqli_query($mysqli,$qry);
	$row=mysqli_fetch_assoc($result);

}
// Update admin profile
if(isset($_POST['submit']))
{
	if($_FILES['image']['name']!="")
	 {		

			$img_res=mysqli_query($mysqli,'SELECT * FROM tbl_admin WHERE id='.$_SESSION['id'].'');
		    $img_res_row=mysqli_fetch_assoc($img_res);
		

		    if($img_res_row['image']!="")
	        {
				 
			unlink('images/'.$img_res_row['image']);
		     }

				   $image="profile.png";
			   $pic1=$_FILES['image']['tmp_name'];
			   $tpath1='images/'.$image;
				
				copy($pic1,$tpath1);

				$data = array( 
				    'username'  =>  $_POST['username'],
				    'password'  =>  $_POST['password'],
				    'email'  =>  $_POST['email'],
				    'image'  =>  $image
				    );
				
				$channel_edit=Update('tbl_admin', $data, "WHERE id = '".$_SESSION['id']."'"); 

		 }
		 else
		 {
				$data = array( 
				    'username'  =>  $_POST['username'],
				    'password'  =>  $_POST['password'],
				    'email'  =>  $_POST['email'] 
				    );
				
				$channel_edit=Update('tbl_admin', $data, "WHERE id = '".$_SESSION['id']."'"); 
	}

	$_SESSION['msg']="11"; 
	header( "Location:profile.php");
	exit;
	 
}


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
            <form action="" name="editprofile" method="post" class="form form-horizontal" enctype="multipart/form-data">
              <div class="section">
                <div class="section-body">
                  <div class="form-group">
                    <label class="col-md-3 control-label">Profile Image :-</label>
                    <div class="col-md-6">
                      <div class="fileupload_block">
                        <input type="file" name="image" id="fileupload">
                        	<?php if($row['image']!="") {?>
                        	  <div class="fileupload_img"><img type="image" src="images/<?php echo $row['image'];?>" alt="profile image" style="width: 100px;height: 100px" /></div>
                        	<?php } else {?>
                        	  <div class="fileupload_img"><img type="image" src="assets/images/add-image.png" alt="add image" /></div>
                        	<?php }?>
                        
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Username :-</label>
                    <div class="col-md-6">
                      <input type="text" name="username" id="username" value="<?php echo $row['username'];?>" class="form-control" required autocomplete="off">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Password :-</label>
                    <div class="col-md-6">
                      <input type="password" name="password" id="password" value="<?php echo $row['password'];?>" class="form-control" required autocomplete="off">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Email :-</label>
                    <div class="col-md-6">
                      <input type="text" name="email" id="email" value="<?php echo $row['email'];?>" class="form-control" required autocomplete="off">
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

        
<?php include("includes/footer.php");?>  

<script type="text/javascript">
// Preview profile image start

function readURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		
		reader.onload = function(e) {
			$("input[name='image']").next(".fileupload_img").find("img").attr('src', e.target.result);
		}
		reader.readAsDataURL(input.files[0]);
	}
}
$("input[name='image']").change(function() { 
	readURL(this);
});
// Preview profile image start
</script> 