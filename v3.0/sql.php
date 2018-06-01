<?php
function connect($db = DB_NAME, $user = DB_UN, $pass = DB_PASS, $loc = 'localhost') {
  return mysqli_connect($loc, $user, $pass, $db);
}

function getProjects() {
  $conn = connect();
  $qr = $conn->query('SELECT * FROM `projects` WHERE `visibility` REGEXP \','.implode(',|,', $_SESSION['priv']).',\'');
  while ($row = mysqli_fetch_array($qr, MYSQLI_ASSOC)) {
    $pid[] = $row['pid'];
    $name[] = $row['name'];
		$vis[] = $row['visibility'];
  }
	$conn->close();
  $result = [];
  for ($i=0; $i<count($name); $i++) {
    array_push($result, [$pid[$i], $name[$i], $vis[$i]]);
  }	
  return $result;
}
function getProject($projid) {
  $conn = connect();
  $qr = $conn->query('SELECT * FROM `projects` WHERE `visibility` REGEXP \','.implode(',|,', $_SESSION['priv']).',\' AND pid=\'' . $projid . '\'');
  while ($row = mysqli_fetch_array($qr, MYSQLI_ASSOC)) {
    $pid[] = $row['pid'];
    $name[] = $row['name'];
		$vis[] = $row['visibility'];
  }
	$conn->close();
  $result = [];
  for ($i=0; $i<count($name); $i++) {
    array_push($result, [$pid[$i], $name[$i], $vis[$i]]);
  }	
  return $result;
}
function getTasks($project) {
  $conn = connect();
  $qr = $conn->query('SELECT * FROM `tasks` WHERE `apid` = "' . $project . '" AND `visibility` REGEXP \','.implode(',|,', $_SESSION['priv']).',\'');
  $pid = array();
  $name = array();
  while ($row = mysqli_fetch_array($qr, MYSQLI_ASSOC)) {
    $pid[] = $row['tid'];
    $name[] = $row['name'];
		$vis[] = $row['visibility'];
    $assigned[] = $row['assigned'];
  }
  if (count($name) == 0) {
    return null;
  }
	$conn->close();
  $result = [];
  for ($i=0; $i<count($name); $i++) {
    array_push($result, [$pid[$i], $name[$i], $vis[$i], $assigned[$i]]);
  }	
  return $result;
}
function getSteps($project, $task) {
	$conn = connect();
  $qr = $conn->query('SELECT * FROM `steps` WHERE `apid` = "' . $project . '" AND `atid` = "' . $task . '" AND `visibility` REGEXP \','.implode(',|,', $_SESSION['priv']).',\'');
  $pid = array();
  $name = array();
  $completed = array();
  while ($row = mysqli_fetch_array($qr, MYSQLI_ASSOC)) {
    $pid[] = $row['sid'];
    $name[] = $row['name'];
    $completed[] = $row['completed'];
		$vis[] = $row['visibility'];
    $assigned[] = $row['assigned'];
  }
  if (count($name) == 0) {
    return null;
  }
	$conn->close();
  $result = [];
  for ($i=0; $i<count($name); $i++) {
    array_push($result, [$pid[$i], $name[$i], $completed[$i], $vis[$i], $assigned[$i]]);
  }	
  return $result;
}

function getCompleteTask($project, $task, $op, $uhh=0) {//OPTIONS (op): 0 = get incomplete, 1 = get complete, 2 = get full count, 3 = get percentage
	$conn = connect();
  $qr = $conn->query('SELECT * FROM `steps` WHERE `apid` = "' . $project . '" AND `atid` = "' . $task . '"');
	$completed = array();
	while ($row = mysqli_fetch_array($qr, MYSQLI_ASSOC)) {
    $completed[] = $row['completed'];
  }
	$conn->close();
	if (count($completed) == 0) {
		if ($uhh==0) {return -1;}
		else {return 0;}
	} else {
	$occarr = array_count_values($completed);
	if (!array_key_exists(0, $occarr)) {
		$occarr[0] = 0;
	}
	if (!array_key_exists(1, $occarr)) {
		$occarr[1] = 0;
	}
	switch($op) {
		case 0:
			return $occarr[0];
			break;
		case 1:
			return $occarr[1];
			break;
		case 2:
			return count($completed);
			break;
		case 3:
			return round(($occarr[1]/count($completed)) * 100, 2);
			break;
	}
	}
}
function getCompleteProject($project, $op, $uhh=0) {//OPTIONS (op): 0 = get incomplete, 1 = get complete, 2 = get full count, 3 = get percentage
	$arr = getTasks($project);
	if (count($arr) == 0) {
		if ($uhh == 0) {
			return -1;
		} else {
			return 0;
		}
	} else {
	switch($op) {
		case 0:
			$result = 0;
			foreach($arr as $i) {
				$result += getCompleteTask($project, $i[0], 0, true);
			}
			return $result;
			break;
		case 1:
			$result = 0;
			foreach($arr as $i) {
				$result += getCompleteTask($project, $i[0], 1, true);
			}
			return $result;
			break;
		case 2:
			$result = 0;
			foreach($arr as $i) {
				$result += getCompleteTask($project, $i[0], 2, true);
			}
			return $result;
			break;
		case 3:
			$complete = 0;
			foreach($arr as $i) {
				$complete += getCompleteTask($project, $i[0], 1, true);
			}
			$count = 0;
			foreach($arr as $i) {
				$count += getCompleteTask($project, $i[0], 2, true);
			}
			$result = round(($complete/$count) * 100, 2);
			if ($result <= 100) {
				return $result;
			} else {
				return -1;
			}
			break;
	}
	}
}

function insertProject($name) {
	$conn = connect();
	$conn->query(sprintf("INSERT INTO `projects` (`name`) VALUES ('%s')", $name));
  echo($conn->error);
	$conn->close();
}
function insertTask($pid, $name) {
	$conn = connect();
	$qr = $conn->query("SELECT * FROM `projects` WHERE `PID`='$pid'");
	while ($row = mysqli_fetch_array($qr, MYSQLI_ASSOC)) {
    $vis = $row['visibility'];
  }
	$conn->query(sprintf("INSERT INTO `tasks` (`apid`, `name`, `visibility`) VALUES ('%s', '%s', '%s')", $pid, $name, $vis));
	$conn->close();
}
function insertStep($pid, $tid, $name) {
	$conn = connect();
	$qr = $conn->query("SELECT * FROM `tasks` WHERE `TID`='$tid'");
	while ($row = mysqli_fetch_array($qr, MYSQLI_ASSOC)) {
    $vis = $row['visibility'];
  }
	$conn->query(sprintf("INSERT INTO `steps` (`atid`, `apid`, `name`, `visibility`) VALUES ('%s', '%s', '%s', '%s')", $tid, $pid, $name, $vis));
	$conn->close();
}
function stepSetComplete($sid, $val) {
	$conn = connect();
	$conn->query(sprintf("UPDATE `steps` SET `completed` = '%s' WHERE `steps`.`sid` = %s", $val, $sid));
	$conn->close();
}
function editProject($pid, $name) {
	$conn = connect();
	$conn->query(sprintf("UPDATE `projects` SET `name` = '%s' WHERE `projects`.`pid` = '%s'", $name, $pid));
	$conn->close();
}
function editTask($tid, $name) {
	$conn = connect();
	$conn->query(sprintf("UPDATE `tasks` SET `name` = '%s' WHERE `tasks`.`tid` = '%s'", $name, $tid));
	$conn->close();
}
function editStep($sid, $name) {
	$conn = connect();
	$conn->query(sprintf("UPDATE `steps` SET `name` = '%s' WHERE `steps`.`sid` = '%s'", $name, $sid));
	$conn->close();
}
function deleteStep($sid) {
	$conn = connect();
	$conn->query(sprintf("DELETE FROM steps WHERE `steps`.`sid`='%s'", $sid));
	$conn->close();
}
function deleteTask($tid) {
	$conn = connect();
	$conn->query(sprintf("DELETE FROM tasks WHERE `tasks`.`tid`=%s", $tid));
	$conn->query(sprintf("DELETE FROM steps WHERE `steps`.`atid`=%s", $tid));
	$conn->close();
}
function deleteProject($pid) {
	$conn = connect();
	$conn->query(sprintf("DELETE FROM projects WHERE `projects`.`pid`='%s'", $pid));
	$conn->query(sprintf("DELETE FROM tasks WHERE `tasks`.`apid`='%s'", $pid));
	$conn->query(sprintf("DELETE FROM steps WHERE `steps`.`apid`='%s'", $pid));
	$conn->close();
}
function editProjVis($pid, $vis, $all) {
	$conn = connect();
	$conn->query(sprintf("UPDATE `projects` SET `visibility`='%s' WHERE `pid`='$pid'", 'f,admin,' . $vis . ',l'));
	if ($all) {
  	$conn->query(sprintf("UPDATE `tasks` SET `visibility`='%s' WHERE `apid`='$pid'", 'f,admin,' . $vis . ',l'));
		$conn->query(sprintf("UPDATE `steps` SET `visibility`='%s' WHERE `apid`='$pid'", 'f,admin,' . $vis . ',l'));
  }
  $conn->close();
}
function editTaskVis($tid, $vis, $all) {
	$conn = connect();
	$conn->query(sprintf("UPDATE `tasks` SET `visibility`='%s' WHERE `tid`='$tid'", 'f,admin,' . $vis . ',l'));
	if ($all) {
  	$conn->query(sprintf("UPDATE `steps` SET `visibility`='%s' WHERE `atid`='$tid'", 'f,admin,' . $vis . ',l'));
  }
  $conn->close();
}
function editStepVis($sid, $vis) {
	$conn = connect();
	$conn->query(sprintf("UPDATE `steps` SET `visibility`='%s' WHERE `sid`='$sid'", 'f,admin,' . $vis . ',l'));
	$conn->close();
}
function editProjAssigned($proj, $group) {
  $conn = connect();
  $conn->query("UPDATE `projects` SET `assigned`='$group'' WHERE `pid`='$proj'");
  $conn->close();
}
function editTaskAssigned($task, $group) {
  $conn = connect();
  $conn->query("UPDATE `tasks` SET `assigned`='$group'' WHERE `tid`='$task'");
  $conn->close();
}
function editStepAssigned($step, $group) {
  $conn = connect();
  $conn->query("UPDATE `steps` SET `assigned`='$group'' WHERE `sid`='$step'");
  $conn->close();
}
function newuser($user, $hash) {
	$conn = connect();
  if (!preg_match('/edit-.*/', $user)) {
    if (mysqli_num_rows($conn->query("SELECT * FROM `logins` WHERE username='$user'"))==0) {
      $priv = 'all,'.$user.',edit-'.$user;
      $conn->query("INSERT INTO `logins` (`username`, `password`, `priviliges`) VALUES ('$user', '$hash', '$priv')");
      $conn->close();
      return true;
    } else {
      $conn->close();
      return false;
    }
  } else {
    return false;
  }
}
function loginuser($user, $hash) {
	$conn = connect();
	$qr = $conn->query("SELECT * FROM `logins` WHERE username='$user'");
	$conn->close();
	if (mysqli_num_rows($qr)==0) {
		return "noexist";
	} else {
		while ($row = mysqli_fetch_array($qr, MYSQLI_ASSOC)) {
			$uid[]  = $row['UID'];
			$pass[] = $row['password'];
			$priv[] = $row['priviliges'];
		}
		if (!password_verify($hash, $pass[0])) {
			return "nopass";
		} else {
			$privs = explode(',', $priv[0]);
      $editprivs = [];
      foreach($privs as $p) {
        if (preg_match('/edit-.*/', $p) != 0) {
          array_push($editprivs, $p);
        }
      }
      error_log(implode(',', $editprivs));
			if (!in_array('admin', $privs)) {
				//return "noguest"; //Implement guest code here
				$_SESSION['priv'] = $privs;
				$_SESSION['UID'] = $uid[0];
        $_SESSION['editprivs'] = $editprivs;
				return "correct";
			} else {
				$_SESSION['priv'] = $privs;
				$_SESSION['UID'] = $uid[0];
        $_SESSION['editprivs'] = $editprivs;
				return "correct";
			}
		}
	}
}