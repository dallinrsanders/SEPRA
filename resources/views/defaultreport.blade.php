<h1>Hosts</h1><br>
@foreach($Workspace->host as $Host)
<p>	<h2>{{$Host->name}} {{$Host->ip}}</h2>
	<ul>
		<li>Mac: {{$Host->mac}}</li>
		<li>OS: {{$Host->OS}}</li>
		<li>Description: {{$Host->description}}
	</ul>
	<p><h3>Services</h3>
	@foreach($Host->service as $ThisService)
		<h4>{{$ThisService->port}} {{$ThisService->protocol}}</h4>
		<ul>
			<li>Name: {{$ThisService->name}}</li>
			<li>Version: {{$ThisService->version}}</li>
			<li>Status: {{$ThisService->status}}</li>
			<li>Description: {{$ThisService->description}}</li>
		</ul>
		<p>
			<h5>Vulnerabilities</h5>
				@foreach($ThisService->vulnerability as $ThisVulnerability)
					<b>{{$ThisVulnerability->name}}</b>
					<ul>
						<li>Classification: {{$ThisVulnerability->classification}}</li>
						<li>Ease of Resolution: {{$ThisVulnerability->easeofresolution}}</li>
						<li>Description: {{$ThisVulnerability->description}}</li>
					</ul>
				@endforeach
			</h5>
		</p>
	@endforeach
	</p>
</p>
@endforeach