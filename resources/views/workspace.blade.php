@extends('layouts.app')

@section('content')
<script>

function ShowNewWorkspace(){
	document.getElementById("Name").value="";
	document.getElementById("Active").checked=true;
	$('#NewWorkspaceModal').modal('show')
}
function SaveWorkspace(){
	document.getElementById("WorkspaceForm").submit();
}
</script>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Workspace</div>

                <div class="card-body">
				<input type=button class='btn btn-primary' value="New Workspace" onclick="javascript:ShowNewWorkspace()"><br>
				<?php
				$AllWorkspaces = App\Workspace::all();
				?>
				<table class="table table-striped">
				<tr><th>Name</th><th>Active</th></tr>
				@foreach($AllWorkspaces as $ThisWorkspace)
				<tr onclick="javascript:window.location='ViewWorkspace/{{$ThisWorkspace->id}}'"><td>{{$ThisWorkspace->name}}</td>
					<td>@if($ThisWorkspace->Active==1)
						Yes	
						@else
							No
						@endif
					</td>
				@endforeach
				</table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal" tabindex="-1" role="dialog" id='NewWorkspaceModal'>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">New Workspace</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	  <form action="{{route('NewWorkspace')}}" method=post id=WorkspaceForm>
	  
    {{ csrf_field() }} 
	  <div class="form-group">
	  <label for="Name">Name</label>
	  <input type=text class=form-control id="Name" name="Name">
	  </div>
	  <div class="form-group">
	  <label for="Active">Active</label>
	  <input type=checkbox class=form-control-check id="Active" name=Active value=1 checked>
	  </div>
	  </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="javascript:SaveWorkspace()">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection
