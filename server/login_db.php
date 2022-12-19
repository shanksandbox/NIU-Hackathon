<?php include("includes/connection.php");
	 
	$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
	$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

	if($username=="")
	{
		 $_SESSION['msg']="1"; 
		 header( "Location:index.php");
		 exit;
		 
	}
	else if($password=="")
	{
		$_SESSION['msg']="2"; 
		header( "Location:index.php");
		exit;		 
	}	 
	else
	{
		$qry="SELECT * FROM tbl_admin WHERE username='".$username."' and password='".$password."'";
		 
		$result=mysqli_query($mysqli,$qry);		
		
		if(mysqli_num_rows($result) > 0)
		{ 
			$row=mysqli_fetch_assoc($result);

			$_SESSION['id']=$row['id'];
		    $_SESSION['admin_name']=$row['username'];
			
			$_SESSION['msg']="20";   
			header( "Location:home.php");
			exit;
				
		}
		else
		{
			$_SESSION['msg']="4"; 
			header( "Location:index.php");
			exit;
			 
		}
	}
	
?> 