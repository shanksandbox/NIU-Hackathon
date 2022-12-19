<?php 
  $page_title='Manage Slider';

  include("includes/header.php");
  
  	  // Get all slider	
      $tableName="tbl_slider";   
      $targetpage = "manage_slider.php"; 
      $limit = 12; 

      $query = "SELECT COUNT(*) as num FROM $tableName";
      $total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query));
      $total_pages = $total_pages['num'];

      $stages = 3;
      $page=0;
      if(isset($_GET['page'])){
      $page = mysqli_real_escape_string($mysqli,$_GET['page']);
      }
      if($page){
      $start = ($page - 1) * $limit; 
      }else{
      $start = 0; 
      } 

      $qry="SELECT * FROM tbl_slider
      ORDER BY tbl_slider.`id` DESC LIMIT $start, $limit";

      $result=mysqli_query($mysqli,$qry); 


  // paramater wise info
  function get_single_info($place_id,$param,$type='Place')
  {
    global $mysqli;

    switch ($type) {
      case 'Place':
        $query="SELECT * FROM tbl_places WHERE `p_id`='$place_id'";
        break;
     
      default:
        $query="SELECT * FROM tbl_places WHERE `p_id`='$place_id'";
        break;
    }

    $sql = mysqli_query($mysqli,$query)or die(mysqli_error($mysqli));
    $row=mysqli_fetch_assoc($sql);

    return stripslashes($row[$param]);
  } 
   
?>


<div class="row">
  <div class="col-xs-12">
  	<?php
      	if(isset($_SERVER['HTTP_REFERER']))
      	{
      		echo '<a href="'.$_SERVER['HTTP_REFERER'].'"><h4 class="pull-left" style="font-size: 20px;color: #e91e63"><i class="fa fa-arrow-left"></i> Back</h4></a>';
	    }
      ?>
    <div class="card mrg_bottom">
      <div class="page_title_block">
        <div class="col-md-5 col-xs-12">
          <div class="page_title"></div>
        </div>
        <div class="col-md-7 col-xs-12">
          <div class="search_list">
            <div class="add_btn_primary"> <a href="add_slider.php?add=yes">Add New</a> </div>
          </div>
        </div>
      </div>
       <div class="clearfix"></div>
      <div class="col-md-12 mrg-top">
      	<div class="col-md-12 col-sm-12">
                
        <div class="row">
          <?php 
          $i=0;
          while($row=mysqli_fetch_array($result))
          {
              switch ($row['slider_type']) {
                case 'Place':
                  $slider_title=get_single_info($row['place_id'],'place_name','Place');
                  $image=$row['external_image'];
                  break;

                default:
                  $slider_title=$row['slider_title'];
                  $image=$row['external_image'];
                  break;
            }
          ?>
          <?php 
            if($row['slider_type']!='quote')
            {
          ?>
          <div class="col-lg-6 col-sm-6 col-xs-12">
            <div class="block_wallpaper"> 
              <div class="wall_category_block" style="text-align: right;">
                <div class="row" style="padding: 10px;">
                  <span class="label label-success"><?=$row['slider_type']?></span>  
                </div>
              </div>          
              <div class="wall_image_title">
                <h2><a href="javascript:void(0)"><?php echo $slider_title;?></a></h2>
                <ul> 
                  <?php 
                    if($row['slider_type']!='external' AND $row['slider_type']!='Place')
                    {
                      if($slider_type=='Place'){
                  ?>
                    <li><a href="javascript:void(0)" data-toggle="tooltip" data-tooltip="Place"><i class="fa fa-mobile"></i></a></li>
                  <?php 
                    }
                    else
                    {
                  ?>
                  <?php 
                    }
                  }
                  else if($row['slider_type']=='external'){
                    ?>
                    <li><a href="<?=$row['external_url']?>" target="_blank" data-toggle="tooltip" data-tooltip="URL"><i class="fa fa-link"></i></a></li>
                    <?php
                  }
                  ?>

                  <li><a href="" class="btn_delete_a" data-id="<?php echo $row['id'];?>" data-toggle="tooltip" data-tooltip="Delete"><i class="fa fa-trash"></i></a></li>

                  <li><a href="edit_slider.php?edit_id=<?php echo $row['id'];?>" data-toggle="tooltip" data-tooltip="Edit"><i class="fa fa-edit"></i></a></li>        
                  
                  <?php if($row['status']!="0"){?>
                    <li><div class="row toggle_btn"><a href="javascript:void(0)" data-id="<?php echo $row['id'];?>" data-action="deactive" data-column="status" data-toggle="tooltip" data-tooltip="ENABLE"><img src="assets/images/btn_enabled.png" alt="wallpaper_1" /></a></div></li>

                  <?php }else{?>
                  
                    <li><div class="row toggle_btn"><a href="javascript:void(0)" data-id="<?=$row['id']?>" data-action="active" data-column="status" data-toggle="tooltip" data-tooltip="DISABLE"><img src="assets/images/btn_disabled.png" alt="wallpaper_1" /></a></div></li>
              
                  <?php }?>
                </ul>
              </div>
              <span>
                <img src="images/<?php echo $image;?>"/>
              </span>

            </div>
          </div>
          <?php
            }
            else
            {
              ?>
              <div class="col-lg-6 col-sm-6 col-xs-12">
                <div class="block_wallpaper" style="height: 300px;background: #<?=get_single_info($row['[place_id]'],'quote_bg','quote')?>">
                  <div class="wall_category_block" style="text-align: right;">
                    <div class="row" style="padding: 10px;">
                      <span class="label label-success"><?=$row['slider_type']?></span>  
                    </div>
                  </div>
                  <div class="wall_image_title">
                    <ul>

                      <li><a href="" data-id="<?php echo $row['id'];?>" class="btn btn-danger btn_delete btn_delete_a"><i class="fa fa-trash"></i></a></li>

                      <li><a href="edit_slider.php?edit_id=<?php echo $row['id'];?>" data-toggle="tooltip" data-tooltip="Edit"><i class="fa fa-edit"></i></a></li>        
                      
                      <?php if($row['status']!="0"){?>
                        <li><div class="row toggle_btn"><a href="javascript:void(0)" data-id="<?php echo $row['id'];?>" data-action="deactive" data-column="status" data-toggle="tooltip" data-tooltip="ENABLE"><img src="assets/images/btn_enabled.png" alt="wallpaper_1" /></a></div></li>

                      <?php }else{?>
                      
                        <li><div class="row toggle_btn"><a href="javascript:void(0)" data-id="<?=$row['id']?>" data-action="active" data-column="status" data-toggle="tooltip" data-tooltip="DISABLE"><img src="assets/images/btn_disabled.png" alt="wallpaper_1" /></a></div></li>
                  
                      <?php }?>
                    </ul>
                  </div>
                
                 </div>
              </div>
              <?php
            }
          $i++;
        }
    ?>     
           
  </div>
      </div>
      <div class="col-md-12 col-xs-12">
        <div class="pagination_item_block">
          <nav>
            <?php if(!isset($_POST["data_search"])){ include("pagination.php");}?>
          </nav>
        </div>
      </div>
      <div class="clearfix"></div>
    </div>
  </div>
</div>
        
<?php include("includes/footer.php");?>  

<script type="text/javascript">
// for slider enable and disable
 $(".toggle_btn a").on("click",function(e){
      e.preventDefault();
      var _for=$(this).data("action");
      var _id=$(this).data("id");
      var _column=$(this).data("column");
      var _table='tbl_slider';

      $.ajax({
        type:'post',
        url:'processData.php',
        dataType:'json',
  		data:{id:_id,for_action:_for,column:_column,table:_table,'action':'toggle_status','tbl_id':'id'},
          success:function(res){
            console.log(res);
            if(res.status=='1'){
              location.reload();
            }
          }
      });
  });

// for category delete
$(".btn_delete_a").click(function(e){
      e.preventDefault();
      
       var _id=$(this).data("id");
       var _table='tbl_slider';
      
        confirmDlg = duDialog('Are you sure?', 'All data will be removed which belong to this!', {
        init: true,
        dark: false, 
        buttons: duDialog.OK_CANCEL,
        okText: 'Proceed',
        callbacks: {
          okClick: function(e) {
            $(".dlg-actions").find("button").attr("disabled",true);
            $(".ok-action").html('<i class="fa fa-spinner fa-pulse"></i> Please wait..');
            $.ajax({
              type:'post',
              url:'processData.php',
              dataType:'json',
              data:{id:_id,tbl_nm:_table,'action':'removeSlider'},
              success:function(res){
                location.reload();
              }
            });

          } 
        }
      });
      confirmDlg.show();
    });

</script>
