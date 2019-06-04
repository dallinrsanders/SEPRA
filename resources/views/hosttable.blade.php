
				<table class="table table-striped">
				<tr><th>Name</th><th>IP</th><th>OS</th><th>Services</th><th>Vulns</th><th>Credentials</th><th>Creation Time</th></tr>
				
				<?php 
				foreach($Hosts as $ThisHost){
				$ThisServices=0;
				$ThisVulns=0;
				$ThisCredentials=0;
				$Information2=0;
				$Low2=0;
				$Medium2=0;
				$High2=0;
				$Critical2=0;
				foreach($ThisHost->service as $ThisService){
					$ThisServices++;
					foreach($ThisService->vulnerability as $ThisVulnerability){
						$ThisVulns++;
						if($ThisVulnerability->classification==0){
							$Information2++;
						}
						elseif($ThisVulnerability->classification<4){
							$Low2++;
						}
						elseif($ThisVulnerability->classification<7){
							$Medium2++;
						}
						elseif($ThisVulnerability->classification<9){
							$High2++;
						}
						else{
							$Critical2++;
						}
					}
				}
				foreach($ThisHost->credential as $ThisCredential){
					$ThisCredentials++;
				}
				if(($ThisVulns==0&&$NoVulns=="checked")||($Information2>0&&$Information=="checked")||($Low2>0&&$Low=="checked")||($Medium2>0&&$Medium=="checked")||($High2>0&&$High=="checked")||($Critical2>0&&$Critical=="checked")){
				?>
				
				<tr onclick="javascript:window.location='ViewHost/{{$ThisHost->id}}'"><td>{{$ThisHost->name}}</td>
				<td>{{$ThisHost->ip}}</td>
				<td>{{$ThisHost->OS}}</td>
				<td>{{$ThisServices}}</td>
				<td>{{$ThisVulns}}</td>
				<td>{{$ThisCredentials}}</td>
				<td>{{$ThisHost->created_at}}</td>
				</tr>
				<?php }} ?>
				</table>
