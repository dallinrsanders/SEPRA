@extends('layouts.app')

@section('content')
<script>
Name="{{$Host->name}}";
IP="{{$Host->ip}}";
Mac="{{$Host->mac}}";
OS="{{$Host->OS}}";
Description=<?php echo json_encode($Host->description); ?>;
function Decode(encodedStr){
	var parser = new DOMParser;
	var dom = parser.parseFromString(
		'<!doctype html><body>' + encodedStr,
		'text/html');
	var decodedString = dom.body.textContent;
	return decodedString;
}
function ShowEditHost(){
	document.getElementById("EditName").value=Decode(Name);
	document.getElementById("EditIP").value=Decode(IP);
	document.getElementById("EditMac").value=Decode(Mac);
	document.getElementById("EditOS").value=Decode(OS);
document.getElementById("EditDescription").value=Decode(Description);
	$('#EditHostModal').modal('show')
}
function SaveEditHost(){
	$.post("{{ route('EditHost') }}",
  {
	"id":"{{$Host->id}}",
    "name": document.getElementById("EditName").value,
    "ip": document.getElementById("EditIP").value,
	"os": document.getElementById("EditOS").value,
	"mac": document.getElementById("EditMac").value,
	"description": document.getElementById("EditDescription").value,
	"_token": "{{ csrf_token() }}"
  },
  function(data, status){
    document.getElementById("HostInformation").innerHTML=data;
  });
	$('#EditHostModal').modal('hide');
    Name= document.getElementById("EditName").value;
    IP= document.getElementById("EditIP").value;
	OS= document.getElementById("EditOS").value;
	Mac= document.getElementById("EditMac").value;
	Description= document.getElementById("EditDescription").value;
}
function DeleteHost(){
var r = confirm("Are you sure you want to delete this host?");
if (r == true) {
  window.location="../DeleteHost/{{$Host->id}}";
} 
}
function ShowNewService(){
	document.getElementById("NewName").value="";
	document.getElementById("NewPort").value="";
	document.getElementById("NewProtocol").value="";
	document.getElementById("NewVersion").value="";
	document.getElementById("NewStatus").options.selectedIndex=0;
	document.getElementById("NewWebsite").checked=false;
document.getElementById("NewDescription").value="";
	$('#NewServiceModal').modal('show')
}
function SaveNewService(){
	$.post("{{ route('NewService') }}",
  {
    "name": document.getElementById("NewName").value,
    "port": document.getElementById("NewPort").value,
	"protocol": document.getElementById("NewProtocol").value,
	"version": document.getElementById("NewVersion").value,
	"status": document.getElementById("NewStatus").value,
	"web": (document.getElementById("NewWebsite").checked)?1:0,
	"description": document.getElementById("NewDescription").value,
	"host":"{{$Host->id}}",
	"_token": "{{ csrf_token() }}"
  },
  function(data, status){
    document.getElementById("ServiceTable").innerHTML=data;
  });
	$('#NewServiceModal').modal('hide');
}
function ShowNewCredential(){
	document.getElementById("NewUsername").value="";
	document.getElementById("NewPassword").value="";
	document.getElementById("NewCredentialDescription").value="";
	$('#NewCredentialModal').modal('show')
}
function SaveNewCredential(){
	$.post("{{ route('NewCredential') }}",
  {
    "username": document.getElementById("NewUsername").value,
    "password": document.getElementById("NewPassword").value,
	"description": document.getElementById("NewCredentialDescription").value,
	"host":"{{$Host->id}}",
	"_token": "{{ csrf_token() }}"
  },
  function(data, status){
    document.getElementById("CredentialTable").innerHTML=data;
  });
	$('#NewCredentialModal').modal('hide');
}
function UploadFile(){
	document.getElementById("file").value=null;
	
	$('#UploadFileModal').modal('show')
}
function SaveUpload(){
	var formData = new FormData();
	formData.append('file', $('#file')[0].files[0]);
	formData.append('id',"{{$Host->id}}");
	formData.append('_token',"{{ csrf_token() }}");

	$.ajax({
       url : '{{route("UploadHostFile")}}',
       type : 'POST',
       data : formData,
       processData: false,  // tell jQuery not to process the data
       contentType: false,  // tell jQuery not to set contentType
       success : function(data) {
		   JSONdata=JSON.parse(data);
		   TableRow = document.createElement("tr");
		   TableColumn1 = document.createElement("td");
		   TableColumn2 = document.createElement("td");
		   TableColumn1.innerHTML=JSONdata.name;
		   TableColumn2.innerHTML="<input type=button class=\"btn btn-primary\" value=\"Download\" onclick=\"window.location='../DownloadFile/"+JSONdata.uploadid+"'\"> <input type=button class=\"btn btn-danger\" value=\"Delete\" onclick=\"javascript:DeleteFile("+JSONdata.id+",this)\"> ";
		   TableRow.append(TableColumn1);
		   TableRow.append(TableColumn2);
		   document.getElementById("UploadedFile").append(TableRow);
	$('#UploadFileModal').modal('hide')
       }
	});
}
function DeleteFile(id,input){
	r=confirm("Are you sure you want to delete this file?")
	if(r){
	ThisTableRow=input.parentElement.parentElement
	ThisTableRow.parentElement.removeChild(ThisTableRow);
	$.post("{{ route('DeleteHostFile') }}",
  {
	"id": id,
	"_token": "{{ csrf_token() }}"
  },
  function(data, status){
	  
  });
	}
	
}
</script>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
			<h2>{{$Host->name}} {{$Host->ip}}</h2>
            <div class="card">
                <div class="card-header">Information</div>
				
                <div class="card-body">
				<span id="HostInformation">
					
				@component('hostinformation',['Host'=>$Host])
				@endcomponent
				</span>
				
			<input type=button class="btn btn-primary" value="Edit" onclick="javasript:ShowEditHost()" /> <input type=button class="btn btn-danger" value="Delete Host" onclick="javascript:DeleteHost()" />
                </div>
            </div>
			<div class="card">
                <div class="card-header">Services</div>
                <div class="card-body">
				<input type=button class="btn btn-primary" value="Add Service" onclick="javascript:ShowNewService()"/>
				<span id="ServiceTable">
					
				@component('servicetable',['Host'=>$Host])
				@endcomponent
				</span>
				
			
                </div>
            </div>
			<div class="card">
                <div class="card-header">Credentials</div>
                <div class="card-body">
				<input type=button class="btn btn-primary" value="Add Credentials" onclick="javascript:ShowNewCredential()"/>
				<span id="CredentialTable">
					
				@component('credentialtable',['Host'=>$Host])
				@endcomponent
				</span>
				
			
                </div>
            </div>
			<div class="card">
                <div class="card-header">Files</div>
                <div class="card-body">
				<table id=UploadedFile class="table table-striped">
				<tr><th>File</th><th></th></tr>
				@foreach($Host->hostfile as $ThisFile)
					<tr><td>{{$ThisFile->upload->name}}</td>
						<td><input type=button class="btn btn-primary" value="Download" onclick="window.location='../DownloadFile/{{$ThisFile->upload_id}}'"> 
						<input type=button class="btn btn-danger" value="Delete" onclick="javascript:DeleteFile({{$ThisFile->id}},this)"> </td></tr>
				@endforeach
				</table>
				<input type=button class="btn btn-success" value="Upload File" onclick="UploadFile()"><br><br>
				
			
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal" tabindex="-1" role="dialog" id='EditHostModal'>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Host</h5>
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
	  <label for="EditIP">IP</label>
	  <input type=text class=form-control id="EditIP" name=EditIP>
	  </div>
	  <div class="form-group">
	  <label for="EditMac">Mac</label>
	  <input type=text class=form-control id="EditMac" name=EditMac>
	  </div>
	  <div class="form-group">
	  <label for=EditOS>OS</label>
	  <input type=text class=form-control id="EditOS" name=EditOS>
	  </div>
	  <div class="form-group">
	  <label for=EditDescription>Description</label>
	  <textarea class=form-control id="EditDescription" rows=5 cols=40 name=EditDescription></textarea>
	  </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="javascript:SaveEditHost()">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal" tabindex="-1" role="dialog" id='NewServiceModal'>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">New Service</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	  <div class="form-group">
	  <label for="NewName">Name</label>
	  <input type=text class=form-control id="NewName" name="NewName">
	  </div>
	  <div class="form-group">
	  <label for="NewPort">Port</label>
	  <input type=text class=form-control id="NewPort" name=NewPort>
	  </div>
	  <div class="form-group">
	  <label for="NewProtocol">Protocol</label>
	  <input type=text class=form-control id="NewProtocol" name=NewProtocol>
	  </div>
	  <div class="form-group">
	  <label for=NewVersion>Version</label>
	  <input type=text class=form-control id="NewVersion" name=NewVersion>
	  </div>
	  <div class="form-group">
	  <label for=NewStatus>Status</label>
	  <select class=form-control id=NewStatus name=NewStatus>
		<option value=Open>Open</option>
		<option value=Closed>Closed</option>
		<option value=Filtered>Filtered</option>
	  </select>
	  </div>
	  <div class="form-group">
	  <label class="form-check-label" for=NewWebsite>Is this a web service</label>
	  <input type=checkbox class=form-check-input1 id="NewWebsite" name=NewWebsite value=1>
	  </div>
	  <div class="form-group">
	  <label for=NewDescription>Description</label>
	  <textarea class=form-control id="NewDescription" rows=5 cols=40 name=NewDescription></textarea>
	  </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="javascript:SaveNewService()">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal" tabindex="-1" role="dialog" id='NewCredentialModal'>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">New Credential</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	  <div class="form-group">
	  <label for="NewUsername">Username</label>
	  <input type=text class=form-control id="NewUsername" name="NewUsername">
	  </div>
	  <div class="form-group">
	  <label for="NewPassword">Password</label>
	  <input type=text class=form-control id="NewPassword" name=NewPassword>
	  </div>
	  <div class="form-group">
	  <label for=NewCredentialDescription>Description</label>
	  <textarea class=form-control id="NewCredentialDescription" rows=5 cols=40 name=NewCredentialDescription></textarea>
	  </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="javascript:SaveNewCredential()">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id='UploadFileModal'>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Upload File</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	  <div class="form-group">
	  <label for="EditName">File</label>
	  <input type=file class=form-control id="file" name="file">
	  </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="javascript:SaveUpload()">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection
