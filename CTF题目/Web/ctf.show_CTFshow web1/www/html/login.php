<?php
		error_reporting(0);
		session_start();
		$con = mysqli_connect("localhost","root","root","web15");
        if (!$con)
        {
            die('Could not connect: ' . mysqli_error());
        }
		$username=$_POST['username'];
		$password=$_POST['password'];
		if(isset($username) && isset($password)){
			if(preg_match("/group|union|select|from|or|and|regexp|substr|like|create|drop|\,|\`|\!|\@|\#|\%|\^|\&|\*|\(|\)|\（|\）|\_|\+|\=|\]|\;|\'|\’|\“|\"|\<|\>|\?/i",$username)){
				die("error");
			}
			$sql="select pwd from user where uname = '$username' limit 1";
			$res=mysqli_query($con,$sql);
			$row = mysqli_fetch_array($res);
			if($row['pwd']===$password){
				$_SESSION["login"] = true;
				header("location:/user_main.php?order=id");
			}else{
				header("location:/index.php");
			}
		}else{
			header("location:/index.php");
		}

?>
