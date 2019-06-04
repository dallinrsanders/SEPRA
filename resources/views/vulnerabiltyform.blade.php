<script>
function UpdateClassification(){
	Classification=document.getElementById("Classification").value;
	if(Classification==""){
		document.getElementById("ClassificationCategory").value="Unclassified";
	}
	else if(Classification==0){
		document.getElementById("ClassificationCategory").value="Information";
	}
	else if(Classification>0&&Classification<4){
		document.getElementById("ClassificationCategory").value="Low";
	}
	else if(Classification>=4&&Classification<7){
		document.getElementById("ClassificationCategory").value="Medium";
	}
	else if(Classification>=7&&Classification<9){
		document.getElementById("ClassificationCategory").value="High";
	}
	else if(Classification>=9){
		document.getElementById("ClassificationCategory").value="Critical";
	}
}
</script>
<div class="form-group">
	<label for="Name">Name</label>
	<input type=text class=form-control id=Name Name=Name />
</div>
<div class="form-group">
	<label for="CVE">CVE</label>
	<input type=text class=form-control id=CVE Name=CVE />
</div>
<div class="form-group">
	<label for="Classification">Classification</label>
	<input type=number class=form-control id=Classification Name=Classification max=10 min=0 step=0.1 onchange="javascript:UpdateClassification()">
</div>
<div class="form-group">
	<label for="ClassificationCategory">Classification Category</label>
	<input type=text class=form-control id=ClassificationCategory Name=ClassificationCategory disabled value="Unclassified">
</div>
<div class="form-group">
	<label for="EaseOfResolution">Ease of Resolution</label>
	<select class=form-control id=EaseOfResolution name=EaseOfResolution>
		<option value=0>Undetermined</option>
		<option value=1>Trivial</option>
		<option value=2>Simple</option>
		<option value=3>Moderate</option>
		<option value=4>Difficult</option>
		<option value=5>Infeasable</option>
	</select>
</div>
<div class="form-group">
	<label for="Description">Description</label>
	<textarea class=form-control id=Description Name=Description cols=40 rows=5></textarea>
</div>
<div class="form-group">
	<label for="References">References</label>
	<textarea class=form-control id=References Name=References cols=40 rows=5></textarea>
</div>
<div class="form-group">
	<label for="Resolution">Resolution</label>
	<textarea class=form-control id=Resolution Name=Resolution cols=40 rows=5></textarea>
</div>
<div class="form-group">
	<label for="Policy">Policy Violations</label>
	<textarea class=form-control id=Policy Name=Policy cols=40 rows=5></textarea>
</div>
<div class="form-check">
<label><b>Impact</b></label><br>
	<input type=checkbox class="form-check-input" id=Confidentiality Name=Confidentiality value=1>
	<label for="Confidentiality" class="form-check-label">Confidentiality</label>
</div>
<div class="form-check">
	<input type=checkbox class="form-check-input" id=Integrity Name=Integrity value=1>
	<label for="Integrity" class="form-check-label">Integrity</label>
</div>
<div class="form-check">
	<input type=checkbox class="form-check-input" id=Availability Name=Availability value=1>
	<label for="Availability" class="form-check-label">Availability</label>
</div>
<div class="form-group">
	<label for="Data">Data</label>
	<textarea class=form-control id=Data Name=Data cols=40 rows=5></textarea>
</div>
@if($Service->website==1)
	
<div class="form-group">
	<label for="Request">Request</label>
	<textarea class=form-control id=Request Name=Request cols=40 rows=5></textarea>
</div>
<div class="form-group">
	<label for="Data">Response</label>
	<textarea class=form-control id=Response Name=Response cols=40 rows=5></textarea>
</div>
<div class="form-group">
	<label for="Method">Method</label>
	<input type=text class=form-control id=Method Name=Method />
</div>
<div class="form-group">
	<label for="ParamNames">Param Names</label>
	<input type=text class=form-control id=ParamNames Name=ParamNames />
</div>
<div class="form-group">
	<label for="ParamValues">Param Values</label>
	<input type=text class=form-control id=ParamValues Name=ParamValues />
</div>
<div class="form-group">
	<label for="Path">Path</label>
	<input type=text class=form-control id=Path Name=Path />
</div>
<div class="form-group">
	<label for="StatusCode">Status Code</label>
	<input type=text class=form-control id=StatusCode Name=StatusCode />
</div>
<div class="form-group">
	<label for="Query">Query</label>
	<input type=text class=form-control id=Query Name=Query />
</div>
<div class="form-group">
	<label for="Website">Website</label>
	<input type=text class=form-control id=Website Name=Website />
</div>
@endif