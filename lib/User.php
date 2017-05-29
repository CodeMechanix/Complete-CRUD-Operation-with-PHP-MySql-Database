<?php
	include_once 'Session.php';
	include 'Database.php';
	class User
	{
		private $db;
		public function __construct()
		{
			$this->db = new Database(); 
		}
		public function UserReg($data)
		{	
			$name=$data['name'];
			$username=$data['username'];
			$email= $data['email'];
			$password=($data['password']);
			$chk_email = $this->emailCheck($email);
			if($name=="" || $username=="" || $email=="" || $password=="")
			{
				$msg="<div class='alert alert-danger' ><strong>Error! </strong> Field must not be empty</div>";
				return $msg;
			}
			if(strlen($username)<3)
			{
					$msg="<div class='alert alert-danger' ><strong>Error! </strong> User name is too short</div>";
				return $msg;
			}
			else if (preg_match('/[^a-z0-9_-]+/i', $username))
			{
				$msg="<div class='alert alert-danger' ><strong>Error! </strong> username must only contain alphanumerical, deshes and underscores!</div>";
				return $msg;
			}
			if(filter_var($email,FILTER_VALIDATE_EMAIL)== false)
			{
				$msg="<div class='alert alert-danger' ><strong>Error! </strong> Your email is not valid</div>";
				return $msg;
			}
			if($chk_email==true)
			{
				$msg="<div class='alert alert-danger' ><strong>Error! </strong> Your email is already existed</div>";
				return $msg;
			}
			$password=md5($data['password']);
			$insert_sql="INSERT INTO tbl_user (name, username,email, password)
			VALUES (:name,:username,:email,:password)";
			$query= $this->db->pdo->prepare($insert_sql);
			$query->bindValue(':name',$name);
			$query->bindValue(':username',$username);
			$query->bindValue(':email',$email);
			$query->bindValue(':password',$password);
			$result=$query->execute();
			if($result)
			{
				$msg="<div class='alert alert-success' ><strong>Success! </strong> you have been registered</div>";
				return $msg;
			}
			else
			{
				$msg="<div class='alert alert-danger' ><strong>Error! </strong> Insertion Problem</div>";
				return $msg;
			}
	    }
	    public function emailCheck($email)
		{
			$sql= "SELECT email from tbl_user where email=:email";
			$query= $this->db->pdo->prepare($sql);
			$query->bindValue(':email',$email);
			$query->execute();
			if($query->rowCount()>0)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		public  function getLoginUser($email,$password)
		{
			$sql= "SELECT * from tbl_user where email=:email AND password=:password LIMIT 1";
			$query= $this->db->pdo->prepare($sql);
			$query->bindValue(':email',$email);
			$query->bindValue(':password',$password);
			$query->execute();
			$result=$query->fetch(PDO::FETCH_OBJ);
			return $result;
		}
		public function UserLogin($data)
		{	
			$email= $data['email'];
			$password=md5($data['password']);
			$chk_email = $this->emailCheck($email);
			if($email=="" || $password=="")
			{
				$msg="<div class='alert alert-danger' ><strong>Error!</strong> Field must not be empty</div>";
				return $msg;
			}
		    if(filter_var($email,FILTER_VALIDATE_EMAIL)== false)
			{
				echo "Moja Los";
				$msg="<div class='alert alert-danger' ><strong>Error! </strong> Your email is not valid</div>";
				return $msg;
			}
			if($chk_email==false)
			{

				$msg="<div class='alert alert-danger'><strong>Error!</strong>Your email does not exist</div>";
				return $msg;
			}
			$result = $this->getLoginUser($email,$password);
			if($result)
			{
				Session::init();
				Session::set("login", true);
				Session::set("id",$result->id);
				Session::set("name",$result->name);
				Session::set("username",$result->username);
				Session::set("loginmsg","<div class='alert alert-success' ><strong>Success! </strong> You are Logged in</div>");
				header("Location:index.php");
			}
			else
			{
				$msg="<div class='alert alert-danger' ><strong>Error! </strong> Insertion Problem</div>";
				return $msg;
			}
		}
		public function getUserData()
		{
			$sql= "SELECT * FROM tbl_user ORDER BY id ASC";
			$query= $this->db->pdo->prepare($sql);
			$query->execute();
			$result = $query->fetchAll();
			return $result;
		}
		public function getUserById($id)
		{
			$sql= "SELECT * from tbl_user where id = :id LIMIT 1";
			$query= $this->db->pdo->prepare($sql);
			$query->bindValue(':id',$id);
			$query->execute();
			$result=$query->fetch(PDO::FETCH_OBJ);
			return $result;
		}
		public function updateUserData($id,$data)
		{
			$name=$data['name'];
			$username=$data['username'];
			$email= $data['email'];
			//$password=md5($data['password']);
			//$chk_email = $this->emailCheck($email);
			if($name=="" || $username=="" || $email=="")
			{
				$msg="<div class='alert alert-danger' ><strong>Error! </strong> Field must not be empty</div>";
				return $msg;
			}
			if(strlen($username)<3)
			{
					$msg="<div class='alert alert-danger' ><strong>Error! </strong> User name is too short</div>";
				return $msg;
			}
			else if (preg_match('/[^a-z0-9_-]+/i', $username))
			{
				$msg="<div class='alert alert-danger' ><strong>Error! </strong> username must only contain alphanumerical, deshes and underscores!</div>";
				return $msg;
			}
			if(filter_var($email,FILTER_VALIDATE_EMAIL)== false)
			{
				$msg="<div class='alert alert-danger' ><strong>Error! </strong> Your email is not valid</div>";
				return $msg;
			}
			$insert_sql="UPDATE tbl_user set
				name = :name,
				username = :username,
				email = :email
				WHERE id = :id";
			$query= $this->db->pdo->prepare($insert_sql);
			$query->bindValue(':name',$name);
			$query->bindValue(':username',$username);
			$query->bindValue(':email',$email);
			$query->bindValue(':id',$id);
			$result=$query->execute();
			if($result)
			{
				$msg="<div class='alert alert-success' ><strong>Success! </strong> User data updated Successfully</div>";
				return $msg;
			}
			else
			{
				$msg="<div class='alert alert-danger' ><strong>Error! </strong> User data not updated  Successfully</div>";
				return $msg;
			}
		}
		private function checkPassword($old_pass, $id)
		{
			$oldPass=md5($old_pass);
			$sql= "SELECT password from tbl_user where password=:password AND id=:id";
			$query= $this->db->pdo->prepare($sql);
			$query->bindValue(':password',$old_pass);
			$query->bindValue(':id',$id);
			$query->execute();
			if($query->rowCount()>0)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		public function updatePassword($id,$data)
		{
			$old_pass = $data['old_pass'];
			$new_pass = $data['password'];
			$chk_pass= $this->checkPassword($id,$old_pass);
			if ($old_pass == "" OR $new_pass == "")
			{
				$msg="<div class='alert alert-danger' ><strong>Error! </strong> Field Must Not be Empty</div>";
				return $msg;
			}
			if($chk_pass==false)
			{
				$msg="<div class='alert alert-danger' ><strong>Error! </strong> Old Password Does not Exist</div>";
				return $msg;
			}
			if (strlen($new_pass)<6) 
			{
				$msg="<div class='alert alert-danger' ><strong>Error! </strong> Password length is too small</div>";
				return $msg;
			}
			$password= md5($new_pass);
			$update_sql="UPDATE tbl_user SET password   =:password									   
				 WHERE id     =:id";
			$query= $this->db->pdo->prepare($update_sql);
			$query->bindValue(':password',$password);
			$query->bindValue(':id',$id);
		 	echo $query->rowCount();
			$result=$query->execute();
			if($result)
			{
				$msg="<div class='alert alert-success' ><strong>Success! </strong> Password have been updated</div>";
				return $msg;
			}
			else
			{
				$msg="<div class='alert alert-danger' ><strong>Error! </strong> Update  Problem</div>";
				return $msg;
			}
		}	
	}
?> 