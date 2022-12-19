<?php 
$page_title="SMTP Settings";

include("includes/header.php");
require("language/language.php");

// Get smtp settings
$qry="SELECT * FROM tbl_smtp_settings WHERE `id`='1'";
$result=mysqli_query($mysqli,$qry);
$row=mysqli_fetch_assoc($result);

// Update smto settings
if(isset($_POST['submit'])){

  $key=($_POST['smtpIndex']=='gmail') ? '0' : '1';

  $password='';
  if($_POST['smtp_password'][$key]!=''){
    $password=$_POST['smtp_password'][$key];
  }else{
    if($key==0){
      $password=$row['smtp_gpassword'];
    }
    else{
      $password=$row['smtp_password']; 
    }
  }

  if($key==0){

    $data = array(    
      'smtp_type'  =>  'gmail',
      'smtp_ghost'  =>  $_POST['smtp_host'][$key],
      'smtp_gemail'  =>  $_POST['smtp_email'][$key],
      'smtp_gpassword'  =>  $password,
      'smtp_gsecure'  =>  $_POST['smtp_secure'][$key],
      'gport_no'  =>  $_POST['port_no'][$key]
    );

  }
  else{

    $data = array(    
      'smtp_type'  =>  'server',
      'smtp_host'  =>  $_POST['smtp_host'][$key],
      'smtp_email'  =>  $_POST['smtp_email'][$key],
      'smtp_password'  =>  $password,
      'smtp_secure'  =>  $_POST['smtp_secure'][$key],
      'port_no'  =>  $_POST['port_no'][$key]
    );
  }

  $sql="SELECT * FROM tbl_smtp_settings WHERE `id`='1'";
  $res=mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));

  if(mysqli_num_rows($res) > 0){
    $update=Update('tbl_smtp_settings', $data, "WHERE `id` = '1'");  
  }
  else{
    $insert=Insert('tbl_smtp_settings',$data);
  }
  
  $_SESSION['msg']="11";
  header( "Location:smtp_settings.php");
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
        <div class="row">
          <div class="col-md-8">
            <form action="" method="post" class="form form-horizontal" enctype="multipart/form-data">
              <div class="section">
                <div class="section-body">
                  <div class="form-group">
                    <label class="col-md-3 control-label">SMTP Type <span style="color: red">*</span>:-</label>
                    <div class="col-md-9">
                      <div class="radio radio-inline" style="margin-top: 10px; padding-left: 5px">
                        <input type="radio" name="smtp_type" id="gmail" value="gmail" <?php if($row['smtp_type']=='gmail'){ echo ' checked="" disabled="disabled"';} ?>>
                        <label for="gmail">Gmail SMTP</label>
                      </div>
                      <div class="radio radio-inline" style="margin-top: 10px; padding-left: 5px">
                        <input type="radio" name="smtp_type" id="server" value="server" <?php if($row['smtp_type']=='server'){ echo ' checked="" disabled="disabled"';} ?>>
                        <label for="server">Server SMTP</label>
                      </div>
                    </div>
                  </div>
                  <br/>

                  <input type="hidden" name="smtpIndex" value="<?=$row['smtp_type']?>">

                  <div class="gmailContent" <?php if($row['smtp_type']=='gmail'){ echo 'style="display:block"';}else{ echo 'style="display:none"';} ?>>
                    <div class="form-group">
                      <label class="col-md-3 control-label">SMTP Host <span style="color: red">*</span>:-</label>
                      <div class="col-md-9">
                        <input type="text" name="smtp_host[]" class="form-control" value="<?=$row['smtp_ghost']?>" placeholder="mail.example.in" <?php if($row['smtp_type']=='gmail'){ echo 'required';}?> >
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-3 control-label">Email <span style="color: red">*</span>:-</label>
                      <div class="col-md-9">
                        <input type="text" name="smtp_email[]" class="form-control" value="<?=$row['smtp_gemail']?>" placeholder="info@example.com" <?php if($row['smtp_type']=='gmail'){ echo 'required';}?> >
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-md-3 control-label">Password <span style="color: red">*</span>:-</label>
                      <div class="col-md-9">
                        <input type="password" name="smtp_password[]" class="form-control" value="" placeholder="********">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-md-3 control-label">SMTPSecure :-</label>
                      <div class="col-md-5">
                        <select name="smtp_secure[]" class="select2" <?php if($row['smtp_type']=='gmail'){ echo 'required';}?> >
                         <option value="tls" <?php if($row['smtp_gsecure']=='tls'){ echo 'selected';} ?>>TLS</option>
                         <option value="ssl" <?php if($row['smtp_gsecure']=='ssl'){ echo 'selected';} ?>>SSL</option>
                       </select>
                     </div>
                     <div class="col-md-4">
                      <input type="text" name="port_no[]" class="form-control" value="<?=$row['gport_no']?>" placeholder="Enter Port No." <?php if($row['smtp_type']=='gmail'){ echo 'required';}?> >
                    </div>
                   </div>
                </div>

                <div class="serverContent" <?php if($row['smtp_type']=='server'){ echo 'style="display:block"';}else{ echo 'style="display:none"';} ?>>
                  <div class="form-group">
                    <label class="col-md-3 control-label">SMTP Host <span style="color: red">*</span>:-</label>
                    <div class="col-md-9">
                      <input type="text" name="smtp_host[]" id="smtp_host" class="form-control" value="<?=$row['smtp_host']?>" placeholder="mail.example.in" <?php if($row['smtp_type']=='server'){ echo 'required';}?> >
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Email <span style="color: red">*</span>:-</label>
                    <div class="col-md-9">
                      <input type="text" name="smtp_email[]" id="smtp_email" class="form-control" value="<?=$row['smtp_email']?>" placeholder="info@example.com" <?php if($row['smtp_type']=='server'){ echo 'required';}?> >
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-md-3 control-label">Password <span style="color: red">*</span>:-</label>
                    <div class="col-md-9">
                      <input type="password" name="smtp_password[]" id="smtp_password" class="form-control" value="" placeholder="********">
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-md-3 control-label">SMTPSecure :-</label>
                    <div class="col-md-5">
                      <select name="smtp_secure[]" class="select2" <?php if($row['smtp_type']=='server'){ echo 'required';}?> >
                       <option value="tls" <?php if($row['smtp_secure']=='tls'){ echo 'selected';} ?>>TLS</option>
                       <option value="ssl" <?php if($row['smtp_secure']=='ssl'){ echo 'selected';} ?>>SSL</option>
                     </select>
                   </div>
                   <div class="col-md-4">
                    <input type="text" name="port_no[]" id="port_no" class="form-control" value="<?=$row['port_no']?>" placeholder="Enter Port No." <?php if($row['smtp_type']=='server'){ echo 'required';}?> >
                  </div>
                 </div>
              </div>
              <input type="hidden" id="server_data" data-stuff='<?php echo htmlentities(json_encode($row)); ?>'>
            </div>   
            <div class="form-group">
              <div class="col-md-9 col-md-offset-3">
                <button type="submit" name="submit" class="btn btn-primary">Save</button>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="col-md-4">
        <div class="check_smtp" style="border: 1px solid rgb(153, 153, 153);padding: 10px 20px;border-radius: 6px;margin-top:15px;">
          <h4>Check Mail Configuration</h4>
          <p style="color:#8a8a8a;">Send test mail to your email to check Email functionality work or not.</p>
          <hr/>
          <form action="" method="post" id="check_smtp_form">
            <div class="form-group">
              <label class="control-label">Email <span style="color: red">*</span>:-</label>
              <div>
                <input type="text" name="email" class="form-control" autocomplete="off" placeholder="info@example.com"  required="">
              </div>
            </div>
            <div class="form-group">
              <div>
                <button type="submit" name="btn_send" class="btn btn-primary">Send</button>
              </div>
            </div>
          </form> 
        </div>

      </div>
    </div> 

    <br/>
    <div class="alert alert-warning alert-dismissible fade in" role="alert">
      <h4 id="oh-snap!-you-got-an-error!">Note:<a class="anchorjs-link" href="#oh-snap!-you-got-an-error!"><span class="anchorjs-icon"></span></a></h4>
      <p style="margin-bottom: 10px"><i class="fa fa-hand-o-right"></i> This email required otherwise <strong>forgot password</strong> OR <strong>email</strong> feature will not be work.</p> 
    </div>
  </div>
</div>
</div>
</div>


<?php include("includes/footer.php");?>  

<script type="text/javascript">
	// Smtp type settings
  $("input[name='smtp_type']").on("click",function(e){

    var checkbox = $(this);

    e.preventDefault();
    e.stopPropagation();

    var _val=$(this).val();
    if(_val=='gmail'){

      confirmDlg = duDialog('Are you sure?', '', {
        init: true,
        dark: false, 
        buttons: duDialog.OK_CANCEL,
        okText: 'Proceed',
        callbacks: {
          okClick: function(e) {

            confirmDlg.hide();

            checkbox.attr("disabled",true);
            checkbox.prop("checked", true);
            $("#server").attr("disabled",false);
            $("#server").prop("checked", false);


            $(".serverContent").hide();
            $(".gmailContent").show();

            $(".serverContent").find("input").attr("required",false);
            $(".gmailContent").find("input").attr("required",true);

            $("input[name='smtpIndex']").val('gmail');

            $("input[name='smtp_password[]']").attr("required",false);

          } 
        }
      });
      confirmDlg.show();
    }
    else{

      confirmDlg = duDialog('Are you sure?', '', {
        init: true,
        dark: false, 
        buttons: duDialog.OK_CANCEL,
        okText: 'Proceed',
        callbacks: {
          okClick: function(e) {

            confirmDlg.hide();

            checkbox.attr("disabled",true);
            checkbox.prop("checked", true);
            $("#gmail").attr("disabled",false);
            $("#gmail").prop("checked", false);

            $(".gmailContent").hide();
            $(".serverContent").show();

            $("input[name='smtpIndex']").val('server');

            $(".serverContent").find("input").attr("required",true);
            $(".gmailContent").find("input").attr("required",false);

            $("input[name='smtp_password[]']").attr("required",false);

          } 
        }
      });
      confirmDlg.show();
    }
  });

   // Check smtp form 
  $("#check_smtp_form").on("submit",function(event){
    event.preventDefault();
    var email=$(this).find("input[name='email']").val();

    confirmDlg = duDialog('Are you sure?', 'Email will receive to '+email, {
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
            data:{'action':'check_smtp','email':email},
            dataType:'json',
            success:function(res){
              if(res.status==1){
                location.reload();
              }
              else{
                confirmDlg.hide();
                infoDlg = duDialog('Opps!', "'"+res.msg+"'", { init: null,dark: false})
              }
            }
          });

        } 
      }
    });
    confirmDlg.show();
  });

</script>