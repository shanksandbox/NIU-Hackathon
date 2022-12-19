<?php $page_title="Settings";

include("includes/header.php");
	
  // Get settings		
  $qry="SELECT * FROM tbl_settings WHERE `id`='1'";
  $result=mysqli_query($mysqli,$qry);
  $settings_row=mysqli_fetch_assoc($result);
 
  // Update app settings	
  if(isset($_POST['submit']))
  {

    $img_res=mysqli_query($mysqli,"SELECT * FROM tbl_settings WHERE `id`='1'");
    $img_row=mysqli_fetch_assoc($img_res);

    if($_FILES['app_logo']['name']!="")
    {        

        unlink('images/'.$img_row['app_logo']);   

        $app_logo=$_FILES['app_logo']['name'];
        $pic1=$_FILES['app_logo']['tmp_name'];

        $tpath1='images/'.$app_logo;      
        copy($pic1,$tpath1);


          $data = array(      
          'app_from_email'  =>  '-',   
          'app_name'  =>  cleanInput($_POST['app_name']),
          'app_logo'  =>  $app_logo,  
          'app_description'  => addslashes($_POST['app_description']),
          'app_version'  =>  cleanInput($_POST['app_version']),
          'app_author'  =>  cleanInput($_POST['app_author']),
          'app_contact'  =>  cleanInput($_POST['app_contact']),
          'app_email'  => cleanInput($_POST['app_email']),   
          'app_website'  =>  cleanInput($_POST['app_website']),
          'app_developed_by'  =>  cleanInput($_POST['app_developed_by'])                     

          );

}
else
{
          $data = array(      
          'app_from_email'  =>  '-',   
          'app_name'  =>  cleanInput($_POST['app_name']),
          'app_description'  => addslashes($_POST['app_description']),
          'app_version'  =>  cleanInput($_POST['app_version']),
          'app_author'  =>  cleanInput($_POST['app_author']),
          'app_contact'  =>  cleanInput($_POST['app_contact']),
          'app_email'  => cleanInput($_POST['app_email']),   
          'app_website'  =>  cleanInput($_POST['app_website']),
          'app_developed_by'  =>  cleanInput($_POST['app_developed_by'])                     

          );
    } 

    $settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");
  
    $_SESSION['msg']="11";
    header( "Location:settings.php");
    exit;
  
  }

 // Update admob settings
 else if(isset($_POST['admob_submit']))
   {	

      $data = array(
        'publisher_id'  =>  cleanInput($_POST['publisher_id']),
        'interstital_ad'  =>  ($_POST['interstital_ad']) ? 'true' : 'false',
        'interstital_ad_type'  =>  cleanInput($_POST['interstital_ad_type']),
        'interstital_ad_id'  =>  cleanInput($_POST['interstital_ad_id']),
        'interstital_facebook_id'  =>  cleanInput($_POST['interstital_facebook_id']),
        'interstital_ad_click'  =>  cleanInput($_POST['interstital_ad_click']),
        'banner_ad'  =>  ($_POST['banner_ad']) ? 'true' : 'false',
        'banner_ad_type'  =>  cleanInput($_POST['banner_ad_type']),
        'banner_ad_id'  =>  cleanInput($_POST['banner_ad_id']),
        'banner_facebook_id'  =>  cleanInput($_POST['banner_facebook_id']),
        'native_ad'  =>  ($_POST['native_ad']) ? 'true' : 'false',
        'native_ad_type'  =>  cleanInput($_POST['native_ad_type']),
        'native_ad_id'  =>  cleanInput($_POST['native_ad_id']),
        'native_facebook_id'  =>  cleanInput($_POST['native_facebook_id']),
        'native_cat_position'  =>  cleanInput($_POST['native_cat_position']),
        'native_position'  =>  cleanInput($_POST['native_position']),

        'publisher_id_ios'  =>  cleanInput($_POST['publisher_id_ios']),

        'interstital_ad_ios'  =>  ($_POST['interstital_ad_ios']) ? 'true' : 'false',
        'interstital_ad_type_ios'  =>  cleanInput($_POST['interstital_ad_type_ios']),
        'interstital_ad_id_ios'  =>  cleanInput($_POST['interstital_ad_id_ios']),
        'interstital_ad_click_ios'  =>  cleanInput($_POST['interstital_ad_click_ios']),
        'interstital_facebook_id_ios'  =>  cleanInput($_POST['interstital_facebook_id_ios']),

        'banner_ad_ios'  => ($_POST['banner_ad_ios']) ? 'true' : 'false',
        'banner_ad_type_ios'  =>  cleanInput($_POST['banner_ad_type_ios']),
        'banner_ad_id_ios'  =>  cleanInput($_POST['banner_ad_id_ios']),
        'banner_facebook_id_ios'  =>  cleanInput($_POST['banner_facebook_id_ios'])

        /*'native_ad_ios'  =>  ($_POST['native_ad_ios']) ? 'true' : 'false',
        'native_ad_type_ios'  =>  cleanInput($_POST['native_ad_type_ios']),
        'native_ad_id_ios'  =>  cleanInput($_POST['native_ad_id_ios']),
        'native_facebook_id_ios'  =>  cleanInput($_POST['native_facebook_id_ios']),
        'native_cat_position_ios'  =>  cleanInput($_POST['native_cat_position_ios']),
        'native_position_ios'  =>  cleanInput($_POST['native_position_ios'])*/
      );


      $settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");
      
      $_SESSION['msg']="11";
      header( "Location:settings.php");
      exit;
  }

  // Update api settings
  else if(isset($_POST['api_submit']))
  {

        $data = array(
        	    'api_page_limit'  =>  cleanInput($_POST['api_page_limit']),
                'api_cat_order_by'  =>  cleanInput($_POST['api_cat_order_by']),
                'api_cat_post_order_by'  =>  cleanInput($_POST['api_cat_post_order_by'])
        );

   	$settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");
 
    $_SESSION['msg']="11";
    header( "Location:settings.php");
    exit;
  }
 // Update app update popup 
 else if(isset($_POST['app_update_popup']))
    {

      $data = array(
        'app_update_status'  =>  ($_POST['app_update_status']) ? 'true' : 'false',
        'app_new_version'  =>  trim($_POST['app_new_version']),
        'app_update_desc'  =>  trim($_POST['app_update_desc']),
        'app_redirect_url'  =>  trim($_POST['app_redirect_url']),
        'cancel_update_status'  =>  ($_POST['cancel_update_status']) ? 'true' : 'false',

        'app_update_status_ios'  =>  ($_POST['app_update_status_ios']) ? 'true' : 'false',
        'app_new_version_ios'  =>  trim($_POST['app_new_version_ios']),
        'app_update_desc_ios'  =>  trim($_POST['app_update_desc_ios']),
        'app_redirect_url_ios'  =>  trim($_POST['app_redirect_url_ios']),
        'cancel_update_status_ios'  =>  ($_POST['cancel_update_status_ios']) ? 'true' : 'false'
      );

      $settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");

      $_SESSION['msg']="11";
      header("Location:settings.php");
      exit;

  }
  // Update app privacy policy
  else if(isset($_POST['app_pri_poly']))
  {
        $data = array(
              'app_privacy_policy'  =>  addslashes($_POST['app_privacy_policy']) 
        );

        $settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");

	    $_SESSION['msg']="11";
	    header( "Location:settings.php");
	    exit;
  }
 // Update delete intruction start
 else if(isset($_POST['account_delete'])){

  $data = array(
    'account_delete_intruction'  =>  trim($_POST['account_delete_intruction'])
  );

  $settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");

  $_SESSION['msg'] = "11";
  header( "Location:settings.php");
  exit;
} 
// Update delete intruction end 

?>
 
 <style type="text/css">
  .field_lable {
    margin-bottom: 10px;
    margin-top: 10px;
    color: #666;
    padding-left: 15px;
    font-size: 14px;
    line-height: 24px;
  }
  .banner_ads_block .toggle_btn, .interstital_ad_item .toggle_btn{
    margin-top: 6px;
  }
  .lbl{
    left: 13px;
  }
  .video_setting_item{
	background: #f7f7f7;
	border:1px solid rgba(0, 0, 0, 0.1);
	margin-top:0px;
	padding:10px 20px;
	margin-bottom: 10px;
	border-radius:6px;	
}
</style>
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
       
          <div class="card-body mrg_bottom" style="padding: 0px">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" lass="active"><a href="#app_settings" aria-controls="app_settings" role="tab" data-toggle="tab">App Settings</a></li>
           
                <li role="presentation"><a href="#api_settings" aria-controls="api_settings" role="tab" data-toggle="tab">API Settings</a></li>
           

            </ul>
          	 <div class="rows">
            <div class="col-md-12">
             <div class="tab-content">
	              <div role="tabpanel" class="tab-pane active" id="app_settings">   
	                <form action="" name="settings_from" method="post" class="form form-horizontal" enctype="multipart/form-data">
	              <div class="section">
	                <div class="section-body">
	                  <div class="form-group">
	                    <label class="col-md-3 control-label">App Name :-</label>
	                    <div class="col-md-6">
	                      <input type="text" name="app_name" id="app_name" value="<?php echo $settings_row['app_name'];?>" class="form-control">
	                    </div>
	                  </div>
	                  <div class="form-group">
	                    <label class="col-md-3 control-label">App Logo :- <p class="control-label-help">(Recommended resolution: 80X80, 90x90)</p></label>
	                    <div class="col-md-6">
	                      <div class="fileupload_block">
	                       <input type="file" name="app_logo" id="fileupload" onchange="readURL(this)">
	                          <?php if($settings_row['app_logo']!="") {?>
	                            <div class="fileupload_img"><img type="image" id="app_logo" src="images/<?php echo $settings_row['app_logo'];?>" alt="image" style="width: 90px;" /></div>
	                          <?php } else {?>
	                            <div class="fileupload_img"><img id="app_logo" type="image" src="assets/images/portrait.jpg" alt="image" style="width: 90px;"/></div>
	                          <?php }?>
	                      </div>
	                    </div>
	                  </div>
	                  <div class="form-group">
	                    <label class="col-md-3 control-label">App Description :-</label>
	                    <div class="col-md-6">
	                 
	                      <textarea name="app_description" id="app_description" class="form-control"><?php echo $settings_row['app_description'];?></textarea>

	                      <script>CKEDITOR.replace( 'app_description' );</script>
	                    </div>
	                  </div>
	                  <div class="form-group">&nbsp;</div>                 
	                  <div class="form-group">
	                    <label class="col-md-3 control-label">App Version :-</label>
	                    <div class="col-md-6">
	                      <input type="text" name="app_version" id="app_version" value="<?php echo $settings_row['app_version'];?>" class="form-control">
	                    </div>
	                  </div>
	                  <div class="form-group">
	                    <label class="col-md-3 control-label">Author :-</label>
	                    <div class="col-md-6">
	                      <input type="text" name="app_author" id="app_author" value="<?php echo $settings_row['app_author'];?>" class="form-control">
	                    </div>
	                  </div>
	                  <div class="form-group">
	                    <label class="col-md-3 control-label">Contact :-</label>
	                    <div class="col-md-6">
	                      <input type="text" name="app_contact" id="app_contact" value="<?php echo $settings_row['app_contact'];?>" class="form-control">
	                    </div>
	                  </div>     
	                  <div class="form-group">
	                    <label class="col-md-3 control-label">Email :-</label>
	                    <div class="col-md-6">
	                      <input type="text" name="app_email" id="app_email" value="<?php echo $settings_row['app_email'];?>" class="form-control">
	                    </div>
	                  </div>                 
	                   <div class="form-group">
	                    <label class="col-md-3 control-label">Website :-</label>
	                    <div class="col-md-6">
	                      <input type="text" name="app_website" id="app_website" value="<?php echo $settings_row['app_website'];?>" class="form-control">
	                    </div>
	                  </div> 
	                  <div class="form-group">
	                    <label class="col-md-3 control-label">Developed By :-</label>
	                    <div class="col-md-6">
	                      <input type="text" name="app_developed_by" id="app_developed_by" value="<?php echo $settings_row['app_developed_by'];?>" class="form-control">
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
		           <!-- Admob settings -->
                <div role="tabpanel" class="tab-pane" id="admob_settings">   
                  <form action="" name="admob_settings" method="post" class="form form-horizontal" enctype="multipart/form-data">
                    <div class="section">
                      <div class="section-body">            
                          <div class="row">
                            <div class="col-md-6">                
							    <div class="admob_title">Android</div>
								<div class="form-group">
								  <label class="col-md-3 control-label">Publisher ID :-</label>
								  <div class="col-md-9">
								   <input type="text" name="publisher_id" id="publisher_id" value="<?php echo $settings_row['publisher_id'];?>" class="form-control">
								 </div>
								 <div style="height:60px;display:inline-block;position:relative"></div>
							    </div>
                                <div class="banner_ads_block">
                                  <div class="banner_ad_item">
                                    <label class="control-label">Banner Ads :-</label>
                                    <div class="row toggle_btn" style="position: relative;margin-top: -8px;">
                                      <input type="checkbox" id="chk_banner" name="banner_ad" value="true" class="cbx hidden" <?=($settings_row['banner_ad']=='true') ? 'checked=""' : '' ?>>
                                      <label for="chk_banner" class="lbl"></label>
                                    </div>                               
                                  </div>
                                  <div class="col-md-12">
                                    <div class="form-group">
                                      <p class="field_lable">Banner Ad Type :-</p>
                                      <div class="col-md-12">
                                       <select name="banner_ad_type" id="banner_ad_type" class="select2">
                                          <option value="admob" <?php if($settings_row['banner_ad_type']=='admob'){ echo 'selected="selected"'; }?>>Admob</option>
                                          <option value="facebook" <?php if($settings_row['banner_ad_type']=='facebook'){ echo 'selected="selected"'; }?>>Facebook</option>
                                        </select>
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <p class="field_lable">Banner ID :-</p>
                                      <div class="col-md-12 banner_ad_id" style="display: none">
                                        <input type="text" name="banner_ad_id" id="banner_ad_id" value="<?php echo $settings_row['banner_ad_id'];?>" class="form-control">
                                      </div>
                                      <div class="col-md-12 banner_facebook_id" style="display: none">
                                        <input type="text" name="banner_facebook_id" id="banner_facebook_id" value="<?php echo $settings_row['banner_facebook_id'];?>" class="form-control">
                                      </div>
                                    </div>
                                  </div>                   
                                </div>
                                <div class="interstital_ads_block">
                                  <div class="interstital_ad_item">
                                    <label class="control-label">Interstitial Ads :-</label>
                                    <div class="row toggle_btn" style="position: relative;margin-top: -8px;">
                                      <input type="checkbox" id="chk_interstitial" name="interstital_ad" value="true" class="cbx hidden" <?php if($settings_row['interstital_ad']=='true'){?>checked <?php }?>/>
                                      <label for="chk_interstitial" class="lbl"></label>
                                    </div>                  
                                  </div>  
                                  <div class="col-md-12">
                                    <div class="form-group">
                                      <p class="field_lable">Interstitial Ad Type :-</p>
                                      <div class="col-md-12"> 
                                        <select name="interstital_ad_type" id="interstital_ad_type" class="select2">
                                          <option value="admob" <?php if($settings_row['interstital_ad_type']=='admob'){ echo 'selected="selected"'; }?>>Admob</option>
                                          <option value="facebook" <?php if($settings_row['interstital_ad_type']=='facebook'){ echo 'selected="selected"'; }?>>Facebook</option>
                                        </select>                                 
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <p class="field_lable">Interstitial Ad ID :-</p>
                                      <div class="col-md-12 interstital_ad_id" style="display: none">
                                        <input type="text" name="interstital_ad_id" id="interstital_ad_id" value="<?php echo $settings_row['interstital_ad_id'];?>" class="form-control">
                                      </div>

                                      <div class="col-md-12 interstital_facebook_id" style="display: none">
                                        <input type="text" name="interstital_facebook_id" id="interstital_facebook_id" value="<?php echo $settings_row['interstital_facebook_id'];?>" class="form-control">
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <p class="field_lable">Interstitial Clicks :-</p>
                                      <div class="col-md-12">
                                        <input type="text" name="interstital_ad_click" id="interstital_ad_click" value="<?php echo $settings_row['interstital_ad_click'];?>" class="form-control">
                                      </div>
                                    </div>                   
                                  </div>                  
                                </div>

                                <div class="banner_ads_block">
                                  <div class="banner_ad_item">
                                    <label class="control-label">Native Ads:-</label>
                                    <div class="row toggle_btn" style="position: relative;margin-top: -8px;">
                                      <input type="checkbox" id="checked4" name="native_ad" value="true" class="cbx hidden" <?php if($settings_row['native_ad']=='true'){?>checked <?php }?> />
                                      <label for="checked4" class="lbl"></label>
                                    </div>
                                  </div>              
                                  <div class="col-md-12">
                                    <div class="form-group" id="#admob_banner_id">                              
                                        <p class="field_lable">Native Ad Type :-</p>
                                        <div class="col-md-12"> 
                                          <select name="native_ad_type" id="native_ad_type" class="select2">
                                            <option value="admob" <?php if($settings_row['native_ad_type']=='admob'){?>selected<?php }?>>Admob</option>
                                            <option value="facebook" <?php if($settings_row['native_ad_type']=='facebook'){?>selected<?php }?>>Facebook</option>
                            
                                          </select>                                 
                                        </div>
                                        <p class="field_lable">Native Ad ID :-</p>
                                      <div class="col-md-12 native_ad_id" style="display: none">
                                        <input type="text" name="native_ad_id" id="native_ad_id" value="<?php echo $settings_row['native_ad_id'];?>" class="form-control">
                                      </div>
                                      <div class="col-md-12 native_facebook_id" style="display: none">
                                        <input type="text" name="native_facebook_id" id="native_facebook_id" value="<?php echo $settings_row['native_facebook_id'];?>" class="form-control">
                                      </div>
                                      <p class="field_lable">Position of Ad in Category, Sub Category List:- <br/>(MUST USE ODD NUMBER)</p>
                                      <div class="col-md-12"> 
                                          <input type="number" name="native_cat_position" id="native_cat_position" value="<?php echo $settings_row['native_cat_position'];?>" class="form-control ads_click">                                 
                                      </div>
                                      <p class="field_lable">Position of Ad in Other List:-</p>
                                      <div class="col-md-12"> 
                                          <input type="number" name="native_position" id="native_position" value="<?php echo $settings_row['native_position'];?>" class="form-control ads_click">                                 
                                      </div>
                                    </div>                      
                                  </div>
                                </div>
                            </div>
                            <div class="col-md-6">
							    <div class="admob_title">iOS</div>
								  <div class="form-group">
									<label class="col-md-3 control-label">Publisher ID :-</label>
									<div class="col-md-9">
									 <input type="text" name="publisher_id_ios" id="publisher_id_ios" value="<?php echo $settings_row['publisher_id_ios'];?>" class="form-control">
								   </div>                  
								  </div>                                   
                                <div class="banner_ads_block">
                                  <div class="banner_ad_item">
                                    <label class="control-label">Banner Ads :-</label>
                                     <div class="row toggle_btn" style="position: relative;margin-top: -8px;">
                                      <input type="checkbox" id="chk_banner_ios" name="banner_ad_ios" value="true" class="cbx hidden" <?=($settings_row['banner_ad_ios']=='true') ? 'checked=""' : '' ?>>
                                      <label for="chk_banner_ios" class="lbl"></label>
                                    </div>                                     
                                  </div>
                                  <div class="col-md-12">
                                    <div class="form-group">
                                      <label class="col-md-3 control-label">Banner Ad:-</label>
                                      <div class="col-md-12">
                                       <select name="banner_ad_type_ios" id="banner_ad_type_ios" class="select2">
                                          <option value="admob" <?php if($settings_row['banner_ad_type_ios']=='admob'){ echo 'selected="selected"'; }?>>Admob</option>
                                          <option value="facebook" <?php if($settings_row['banner_ad_type_ios']=='facebook'){ echo 'selected="selected"'; }?>>Facebook</option>
                                        </select>
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <label class="field_lable">Banner ID :-</label>
                                      <div class="col-md-12 banner_ad_id_ios" style="display: none">
                                        <input type="text" name="banner_ad_id_ios" id="banner_ad_id_ios" value="<?php echo $settings_row['banner_ad_id_ios'];?>" class="form-control">
                                      </div>
                                        <div class="col-md-12 banner_facebook_id_ios" style="display: none">
                                        <input type="text" name="banner_facebook_id_ios" id="banner_facebook_id_ios" value="<?php echo $settings_row['banner_facebook_id_ios'];?>" class="form-control">
                                      </div>
                                    </div>                    
                                  </div>
                               </div> 

                                <div class="interstital_ads_block">
                                  <div class="interstital_ad_item">
                                    <label class="control-label">Interstitial Ads :-</label>
                                    <div class="row toggle_btn" style="position: relative;margin-top: -8px;">
                                      <input type="checkbox" id="chk_interstitial_ios" name="interstital_ad_ios" value="true" class="cbx hidden" <?php if($settings_row['interstital_ad_ios']=='true'){?>checked <?php }?>/>
                                      <label for="chk_interstitial_ios" class="lbl"></label>
                                    </div> 

                                  </div>  
                                  <div class="col-md-12">
                                   <div class="form-group">
                                      <p class="field_lable">Interstitial Ad Type :-</p>
                                      <div class="col-md-12"> 
                                        <select name="interstital_ad_type_ios" id="interstital_ad_type_ios" class="select2">
                                          <option value="admob" <?php if($settings_row['interstital_ad_type_ios']=='admob'){ echo 'selected="selected"'; }?>>Admob</option>
                                          <option value="facebook" <?php if($settings_row['interstital_ad_type_ios']=='facebook'){ echo 'selected="selected"'; }?>>Facebook</option>
                                        </select>                                 
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <p class="field_lable">Interstitial Ad ID :-</p>
                                      <div class="col-md-12 interstital_ad_id_ios" style="display: none">
                                        <input type="text" name="interstital_ad_id_ios" id="interstital_ad_id_ios" value="<?php echo $settings_row['interstital_ad_id_ios'];?>" class="form-control">
                                      </div>
                                      <div class="col-md-12 interstital_facebook_id_ios" style="display: none">
                                        <input type="text" name="interstital_facebook_id_ios" id="interstital_facebook_id_ios" value="<?php echo $settings_row['interstital_facebook_id_ios'];?>" class="form-control">
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <label class="col-md-3 control-label mr_bottom20">Interstitial  Clicks :-</label>
                                      <div class="col-md-12">
                                        <input type="text" name="interstital_ad_click_ios" id="interstital_ad_click_ios" value="<?php echo $settings_row['interstital_ad_click_ios'];?>" class="form-control">
                                      </div>
                                    </div>                    
                                  </div>                  
                                </div>
                                <!--<div class="banner_ads_block">
                                  <div class="banner_ad_item">
                                    <label class="control-label">Native Ads:-</label>
                                    <div class="row toggle_btn" style="position: relative;margin-top: -8px;">
                                      <input type="checkbox" id="checked4_ios" name="native_ad_ios" value="true" class="cbx hidden" <?php if($settings_row['native_ad_ios']=='true'){?>checked <?php }?> />
                                      <label for="checked4_ios" class="lbl"></label>
                                    </div>
                                  </div>              
                                  <div class="col-md-12">
                                    <div class="form-group" id="#admob_banner_id">                              
                                        <p class="field_lable">Native Ad Type :-</p>
                                        <div class="col-md-12"> 
                                          <select name="native_ad_type_ios" id="native_ad_type_ios" class="select2">
                                            <option value="admob" <?php if($settings_row['native_ad_type_ios']=='admob'){?>selected<?php }?>>Admob</option>
                                            <option value="facebook" <?php if($settings_row['native_ad_type_ios']=='facebook'){?>selected<?php }?>>Facebook</option>
                            
                                          </select>                                 
                                        </div>
                                        <p class="field_lable">Native Ad ID :-</p>
                                      <div class="col-md-12 native_ad_id_ios" style="display: none">
                                        <input type="text" name="native_ad_id_ios" id="native_ad_id_ios" value="<?php echo $settings_row['native_ad_id_ios'];?>" class="form-control">
                                      </div>
                                      <div class="col-md-12 native_facebook_id_ios" style="display: none">
                                        <input type="text" name="native_facebook_id_ios" id="native_facebook_id_ios" value="<?php echo $settings_row['native_facebook_id_ios'];?>" class="form-control">
                                      </div>
                                      <p class="field_lable">Position of Ad in Category, Sub Category List:- <br/>(MUST USE ODD NUMBER)</p>
                                      <div class="col-md-12"> 
                                          <input type="number" name="native_cat_position_ios" id="native_cat_position_ios" value="<?php echo $settings_row['native_cat_position_ios'];?>" class="form-control ads_click">                                 
                                      </div>
                                      <p class="field_lable">Position of Ad in Other List:-</p>
                                      <div class="col-md-12"> 
                                          <input type="number" name="native_position_ios" id="native_position_ios" value="<?php echo $settings_row['native_position_ios'];?>" class="form-control ads_click">
                                      </div>
                                    </div>                      
                                  </div>
                                </div>-->
                            </div>
                        </div>
                        <div class="rows">
                          <div class="col-md-12">
                            <div class="form-group">
                              <div class="col-md-9 col-md-offset-0" style="margin-top:15px;">
                                <button type="submit" name="admob_submit" class="btn btn-primary">Save</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="clearfix"></div>
                  </form>
                </div>
         		<div role="tabpanel" class="tab-pane" id="app_update_popup">   
		          <form action="" name="app_update_popup" method="post" class="form form-horizontal" enctype="multipart/form-data">
		          <div class="section">
		          <div class="section-body">            
		            <div class="row">
		              <div class="col-md-6">                
		                <div class="admob_title">Android</div>
		                 <div class="form-group">
	                     <div class="col-md-12"> 
	                     	<div class="video_setting_item">
							  <div class="row" style="padding: 0px;margin-top: 10px">
								<label class="col-md-10 control-label" style="padding-top: 6px">App Update Popup Show/Hide:-
								 <p class="control-label-help" style="color:#F00">You can show/hide update popup from this option</p>
								</label>
								<div class="col-md-2">
								 <input type="checkbox" id="chk_update" name="app_update_status" value="true" class="cbx hidden" <?php if($settings_row['app_update_status']=='true'){ echo 'checked'; }?>/>
								  <label for="chk_update" class="lbl" style="left:13px;margin-top: 20px;"></label>
								</div>
							  </div>
		                    </div>
	          				<div class="video_setting_item">
							  <div class="row" style="padding: 0px;margin-top: 10px">
								<label class="col-md-6 control-label" style="padding-top: 6px">New App Version Code:-
	                        	<a href="assets/images/android_version_code.png" target="_blank"><p class="control-label-help" style="color:#F00">How to get version code</p></a>
								</label>
								<div class="col-md-11">
 								<input type="number" min="1" name="app_new_version" id="app_new_version" required="" value="<?php echo $settings_row['app_new_version'];?>" class="form-control">								
 							   </div>
							  </div>
		                    </div>
		                    <div class="video_setting_item">
							  <div class="row" style="padding: 0px;margin-top: 10px">
								<label class="col-md-6 control-label" style="padding-top: 6px;margin-bottom:8px">Description:-</label>
								<div class="col-md-11">
								  <textarea name="app_update_desc" class="form-control"><?php echo $settings_row['app_update_desc'];?></textarea>
								</div>
							  </div>
		                    </div>
		                    <div class="video_setting_item">
							  <div class="row" style="padding: 0px;margin-top: 10px">
								<label class="col-md-6 control-label" style="padding-top: 6px">App Link:-
								  <p style="color: red">You will be redirect on this link after click on updaten</p>
								</label>
								<div class="col-md-11">
								  <input type="text" name="app_redirect_url" id="app_redirect_url" required="" value="<?php echo $settings_row['app_redirect_url'];?>" class="form-control">
								</div>
							  </div>
		                    </div>
		                    <div class="video_setting_item">
							  <div class="row" style="padding: 0px;margin-top: 10px">
								<label class="col-md-10 control-label" style="padding-top: 6px">Cancel Option:-
								 <p class="control-label-help" style="color:#F00">Cancel button option will show in app update popup</p>
								</label>
								<div class="col-md-2">
								<input type="checkbox" id="chk_cancel_update" name="cancel_update_status" value="true" class="cbx hidden" <?php if($settings_row['cancel_update_status']=='true'){ echo 'checked'; }?>/>
	                            <label for="chk_cancel_update" class="lbl" style="left:13px;margin-top: 20px;"></label>
								</div>
							  </div>
		                    </div>
			               </div>
			           	  </div>
		              </div>  
		             
		              <div class="col-md-6">                
		                <div class="admob_title">iOS</div>
		                <div class="form-group">
	                     <div class="col-md-12"> 
	                     	<div class="video_setting_item">
							  <div class="row" style="padding: 0px;margin-top: 10px">
								<label class="col-md-10 control-label" style="padding-top: 6px">App Update Popup Show/Hide:-
								 <p class="control-label-help" style="color:#F00">You can show/hide update popup from this option</p>
								</label>
								<div class="col-md-2">
								 <input type="checkbox" id="chk_update_ios" name="app_update_status_ios" value="true" class="cbx hidden" <?php if($settings_row['app_update_status_ios']=='true'){ echo 'checked'; }?>/>
								  <label for="chk_update_ios" class="lbl" style="left:13px;margin-top: 20px;"></label>
								</div>
							  </div>
		                    </div>
	          				<div class="video_setting_item">
							  <div class="row" style="padding: 0px;margin-top: 10px">
								<label class="col-md-6 control-label" style="padding-top: 6px">New App Version Code:-
	                        	<a href="assets/images/android_version_code.png" target="_blank"><p class="control-label-help" style="color:#F00">How to get version code</p></a>
								</label>
								<div class="col-md-11">
 								<input type="number" min="1" name="app_new_version_ios" id="app_new_version_ios" required="" value="<?php echo $settings_row['app_new_version_ios'];?>" class="form-control">								
 							   </div>
							  </div>
		                    </div>
		                    <div class="video_setting_item">
							  <div class="row" style="padding: 0px;margin-top: 10px">
								<label class="col-md-6 control-label" style="padding-top: 6px;margin-bottom:8px">Description:-
								</label>
								<div class="col-md-11">
								  <textarea name="app_update_desc_ios" class="form-control"><?php echo $settings_row['app_update_desc_ios'];?></textarea>
								</div>
							  </div>
		                    </div>
		                    <div class="video_setting_item">
							  <div class="row" style="padding: 0px;margin-top: 10px">
								<label class="col-md-6 control-label" style="padding-top: 6px">App Link:-
								  <p style="color: red">You will be redirect on this link after click on updaten</p>
								</label>
								<div class="col-md-11">
								  <input type="text" name="app_redirect_url_ios" id="app_redirect_url_ios" required="" value="<?php echo $settings_row['app_redirect_url_ios'];?>" class="form-control">
								</div>
							  </div>
		                    </div>
		                    
		                    <div class="video_setting_item">
							  <div class="row" style="padding: 0px;margin-top: 10px">
								<label class="col-md-10 control-label" style="padding-top: 6px">Cancel Option:-
								 <p class="control-label-help" style="color:#F00">Cancel button option will show in app update popup</p>
								</label>
								<div class="col-md-2">
								<input type="checkbox" id="chk_cancel_update_ios" name="cancel_update_status_ios" value="true" class="cbx hidden" <?php if($settings_row['cancel_update_status_ios']=='true'){ echo 'checked'; }?>/>
	                            <label for="chk_cancel_update_ios" class="lbl" style="left:13px;margin-top: 20px;"></label>
								</div>
							  </div>
		                    </div>
			               </div>
		              	</div>
		              </div>
		            </div>
		            </div>                        
		            <div class="form-group">
		             <div class="col-md-9 col-md-offset-0" style="margin-top:15px;">
		              <button type="submit" name="app_update_popup" class="btn btn-primary">Save</button>
		             </div>
		            </div>
		           </div>
		          </form>
		         </div>
	            <div role="tabpanel" class="tab-pane" id="api_settings">   
                <form action="" name="settings_api" method="post" class="form form-horizontal" enctype="multipart/form-data" id="api_form">
                  <input type="hidden" name="length" value="45">
                <div class="section">
                <div class="section-body">
                   	<div class="form-group">
                    <label class="col-md-3 control-label">Pagination Limit:-</label>
                    <div class="col-md-6">
                      <input type="number" name="api_page_limit" id="api_page_limit" value="<?php echo $settings_row['api_page_limit'];?>" class="form-control"> 
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Category List Order By:-</label>
                    <div class="col-md-6">
                        <select name="api_cat_order_by" id="api_cat_order_by" class="select2">
                          <option value="cid" <?php if($settings_row['api_cat_order_by']=='cid'){?>selected<?php }?>>ID</option>
                          <option value="category_name" <?php if($settings_row['api_cat_order_by']=='category_name'){?>selected<?php }?>>Name</option>
                        </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Category Post Order By:-</label>
                    <div class="col-md-6">
                        <select name="api_cat_post_order_by" id="api_cat_post_order_by" class="select2">
                          <option value="ASC" <?php if($settings_row['api_cat_post_order_by']=='ASC'){?>selected<?php }?>>ASC</option>
                          <option value="DESC" <?php if($settings_row['api_cat_post_order_by']=='DESC'){?>selected<?php }?>>DESC</option>
              
                        </select>
                    </div>
                  </div>
                  <br>
                  <div class="form-group">
                    <div class="col-md-9 col-md-offset-3">
                      <button type="submit" name="api_submit" class="btn btn-primary">Save</button>
                    </div>
                  </div>
                </div>
              </div>
               </form>
              </div>
              <br> 
               <div role="tabpanel" class="tab-pane" id="account_delete"> 
	            <div class="rows">
	                <form action="" method="post" class="form form-horizontal" enctype="multipart/form-data">
	                  <div class="section">
	                    <div class="section-body">
	                    	<?php 
	                        if(file_exists('delete_instruction.php'))
								{
	                        ?>
	                        <div class="form-group">
	                          <label class="col-md-3 control-label">Account Delete Instructions URL :-</label>
	                          <div class="col-md-9">
	                            <input type="text" readonly class="form-control" value="<?=getBaseUrl().'delete_instruction.php'?>">
	                          </div>
	                        </div>
	                      <?php } ?>
	                      <div class="form-group">
	                        <label class="col-md-3 control-label">Account Delete Instructions :-</label>
	                        <div class="col-md-9">
	                          <textarea name="account_delete_intruction" id="account_delete_intruction" class="form-control"><?php echo stripslashes($settings_row['account_delete_intruction']);?></textarea>
	                          <script>CKEDITOR.replace('account_delete_intruction');</script>
	                        </div>
	                      </div>
	                      <br/>
	                      <div class="form-group">
	                        <div class="col-md-9 col-md-offset-3">
	                          <button type="submit" name="account_delete" class="btn btn-primary">Save</button>
	                        </div>
	                      </div>
	                      <br>
	                    </div>
	                  </div>
	                </form>
	              </div>
	        	</div>
              <div role="tabpanel" class="tab-pane" id="api_privacy_policy">   
                <form action="" name="api_privacy_policy" method="post" class="form form-horizontal" enctype="multipart/form-data">
              <div class="section">
                <div class="section-body">
                	<?php 
                        if(file_exists('privacy_policy.php'))
                        {
                      ?>
                        <div class="form-group">
                          <label class="col-md-3 control-label">App Privacy Policy URL :-</label>
                          <div class="col-md-9">
                            <input type="text" readonly class="form-control" value="<?=getBaseUrl().'privacy_policy.php'?>">
                          </div>
                        </div>
                      <?php } ?>
                  <div class="form-group">
                    <label class="col-md-3 control-label">App Privacy Policy :-</label>
                    <div class="col-md-9">
                      <textarea name="app_privacy_policy" id="privacy_policy" class="form-control"><?php echo $settings_row['app_privacy_policy'];?></textarea>
                      <script>CKEDITOR.replace( 'privacy_policy' );</script>
                    </div>
                  </div>
                  <br>
                  <div class="form-group">
                    <div class="col-md-9 col-md-offset-3" style="margin-bottom:20px;">
                      <button type="submit" name="app_pri_poly" class="btn btn-primary">Save</button>
                    </div>
                  </div>
                </div>
              </div>
               </form>
           		</div>
      		 </div>
       			<div class="clearfix"></div>
              </div>
            </div>   
          </div>
        </div>
      </div>
    </div>

<?php include("includes/footer.php");?>       

<script type="text/javascript">

  $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
    localStorage.setItem('activeTab', $(e.target).attr('href'));
    document.title = $(this).text()+" | <?=APP_NAME?>";
  });

  var activeTab = localStorage.getItem('activeTab');
  if(activeTab){
    $('.nav-tabs a[href="' + activeTab + '"]').tab('show');
  }

function readURL(input) {
if (input.files && input.files[0]) {
  var reader = new FileReader();
  
  reader.onload = function(e) {
    $("input[name='app_logo']").next(".fileupload_img").find("img").attr('src', e.target.result);
  }
  reader.readAsDataURL(input.files[0]);
}
}
$("input[name='app_logo']").change(function() { 
readURL(this);
});


  
  if($("select[name='banner_ad_type']").val()==='facebook'){
    $(".banner_ad_id").hide();
    $(".banner_facebook_id").show();
  }
  else{
    $(".banner_facebook_id").hide();
    $(".banner_ad_id").show(); 
  }

  $("select[name='banner_ad_type']").change(function(e){
    if($(this).val()==='facebook'){
      $(".banner_ad_id").hide();
      $(".banner_facebook_id").show();
    }
    else{
      $(".banner_facebook_id").hide();
      $(".banner_ad_id").show(); 
    }
  });


  if($("select[name='interstital_ad_type']").val()==='facebook'){
    $(".interstital_ad_id").hide();
    $(".interstital_facebook_id").show();
  }
  else{
    $(".interstital_facebook_id").hide();
    $(".interstital_ad_id").show(); 
  }

  $("select[name='interstital_ad_type']").change(function(e){

    if($(this).val()==='facebook'){
      $(".interstital_ad_id").hide();
      $(".interstital_facebook_id").show();
    }
    else{
      $(".interstital_facebook_id").hide();
      $(".interstital_ad_id").show(); 
    }
  });


  if($("select[name='native_ad_type']").val()==='facebook'){
    $(".native_ad_id").hide();
    $(".native_facebook_id").show();
  }
  else{
    $(".native_facebook_id").hide();
    $(".native_ad_id").show(); 
  }

  $("select[name='native_ad_type']").change(function(e){

    if($(this).val()==='facebook'){
      $(".native_ad_id").hide();
      $(".native_facebook_id").show();
    }
    else{
      $(".native_facebook_id").hide();
      $(".native_ad_id").show(); 
    }
  });


  $("input[name='native_cat_position'], input[name='native_position']").blur(function(e){

    if($(this).val()=='' || parseInt($(this).val()) <= 0){
      $(this).val('1');
    }
  });

//ios
 if($("select[name='banner_ad_type_ios']").val()==='facebook'){
    $(".banner_ad_id_ios").hide();
    $(".banner_facebook_id_ios").show();
  }
  else{
    $(".banner_facebook_id_ios").hide();
    $(".banner_ad_id_ios").show(); 
  }

  $("select[name='banner_ad_type_ios']").change(function(e){
    if($(this).val()==='facebook'){
      $(".banner_ad_id_ios").hide();
      $(".banner_facebook_id_ios").show();
    }
    else{
      $(".banner_facebook_id_ios").hide();
      $(".banner_ad_id_ios").show(); 
    }
  });


  if($("select[name='interstital_ad_type_ios']").val()==='facebook'){
    $(".interstital_ad_id_ios").hide();
    $(".interstital_facebook_id_ios").show();
  }
  else{
    $(".interstital_facebook_id_ios").hide();
    $(".interstital_ad_id_ios").show(); 
  }

  $("select[name='interstital_ad_type_ios']").change(function(e){

    if($(this).val()==='facebook'){
      $(".interstital_ad_id_ios").hide();
      $(".interstital_facebook_id_ios").show();
    }
    else{
      $(".interstital_facebook_id_ios").hide();
      $(".interstital_ad_id_ios").show(); 
    }
  });


  if($("select[name='native_ad_type_ios']").val()==='facebook'){
    $(".native_ad_id_ios").hide();
    $(".native_facebook_id_ios").show();
  }
  else{
    $(".native_facebook_id_ios").hide();
    $(".native_ad_id_ios").show(); 
  }

  $("select[name='native_ad_type_ios']").change(function(e){

    if($(this).val()==='facebook'){
      $(".native_ad_id_ios").hide();
      $(".native_facebook_id_ios").show();
    }
    else{
      $(".native_facebook_id_ios").hide();
      $(".native_ad_id_ios").show(); 
    }
  });


  $("input[name='native_cat_position_ios'], input[name='native_position_ios']").blur(function(e){

    if($(this).val()=='' || parseInt($(this).val()) <= 0){
      $(this).val('1');
    }
  });

</script>