<?php
		error_reporting(0);
		$con = mysqli_connect("localhost","root","root","web15");
        if (!$con)
        {
            die('Could not connect: ' . mysqli_error());
        }
		$username=$_POST['username'];
		$password=$_POST['password'];
		$email=$_POST['email'];
		$nickname=$_POST['nickname'];
		if(preg_match("/group|union|select|from|or|and|regexp|substr|like|create|drop|\`|\!|\@|\#|\%|\^|\&|\*|\(|\)|\（|\）|\_|\+|\=|\]|\;|\'|\’|\“|\"|\<|\>|\?/i",$username)){
				die("error");
		}
		if(preg_match("/group|union|select|from|or|and|regexp|substr|like|create|drop|\`|\!|\@|\#|\%|\^|\&|\*|\(|\)|\（|\）|\_|\+|\=|\]|\;|\'|\’|\“|\"|\<|\>|\?/i",$password)){
				die("error");
		}
		if(preg_match("/group|union|select|from|or|and|regexp|substr|like|create|drop|\`|\!|\#|\%|\^|\&|\*|\(|\)|\（|\）|\-|\_|\+|\=|\{|\}\]|\'|\’|\“|\"|\<|\>|\?/i",$email)){
				die("error");
		}
		if(preg_match("/group|union|select|from|or|and|regexp|substr|like|create|drop|\`|\~|\!|\@|\#|\%|\^|\&|\*|\(|\)|\（|\）|\-|\_|\+|\=|\{|\}|\]|\;|\'|\’|\“|\"|\<|\>|\?/i",$nickname)){
				die("error");
		}
		if(isset($username) && isset($password) && isset($email) && isset($nickname)){
			$sql = "INSERT INTO user (uname, pwd, email,nname) VALUES ('$username', '$password', '$email','$nickname')";
            $res=mysqli_query($con, $sql);
            if ($res) {
				$_SESSION["login"] = true;
				header("location:/index.php");
			} 
		}
		mysqli_close($conn);
		

?>
