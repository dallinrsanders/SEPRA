@extends('layouts.app')

@section('content')
<script>
Name="{{$Service->name}}";
Port="{{$Service->port}}";
Protocol="{{$Service->protocol}}";
Version="{{$Service->version}}";
Status="{{$Service->status}}";
Website=<?php echo ($Service->website==1)?"true":"false";?>;
Description="{{$Service->description}}";
function Decode(encodedStr){
	var parser = new DOMParser;
	var dom = parser.parseFromString(
		'<!doctype html><body>' + encodedStr,
		'text/html');
	var decodedString = dom.body.textContent;
	return decodedString;
}
function ShowEditService(){
	document.getElementById("EditName").value=Decode(Name);
	document.getElementById("EditPort").value=Decode(Port);
	document.getElementById("EditProtocol").value=Decode(Protocol);
	document.getElementById("EditVersion").value=Decode(Version);
	if(Status=="Open"){
		document.getElementById("EditStatus").options.selectedIndex=0;
	}
	else if(Status=="Closed"){
		document.getElementById("EditStatus").options.selectedIndex=1;
	}
	else if(Status=="Filtered"){
		document.getElementById("EditStatus").options.selectedIndex=2;
	}
	document.getElementById("EditWebsite").checked=Website;
document.getElementById("EditDescription").value=Decode(Description);
	$('#EditServiceModal').modal('show')
}
function SaveEditService(){
	$.post("{{ route('EditService') }}",
  {
	"id":"{{$Service->id}}",
    "name": document.getElementById("EditName").value,
    "port": document.getElementById("EditPort").value,
	"protocol": document.getElementById("EditProtocol").value,
	"version": document.getElementById("EditVersion").value,
	"status": document.getElementById("EditStatus").value,
	"website": (document.getElementById("EditWebsite").checked)?1:0,
	"description": document.getElementById("EditDescription").value,
	"_token": "{{ csrf_token() }}"
  },
  function(data, status){
    document.getElementById("ServiceInformation").innerHTML=data;
  });
	$('#EditServiceModal').modal('hide');
    Name= document.getElementById("EditName").value;
    Port= document.getElementById("EditPort").value;
	Protocol= document.getElementById("EditProtocol").value;
	Version= document.getElementById("EditVersion").value;
	Status= document.getElementById("EditStatus").value;
	Website= document.getElementById("EditWebsite").checked;
	Description= document.getElementById("EditDescription").value;
}
function DeleteService(){
var r = confirm("Are you sure you want to delete this service?");
if (r == true) {
  window.location="../DeleteService/{{$Service->id}}";
} 
}
</script>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-12">
			<h2><span onclick="javascript:window.location='../ViewHost/{{$Host->id}}'">{{$Host->name}}</span> {{$Service->name}} {{$Service->port}}</h2>
            <div class="card">
                <div class="card-header">Information</div>
				
                <div class="card-body">
				<span id="ServiceInformation">
					
				@component('serviceinformation',['Service'=>$Service])
				@endcomponent
				</span>
				
			<input type=button class="btn btn-primary" value="Edit" onclick="javasript:ShowEditService()" /> <input type=button class="btn btn-danger" value="Delete Service" onclick="javascript:DeleteService()" />
                </div>
            </div>
			<div class="card">
                <div class="card-header">Vulnerabilities</div>
                <div class="card-body">
				<input type=button class="btn btn-primary" value="Add Vulnerability" onclick="javascript:window.location='../AddVulnerability/{{$Service->id}}'"/>
				<span id="VulnerabilityTable">
					
				@component('vulnerabilitytable',['Service'=>$Service])
				@endcomponent
				</span>
				
			
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal" tabindex="-1" role="dialog" id='EditServiceModal'>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Service</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	  <div class="form-group">
	  <label for="EditName">Name</label>
	  <input type=text class=form-control id="EditName" name="EditName">
	  </div>
	  <div class="form-group">
	  <label for="EditPort">Port</label>
	  <input type=text class=form-control id="EditPort" name=EditPort>
	  </div>
	  <div class="form-group">
	  <label for="EditProtocol">Protocol</label>
	  <input type=text class=form-control id="EditProtocol" name=EditProtocol>
	  </div>
	  <div class="form-group">
	  <label for=EditVersion>Version</label>
	  <input type=text class=form-control id="EditVersion" name=EditVersion>
	  </div>
	  <div class="form-group">
	  <label for=EditStatus>Status</label>
	  <select class=form-control id="EditStatus" name=EditStatus>
		<option value="Open">Open</option>
		<option value="Closed">Closed</option>
		<option value="Filtered">Filtered</option>
	  </select>
	  </div>
	  <div class="form-group">
	  <label class="form-check-label" for=EditWebsite>Website</label><br>
	  <input type=checkbox class=form-check-input1 id="EditWebsite" name=EditWebsite>
	  </div>
	  <div class="form-group">
	  <label for=EditDescription>Description</label>
	  <textarea class=form-control id="EditDescription" rows=5 cols=40 name=EditDescription></textarea>
	  </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="javascript:SaveEditService()">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection
