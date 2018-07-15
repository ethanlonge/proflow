<?php 
function projectPage() {
?>
	<div class='page z-depth-2'>
		<style>
			html {
				overflow: hidden;
			}
			tr {
				cursor: pointer;
			}
			.projectAdd {
				position: absolute;
				bottom: 20px;
				right: 35px;
			}
      .material-icons.close {
      	padding-top: 0;
      }
      .projVis,
			.projEdit,
      .projDelete {
        display: inline;
        float: right;
        cursor: pointer;
        transition: opacity 0.3s;
			}
      .dropdown-trigger {
      	transition: opacity 0.3s;
      }
      .projVis .material-icons,
      .projEdit .material-icons,
			.projDelete .material-icons {
      	opacity: 0.1;
    		padding-top: 0px;
        color: rgba(0,0,0,0.87);
			}
      .projVis .material-icons:hover,
      .projEdit .material-icons:hover,
			.projDelete .material-icons:hover {
  		  opacity: 0.8;
			}
      .projEdit {
        margin-right: 10px;
        margin-left: -10px;
      }
      .projVis {
        margin-right: 20px;
        margin-left: -10px;
      }
			.content {
      	height: 85vh;
        overflow: auto;
      }
		</style>
  	<div class='content'>
    	<table>
        <thead>
          <tr>
              <th>Name</th>
              <th>Completion</th>
          </tr>
        </thead>
        <tbody>
<?php
$arr = getProjects();
for ($i=0; $i<count($arr);$i++) {
  $percent = getCompleteProject($arr[$i][0], 3);
  if ($percent == -1) {
    $percent = "Untrackable";
  } else {
    $percent = $percent."%";
  }
	if (in_array($_SESSION['badmin'], $_SESSION['priv'])) {
  	printf("<tr pname='%s' pid='%u'><td>%s</td><td>%s
    <div class='projDelete'><a class='dropdown-trigger' onclick=\"event.stopPropagation()\" data-target='context-".$arr[$i][0]."'><i class='material-icons'>more_vert</i></a></div>
    <ul id='context-".$arr[$i][0]."' class='dropdown-content'>
        <li><a class=\"modal-trigger\" onclick=\"event.stopPropagation(); projid = ". $arr[$i][0] ."; $('#editProjText').val('". $arr[$i][1] ."'); $('#editProjectModal').modal('open')\"><!--<i class='material-icons'>edit</i>-->Edit</a></li>
        <li><a class=\"modal-trigger\" onclick=\"event.stopPropagation(); projid = '". $arr[$i][0] ."'; vis = '".$arr[$i][2]."'; chipifyVis(); $('#visName').text('". $arr[$i][1] ."'); $('#editProjectVisModal').modal('open')\"><!--<i class='material-icons'>visibility</i>-->Visibility</a></li>
        <li class='divider'></li>
        <li><a class=\"modal-trigger\" onclick=\"event.stopPropagation(); projid = ". $arr[$i][0] ."; $('#delProjName').text('". $arr[$i][1] ."'); $('#deleteProjectModal').modal('open')\"><!--<i class='material-icons'>delete</i>-->Delete</a></li>
    </ul></td></tr>", ucfirst($arr[$i][1]), $arr[$i][0] , ucfirst($arr[$i][1]), $percent);
  } else {
		printf("<tr pname='%s' pid='%u'><td>%s</td><td>%s</td></tr>", ucfirst($arr[$i][1]), $arr[$i][0] , ucfirst($arr[$i][1]), $percent);
	}
}
echo "
        </tbody>
      </table>
			";
	if (in_array($_SESSION['badmin'], $_SESSION['priv'])) {
		echo('<a class="waves-effect waves-light btn modal-trigger projectAdd" href="#addProjectModal">Add</a>');
	}
?>
			<div id="addProjectModal" class="modal">
				<div class="modal-content">
					<h4>Add Project</h4>
					<p><input id='newProjectText' type='text'></p>
				</div>
				<div class="modal-footer">
					<a href="#!" onclick='newProject()' class="modal-action modal-close waves-effect waves-green btn-flat">Add</a>
				</div>
			</div>
    </div>
		<div id="deleteProjectModal" class="modal">
    <div class="modal-content">
      <h4>Delete Project</h4>
      <p>Are you sure you want to delete '<span id='delProjName'></span>'</p>
    </div>
    <div class="modal-footer">
      <a href="#!" onclick='deleteProj()' class="modal-action modal-close waves-effect waves-green btn-flat">Confirm</a>
    </div>
  </div>
  <div id="editProjectModal" class="modal">
    <div class="modal-content">
      <h4>Edit Project</h4>
      <p><input id='editProjText' type='text'></p>
    </div>
    <div class="modal-footer">
      <a href="#!" onclick='editProj()' class="modal-action modal-close waves-effect waves-green btn-flat">Save Changes</a>
    </div>
  </div>
  <div id="editProjectVisModal" class="modal">
    <div class="modal-content">
      <h4>Edit Visibility</h4>
      <p><div id='visChips'></div></p>
    </div>
    <div class="modal-footer">
      <a href="#!" onclick='editVis()' class="modal-action modal-close waves-effect waves-green btn-flat">Save Changes</a>
    </div>
  </div>
		<script>
		var projid;
			$('.content table tbody tr').click(function() {
				location.href = '/pf/helper.php?func=setproj&projname=' + $(this).attr('pname') + '&projid=' + $(this).attr('pid');
			});
			function newProject() {
				location.href = '/pf/helper.php?func=newProject&name=' + $('#newProjectText').val();
			}
			$(document).ready(function() {
				$('.modal').modal();
        var elems = document.querySelectorAll('.dropdown-trigger');
    		var instances = M.Dropdown.init(elems, {'onOpenStart': hideContext,'alignment': 'right','onCloseStart': showContext});
			});
			function deleteProj() {
				location.href = '/pf/helper.php?func=deleteProject&PID=' + projid;
			}
			function editProj() {
				location.href = '/pf/helper.php?func=editProject&PID=' + projid + '&name=' + $('#editProjText').val();
			}
			$('.content table tbody tr div a').click(function(e) {
				e.stopPropagation();
				$($(this).attr('href')).modal('open');
			});
      function editVis() {
    	var chips = M.Chips.getInstance($('#visChips')).chipsData;
      var arrChips = [];
   		for (var i=0; i<chips.length; i++) {
      	arrChips.push(chips[i].tag);
      }
      //console.log('/pf/helper.php?func=editProjVis&PID=' + projid + '&vis=' + arrChips.join(','));
      location.href = '/pf/helper.php?func=editProjVis&PID=' + projid + '&vis=' + arrChips.join(',');
    }
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
    function chipifyVis() {
    	var initchips = [];
      tempchips = vis.split(',');
      tempchips.splice(0,2);
      tempchips.splice(tempchips.length-1, 1);
      for (var i=0; i<tempchips.length; i++) {
      	eval('initchips.push({tag: "' + tempchips[i] + '"})');
      }
   		$('#visChips').chips({
      	data: initchips,
      });
    }
		</script>
  </div>
<?php
}