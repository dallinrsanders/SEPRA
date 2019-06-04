
				<table class="table table-striped">
				<tr><th>Username</th><th>Description</th></tr>
				
				<?php 
				//env('DB_ENCRYPTIONPASSWORD');
				foreach($Host->credential as $ThisCredential){
				?>
				
				<tr onclick="javascript:window.location='../ViewCredential/{{$ThisCredential->id}}'"><td>{{$ThisCredential->username}}</td>
				<td>{{$ThisCredential->Description}}</td>
				</tr>
				<?php } ?>
				</table>
