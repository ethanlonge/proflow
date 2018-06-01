<?php
require_once('config.php');
if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off"){
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect);
    exit();
}
if (isset($_SESSION['dev'])) {
?>
<div class='dev' style='right: 4vw; position: absolute; bottom: 1.7vh;'>
  Development Version (v<?php echo version; ?>)
</div>
<script>var dev=true;</script>
<?php
} else {
  ?><script>var dev=false;</script><?php
}
if (!isset($_SESSION['logged_in'])) {
	if ($_GET['page'] == 'register') {
		addPage('register');
	} else if ($_GET['page'] == 'register/taken') {
		$_SESSION['usernametaken'] = 'true';
		addPage('register');
	} else if ($_GET['page'] == 'login/noexist') {
		$_SESSION['error'] = 0;
		addPage('login');
	} else if ($_GET['page'] == 'login/nopass') {
		$_SESSION['error'] = 1;
		addPage('login');
	} else if ($_GET['page'] == 'login/noguest') {
		$_SESSION['error'] = 2;
		addPage('login');
	} else {
		unset($_SESSION['error']);
		addPage('login');
	}
} else {
	?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.1/css/materialize.min.css">
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.1/js/materialize.min.js"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Proflow</title>
<style>
	html {
		background: #e2e1e0;
	}
  .selected {
    background: rgba(225, 225, 225, 1);
  }
	.menu {
    position: absolute;
    top: calc(6vh - 2px);
    height: calc(100% - 11vh);
    left: 4vw;
    width: 120px;
		border-radius: 5px;
		z-index: 100;
	}
	.menu .menu-items {
    height: 100%;
		background: white;
    margin-top: 0;
	}
	.content {
		padding: 20px 35px 20px 35px;
	}
	li.bottom {
    bottom: 0px;
    position: absolute;
    width: 100%;
	}
	.menu-items li a {
		color:rgba(0,0,0,0.87);
	}
	.menu-items li p {
    display: inline-block;
    margin-bottom: 0;
    line-height: 30px;
    position: relative;
    bottom: 5px;
		margin-left: 5px;
	}
	i.material-icons {
    padding-top: 10px;
	}
	.menu-items li {
    padding-left: 10px;
		cursor: pointer;
		user-select: none;
	}
	.page {
    height: calc(100% - 11vh);
    top: calc(6vh - 2px);
    position: absolute;
    width: calc(100% - 12vw - 120px);
    background-color: white;
    left: calc(8vw + 120px);
	}
	*:focus {
		outline: none;
	}
</style>
<?php
	if ($_GET['page'] == 'logout') {
		addPage("logout");
		die();
	}
	if ($_GET['page'] == "") {
		header("Location: /proflow/home");
	}
if (!isset($_SESSION['project'])) {
	addMenu("projects", 'newsesh');  
	addPage("projects");
} else {
  switch($_GET['page']) {
    case "home":
      addMenu("home", $_SESSION['project']);
      addPage("home");
      break;
    case "tasks":
      addMenu("tasks", $_SESSION['project']);
      addPage("tasks");
     	break;
    case "projects":
      addMenu("projects", $_SESSION['project']);
      addPage("projects");
			break;
		case "login":
			addPage("login");
			break;
    case "git":
      echo("Git is not implemented yet");
      break;
  }
}}?>