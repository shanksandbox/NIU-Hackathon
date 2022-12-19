<?php $page_title="Add Place";

include("includes/header.php");

require_once("thumbnail_images.class.php");

  //All Category
  $cat_qry="SELECT * FROM tbl_category ORDER BY `category_name`";
  $cat_result=mysqli_query($mysqli,$cat_qry);  
	
	// Add place video start
	if(isset($_POST['submit']))
	{
	   
        if ($_POST['place_video'])
        {

              $video_url=$_POST['place_video'];

              $youtube_video_url = addslashes($_POST['place_video']);
              parse_str( parse_url( $youtube_video_url, PHP_URL_QUERY ), $array_of_vars );
              $video_id=  $array_of_vars['v'];
 
        }
        
	   $place_image=rand(0,99999)."_".$_FILES['place_image']['name'];
		 	 
       //Main Image
	   $tpath1='images/'.$place_image; 			 
       $pic1=compress_image($_FILES["place_image"]["tmp_name"], $tpath1, 80);
	 
	   //Thumb Image 
	   $thumbpath='images/thumb/'.$place_image;		
       $thumb_pic1=create_thumb_image($tpath1,$thumbpath,'200','200');   
      
       $data = array( 
	     'p_cat_id'  =>  $_POST['cat_id'],
         'place_name'  =>  cleanInput($_POST['place_name']),
         'place_description' => addslashes($_POST['place_description']),
         'place_address' => cleanInput($_POST['place_address']),
         'place_email' => cleanInput($_POST['place_email']),
         'place_phone' => cleanInput($_POST['place_phone']),
         'place_website'  => cleanInput($_POST['place_website']),
         'place_map_latitude' => cleanInput($_POST['place_map_latitude']),
         'place_map_longitude' => cleanInput($_POST['place_map_longitude']),
         'place_video'  =>  $youtube_video_url,
	     'place_image'  =>  $place_image
		);		

 		$qry = Insert('tbl_places',$data);	

    $place_id=mysqli_insert_id($mysqli);

   $size_sum = array_sum($_FILES['place_gallery_image']['size']);
     
  if($size_sum > 0)
   {  
      for ($i = 0; $i < count($_FILES['place_gallery_image']['name']); $i++) 
      {
   
           $place_gallery_image=rand(0,99999)."_".$_FILES['place_gallery_image']['name'][$i];
         
           //Main Image
           $tpath1='images/gallery/'.$place_gallery_image;       
           $pic1=compress_image($_FILES["place_gallery_image"]["tmp_name"][$i], $tpath1, 80);

            $data1 = array(
                'place_id'=>$place_id,
                'image_name'  => $place_gallery_image                         
                );      

            $qry1 = Insert('tbl_place_gallery',$data1); 
      }
    }
 	    
		$_SESSION['msg']="10";
		header( "Location:add_place.php");
		exit;	
	}
// Add place video start 
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
 
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
            <form action="" name="addeditcategory" method="post" class="form form-horizontal" enctype="multipart/form-data">
              <div class="section">
                <div class="section-body">                 
                  <div class="form-group">
                    <label class="col-md-3 control-label">Place Title :-</label>
                    <div class="col-md-6">
                      <input type="text" name="place_name" id="place_name" value="" class="form-control" required>
                    </div>
                  </div>
				  <div class="form-group">
                    <label class="col-md-3 control-label">Category :-</label>
                    <div class="col-md-6">
                      <select name="cat_id" id="cat_id" class="select2" required>
                        <option value="">--Select Category--</option>
                        <?php while($cat_row=mysqli_fetch_array($cat_result)){?>                       
                        <option value="<?php echo $cat_row['cid'];?>"><?php echo $cat_row['category_name'];?></option>                           
                        <?php }?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Description :-</label>
                    <div class="col-md-6">
                      <textarea name="place_description" id="place_description" class="form-control"></textarea>
                      <script>CKEDITOR.replace( 'place_description' );</script>
                    </div>
                  </div>
                  <div class="form-group">&nbsp;</div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Address :-</label>
                    <div class="col-md-6">
                      <textarea name="place_address" id="place_address" class="form-control" required></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Email :-</label>
                    <div class="col-md-6">
                      <input type="text" name="place_email" id="place_email" value="" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Phone :-</label>
                    <div class="col-md-6">
                      <input type="text" name="place_phone" id="place_phone" value="" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Website URL :-</label>
                    <div class="col-md-6">
                      <input type="text" name="place_website" id="place_website" value="" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Latitude/Longitude :-</label>
                    <div class="col-md-3">
                      <input type="text" name="place_map_latitude" id="place_map_latitude" placeholder="Latitude" value="" class="form-control">
                    </div>
					<div class="col-md-3">
                      <input type="text" name="place_map_longitude" id="place_map_longitude" placeholder="Longitude" value="" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">&nbsp;</label>
                    <div class="col-md-6">
                      Get Latitude and Longitude <a href="http://www.latlong.net" target="_blank">Here!</a>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">YouTube Video URL :-</label>
                    <div class="col-md-6">
                      <input type="text" name="place_video" id="place_video" value="" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Featured Image :- <p class="control-label-help">(Recommended resolution: 600x400)</p></label>
                    <div class="col-md-6">
                      <div class="fileupload_block">
                        <input type="file" name="place_image" id="fileupload" required="required">
                         <div class="fileupload_img"><img id="place_image" type="image" src="assets/images/portrait.jpg" alt="image" style="width: 90px;height: 120px" /></div>
                        	 
                      </div>
                    </div>
                  </div>
                  <div class="form-group" id="image_news">
                    <label class="col-md-3 control-label">Gallery Image :- <p class="control-label-help">(Recommended resolution: 600x400)</p></label>
                    <div class="col-md-6">
                      <div class="fileupload_block">
                        <input type="file" name="place_gallery_image[]" value="" id="fileupload" multiple required>
                        <div class="fileupload_img"><img type="image" src="assets/images/portrait.jpg" alt="Featured image" /></div>
                      </div>
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
  
 // Preview video thumbnail image start
function readURL(input) {
if (input.files && input.files[0]) {
  var reader = new FileReader();
  
  reader.onload = function(e) {
    $("input[name='place_image']").next(".fileupload_img").find("img").attr('src', e.target.result);
  }
  reader.readAsDataURL(input.files[0]);
}
}
$("input[name='place_image']").change(function() { 
readURL(this);
});
// Preview video thumbnail image end
</script>        