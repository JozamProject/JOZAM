<?php
session_start();
$_SESSION["connect"] = false;
?>
<!DOCTYPE html>
<html lang="en" charset="UTF-8">
	<head>
        
		<link rel="stylesheet" href="assets/connect/connect.css">
        <!--
		<script src="assets/connect/firebase.js"></script>
		<script src="assets/connect/firebase-auth-client.js"></script>-->
	</head>
	<body>
		<div id="page-wrap">
		
			 <h1>Welcome to JOZAM Task Manager</h1>
				
			<div class="tabs">
				
			   <div class="tab">
				   <input type="radio" id="tab-1" name="tab-group-1" checked>
				   <label for="tab-1">Login</label>
				   <div class="content">
					<form name='login' id="login">
						<fieldset>
							<label for="name">Email</label>
							<input type="text" name="email" id="email" />
							<label for="password">Password</label>
							<input type="password" name="password" id="password" value=""/>
                            <input id="btn" type="button" value="OK" onclick="return connect()" style="width: 40px; height: 20px;" />
                            <div id="connectdiv" style="font-size : 10px;"></div>
						</fieldset>
					</form>
				   </div>
			   </div>
				
			   <div class="tab">
				   <input type="radio" id="tab-2" name="tab-group-1">
				   <label for="tab-2">Register</label>
				
				   <div class="content">
					<form name='register'>
						<fieldset>
							<label for="name">Email</label>
							<input type="text" name="email" />
							<label for="password">Password</label>
							<input type="password" name="password" value=""  />
							<button>OK</button>
						</fieldset>
					</form>
				   </div> 
			   </div>
				<!--
				<div class="tab">
					<input type="radio" id="tab-3" name="tab-group-1">
					<label for="tab-3">Disconnect</label>
				   <div class="content">
					  <button>Disconnect</button> 
				   </div> 
			   </div>-->
			</div>
		</div>
        
        <script>
            function connect(){
                    var div = document.getElementById('connectdiv');
                    var c=document.getElementById("email");
                    var b=document.getElementById("password");
                    <?php $password="boyer"; $login="marc"; ?>
                    var pass = <?php echo json_encode($password); ?>;
                    var log = <?php echo json_encode($login); ?>;
                    if((c.value==log)&&(b.value==pass) )
                    {
                       window.location = "jozam.php?state=ok";
                    }
                    else
                    {
                        div.innerHTML = 'Login or Password are incorrect'; 
                    }
            }
        </script>
	</body>
</html>