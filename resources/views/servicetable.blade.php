
				<table class="table table-striped">
				<tr><th>Name</th><th>Port</th><th>Protocol</th><th>Version</th><th>Status</th><th>Vulnerabilities</th><th>Highest Vulnerability</th></tr>
				
				<?php 
				foreach($Host->service->sortBy("port") as $ThisService){
				$ThisVulns=0;
				$HighestVulnerability=0;
				foreach($ThisService->vulnerability as $ThisVulnerability){
					$ThisVulns++;
					if($ThisVulnerability->classification>$HighestVulnerability){
						$HighestVulnerability=$ThisVulnerability->classification;
					}
				}
				?>
				
				<tr onclick="javascript:window.location='../ViewService/{{$ThisService->id}}'"><td>{{$ThisService->name}}</td>
				<td>{{$ThisService->port}}</td>
				<td>{{$ThisService->protocol}}</td>
				<td>{{$ThisService->version}}</td>
				<td>{{$ThisService->status}}</td>
				<td>{{$ThisVulns}}</td>
				<td>{{$HighestVulnerability}}</td>
				</tr>
				<?php } ?>
				</table>
