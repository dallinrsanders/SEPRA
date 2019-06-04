@extends('layouts.app')

@section('content')
<script>
function UploadFile(){
	document.getElementById("file").value=null;
	
	$('#UploadFileModal').modal('show')
}
function SaveUpload(){
	var formData = new FormData();
	formData.append('file', $('#file')[0].files[0]);
	formData.append('id',"{{$Workspace2->id}}");
	formData.append('_token',"{{ csrf_token() }}");
	formData.append('plugin',document.getElementById("plugin").value);

	$.ajax({
       url : '{{route("UploadWorkspaceFile")}}',
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
	$.post("{{ route('DeleteWorkspaceFile') }}",
  {
	"id": id,
	"_token": "{{ csrf_token() }}"
  },
  function(data, status){
	  
  });
	}
	
}
function DeleteWorkspace(){
	window.location="../DeleteWorkspace/{{$Workspace2->id}}";
}
</script>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
			<h2>{{$Workspace2->name}}</h2>
            <div class="card">
                <div class="card-header">Information</div>
				
                <div class="card-body">
				<form action="{{route('SaveWorkspace')}}" method=post>
				<input type=hidden name=id value="{{$Workspace2->id}}">
				
    {{ csrf_field() }} 
				<div class="form-group">
					<label for=Name>Name</label>
					<input type=text value="{{$Workspace2->name}}" name=name class="form-control">
				</div>
				<div class="form-group">
					<label for=Active>Active</label>
					<input type=checkbox value="1" name=Active <?php 
					if($Workspace2->Active==1){
						echo "checked readonly disabled";
					}
					?>>
				</div>
			<input type=submit class="btn btn-primary" value="Save" /> <?php if($Workspace2->Active!=1){?><input type=button class="btn btn-danger" value="Delete Workspace" onclick="javascript:DeleteWorkspace()" /><?php } ?>
			</form>
                </div>
            </div>
			<div class="card">
                <div class="card-header">Files</div>
                <div class="card-body">
				<table id=UploadedFile class="table table-striped">
				<tr><th>File</th><th></th></tr>
				@foreach($Workspace2->workfile as $ThisFile)
					<tr><td>{{$ThisFile->upload->name}}</td>
						<td><input type=button class="btn btn-primary" value="Download" onclick="window.location='../DownloadFile/{{$ThisFile->upload_id}}'"> 
						<input type=button class="btn btn-danger" value="Delete" onclick="javascript:DeleteFile({{$ThisFile->id}},this)"> </td></tr>
				@endforeach
				</table>
				<input type=button class="btn btn-success" value="Upload File" onclick="UploadFile()">
			
                </div>
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
	  <label for="file">File</label>
	  <input type=file class=form-control id="file" name="file">
	  </div>
	  <div class="form-group">
	  <label for="plugin">Plugin</label>
	  <select class=form-control id="plugin" name="plugin">
		<option value="None">None</option>
		<option value="Nmap">Nmap</option>
		<option value="Nikto">Nikto</option>
		<option value="OpenVAS">OpenVAS</option>
	  </select>
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
