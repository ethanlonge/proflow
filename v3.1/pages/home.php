<?php
function homePage() {
echo "
<div class='page z-depth-2'>
	<style>
    div#taskData {
    	float: right;
      position: absolute;
      font-size: 25px;
      right: 5vw;
      top: calc(50% - 12.5px);
    }
    div#taskPercent {
      display: inline;
    }
    div#taskFraction {
    	display: inline;
    	/*margin-left: 5vw;*/
      float: right;
      text-align:right;
		}
		tr {
			border-bottom: none;
		}
  </style>
  <div class='content'>
  	<table style='height: calc(100% - 40px)'><tbody><tr height='15%'><td colspan='2'><h4>". $_SESSION['project'] ."</h4></td></tr><tr class='hide-on-small-only' style='height: 70%'><td colspan='2'><div style=''><canvas id='completeTasks' style='' width='650%' height='200%'></canvas></div></td></tr>
    <!--<div id='taskData'>-->
    	<tr height='15%'><td><div id='taskPercent'>" . getCompleteProject($_SESSION['projid'], 3) . "% Completed</div>
      </td><td><div id='taskFraction'>" . getCompleteProject($_SESSION['projid'], 1) . "/" . getCompleteProject($_SESSION['projid'], 2) . " Tasks Completed</div></td>
  	<!--</div>--></tr></tbody></table>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js'></script>
  <script>
  var data = {
  	datasets: [{
    	data: [". getCompleteProject($_SESSION['projid'], 1) .", ". getCompleteProject($_SESSION['projid'], 0) ."],
      backgroundColor: [
      					'#2196f3',
                '#f44336',
            ],
    }],
    labels: ['Complete Steps', 'Incomplete Steps']
  }
  var options = {
  	responsive: true,
    maintainAspectRatio: true
  }
  $('#completeTasks')[0].width = $('tr')[1].clientWidth;
  $('#completeTasks')[0].height = $('tr')[1].clientHeight-8;
  var myPieChart = new Chart($('#completeTasks'),{
    type: 'pie',
    data: data,
    options: options
	});
  </script>
</div>";
}
