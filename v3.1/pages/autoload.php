<?php
require('login.php');
require('home.php');
require('menu.php');
require('projects.php');
require('tasks.php');

function addPage($page) {
  switch($page) {
    case "projects":
      projectPage();
      break;
    case "home":
      homePage();
      break;
    case "tasks":
      tasksPage();
      break;
    case "login":
      loginPage();
      break;
    case "register":
      registerPage();
      break;
    case "logout":
      session_destroy();
      unset($_SESSION);
      header('Location: /proflow/login');
      break;
    default:
      print("PAGE IS INVALID");
  }
}