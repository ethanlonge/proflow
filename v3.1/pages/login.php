<?php 
function loginPage() { 
  ?><html>
<head>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
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
      <div class="container" style="width: 360px;">
        <div class="z-depth-1 grey lighten-4 row" style="padding: 32px 48px 0px 48px; border: 1px solid #EEE;">
          <!--<form class="col s12" action='/pf/helper.php?func=register' method="post">-->
          <form class="col s12" onsubmit="projSet(); return false;">
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
            </div>
            <div class='row'>
              <div class='col s12'>
                <p style="margin-top:-35px;padding-top:25px;padding-bottom:10px;">
                  <input type="checkbox" id="projset" name="projset" checked/>
                  <label for="projset">Create Project Set</label>
                </p>
              </div>
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
  <div id="createProjSet" class="modal">
    <div class="modal-content">
      <h4>
        Create Project Set
      </h4>
      <p>
        Name of project: <input type="text" id="projSetName">
        Entry Code: <?php echo($code = createCode()); ?>
      </p>
    </div>
    <div class="modal-footer">
      <a href="#!" class="modal-close waves-effect waves-green btn-flat">Cancel</a>
      <a id="createProjSubmit" class="modal-close waves-effect waves-green btn-flat">Finish</a>
    </div>
  </div>
  <div id="joinProjSet" class="modal">
    <div class="modal-content">
    	<h4>
        Join Project Set
      </h4>
      <p>
        Entry Code: <input type="number" id="projSetCode">
        <span class="hide">Project Set: <span id="returnProjSetName"></span></span>
      </p>
    </div>
    <div class="modal-footer">
      <a href="#!" class="modal-close waves-effect waves-green btn-flat">Cancel</a>
      <a id="joinProjSubmit" class="modal-close waves-effect waves-green btn-flat">Finish</a>
    </div>
  </div>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.1/jquery.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>
	<script>
		<?php if (isset($taken)) {
		?>
		M.toast({html: 'Username Taken'})
		<?php
		}
		?>
    $(document).ready(function() {
      $(".modal").modal();
    });
    $('#username').change(function() {
      $.post("/pf/helper.php?action=rUsername", {
        "username": $("#username").val()
      }, function(data) {
        if (data != "true") {
          $("#username").removeClass("valid");
          $("#username").addClass("invalid");
        } else {
          $("#username").addClass("valid");
          $("#username").removeClass("invalid");
        }
      });
    });
    $('#password').change(function() {
			if ($(this).val() != $('#cpassword').val()) {
				$("#password").removeClass("valid");
        $("#password").addClass("invalid");
        $("#cpassword").removeClass("valid");
        $("#cpassword").addClass("invalid");
			} else {
        $("#password").removeClass("invalid");
        $("#password").addClass("valid");
        $("#cpassword").removeClass("invalid");
        $("#cpassword").addClass("valid");
      }
    });
		$('#cpassword').change(function() {
			if ($(this).val() != $('#password').val()) {
				$("#password").removeClass("valid");
        $("#password").addClass("invalid");
        $("#cpassword").removeClass("valid");
        $("#cpassword").addClass("invalid");
			} else {
        $("#password").removeClass("invalid");
        $("#password").addClass("valid");
        $("#cpassword").removeClass("invalid");
        $("#cpassword").addClass("valid");
      }
		});
    $("#projSetCode").change(function() {
      $.post("/pf/helper.php?action=rJoinCode", {
        "code": $(this).val()
      }, function(data) {
        if (data != "invalid") {
          $("#projSetCode").removeClass("invalid");
          $("#projSetCode").addClass("valid");
          $("#returnProjSetName").parent().removeClass("hide");
          $("#returnProjSetName").text(data);
        } else {
          $("#projSetCode").addClass("invalid");
          $("#projSetCode").removeClass("valid");
          $("#returnProjSetName").parent().addClass("hide");
        }
      });
    });
    $("#projSetName").change(function() {
      $.post("/pf/helper.php?action=rName", {
        "projSetName": $("#projSetName").val()
      }, function(data) {
        if (data == "true") {
          $("#projSetName").removeClass("valid");
          $("#projSetName").addClass("invalid");
        } else {
          $("#projSetName").removeClass("invalid");
          $("#projSetName").addClass("valid");
        }
      });
    });
    $("#createProjSubmit").click(function() {
      if ($("#projSetName").hasClass("valid")) {
        $.post("/pf/helper.php?action=register&type=create", {
          "username": $("#username").val(),
          "password": $("#password").val(),
          "projSetName": $("#projSetName").val(),
          "nCode": <?php echo($code); ?>
        }, function(data) {
          window.location.href = "/proflow/login";
        });
      } else {
        M.toast({html: 'Project Set Name Taken'});
        return false;
      }
    });
    $("#joinProjSubmit").click(function() {
    	if ($("#projSetCode").hasClass("valid")) {
        $.post("/pf/helper.php?action=register&type=join", {
          "username": $("#username").val(),
          "password": $("#password").val(),
          "code": $("#projSetCode").val()
        }, function(data) {
          window.location.href = "/proflow/login";
        });
      } else {
        M.toast({html: 'Invalid Project Set Code'});
        return false;
      }
    });
    function projSet() {
      if ($("#password").val() != "" && $("#username").val() != "" && $("#cpassword").val() != "") {
        if (!$("#username").hasClass("invalid")) {
          if (!$("#cpassword").hasClass("invalid") && !$("#password").hasClass("invalid")) {
            if ($("#projset")[0].checked) {
              $("#createProjSet").modal("open");
            } else {
              $("#joinProjSet").modal("open");
            }
          } else {
            M.toast({html: 'Passwords do not match'});
          }
        } else {
          M.toast({html: 'Username is taken'});
        }
      } else {
        M.toast({html: 'Please fill out all the fields'});
      }
    }
	</script>
	</body>

</html><?php
}?>