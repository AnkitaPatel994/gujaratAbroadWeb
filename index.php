<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Gujarat Abroad</title>
      <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <body>
	<div class="login">
		<div class="login-screen">
			<div class="app-title">
				<h1> Admin Login</h1>
			</div>

			<div class="login-form">
            	
                <form class="login-form" action="LoginSubmit.php" method="post">
                    <div class="control-group">
                    	<input type="text" name="txtUsername" class="login-input" placeholder="Username" required='required'>
                    </div>
                    <div class="control-group">
                    	<input type="password" name="txtPassword" class="login-input" placeholder="Password" required='required'>
                    </div>
                    <a ><input type="submit" value="Login" class="btn btn-primary btn-large btn-block"></a><br>
                    
                     <?php
                        if(isset($_GET['err']))
                        echo "Invalid username / password";
                    ?> 
                </form>              
               
				
			</div>
		</div>
	</div>
</body>
  
  
</body>
</html>
