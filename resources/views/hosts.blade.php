@extends('layouts.app')

@section('content')
<script>
function ShowNewHost(){
	document.getElementById("NewName").value="";
	document.getElementById("NewIP").value="";
	document.getElementById("NewMac").value="";
	document.getElementById("NewOS").value="";
	document.getElementById("NewDescription").value="";
	$('#NewHostModal').modal('show')
}
function SaveNewHost(){
	$.post("{{ route('NewHost') }}",
  {
    "name": document.getElementById("NewName").value,
    "ip": document.getElementById("NewIP").value,
	"os": document.getElementById("NewOS").value,
	"mac": document.getElementById("NewMac").value,
	"description": document.getElementById("NewDescription").value,
	"NoVulns":"{{$NoVulns}}",
	"Information":"{{$Information}}",
	"Low":"{{$Low}}",
	"Medium":"{{$Medium}}",
	"High":"{{$High}}",
	"Critical":"{{$Critical}}",
	"_token": "{{ csrf_token() }}"
  },
  function(data, status){
    document.getElementById("HostTable").innerHTML=data;
  });
	$('#NewHostModal').modal('hide')
}
</script>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">Hosts</div>
				
                <div class="card-body">
				<form><input type=text name=Search value="{{$Search}}"> <input type=submit class="btn btn-success" /><br>
				No Vulns <input type=checkbox name=NoVulns {{$NoVulns}} /> Information <input type=checkbox name=Information {{$Information}} /> Low <input type=checkbox name=Low {{$Low}} /> Medium <input type=checkbox name=Medium {{$Medium}} /> High <input type=checkbox name=High {{$High}} /> Critical <input type=checkbox name=Critical {{$Critical}} /></form>
				<input type=button class="btn btn-primary" value="New Host" onclick="javascript:ShowNewHost()" /><br><br>
				<span id="HostTable">
				@component('hosttable',['Hosts'=>$Hosts,"NoVulns"=>$NoVulns,"Information"=>$Information,"Low"=>$Low,"Medium"=>$Medium,"High"=>$High,"Critical"=>$Critical])
				@endcomponent
				</span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal" tabindex="-1" role="dialog" id='NewHostModal'>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">New Host</h5>
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
	  <label for="NewIP">IP</label>
	  <input type=text class=form-control id="NewIP" name=NewIP>
	  </div>
	  <div class="form-group">
	  <label for="NewMac">Mac</label>
	  <input type=text class=form-control id="NewMac" name=NewMac>
	  </div>
	  <div class="form-group">
	  <label for=NewOS>OS</label>
	  <input type=text class=form-control id="NewOS" name=NewOS>
	  </div>
	  <div class="form-group">
	  <label for=NewDescription>Description</label>
	  <textarea class=form-control id="NewDescription" rows=5 cols=40 name=NewDescription></textarea>
	  </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="javascript:SaveNewHost()">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection
