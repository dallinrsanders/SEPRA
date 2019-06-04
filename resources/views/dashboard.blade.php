@extends('layouts.app')

@section('content')
<?php
$Information=0;//x=0
$Low=0;//0<x<4
$Medium=0;//4<=x<7
$High=0;//7<=x<9
$Critical=0;//9<=x
$MethodologyCompleted=0;
$MethodologyTotal=0;
$MethodologyPassed=0;
$VulnerabilityWords=[];
$TotalWords=0;
$LameWords=["a","an","in","a","of","to","can","is","not","and","the","","by","for","this","are","from","with"];
$ServiceWords=[];
$TotalServiceWords=0;
$OSs=[];
$Top50VulnerabilityWords=[];
$Top50ServiceWords=[];
foreach($Workspace->host as $ThisHost){
	foreach($ThisHost->service as $ThisService){
		foreach($ThisService->vulnerability as $ThisVulnerability){
			if($ThisVulnerability->classification==0){
				$Information++;
			}
			elseif($ThisVulnerability->classification>0&&$ThisVulnerability->classification<4){
				$Low++;
			}
			elseif($ThisVulnerability->classification>=4&&$ThisVulnerability->classification<7){
				$Medium++;
			}
			elseif($ThisVulnerability->classification>=7&&$ThisVulnerability->classification<9){
				$High++;
			}
			elseif($ThisVulnerability->classification>=9){
				$Critical++;
			}
			$IndividualVulnerabilityWords = explode(" ",$ThisVulnerability->description);
			foreach($IndividualVulnerabilityWords as $ThisWord){
				if(!in_array(strtolower($ThisWord),$LameWords)){
				if(!isset($VulnerabilityWords[$ThisWord])){
					$VulnerabilityWords[$ThisWord]=0;
				}
				$VulnerabilityWords[$ThisWord]++;
				$TotalWords++;
				}
			}
		}
		$IndividualServiceWords = explode(" ",$ThisService->name);
			foreach($IndividualServiceWords as $ThisWord){
				if(!in_array(strtolower($ThisWord),$LameWords)){
				if(!isset($ServiceWords[$ThisWord])){
					$ServiceWords[$ThisWord]=0;
				}
				$ServiceWords[$ThisWord]++;
				$TotalServiceWords++;
				}
			}
	}
	if(!isset($OSs[$ThisHost->OS])){
		$OSs[$ThisHost->OS]=0;
	}
	$OSs[$ThisHost->OS]++;
}
	foreach($Workspace->workspacemethodology as $ThisWorkspaceMethodology){
		foreach($ThisWorkspaceMethodology->methodology->msection as $MSection){
			foreach($MSection->mquestion as $MQuestion){
				$MethodologyTotal++;
			}
		}
		foreach($ThisWorkspaceMethodology->manswer as $MAnswer){
			if($MAnswer->completed==1){
				$MethodologyCompleted++;
				if($MAnswer->passed==1){
					$MethodologyPassed++;
				}
			}
		}
	}
foreach($VulnerabilityWords as $Word=>$TotalNumber){
	if(count($Top50VulnerabilityWords)<50){
		$Top50VulnerabilityWords[$Word]=$TotalNumber;
	}
	else{
		foreach($Top50VulnerabilityWords as $Word2=>$TotalNumber2){
			if($TotalNumber2<$TotalNumber){
				unset($Top50VulnerabilityWords[$Word2]);
				$Top50VulnerabilityWords[$Word]=$TotalNumber;
				break;
			}
		}
	}
}
foreach($ServiceWords as $Word=>$TotalNumber){
	if(count($Top50ServiceWords)<50){
		$Top50ServiceWords[$Word]=$TotalNumber;
	}
	else{
		foreach($Top50ServiceWords as $Word2=>$TotalNumber2){
			if($TotalNumber2<$TotalNumber){
				unset($Top50ServiceWords[$Word2]);
				$Top50ServiceWords[$Word]=$TotalNumber;
				break;
			}
		}
	}
}
$MethodologyIncomplete=$MethodologyTotal-$MethodologyCompleted;
$MethodologyFailed=$MethodologyCompleted-$MethodologyPassed;
$MaxTotalCount=0;
foreach($Top50VulnerabilityWords as $Word=>$TotalNumber){
	if($TotalNumber>$MaxTotalCount){
		$MaxTotalCount=$TotalNumber;
	}
}
$MaxTotalServiceCount=0;
foreach($Top50ServiceWords as $Word=>$TotalNumber){
	if($TotalNumber>$MaxTotalServiceCount){
		$MaxTotalServiceCount=$TotalNumber;
	}
}
?>
<script type="text/javascript" src="/loader.js"></script>
<script type="text/javascript" src="/d3.js"></script>
<script type="text/javascript" src="/wordcloud.js"></script>
<script>
google.charts.load('current', {packages: ['corechart', 'bar']});
google.charts.setOnLoadCallback(drawRightY);

function drawRightY() {
	var data = new google.visualization.DataTable();
data.addColumn('string', 'Class'); // Implicit domain label col.
data.addColumn('number', 'Vulnerabilities'); // Implicit series 1 data col.
data.addColumn({'type':'string', 'role':'style'});  // interval role col.
data.addRows([
        ['Information', {{$Information}},'color: green'],
        ['Low', {{$Low}},'color: blue'],
        ['Medium', {{$Medium}},'color: yellow'],
        ['High', {{$High}},'color: orange'],
        ['Critical', {{$Critical}},'color: red']
]);
      var classOptions = {
        chart: {
          title: 'Vulnerabilities By Class'
        },
        vAxis: {
          title: 'Total Vulnerabilities',
          minValue: 0,
        },
        hAxis: {
          title: 'Class'
        },
        bars: 'vertical',
        
      };
      var classChart = new google.charts.Bar(document.getElementById('chart_div'));
      classChart.draw(data, classOptions);
	  
	   var Methodologydata = google.visualization.arrayToDataTable([
          ['Progress', 'Questions'],
          ['Completed',     {{$MethodologyCompleted}}],
          ['Incomplete',      {{$MethodologyIncomplete}}],
        ]);
		var Methodologyoptions = {
          title: 'Methodology Progress',
          is3D: true,
        };
      var MethodologyChart = new google.visualization.PieChart(document.getElementById('methodologychart_div'));
      MethodologyChart.draw(Methodologydata, Methodologyoptions);
	  
	   var MethodologyPasseddata = google.visualization.arrayToDataTable([
          ['Status', 'Questions'],
          ['Passed',      {{$MethodologyPassed}}],
          ['Failed',     {{$MethodologyFailed}}],
        ]);
		var MethodologyPassedoptions = {
          title: 'Methodology Completed Questions Passed',
          is3D: true,
        };
      var MethodologyPassedChart = new google.visualization.PieChart(document.getElementById('methodologypassedchart_div'));
      MethodologyPassedChart.draw(MethodologyPasseddata, MethodologyPassedoptions);
	  
	   var OSdata = google.visualization.arrayToDataTable([
          ['OS', 'Total'],
		  <?php
			foreach($OSs as $OS=>$Total){
				echo "['".str_replace("'","",$OS)."',".$Total."],";
			}
		  ?>
        ]);
		var OSoptions = {
          title: 'Operating Systems',
          is3D: true,
        };
      var OSChart = new google.visualization.PieChart(document.getElementById('oschart_div'));
      OSChart.draw(OSdata, OSoptions);
    }
</script>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
				<div id="chart_div"></div>
				<div id="methodologychart_div"></div>
				<div id="methodologypassedchart_div"></div>
				<h3>Vulnerabilities</h3>
				<div id="vulnerabilitywords"></div>
				<h3>Services</h3>
				<div id="servicewords"></div>
				<div id="oschart_div"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

// List of words
var myWords = [
<?php
$i=0;
	foreach($Top50VulnerabilityWords as $ThisWord=>$TotalCount){
		if($TotalCount>1){
		if($i>0){
			echo ", ";
		}
		else{
			$i++;
		}
		echo "{word: \"".str_replace("\n","",str_replace('"',"'",$ThisWord))."\", size: \"".round(($TotalCount/$MaxTotalCount)*60) . "\"}";
		}
	}
?> ]


var myWords2 = [
<?php
$i=0;
	foreach($Top50ServiceWords as $ThisWord=>$TotalCount){
		if($TotalCount>1){
		if($i>0){
			echo ", ";
		}
		else{
			$i++;
		}
		echo "{word: \"".str_replace('"',"'",$ThisWord)."\", size: \"".round(($TotalCount/$MaxTotalServiceCount)*60) . "\"}";
		}
	}
?> ]

// set the dimensions and margins of the graph
var margin = {top: 10, right: 10, bottom: 10, left: 10},
    width = 900 - margin.left - margin.right,
    height = 450 - margin.top - margin.bottom;

// append the svg object to the body of the page
var svg = d3.select("#vulnerabilitywords").append("svg")
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom)
  .append("g")
    .attr("transform",
          "translate(" + margin.left + "," + margin.top + ")");

// append the svg object to the body of the page
var svg2 = d3.select("#servicewords").append("svg")
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom)
  .append("g")
    .attr("transform",
          "translate(" + margin.left + "," + margin.top + ")");

// Constructs a new cloud layout instance. It run an algorythm to find the position of words that suits your requirements
// Wordcloud features that are different from one word to the other must be here
var layout = d3.layout.cloud()
  .size([width, height])
  .words(myWords.map(function(d) { return {text: d.word, size:d.size}; }))
  .padding(5)        //space between words
  .rotate(function() { return ~~(Math.random() * 2) * 90; })
  .fontSize(function(d) { return d.size; })      // font size of words
  .on("end", draw);
layout.start();

var layout2 = d3.layout.cloud()
  .size([width, height])
  .words(myWords2.map(function(d) { return {text: d.word, size:d.size}; }))
  .padding(5)        //space between words
  .rotate(function() { return ~~(Math.random() * 2) * 90; })
  .fontSize(function(d) { return d.size; })      // font size of words
  .on("end", draw2);
layout2.start();

// This function takes the output of 'layout' above and draw the words
// Wordcloud features that are THE SAME from one word to the other can be here
function draw(words) {
  svg
    .append("g")
      .attr("transform", "translate(" + layout.size()[0] / 2 + "," + layout.size()[1] / 2 + ")")
      .selectAll("text")
        .data(words)
      .enter().append("text")
        .style("font-size", function(d) { return d.size; })
        .style("fill", "#69b3a2")
        .attr("text-anchor", "middle")
        .style("font-family", "Impact")
        .attr("transform", function(d) {
          return "translate(" + [d.x, d.y] + ")rotate(" + d.rotate + ")";
        })
        .text(function(d) { return d.text; });
}
function draw2(words) {
  svg2
    .append("g")
      .attr("transform", "translate(" + layout.size()[0] / 2 + "," + layout.size()[1] / 2 + ")")
      .selectAll("text")
        .data(words)
      .enter().append("text")
        .style("font-size", function(d) { return d.size; })
        .style("fill", "#69b3a2")
        .attr("text-anchor", "middle")
        .style("font-family", "Impact")
        .attr("transform", function(d) {
          return "translate(" + [d.x, d.y] + ")rotate(" + d.rotate + ")";
        })
        .text(function(d) { return d.text; });
}
</script>
@endsection
