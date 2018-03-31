<?php
function tasksPage() {
  $tf = ['', 'checked'];
  echo "
  <div class='page z-depth-2'>
  	<style>
    	.content {
      	height: 85vh;
        overflow: auto;
      }
      .stepAdd {
      	margin-top: 20px;
      }
      .taskAdd {
      	position: absolute;
      	bottom: 20px;
        right: 35px;
      }
      html {
      	overflow: hidden;
      }
      .collapsible-header {
      	position: relative;
      }
      .taskDelete,
      .taskEdit {
      	position: absolute;
        right: 0px;
      }
      .taskEdit,
      .taskDelete,
      .stepEdit,
      .stepDelete {
        display: inline;
        float: right;
        cursor: pointer;
        opacity: 0.1;
        transition: opacity 0.3s;
			}
      .taskEdit .material-icons,
      .taskDelete .material-icons,
      .stepEdit .material-icons,
			.stepDelete .material-icons {
    		padding-top: 0px;
        color: rgba(0,0,0,0.87);
			}
      .taskEdit:hover,
			.taskDelete:hover,
      .stepEdit:hover,
			.stepDelete:hover {
  		  opacity: 0.8;
			}
      .taskEdit {
      	margin-right: 35px;
      }
      .stepEdit {
        margin-right: 10px;
        margin-left: -10px;
      }
    </style>
  	<div class='content'>";
  $arr = getTasks($_SESSION['projid']);
  if (count($arr) > 0) {
   	echo "<ul class='collapsible'>";
    	for ($i=0; $i<count($arr); $i++) {
        echo "<li tid='". $arr[$i][0] ."'>
      <div class='collapsible-header'>" . $arr[$i][1] . " (" . getCompleteTask($_SESSION['projid'], $arr[$i][0], 3, 1) . "%)<div class='taskDelete'><a class=\"modal-trigger\" href=\"#modal5\" onclick=\"taskid = '". $arr[$i][0] ."'; $('#delTaskName').text('". $arr[$i][1] ."');\"><i class='material-icons'>delete</i></a></div><div class='taskEdit'><a class=\"modal-trigger\" href=\"#modal6\" onclick=\"taskid = '". $arr[$i][0] ."'; $('#delStepName').text('". $arr[$i][1] ."')\"><i class='material-icons'>edit</i></a></div></div>
      <div class='collapsible-body'>
      "; 
        $sarr = getSteps($_SESSION['projid'], $arr[$i][0]);
        if (count($sarr) > 0) {
          echo "<table><thead><tr><th>Step</th><th>Complete</th></tr></thead><tbody>";
          for ($j=0; $j<count($sarr); $j++) {
           	printf("<tr sid='%s'><td>%s</td><td><label><input type='checkbox' sid='%s' %s><span></span></label><div class='stepDelete'><a class=\"modal-trigger\" href=\"#modal3\" onclick=\"stepid = '". $sarr[$j][0] ."'; $('#delStepName').text('". $sarr[$j][1] ."'); $('#delStepTaskName').text('". $arr[$i][1] ."')\"><i class='material-icons'>delete</i></a></div><div class='stepEdit'><a class=\"modal-trigger\" href=\"#modal4\" onclick=\"stepid = '". $sarr[$j][0] ."'\"><i class='material-icons'>edit</i></a></div></td></tr>", $sarr[$j][0], $sarr[$j][1], $sarr[$j][0], $tf[$sarr[$j][2]]);
          }
          echo "</tbody></table>";
        } else {
          echo "<h4>There are no steps. Add some below</h4>";
        }
        echo '
  <a class="waves-effect waves-light btn modal-trigger stepAdd" href="#modal1" onclick="projid = \''. $_SESSION['projid'] .'\'; taskid = \''. $arr[$i][0] .'\'">Add</a>
 		</div>
    	</li>';
      }
    echo "</ul>";
  } else {
    echo "<h4>There are no tasks. Add some below</h4><style>.content {display: flex; justify-content: center; align-items: center;}</style>";
  }
  echo "
  <a class=\"waves-effect waves-light btn modal-trigger taskAdd\" href=\"#modal2\" onclick=\"projid = '". $_SESSION['projid'] ."'; taskid = '". $arr[$i][0] ."'\">Add</a>
  	</div>
  </div>
  <div id=\"modal1\" class=\"modal\">
    <div class=\"modal-content\">
      <h4>Add Step</h4>
      <p><input id='newStepText' type='text'></p>
    </div>
    <div class=\"modal-footer\">
      <a href=\"#!\" onclick='newStep()' class=\"modal-action modal-close waves-effect waves-green btn-flat\">Add</a>
    </div>
  </div>
  <div id=\"modal2\" class=\"modal\">
    <div class=\"modal-content\">
      <h4>Add Task</h4>
      <p><input id='newTaskText' type='text'></p>
    </div>
    <div class=\"modal-footer\">
      <a href=\"#!\" onclick='newTask()' class=\"modal-action modal-close waves-effect waves-green btn-flat\">Add</a>
    </div>
  </div>
  <div id=\"modal3\" class=\"modal\">
    <div class=\"modal-content\">
      <h4>Delete Step</h4>
      <p>Are you sure you want to delete '<span id='delStepName'></span>' from '<span id='delStepTaskName'></span>'</p>
    </div>
    <div class=\"modal-footer\">
      <a href=\"#!\" onclick='deleteStep()' class=\"modal-action modal-close waves-effect waves-green btn-flat\">Confirm</a>
    </div>
  </div>
  <div id=\"modal4\" class=\"modal\">
    <div class=\"modal-content\">
      <h4>Edit Step</h4>
      <p><input id='editStepText' type='text'></p>
    </div>
    <div class=\"modal-footer\">
      <a href=\"#!\" onclick='editStep()' class=\"modal-action modal-close waves-effect waves-green btn-flat\">Save Changes</a>
    </div>
  </div>
  <div id=\"modal5\" class=\"modal\">
    <div class=\"modal-content\">
      <h4>Delete Task</h4>
      <p>Are you sure you want to delete '<span id='delTaskName'></span>'</p>
    </div>
    <div class=\"modal-footer\">
      <a href=\"#!\" onclick='deleteTask()' class=\"modal-action modal-close waves-effect waves-green btn-flat\">Confirm</a>
    </div>
  </div>
  <div id=\"modal6\" class=\"modal\">
    <div class=\"modal-content\">
      <h4>Edit Task</h4>
      <p><input id='editTaskText' type='text'></p>
    </div>
    <div class=\"modal-footer\">
      <a href=\"#!\" onclick='editTask()' class=\"modal-action modal-close waves-effect waves-green btn-flat\">Save Changes</a>
    </div>
  </div>
 	<script>
  	var projid;
    var taskid;
    var stepid;
  	$(document).ready(function() {
    	$('.collapsible').collapsible();
      $('input[type=checkbox]').click(function() {
        var on = $(this).attr('checked')=='checked'? 0:1;
      	location.href = '/pf/helper.php?func=completeStep&SID=' + $(this).attr('sid') + '&val=' + on;	
      });
      $('.modal').modal();
    });
    function newStep() {
    	location.href = '/pf/helper.php?func=newStep&PID=' + projid + '&TID=' + taskid + '&name=' + $('#newStepText').val();
    }
    function newTask() {
    	location.href = '/pf/helper.php?func=newTask&PID=' + projid + '&name=' + $('#newTaskText').val();
    }
    function deleteStep() {
    	location.href = '/pf/helper.php?func=deleteStep&SID=' + stepid;
    }
    function editStep() {
    	location.href = '/pf/helper.php?func=editStep&SID=' + stepid + '&name=' + $('#editStepText').val();
    }
    function deleteTask() {
    	location.href = '/pf/helper.php?func=deleteTask&TID=' + taskid;
    }
    function editTask() {
    	location.href = '/pf/helper.php?func=editTask&TID=' + taskid + '&name=' + $('#editTaskText').val();
    }
  </script>
  ";
}