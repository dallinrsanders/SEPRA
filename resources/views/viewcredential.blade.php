@extends('layouts.app')

@section('content')
<script>
function Delete(){
	r=confirm("Are you sure you would like to Delete?");
	if(r){
		window.location="../DeleteCredential/{{$Credential->id}}";
	}
}
</script>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
			<h2 onclick="window.location='../ViewHost/{{$Credential->host->id}}'">{{$Credential->host->ip}}</h2>
            <div class="card">
                <div class="card-header">Information</div>
				
                <div class="card-body">
				<form action="../SaveCredential" method="Post">
				
    {{ csrf_field() }} 
				<input type=hidden value="{{$Credential->id}}" name=id />
				<div class="form-group">
					<label for=username>Username</label>
					<input type=text class=form-control value="{{$Credential->username}}" name=username />
				</div>
				<div class="form-group">
					<label for=password>Password</label>
					<input type=text class=form-control  value="{{$Password}}" name=password />
				</div>
				<div class="form-group">
					<label for=Description>Description</label>
					<textarea class=form-control   name=description>{{$Credential->Description}}</textarea>
				</div>
			<input type=submit class="btn btn-primary" value="Save" /> <input type=button class="btn btn-danger" value="Delete" onclick="javascript:Delete()" />
			</form>
				</div>
            </div>
		</div>
    </div>
</div>
@endsection
