<?php
function connect($db = DB_NAME, $user = DB_UN, $pass = DB_PASS, $loc = 'localhost') {
  return mysqli_connect($loc, $user, $pass, $db);
}

function getProjects() {
  $conn = connect();
  $qr = $conn->query('SELECT * FROM `projects`');
  while ($row = mysqli_fetch_array($qr, MYSQLI_ASSOC)) {
    $pid[] = $row['pid'];
    $name[] = $row['name'];
  }
	$conn->close();
  $result = [];
  for ($i=0; $i<count($name); $i++) {
    array_push($result, [$pid[$i], $name[$i]]);
  }	
  return $result;
}
function getTasks($project) {
  $conn = connect();
  $qr = $conn->query('SELECT * FROM `tasks` WHERE `apid` = "' . $project . '"');
  $pid = array();
  $name = array();
  while ($row = mysqli_fetch_array($qr, MYSQLI_ASSOC)) {
    $pid[] = $row['tid'];
    $name[] = $row['name'];
  }
  if (count($name) == 0) {
    return null;
  }
	$conn->close();
  $result = [];
  for ($i=0; $i<count($name); $i++) {
    array_push($result, [$pid[$i], $name[$i]]);
  }	
  return $result;
}
function getSteps($project, $task) {
	$conn = connect();
  $qr = $conn->query('SELECT * FROM `steps` WHERE `apid` = "' . $project . '" AND `atid` = "' . $task . '"');
  $pid = array();
  $name = array();
  $completed = array();
  while ($row = mysqli_fetch_array($qr, MYSQLI_ASSOC)) {
    $pid[] = $row['sid'];
    $name[] = $row['name'];
    $completed[] = $row['completed'];
  }
  if (count($name) == 0) {
    return null;
  }
	$conn->close();
  $result = [];
  for ($i=0; $i<count($name); $i++) {
    array_push($result, [$pid[$i], $name[$i], $completed[$i]]);
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
	$conn->close();
}
function insertTask($pid, $name) {
	$conn = connect();
	$conn->query(sprintf("INSERT INTO `tasks` (`apid`, `name`) VALUES ('%s', '%s')", $pid, $name));
	$conn->close();
}
function insertStep($pid, $tid, $name) {
	$conn = connect();
	$conn->query(sprintf("INSERT INTO `steps` (`atid`, `apid`, `name`) VALUES ('%s', '%s', '%s')", $tid, $pid, $name));
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