<?php 
function loginPage() { 
  ?><html>
<head>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">
  <style>
    body {
      display: flex;
      min-height: 100vh;
      flex-direction: column;
      background: #e2e1e0;
    }
    main {
      flex: 1 0 auto;
    }
    .input-field input[type=date]:focus+label,
    .input-field input[type=text]:focus+label,
    .input-field input[type=email]:focus+label,
    .input-field input[type=password]:focus+label {
      color: #e91e63;
    }

    .input-field input[type=date]:focus,
    .input-field input[type=text]:focus,
    .input-field input[type=email]:focus,
    .input-field input[type=password]:focus {
      border-bottom: 2px solid #e91e63;
      box-shadow: none;
    }
  </style>
  <title>Proflow</title>
</head>

<body>
  <main style='display: flex; justify-content: center; align-items: center'>
    <center>
      <div class="container">
        <div class="z-depth-1 grey lighten-4 row" style="display: inline-block; padding: 32px 48px 0px 48px; border: 1px solid #EEE;">
          <form class="col s12" action='/pf/helper.php?func=login' method="post">
            <div class='row'>
              <div class='input-field col s12'>
                <input class='validate' type='text' name='username' id='username' />
                <label for='username'>Username</label>
              </div>
            </div>
            <div class='row'>
              <div class='input-field col s12'>
                <input class='validate' type='password' name='password' id='password' />
                <label for='password'>Password</label>
              </div>
              <label style='float: right;'>
								<a class='pink-text' style='opacity: 0'><b>Use your UNIX account login and password</b></a>
							</label>
            </div>
            <br/>
            <center style='margin-top: -25px'>
              <div class='row'>
                <button type='submit' name='btn_login' class='col s12 btn btn-large waves-effect'>Login</button>
              </div>
            </center>
          </form>
        </div>
      </div>
			<a href="/proflow/register">Create account</a>
    </center>
  </main>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.1/jquery.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>
	<script>
		<?php if (isset($_SESSION['error'])) { 
		?>M.toast({html: '<?php
			$responses = ['Username does not exist', 'Password is incorrect', 'Guest mode is not enabled'];
			echo($responses[$_SESSION['error']]);
			unset($_SESSION['error']);
		?>'});<?php
		}?>
	</script>
</body>
</html><?php
}
function registerPage() { 
	if (isset($_SESSION['usernametaken'])) {
		$taken = true;
		unset($_SESSION['usernametaken']);
	}
  ?><html>
<head>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">
	<style>
    body {
      display: flex;
      min-height: 100vh;
      flex-direction: column;
      background: #e2e1e0;
    }
    main {
      flex: 1 0 auto;
    }
    .input-field input[type=date]:focus+label,
    .input-field input[type=text]:focus+label,
    .input-field input[type=email]:focus+label,
    .input-field input[type=password]:focus+label {
      color: #e91e63;
    }

    .input-field input[type=date]:focus,
    .input-field input[type=text]:focus,
    .input-field input[type=email]:focus,
    .input-field input[type=password]:focus {
      border-bottom: 2px solid #e91e63;
      box-shadow: none;
    }
  </style>
  <title>Proflow</title>
</head>

<body>
  <main style='display: flex; justify-content: center; align-items: center'>
    <center>
      <div class="container">
        <div class="z-depth-1 grey lighten-4 row" style="display: inline-block; padding: 32px 48px 0px 48px; border: 1px solid #EEE;">
          <form class="col s12" action='/pf/helper.php?func=register' method="post">
            <div class='row'>
              <div class='input-field col s12'>
                <input class='validate' type='text' name='username' id='username' />
                <label for='username'>Username</label>
              </div>
            </div>
            <div class='row'>
              <div class='input-field col s12'>
                <input class='validate' type='password' name='password' id='password' />
                <label for='password'>Password</label>
              </div>
						</div>
						<div class='row'>
							<div class='input-field col s12'>
                <input class='validate' type='password' name='cpassword' id='cpassword' />
                <label for='cpassword'>Confirm Password</label>
              </div>
              <label style='float: right;'>
								<a class='pink-text' style='opacity: 0'><b>Use your UNIX account login and password</b></a>
							</label>
            </div>
            <br/>
            <center style='margin-top: -25px'>
              <div class='row'>
                <button type='submit' name='btn_login' class='col s12 btn btn-large waves-effect'>Register</button>
              </div>
            </center>
          </form>
        </div>
      </div>
			<a href="/proflow/login">Login to an existing account</a>
    </center>
  </main>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.1/jquery.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>
	<script>
		<?php if (isset($taken)) {
		?>
		M.toast({html: 'Username Taken'})
		<?php
		}
		?>
		$('#cpassword').change(function() {
			if ($(this).val() != $('#password').val()) {
				$(this)[0].setCustomValidity("Passwords Don't Match");
			} else {
				$(this)[0].setCustomValidity('');
			}
		});
	</script>
	</body>

</html><?php
}?>