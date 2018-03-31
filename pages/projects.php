<?php 
function projectPage() {
echo "
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
			.projEdit,
      .projDelete {
        display: inline;
        float: right;
        cursor: pointer;
        opacity: 0.1;
        transition: opacity 0.3s;
			}
      .projEdit .material-icons,
			.projDelete .material-icons {
    		padding-top: 0px;
        color: rgba(0,0,0,0.87);
			}
      .projEdit:hover,
			.projDelete:hover {
  		  opacity: 0.8;
			}
      .projEdit {
        margin-right: 10px;
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
        	";
$arr = getProjects();
for ($i=0; $i<count($arr);$i++) {
  $percent = getCompleteProject($arr[$i][0], 3);
  if ($percent == -1) {
    $percent = "Untrackable";
  } else {
    $percent = $percent."%";
  }
  printf("<tr pname='%s' pid='%u'><td>%s</td><td>%s<div class='projDelete'><a class=\"modal-trigger\" href=\"#modal2\" onclick=\"projid = ". $arr[$i][0] ."; $('#delProjName').text('". $arr[$i][1] ."');\"><i class='material-icons'>delete</i></a></div><div class='projEdit'><a class=\"modal-trigger\" href=\"#modal3\" onclick=\"projid = ". $arr[$i][0] ."; $('#delProjName').text('". $arr[$i][1] ."')\"><i class='material-icons'>edit</i></a></div></div></td></tr>", ucfirst($arr[$i][1]), $arr[$i][0] , ucfirst($arr[$i][1]), $percent);
}
echo "
        </tbody>
      </table>
			".'<a class="waves-effect waves-light btn modal-trigger projectAdd" href="#modal1">Add</a>'."
			<div id=\"modal1\" class=\"modal\">
				<div class=\"modal-content\">
					<h4>Add Project</h4>
					<p><input id='newProjectText' type='text'></p>
				</div>
				<div class=\"modal-footer\">
					<a href=\"#!\" onclick='newProject()' class=\"modal-action modal-close waves-effect waves-green btn-flat\">Add</a>
				</div>
			</div>
    </div>
		<div id=\"modal2\" class=\"modal\">
    <div class=\"modal-content\">
      <h4>Delete Project</h4>
      <p>Are you sure you want to delete '<span id='delProjName'></span>'</p>
    </div>
    <div class=\"modal-footer\">
      <a href=\"#!\" onclick='deleteProj()' class=\"modal-action modal-close waves-effect waves-green btn-flat\">Confirm</a>
    </div>
  </div>
  <div id=\"modal3\" class=\"modal\">
    <div class=\"modal-content\">
      <h4>Edit Project</h4>
      <p><input id='editProjText' type='text'></p>
    </div>
    <div class=\"modal-footer\">
      <a href=\"#!\" onclick='editProj()' class=\"modal-action modal-close waves-effect waves-green btn-flat\">Save Changes</a>
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
		</script>
  </div>
";
}