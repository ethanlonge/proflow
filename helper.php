<?php
require('config.php');
if ($_SESSION['logged_in'] == true) {
switch($_GET['func']) {
  case "setproj":
    $_SESSION['project'] = $_GET['projname'];
    $_SESSION['projid'] = $_GET['projid'];
    header('Location: /proflow/home');
    break;
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
    print("Invalid Function: " . $_GET['func']);
    break;
}
} else {
  if ($_GET['func'] == "login") {
    $username = "8C6976E5B5410415BDE908BD4DEE15DFB167A9C873FC4BB8A81F6F2AB448A918";
    $password = "39A813325BF711B9DB46EEE19BF45D23BB0A5B50E9AED36867D1A31A1A67D8B9";
    echo(strtoupper(hash("sha256", $_POST['username'])));
    if (strtoupper(hash("sha256", $_POST['username'])) == $username && strtoupper(hash("sha256", $_POST['password'])) == $password) {
      $_SESSION['logged_in'] = true;
      header("Location: /proflow/home");
    } else {
      header("Location: /proflow/login");
    }
  }
}