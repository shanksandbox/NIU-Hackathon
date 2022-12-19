<?php $page_title='Add Slider';

include("includes/header.php");

// Add slider start
if(isset($_POST['submit']) AND isset($_GET['add']))
  {
          $slider_type=trim($_POST['slider_type']);

          $ext = pathinfo($_FILES['external_image']['name'], PATHINFO_EXTENSION);

          $external_image=rand(0,99999)."_slider.".$ext;

          //Main Image
          $tpath1='images/'.$external_image;   

          if($ext!='png')  {
            $pic1=compress_image($_FILES["external_image"]["tmp_name"], $tpath1, 80);
          }
          else{
            $tmp = $_FILES['external_image']['tmp_name'];
            move_uploaded_file($tmp, $tpath1);
          }

          $place_id=0;
          $external_url=addslashes(trim($_POST['external_url']));
     	
     	 switch ($slider_type) {
          case 'Place':
              $place_id=$_POST['place_id'];
            break;
         	
          default:
            break;
        }
      
      if($place_id!=0){
        $sql="SELECT * FROM tbl_slider WHERE `place_id`='$place_id' AND `slider_type`='$slider_type' AND `status`='1'";
        $res=mysqli_query($mysqli, $sql);
        if($res->num_rows > 0){
          $_SESSION['class']='alert-danger';
          $_SESSION['msg']='This '.ucwords($slider_type)." is already exists !!";
          header( "Location:add_slider.php?add");
          exit;
        }
      }

	    $data = array(
	     'place_id' =>  $place_id,
         'slider_type' =>  $slider_type,
         'external_url' =>  $external_url,
         'external_image' =>  $external_image
	    );  

	    $qry = Insert('tbl_slider',$data);
      
	    $_SESSION['msg']="10";
	    header( "Location:manage_slider.php");
	    exit; 
  }
 // Add slider end
?>

<!-- For Font Family -->
<link rel="stylesheet" type="text/css" href="assets/css/quotes_fonts.css">
<!-- End -->
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
            <form action="" name="addeditlanguage" method="post" class="form form-horizontal" enctype="multipart/form-data">
              <div class="section">
                <div class="section-body">
                  <div class="form-group">
                    <label class="col-md-3 control-label">Type :-</label>
                    <div class="col-md-6">
                      <select class="select2" required="" name="slider_type">
                        <option value="Place">Place</option>
                        <option value="external">External Banner</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group video_status">
                    <label class="col-md-3 control-label" for="id">Place :-</label>
                    <div class="col-md-6">
                      <select name="place_id" id="place_id" class="select2" required="">
                        <option value="">--Select Place--</option>
                         <?php
                          $sql="SELECT * FROM tbl_places WHERE tbl_places.`place_status`='1' ORDER BY `p_id` DESC";
                          $res=mysqli_query($mysqli, $sql);
                          while($row=mysqli_fetch_array($res))
                          {?>                       
                          <option value="<?php echo $row['p_id'];?>"><?php echo $row['place_name'];?></option>                           
                        <?php }
                          mysqli_free_result($res);
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="external_banner" style="display: none;">
                    <div class="form-group">
                      <label class="col-md-3 control-label" for="external_url">External URL :-</label>
                      <div class="col-md-6">
                        <input type="text" name="external_url" placeholder="Enter external url" id="external_url" value="<?php echo $row['external_url'];?>" class="form-control">
                      </div>
                    </div>
                	</div>
                   </div>
                    <div class="form-group">
                      <label class="col-md-3 control-label">Select Image :-
                        <p class="control-label-help">(Recommended resolution: Landscape: 800x500,650x450</p>
                      </label>
                      <div class="col-md-6">
                        <div class="fileupload_block">
                          <input type="file" name="external_image" value="fileupload" id="fileupload" accept=".png, .jpg, .jpeg" required>
                          <div class="fileupload_img" id="uploadPreview">
                          	<img width="100%" type="image" src="assets/images/Landscape.jpg" alt="image alt" style="width: 120px;height: 120px"/> 
                          </div>
                      </div>
                    </div>
                  <div class="form-group">
                   <div class="col-md-9 col-md-offset-3">
                      <button type="submit" name="submit" class="btn btn-primary">Save</button>
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
  $("select[name='slider_type']").on("change",function(e){
    var type=$(this).val();

    $(".video_status").find("select").attr("required",false);
    $(".external_banner").find("input").attr("required",false);

    switch (type) {
      case 'Place':
        {
          $(".video_status").show();
          $(".video_status").find("select").attr("required",true);
          $(".external_banner").hide();
        }
        break;

      case 'external':
        {
          $(".external_banner").show();
          $(".external_banner").find("input").attr("required",true);
          $(".video_status").hide();
        }
        break;
    }

  });

  $("select[name='place_id']").on("change",function(e){
    var url=$(this).children("option:selected").data("url");
    $(this).parent("div").find(".preview").attr('src',url);
    $(this).parent("div").find(".preview").show();
  });

  var _URL = window.URL || window.webkitURL;

  $("#fileupload").change(function(e) {
      var file, img;
      var thisFile=$(this);

      var countCheck=0;
      
      if ((file = this.files[0])) {
          img = new Image();
          img.onload = function() {
              if(this.width < this.height){
                alert("Image width must be greater than image height !");
                thisFile.val('');
                $('#uploadPreview').html('');
                return false;
              }
              else if(this.width == this.height){
                alert("Image width must not equal to image height !");
                thisFile.val('');
                $('#uploadPreview').html('');
                return false;
              }
              
          };
          img.onerror = function() {
              alert( "not a valid file: " + file.type);
          };

          img.src = _URL.createObjectURL(file);
          
          $('#uploadPreview').html('<img src="'+img.src+'" style="width: 120px;height: 120px""/>');

      }

  });

</script>