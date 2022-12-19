<?php $page_title="Dashboard";

include("includes/header.php");

// Categories count 
$qry_cat="SELECT COUNT(*) as num FROM tbl_category";
$total_category= mysqli_fetch_array(mysqli_query($mysqli,$qry_cat));
$total_category = $total_category['num'];

// Place count start
$qry_places="SELECT COUNT(*) as num FROM tbl_places";
$total_places = mysqli_fetch_array(mysqli_query($mysqli,$qry_places));
$total_places = $total_places['num'];

// Users count start
$qry_users="SELECT COUNT(*) as num FROM tbl_users";
$total_users = mysqli_fetch_array(mysqli_query($mysqli,$qry_users));
$total_users = $total_users['num']; 

// Users filter of graph start

$countStr='';

$no_data_status=false;
$count=$monthCount=0;

for ($mon=1; $mon<=12; $mon++) {

  $monthCount++;

  if(isset($_GET['filterByYear'])){
    $year=$_GET['filterByYear'];
  }
  else{
    $year=date('Y');
  }

  $month = date('M', mktime(0,0,0,$mon, 1, $year));

  $sql_user="SELECT `id` FROM tbl_users WHERE `registration_on` <> 0 AND DATE_FORMAT(FROM_UNIXTIME(`registration_on`), '%c') = '$mon' AND DATE_FORMAT(FROM_UNIXTIME(`registration_on`), '%Y') = '$year'";

  $totalcount=mysqli_num_rows(mysqli_query($mysqli, $sql_user));

  $countStr.="['".$month."', ".$totalcount."], ";

  if($totalcount==0){
    $count++;
  }

}

if($monthCount > $count){
  $no_data_status=false;
}
else{
  $no_data_status=true;
}

$countStr=rtrim($countStr, ", ");

// Users filter of graph end

?>       
      
 <?php 
  $sql_smtp="SELECT * FROM tbl_smtp_settings WHERE `id`='1'";
  $res_smtp=mysqli_query($mysqli,$sql_smtp);
  $row_smtp=mysqli_fetch_assoc($res_smtp);

  $smtp_warning=true;

  if(!empty($row_smtp))
  {

    if($row_smtp['smtp_type']=='server'){
      if($row_smtp['smtp_host']!='' AND $row_smtp['smtp_email']!=''){
        $smtp_warning=false;
      }
      else{
        $smtp_warning=true;
      }  
    }
    else if($row_smtp['smtp_type']=='gmail'){
      if($row_smtp['smtp_ghost']!='' AND $row_smtp['smtp_gemail']!=''){
        $smtp_warning=false;
      }
      else{
        $smtp_warning=true;
      }  
    }
  }

  if($smtp_warning)
  {
?>

<?php } ?>

    <div class="row">
      <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12"> <a href="manage_category.php" class="card card-banner card-green-light">
        <div class="card-body"> <i class="icon fa fa-sitemap fa-4x"></i>
          <div class="content">
            <div class="title">Categories</div>
            <div class="value"><span class="sign"></span><?php echo $total_category;?></div>
          </div>
        </div>
        </a> 
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12"> <a href="manage_places.php" class="card card-banner card-yellow-light">
        <div class="card-body"> <i class="icon fa fa-map-marker fa-4x"></i>
          <div class="content">
            <div class="title">Places</div>
            <div class="value"><span class="sign"></span><?php echo $total_places;?></div>
          </div>
        </div>
        </a> 
        </div>
         <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12"> <a href="manage_users.php" class="card card-banner card-orange-light">
        <div class="card-body"> <i class="icon fa fa-users fa-4x"></i>
          <div class="content">
            <div class="title">Users</div>
            <div class="value"><span class="sign"></span><?php echo $total_users;?></div>
          </div>
        </div>
        </a> 
     </div> 
    </div>
	<div class="row">
	  <div class="col-lg-12">
	    <div class="container-fluid" style="background: #FFF;box-shadow: 0px 5px 10px 0px #CCC;border-radius: 2px;">
	      <div class="col-lg-10">
	        <h3>Users Analysis</h3>
	      </div>
	      <div class="col-lg-2" style="padding-top: 20px">
	        <form method="get" id="graphFilter">
	          <select class="form-control" name="filterByYear" style="box-shadow: none;height: auto;border-radius: 0px;font-size: 16px;">
	            <?php 
	              $currentYear=date('Y');
	              $minYear=2018;

	              for ($i=$currentYear; $i >= $minYear ; $i--) { 
	                ?>
	                <option value="<?=$i?>" <?=(isset($_GET['filterByYear']) && $_GET['filterByYear']==$i) ? 'selected' : ''?>><?=$i?></option>
	                <?php
	              }
	            ?>
	          </select>
	        </form>
	      </div>
	      <div class="col-lg-12">
	        <?php 
	          if($no_data_status){
	            ?>
	            <h3 class="text-muted text-center" style="padding-bottom: 2em">No data found !</h3>
	            <?php
	          }
	          else{
	            ?>
	         <div id="registerChart">
	              <p style="text-align: center;"><i class="fa fa-spinner fa-spin" style="font-size:3em;color:#aaa;margin-bottom:50px" aria-hidden="true"></i></p>
	            </div>
	            <?php    
	          }
	        ?>
	      </div>
	    </div>
	  </div>
	</div>
	<br>

<?php include("includes/footer.php");?>       

<?php 
// Users filter of graph
if(!$no_data_status){
  ?>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
    google.charts.load('current', {packages: ['corechart', 'line']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Month');
      data.addColumn('number', 'Total Users');

      data.addRows([<?=$countStr?>]);

      var options = {
        curveType: 'function',
        fontSize: 15,
        hAxis: {
          title: "Months of <?=(isset($_GET['filterByYear'])) ? $_GET['filterByYear'] : date('Y')?>",
          titleTextStyle: {
            color: '#000',
            bold:'true',
            italic: false
          },
        },
        vAxis: {
          title: "Nos. of Users",
          titleTextStyle: {
            color: '#000',
            bold:'true',
            italic: false,
          },
          gridlines: { count: -1},
          format: '#',
          viewWindowMode: "explicit", viewWindow: {min: 0, max: 'auto'},
        },
        height: 400,
        chartArea:{
          left:50,top:20,width:'100%',height:'auto'
        },
        legend: {
          position: 'bottom'
        },
        colors: ['#3366CC', 'green','red'],
        lineWidth:4,
        animation: {
          startup: true,
          duration: 1200,
          easing: 'out',
        },
        pointSize: 5,
        pointShape: "circle",

      };
      var chart = new google.visualization.LineChart(document.getElementById('registerChart'));

      chart.draw(data, options);
    }


    $(document).ready(function () {
      $(window).resize(function(){
        drawChart();
      });
    });
  </script>
  <?php
}
?>
<script type="text/javascript">

    // filter of graph
    $("select[name='filterByYear']").on("change",function(e){
      $("#graphFilter").submit();
    });

  </script>        
