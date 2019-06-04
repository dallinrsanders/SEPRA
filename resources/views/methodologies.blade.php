@extends('layouts.app')

@section('content')
<script>

function ShowNewMethodology(){
	$('#NewMethodologyModal').modal('show')
}
function SaveNewMethodology(){
		document.getElementById("NewMethodologyForm").submit();
}
</script>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Methodologies</div>
				
                <div class="card-body">
				<input type=button class="btn btn-primary" value="New Methodology" onclick="javascript:ShowNewMethodology()" /><br><br>
				
				<table class="table table-striped">
				<tr><td>Name</td><td>Created Time</td></tr>
				@foreach($Workspace->workspacemethodology as $Methodology)
					<tr onclick="javascript:window.location='EditMethodology/{{$Methodology->id}}'"><td>{{$Methodology->methodology->name}}</td><td>{{$Methodology->created_at}}</td></tr>
				@endforeach
				</table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id='NewMethodologyModal'>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">New Methodology</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	  <form action="NewMethodology" method=post id=NewMethodologyForm>
    {{ csrf_field() }} 
	  <div class="form-group">
	  <label for="Methodology">Methodology</label>
	  <select class=form-control id="Methodology" name="Methodology">
		@foreach($AllMethodologies as $ThisMethodology)
			<option value="{{$ThisMethodology->id}}">{{$ThisMethodology->name}}</option>
		@endforeach
		
	  </select>
	  </div>
	  </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="javascript:SaveNewMethodology()">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection
