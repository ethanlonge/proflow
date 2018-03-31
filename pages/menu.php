<?php
function addMenu($page, $project) {
  if ($project == "newsesh") {
    echo "
    <div class='menu'>
      <ul class='menu-items side-nav fixed z-depth-2'>
      	<li class='selected'><a href='/proflow/projects'><i class='material-icons'>assessment</i> <p>Projects</p></a></li>
      </ul>
    </div>
   	";
  } else {
    $selh = "";
    $selt = "";
    $selp = "";
    if($page == "projects") {$selp = "selected";}
    if($page == "tasks") {$selt = "selected";}
    if($page == "home") {$selh = "selected";}
    echo "
    <div class='menu'>
      <ul class='menu-items side-nav fixed z-depth-2'>
        <li class='". $selh . "'><a href='/proflow/home'><i class='material-icons'>home</i> <p>Home</p></a></li>
        <li class='". $selt . "'><a href='/proflow/tasks'><i class='material-icons'>content_paste</i> <p>Tasks</p></a></li>
        <li class='bottom ". $selp . "'><a href='/proflow/projects'><i class='material-icons'>assessment</i> <p>". $project ."</p></a></li>
      </ul>
    </div>
    ";
  }
}