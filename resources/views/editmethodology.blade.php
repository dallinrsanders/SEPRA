@extends('layouts.app')

@section('content')
<script>
 var CurrentQuestionID;
function UploadFile(QuestionID){
	document.getElementById("file").value=null;
	CurrentQuestionID=QuestionID;
	$('#UploadFileModal').modal('show')
}
function SaveUpload(){
	var formData = new FormData();
	formData.append('file', $('#file')[0].files[0]);
	formData.append('workspacemethodologyid',"{{$WorkspaceMethodology->id}}");
	formData.append('questionid',CurrentQuestionID);
	formData.append('_token',"{{ csrf_token() }}");

	$.ajax({
       url : '{{route("UploadWorkspaceMethodologyFile")}}',
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
		   document.getElementById("UploadFileTable"+CurrentQuestionID).append(TableRow);
	$('#UploadFileModal').modal('hide')
       }
	});
}
function DeleteFile(id,input){
	r=confirm("Are you sure you want to delete this file?")
	if(r){
	ThisTableRow=input.parentElement.parentElement
	ThisTableRow.parentElement.removeChild(ThisTableRow);
	$.post("{{ route('DeleteWorkspaceMethodologyFile') }}",
  {
	"id": id,
	"_token": "{{ csrf_token() }}"
  },
  function(data, status){
	  
  });
	}
	
}
function DeleteMethodology(){
	r=confirm("Are you sure you would like to delete this Methodology?");
	if(r){
	window.location="../DeleteMethodology/{{$WorkspaceMethodology->id}}";
	}
}
function UpdateAnswer(inp,ID){
	setTimeout(function(){UpdateAnswer2(inp,ID)},200);
}
function UpdateAnswer2(inp,ID){
	$.post("{{ route('UpdateAnswer') }}",
  {
    "answer": inp.value,
    "questionid": ID,
	"workspacemethodologyid": "{{$WorkspaceMethodology->id}}",
	"_token": "{{ csrf_token() }}"
  },
  function(data, status){
  });
}
function UpdateCompleted(inp,ID){
	$.post("{{ route('UpdateCompleted') }}",
  {
    "completed": inp.checked,
    "questionid": ID,
	"workspacemethodologyid": "{{$WorkspaceMethodology->id}}",
	"_token": "{{ csrf_token() }}"
  },
  function(data, status){
  });
	
}
function UpdatePassed(inp,ID){
	$.post("{{ route('UpdatePassed') }}",
  {
    "passed": inp.checked,
    "questionid": ID,
	"workspacemethodologyid": "{{$WorkspaceMethodology->id}}",
	"_token": "{{ csrf_token() }}"
  },
  function(data, status){
  });
	
}
</script>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
			<h2>{{$WorkspaceMethodology->methodology->name}}</h2>
			@foreach($WorkspaceMethodology->methodology->msection as $MSection)
				<div class="card">
					<div class="card-header">{{$MSection->title}}</div>
					<div class="card-body">
						<ol>
						@foreach($MSection->mquestion as $MQuestion)
							<li>
								<p>{{$MQuestion->question}}</p>
								<?php 
									$AnswerCount=$WorkspaceMethodology->manswer->where("mquestion_id",$MQuestion->id)->count();
									$AnswerText="";
									if($AnswerCount>0){
										$AnswerText= $WorkspaceMethodology->manswer->where("mquestion_id",$MQuestion->id)->first()->answer;
									}
									?>
								<textarea class="form-control" rows=5 onpaste="javascript:UpdateAnswer(this,{{$MQuestion->id}})" onkeypress="javascript:UpdateAnswer(this,{{$MQuestion->id}})" onchange="javascript:UpdateAnswer(this,{{$MQuestion->id}})">{{$AnswerText}}</textarea>
								<br>
								Completed <input type=checkbox <?php
									if($AnswerCount>0){
										if($WorkspaceMethodology->manswer->where("mquestion_id",$MQuestion->id)->first()->completed==1){
											echo "checked";
										}
									}
								?> onchange="javascript:UpdateCompleted(this,{{$MQuestion->id}})">
								<br>
								Passed <input type=checkbox <?php
									if($AnswerCount>0){
										if($WorkspaceMethodology->manswer->where("mquestion_id",$MQuestion->id)->first()->passed==1){
											echo "checked";
										}
									}
								?> onchange="javascript:UpdatePassed(this,{{$MQuestion->id}})" >
							</li>
				<table  class="table table-striped" id="UploadFileTable{{$MQuestion->id}}">
				<tr><th>File</th><th></th></tr>
				@if($AnswerCount>0)
				@foreach($WorkspaceMethodology->manswer->where("mquestion_id",$MQuestion->id)->first()->mfile as $ThisFile)
					<tr><td>{{$ThisFile->upload->name}}</td>
						<td><input type=button class="btn btn-primary" value="Download" onclick="window.location='../DownloadFile/{{$ThisFile->upload_id}}'"> 
						<input type=button class="btn btn-danger" value="Delete" onclick="javascript:DeleteFile({{$ThisFile->id}},this)"> </td></tr>
				@endforeach
				@endif
				</table>
				<input type=button class="btn btn-success" value="Upload File" onclick="UploadFile({{$MQuestion->id}})">
						@endforeach
						</ol>
						
					</div>
				</div>
			@endforeach<br><br>
			<input type=button class="btn btn-danger" value="Delete Methodology" onclick="javascript:DeleteMethodology()">
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
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="javascript:SaveUpload()">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection
