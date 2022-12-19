<?php 
    $page_title="Envato Verify Purchase";

    include("includes/header.php");
    
    $qry="SELECT * FROM tbl_settings WHERE `id`='1'";
    $result=mysqli_query($mysqli,$qry);
    $settings_row=mysqli_fetch_assoc($result);
    
    if(isset($_POST['android_verify_btn'])){



            $data = array(
                'envato_buyer_name' => trim($_POST['envato_buyer_name']),
                'envato_purchase_code' => trim($_POST['envato_purchase_code']),
                'envato_purchased_status' => 1,
                'package_name' => trim($_POST['package_name'])
            );

            $settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");

            $config_file_default    = "includes/app.default";
            $config_file_name       = "api.php";    

            $config_file_path       = $config_file_name;

            $config_file = file_get_contents($config_file_default);

            $f = @fopen($config_file_path, "w+");

            if(@fwrite($f, $config_file) > 0){
            }

            $admin_url = getBaseUrl();


            $_SESSION['class']="success";
            $_SESSION['msg']="19";
            header( "Location:verification.php");
            exit;

    }
    else if(isset($_POST['ios_verify_btn'])){


            $data = array(
                'envato_buyer_name' => trim($_POST['envato_buyer_name']),
                'envato_ios_purchase_code' => trim($_POST['envato_ios_purchase_code']),
                'envato_ios_purchased_status' => 1,
                'ios_bundle_identifier' => trim($_POST['ios_bundle_identifier'])
            );

            $settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");

            $config_file_default    = "includes/app.ios.default";
            $config_file_name       = "ios_api.php";    

            $config_file_path       = $config_file_name;

            $config_file = file_get_contents($config_file_default);

            $f = @fopen($config_file_path, "w+");

            if(@fwrite($f, $config_file) > 0){
            }

            $admin_url = getBaseUrl();


            $_SESSION['class']="success";
            $_SESSION['msg']="19";
            header( "Location:verification.php");
            exit;

    }
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
            <li role="presentation" class="active"><a href="#android_verify" aria-controls="android_verify" role="tab" data-toggle="tab"><i class="fa fa-android" aria-hidden="true"></i> Android App Verify</a></li>
            <li role="presentation"><a href="#ios_verify" aria-controls="ios_verify" role="tab" data-toggle="tab"><i class="fa fa-apple" aria-hidden="true"></i> iOS App Verify</a></li>
        </ul>
      
       <div class="tab-content">
       		
          <!-- android app verify -->
          <div role="tabpanel" class="tab-pane active" id="android_verify">   
             <form action="" name="verify_purchase" method="post" class="form form-horizontal" enctype="multipart/form-data" id="api_form">

              <div class="rows">
                <div class="col-md-12">
                    <div class="section">
                      <div class="section-body">
                        <div class="form-group">
                          <label class="col-md-4 control-label">Envato Username :-
                            <p class="control-label-help" style="margin-bottom: 5px">https://codecanyon.net/user/<u style="color: #e91e63">viaviwebtech</u></p>
                            <p class="control-label-help">(<u style="color: #e91e63">viaviwebtech</u> is username)</p>
                          </label>
                          <div class="col-md-6">
                            <input type="text" name="envato_buyer_name" readonly="" value="<?php echo $settings_row['envato_buyer_name'];?>" class="form-control" placeholder="viaviwebtech">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-4 control-label">Envato Purchase Code :-

                            <p class="control-label-help">(<a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code" target="_blank">Where Is My Purchase Code?</a>)</p>
                          </label>
                          <div class="col-md-6">
                            <input type="text" name="envato_purchase_code" value="<?php echo $settings_row['envato_purchase_code'];?>" class="form-control" placeholder="xxxx-xxxx-xxxx-xxxx-xxxx">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-4 control-label">Android Package Name :-
                            <p class="control-label-help">(More info in Android Doc)</p>
                          </label>
                          <div class="col-md-6">
                            <input type="text" name="package_name" id="package_name" value="<?php echo $settings_row['package_name'];?>" class="form-control" placeholder="com.example.myapp">
                          </div>
                        </div>
                        <div class="form-group">
                        <div class="col-md-9 col-md-offset-4">
                          <button type="submit" name="android_verify_btn" class="btn btn-primary">Save</button>
                        </div>
                        </div>
                      </div>
                    </div>
                </div>
              </div>
              <div class="clearfix"></div>
            </form>
          </div>

          <!-- iOs app verify -->
          <div role="tabpanel" class="tab-pane" id="ios_verify">   
             <form action="" name="ios_verify" method="post" class="form form-horizontal" enctype="multipart/form-data" id="api_form">

              <div class="rows">
                <div class="col-md-12">
                    <div class="section">
                      <div class="section-body">
                        <div class="form-group">
                          <label class="col-md-4 control-label">Envato Username :-
                            <p class="control-label-help" style="margin-bottom: 5px">https://codecanyon.net/user/<u style="color: #e91e63">viaviwebtech</u></p>
                            <p class="control-label-help">(<u style="color: #e91e63">viaviwebtech</u> is username)</p>
                          </label>
                          <div class="col-md-6">
                            <input type="text" name="envato_buyer_name" readonly="" value="<?php echo $settings_row['envato_buyer_name'];?>" class="form-control" placeholder="viaviwebtech">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-4 control-label">Envato Purchase Code :-
                            <p class="control-label-help">(<a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code" target="_blank">Where Is My Purchase Code?</a>)</p>
                          </label>
                          <div class="col-md-6">
                            <input type="text" name="envato_ios_purchase_code"  id="envato_ios_purchase_code" value="<?php echo $settings_row['envato_ios_purchase_code'];?>" class="form-control" placeholder="xxxx-xxxx-xxxx-xxxx-xxxx">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-4 control-label">iOS  Bundle Identifier :-
                            <p class="control-label-help">(More info in iOS Doc)</p>
                          </label>
                          <div class="col-md-6">
                            <input type="text" name="ios_bundle_identifier" id="ios_bundle_identifier" value="<?php echo $settings_row['ios_bundle_identifier'];?>" class="form-control" placeholder="com.example.myapp.ios">
                          </div>
                        </div>
                        <div class="form-group">
                        <div class="col-md-9 col-md-offset-4">
                          <button type="submit" name="ios_verify_btn" class="btn btn-primary">Save</button>
                        </div>
                        </div>
                      </div>
                    </div>
                </div>
                <div class="clearfix"></div>
              </div>
            </form>
          </div>
          
        </div>   

      </div>
    </div>
  </div>
</div>
     
        
<?php include("includes/footer.php");?>       

<script type="text/javascript">
// Show current tab start
$('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
	localStorage.setItem('activeTab', $(e.target).attr('href'));
	document.title = $(this).text()+" | <?=APP_NAME?>";
});

var activeTab = localStorage.getItem('activeTab');
if(activeTab){
	$('.nav-tabs a[href="' + activeTab + '"]').tab('show');
}
</script>