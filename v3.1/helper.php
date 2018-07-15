<?php
require_once('config.php');
if ($_SESSION['logged_in'] == true) {
switch($_GET['func']) {
  case "setproj":
    $_SESSION['project'] = $_GET['projname'];
    $_SESSION['projid'] = $_GET['projid'];
    if (in_array($_SESSION['badmin'], $_SESSION['priv'])) {
      $_SESSION['projedit'] = true;
    } else {
      $_SESSION['projedit'] = false;
      for ($p=0; $p<count($_SESSION['priv']); $p++) {
        if (strpos(getProject($_GET['projid']), $_SESSION['editprivs'][$p]) !== false) {
          $_SESSION['projedit'] = true;
        }
      }
    }
    header('Location: /proflow/home');
    break;
  case "login":
    $username = "8C6976E5B5410415BDE908BD4DEE15DFB167A9C873FC4BB8A81F6F2AB448A918";
    $password = "39A813325BF711B9DB46EEE19BF45D23BB0A5B50E9AED36867D1A31A1A67D8B9";
    echo(strtoupper(hash("sha256", $_POST['username'])));
    if (strtoupper(hash("sha256", $_POST['username'])) == $username && strtoupper(hash("sha256", $_POST['password'])) == $password) {
      $_SESSION['logged_in'] = true;
      header("Location: /proflow/home");
    } else {
      header("Location: /proflow/login");
    }
    break;
  default:
    //if (in_array('edit', $_SESSION['priv'])) {
    if (true) {
      switch($_GET['func']) {
        case "newProject":
          insertProject($_GET['name']);
          header('Location: /proflow/projects');
          break;
        case "newTask":
          insertTask($_GET['PID'], $_GET['name']);
          header('Location: /proflow/tasks');
          break;
        case "newStep":
          insertStep($_GET['PID'], $_GET['TID'], $_GET['name']);
          header('Location: /proflow/tasks');
          break;
        case "completeStep":
          stepSetComplete($_GET['SID'], $_GET['val']);
          header('Location: /proflow/tasks');
          break;
        case "deleteProject":
          deleteProject($_GET['PID']);
          header('Location: /proflow/projects');
          break;
        case "deleteTask":
          deleteTask($_GET['TID']);
          header('Location: /proflow/tasks');
          break;
        case "deleteStep":
          deleteStep($_GET['SID']);
          header('Location: /proflow/tasks');
          break;
        case "editProject":
          editProject($_GET['PID'], $_GET['name']);
          header('Location: /proflow/projects');
          break;
        case "editTask":
          editTask($_GET['TID'], $_GET['name']);
          header('Location: /proflow/tasks');
          break;
        case "editStep":
          editStep($_GET['SID'], $_GET['name']);
          header('Location: /proflow/tasks');
          break;
        case "editProjVis":
          if ($_GET['all'] == true) {
            $all = true;
          } else {
            $all = false;
          }
          editProjVis($_GET['PID'], $_GET['vis'], $all);
          header('Location: /proflow/tasks');
          break;
        case "editTaskVis":
          if ($_GET['all'] == "true") {
            $all = false;
          } else {
            $all = true;
          }
          editTaskVis($_GET['TID'], $_GET['vis'], $all);
          header('Location: /proflow/tasks');
          break;
        case "editStepVis":
          editStepVis($_GET['SID'], $_GET['vis']);
          header('Location: /proflow/tasks');
          break;
        case "editProjAssigned":
          editProjAssigned($_GET['PID'], $_GET['group']);
          header('Location: /proflow/projects');
          break;
        case "editTaskAssigned":
          editTaskAssigned($_GET['TID'], $_GET['group']);
          header('Location: /proflow/tasks');
         	break;
        case "editStepAssigned":
          editStepAssigned($_GET['SID'], $_GET['group']);
          header('Location: /proflow/tasks');
          break;
        case "testProjSetName":
          echo(testProjSetName($_GET['name']));
          break;
        default:
          print("Invalid Function: " . $_GET['func']);
      }
    } else {
    	print("Invalid Function: " . $_GET['func']);
    }
    break;
}
} else {
  if ($_GET['func'] == "oldlogin") {
    $username = "8C6976E5B5410415BDE908BD4DEE15DFB167A9C873FC4BB8A81F6F2AB448A918";
    $password = "39A813325BF711B9DB46EEE19BF45D23BB0A5B50E9AED36867D1A31A1A67D8B9";
    echo(strtoupper(hash("sha256", $_POST['username'])));
    if (strtoupper(hash("sha256", $_POST['username'])) == $username && strtoupper(hash("sha256", $_POST['password'])) == $password) {
      $_SESSION['logged_in'] = true;
      $_SESSION['UID'] = 0;
      $_SESSION['priv'] = ['admin', 'all', 'edit'];
      header("Location: /proflow/home");
    } else {
      header("Location: /proflow/login");
    }
  } else if ($_GET['func'] == 'login') {
    switch(loginuser($_POST['username'], $_POST['password'])) {
      case "noexist":
        header('Location: /proflow/login/noexist');
        break;
      case "nopass":
        header('Location: /proflow/login/nopass');
        break;
      case "noguest":
        header('Location: /proflow/login/noguest');
        break;
      case "correct":
        $_SESSION['logged_in'] = true;
      	header("Location: /proflow/home");
        break;
    }
  } else if ($_GET['func'] == 'register') {
    $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $user = $_POST['username'];
    if (newuser($user, $hash)) {
      header('Location: /proflow/login');
    } else {
      header('Location: /proflow/register/taken');
    }
  } else {
    switch($_GET['action']) {
      case "rUsername":
        echo(checkUsername($_POST['username']));
        break;
      case "rJoinCode":
        echo(checkCode($_POST['code']));
        break;
      case "register":
        $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $user = $_POST['username'];
        $add = 0;
        $add2 = 123456;
        $type = true;
        if ($_GET['type'] == "join") {
          $type = false;
          $add = $_POST['code'];
        } else {
          $add = $_POST['projSetName'];
          $add2 = $_POST['nCode'];
        }
        rnewuser($user, $hash, $type, $add, $add2);
        break;
      case "rName":
        echo(checkName($_POST['projSetName']));
        break;
  	}
  }
}