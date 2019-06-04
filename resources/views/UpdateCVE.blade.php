@extends('layouts.app')

@section('content')
<script>
	MaxCVE="{{$MaxCVE}}";
	MaxYear=MaxCVE.split("-")[1];
	CurrentYear=MaxYear;
function UpdateCVE(){
	document.getElementById("Progress").innerHTML="Updating "+CurrentYear;
	$.post("{{ route('UpdateCVEAjax') }}",
  {
	"year": CurrentYear,
	"_token": "{{ csrf_token() }}"
  },
  function(data, status){
	  var d = new Date();
var n = d.getFullYear();
	  CurrentYear++;
	  if(CurrentYear<=n){
	  UpdateCVE();
	  }
	  else{
		  alert("Finished");
	  }
  });
}
window.onload = function(){UpdateCVE()}
</script>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Update Progress</div>
				
                <div class="card-body" id=Progress>
				Updating
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
