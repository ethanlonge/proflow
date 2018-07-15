<?php
function tasksPage() {
  $tf = ['', 'checked'];
  ?>
  <div class='page z-depth-2'>
  	<style>
    	.content {
      	height: 85vh;
        overflow: auto;
      }
      .material-icons.close {
      	padding-top: 0;
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
			.taskVis,
      .taskDelete,
      .taskEdit {
      	/*position: absolute !important;*/
        right: 0px;
      }
			.taskVis,
      .taskEdit,
      .taskDelete,
      .stepEdit,
      .stepDelete,
      .stepVis {
        display: inline;
        float: right;
        cursor: pointer;
        transition: opacity 0.3s;
			}
      .taskDelete {
      	z-index: 500;
        position: absolute;
        margin-top: 0px;
      }
			.taskVis .material-icons,
      .taskEdit .material-icons,
      .taskDelete .material-icons,
      .stepEdit .material-icons,
			.stepDelete .material-icons,
      .stepVis .material-icons {
    		padding-top: 0px;
        opacity: 0.1;
        transition: opacity 0.3s;
        color: rgba(0,0,0,0.87);
			}
			.taskVis .material-icons:hover,
      .taskEdit .material-icons:hover,
			.taskDelete .material-icons:hover,
      .taskDelete .material-icons:hover,
      .stepEdit .material-icons:hover,
			.stepDelete .material-icons:hover,
      .stepVis .material-icons:hover {
  		  opacity: 0.8;
			}
      .taskEdit {
      	margin-right: 35px;
      }
			.taskVis {
      	margin-right: 70px;
      }
      .stepEdit {
        margin-right: 10px;
        margin-left: -10px;
      }
      .stepVis {
      	margin-right: 20px;
        margin-left: -10px;
      }
			input[type='checkbox']:not(:checked):disabled+span:not(.lever):before {
				border: 2px solid #5a5a5a;
				background-color: initial;
			}
			input[type='checkbox']:checked+span:not(.lever):before {
				border-right: 2px solid #26a69a;
				border-bottom: 2px solid #26a69a;
			}
      a.dropdown-trigger {
      	transition: opacity 0.3s;
      }
    </style>
  	<div class='content'>
    <?php
  $arr = getTasks($_SESSION['projid']);
  if (count($arr) > 0) {
   	echo "<ul class='collapsible'>";
    	for ($i=0; $i<count($arr); $i++) {
				//if (in_array('edit', $_SESSION['priv'])) {
        if (in_array($_SESSION['badmin'], $_SESSION['priv'])) {
          $match = true;
        } else {
          $match = false;
          for ($p=0; $p<count($_SESSION['priv']); $p++) {
            if (strpos($arr[$i][2], $_SESSION['editprivs'][$p]) !== false) {
              $match = true;
            }
          }
        }
        if ($match) {
        echo("<li tid='". $arr[$i][0] ."'>
      <div class='collapsible-header'>" . $arr[$i][1] . " (" . getCompleteTask($_SESSION['projid'], $arr[$i][0], 3, 1) . "%)
      <div class='taskDelete'><a class='dropdown-trigger' onclick=\"event.stopPropagation()\" data-target='context-".$arr[$i][0]."'><i class='material-icons'>more_vert</i></a></div>
      <div class='taskEdit'></div>
      <div class='taskVis'></div></div>
      <ul id='context-".$arr[$i][0]."' class='dropdown-content'>
        <li><a class=\"modal-trigger\" onclick=\"event.stopPropagation(); taskid = '". $arr[$i][0] ."'; $('#editTaskText').val('". $arr[$i][1] ."'); $('#editTaskModal').modal('open')\"><!--<i class='material-icons'>edit</i>-->Edit</a></li>
        <li><a class=\"modal-trigger\" onclick=\"event.stopPropagation(); taskid = '". $arr[$i][0] ."'; vis = '".$arr[$i][2]."'; chipifyTVis(); $('#visTaskName').text('". $arr[$i][1] ."'); $('#editTaskVisModal').modal('open')\"><!--<i class='material-icons'>visibility</i>-->Visibility</a></li>
        <li class='divider'></li>
        <li><a class=\"modal-trigger\" onclick=\"event.stopPropagation(); taskid = '". $arr[$i][0] ."'; $('#delTaskName').text('". $arr[$i][1] ."'); $('#deleteTaskModal').modal('open')\"><!--<i class='material-icons'>delete</i>-->Delete</a></li>
      </ul>
      <div class='collapsible-body'>
      "); 
				} else {
					echo "<li tid='". $arr[$i][0] ."'>
      <div class='collapsible-header'>" . $arr[$i][1] . " (" . getCompleteTask($_SESSION['projid'], $arr[$i][0], 3, 1) . "%)</div>
      <div class='collapsible-body'>
      "; 
				}
        $sarr = getSteps($_SESSION['projid'], $arr[$i][0]);
        if (count($sarr) > 0) {
          echo "<table><thead><tr><th>Step</th><th>Complete</th></tr></thead><tbody>";
          	for ($j=0; $j<count($sarr); $j++) {
                if (in_array($_SESSION['badmin'], $_SESSION['priv'])) {
              $match = true;
            } else {
              $match = false;
              for ($p=0; $p<count($_SESSION['priv']); $p++) {
                if (strpos($sarr[$j][3], $_SESSION['editprivs'][$p]) !== false) {
                  $match = true;
                }
              }
            }
						if ($match) {
           		printf("<tr sid='%s'><td>%s</td><td>
              <label><input class='stepCheckbox' type='checkbox' sid='%s' %s><span></span></label>
              <div class='stepDelete'><a class='dropdown-trigger' onclick=\"event.stopPropagation()\" data-target='context-".$sarr[$j][0]."'><i class='material-icons'>more_vert</i></a></div>
              <ul id='context-".$sarr[$j][0]."' class='dropdown-content'>
        			<li><a class=\"modal-trigger\" href=\"#editStepModal\" onclick=\"event.stopPropagation(); stepid = '". $sarr[$j][0] ."'; $('#editStepText').val('".$sarr[$j][1]."'); $('#editStepModal').modal('open')\"><!--<i class='material-icons'>edit</i>-->Edit</a></li>
        			<li><a class=\"modal-trigger\" href=\"#editStepVisModal\" onclick=\"event.stopPropagation(); stepid = '". $sarr[$j][0] ."'; vis = '".$sarr[$j][3]."'; chipifySVis(); $('#visStepName').text('". $sarr[$j][1] ."'); $('#editStepVisModal').modal('open')\"><!--<i class='material-icons'>visibility</i>-->Visibility</a></li>
              <li class='divider'></li>
        			<li><a class=\"modal-trigger\" href=\"#deleteStepModal\" onclick=\"event.stopPropagation(); stepid = '". $sarr[$j][0] ."'; $('#delStepName').text('". $sarr[$j][1] ."'); $('#delStepTaskName').text('". $arr[$i][1] ."'); $('#deleteStepModal').modal('open')\"><!--<i class='material-icons'>delete</i>-->Delete</a></li>
     				 	</ul></td></tr>"
              , $sarr[$j][0], $sarr[$j][1], $sarr[$j][0], $tf[$sarr[$j][2]]);
						} else {
							printf("<tr sid='%s'><td>%s</td><td><label><input type='checkbox' sid='%s' %s disabled='disabled'><span></span></label></td></tr>", $sarr[$j][0], $sarr[$j][1], $sarr[$j][0], $tf[$sarr[$j][2]]);
						}
					}
          echo "</tbody></table>";
        } else {
          if (in_array($_SESSION['badmin'], $_SESSION['priv'])) {
            $match = true;
          } else {
            $match = false;
            for ($p=0; $p<count($_SESSION['priv']); $p++) {
              if (strpos($arr[$i][2], $_SESSION['editprivs'][$p]) !== false) {
                $match = true;
              }
            }
          }
					if ($match) {
          	echo "<h4><i>There are no steps. Add some below</i></h4>";
					} else {
						echo "<h4><i>There are no steps</i></h4>";
					}
        }
        if (in_array($_SESSION['badmin'], $_SESSION['priv'])) {
          $match = true;
        } else {
          $match = false;
          for ($p=0; $p<count($_SESSION['priv']); $p++) {
            if (strpos($arr[$i][2], $_SESSION['editprivs'][$p]) !== false) {
              $match = true;
            }
          }
        }
				if ($match) {
        echo '
  <a class="waves-effect waves-light btn modal-trigger stepAdd" href="#addStepModal" onclick="projid = \''. $_SESSION['projid'] .'\'; taskid = \''. $arr[$i][0] .'\'">Add</a>
 		</div>
    	</li>';
				}
      }
    echo "</ul>";
  } else {
		if ($_SESSION['projedit']) {
    	echo "<h4><i>There are no tasks. Add some below</i></h4><style>.content {display: flex; justify-content: center; align-items: center;}</style>";
		} else {
			echo "<h4><i>There are no tasks</i></h4><style>.content {display: flex; justify-content: center; align-items: center;}</style>";
		}
	}
	if ($_SESSION['projedit']) {
  	echo "
		<a class=\"waves-effect waves-light btn modal-trigger taskAdd\" href=\"#addTaskModal\" onclick=\"projid = '". $_SESSION['projid'] ."'; taskid = '". $arr[$i][0] ."'\">Add</a>
			</div>
		</div>";
	}
	?>
  <div id="addStepModal" class="modal">
    <div class="modal-content">
      <h4>Add Step</h4>
      <p><input id='newStepText' type='text'></p>
    </div>
    <div class="modal-footer">
      <a href="#!" onclick='newStep()' class="modal-action modal-close waves-effect waves-green btn-flat">Add</a>
    </div>
  </div>
  <div id="addTaskModal" class="modal">
    <div class="modal-content">
      <h4>Add Task</h4>
      <p><input id='newTaskText' type='text'></p>
    </div>
    <div class="modal-footer">
      <a href="#!" onclick='newTask()' class="modal-action modal-close waves-effect waves-green btn-flat">Add</a>
    </div>
  </div>
  <div id="deleteStepModal" class="modal">
    <div class="modal-content">
      <h4>Delete Step</h4>
      <p>Are you sure you want to delete '<span id='delStepName'></span>' from '<span id='delStepTaskName'></span>'</p>
    </div>
    <div class="modal-footer">
      <a href="#!" onclick='deleteStep()' class="modal-action modal-close waves-effect waves-green btn-flat">Confirm</a>
    </div>
  </div>
  <div id="editStepModal" class="modal">
    <div class="modal-content">
      <h4>Edit Step</h4>
      <p><input id='editStepText' type='text'></p>
    </div>
    <div class="modal-footer">
      <a href="#!" onclick='editStep()' class="modal-action modal-close waves-effect waves-green btn-flat">Save Changes</a>
    </div>
  </div>
  <div id="deleteTaskModal" class="modal">
    <div class="modal-content">
      <h4>Delete Task</h4>
      <p>Are you sure you want to delete '<span id='delTaskName'></span>'</p>
    </div>
    <div class="modal-footer">
      <a href="#!" onclick='deleteTask()' class="modal-action modal-close waves-effect waves-green btn-flat">Confirm</a>
    </div>
  </div>
  <div id="editTaskModal" class="modal">
    <div class="modal-content">
      <h4>Edit Task</h4>
      <p><input id='editTaskText' type='text'></p>
    </div>
    <div class="modal-footer">
      <a href="#!" onclick='editTask()' class="modal-action modal-close waves-effect waves-green btn-flat">Save Changes</a>
    </div>
  </div>
  <div id="editTaskVisModal" class="modal">
    <div class="modal-content">
      <h4>Edit Visibility</h4>
      <p><div id='taskVisChips'></div></p>
    </div>
    <div class="modal-footer">
    <p style='display: inline; float: left; margin-left: 20px; margin-top: 10px'><label><input id='stepAll' type='checkbox' checked='checked'/><span>Apply to steps</span></label></p>
      <a href="#!" onclick='editTaskVis()' class="modal-action modal-close waves-effect waves-green btn-flat">Save Changes</a>
    </div>
  </div>
  <div id="editStepVisModal" class="modal">
    <div class="modal-content">
      <h4>Edit Visibility</h4>
      <p><div id='stepVisChips'></div></p>
    </div>
    <div class="modal-footer">
      <a href="#!" onclick='editStepVis()' class="modal-action modal-close waves-effect waves-green btn-flat">Save Changes</a>
    </div>
  </div>
 	<script>
  	var projid;
    var taskid;
    var stepid;
  	$(document).ready(function() {
    	$('.collapsible').collapsible();
      //$('.dropdown-trigger').dropdown('alignment','right');
      var elems = document.querySelectorAll('.dropdown-trigger');
    	var instances = M.Dropdown.init(elems, {'onOpenStart': hideContext,
                                              'alignment': 'right',
                                              'onCloseStart': showContext});
      $('.stepCheckbox').click(function() {
        var on = $(this).attr('checked')=='checked'? 0:1;
      	location.href = '/pf/helper.php?func=completeStep&SID=' + $(this).attr('sid') + '&val=' + on;	
      });
      $('.modal').modal();
      $('.collapsible-header div').css('position', 'absolute')
    });
    function hideContext() {
    	$('.dropdown-trigger').css('opacity', '0');
      setTimeout(function() {
      	$('.dropdown-trigger').css('pointer-events', 'none');
      }, 300);
    }
    function showContext() {
    	$('.dropdown-trigger').css('pointer-events', '');
    	$('.dropdown-trigger').css('opacity', '');
    }
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
    function editTaskVis() {
    	var chips = M.Chips.getInstance($('#taskVisChips')).chipsData;
      var arrChips = [];
   		for (var i=0; i<chips.length; i++) {
      	arrChips.push(chips[i].tag);
      }
      //console.log('/pf/helper.php?func=editTaskVis&TID=' + taskid + '&vis=' + arrChips.join(','));
      location.href = '/pf/helper.php?func=editTaskVis&TID=' + taskid + '&vis=' + arrChips.join(',') + '&all=' + !$('#stepAll').is(':checked');
    }
    function editStepVis() {
    	var chips = M.Chips.getInstance($('#stepVisChips')).chipsData;
      var arrChips = [];
   		for (var i=0; i<chips.length; i++) {
      	arrChips.push(chips[i].tag);
      }
      //console.log('/pf/helper.php?func=editTaskVis&SID=' + taskid + '&vis=' + arrChips.join(','));
      location.href = '/pf/helper.php?func=editStepVis&SID=' + stepid + '&vis=' + arrChips.join(',');
    }
    function chipifyTVis() {
    	var initchips = [];
      tempchips = vis.split(',');
      tempchips.splice(0,2);
      tempchips.splice(tempchips.length-1, 1);
      if (tempchips[0] != '') {
     		for (var i=0; i<tempchips.length; i++) {
          eval('initchips.push({tag: "' + tempchips[i] + '"})');
        }
        $('#taskVisChips').chips({
          data: initchips,
        });
      } else {
      	$('#taskVisChips').chips();
      }
    }
    function chipifySVis() {
    	var initchips = [];
      tempchips = vis.split(',');
      tempchips.splice(0,2);
      tempchips.splice(tempchips.length-1, 1);
      if (tempchips[0] != '') {
        for (var i=0; i<tempchips.length; i++) {
          eval('initchips.push({tag: "' + tempchips[i] + '"})');
        }
        $('#stepVisChips').chips({
          data: initchips,
        });
      } else {
      	$('#stepVisChips').chips();
      }
    }
  </script>
<?php
}