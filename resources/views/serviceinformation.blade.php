<div class="table-responsive">
				<table class="table table-striped">
				<tr><th>Field</th><th>Value</th></tr>
				<tr>
					<td>Name</td>
					<td>{{$Service->name}}</td>
				</tr>
				<tr>
					<td>Port</td>
					<td>{{$Service->port}}</td>
				</tr>
				<tr>
					<td>Protocol</td>
					<td>{{$Service->protocol}}</td>
				</tr>
				<tr>
					<td>Version</td>
					<td>{{$Service->version}}</td>
				</tr>
				<tr>
					<td>Status</td>
					<td>{{$Service->status}}</td>
				</tr>
				<tr>
					<td>Website</td>
					<td><input type=checkbox <?php echo ($Service->website==1)?"checked=true":"";?> /></td>
				</tr>
				<tr>
					<td>Description</td>
					<td>{{$Service->description}}</td>
				</tr>
			</table>
</div>