<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Workspace;
use App\Host;
use App\Hostfile;
use App\Service;
use App\Credential;
use App\Vulnerabilitytemplate;
use App\Vulnerability;
use App\Http\Requests;
use App\Upload;
use App\Vulnfile;
use App\Workfile;
use App\Methodology;
use App\Workspacemethodology;
use App\Manswer;
use App\Mfile;
use Illuminate\Http\Request;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function Dashboard()
    {
		$Workspace=Workspace::where("Active",1)->first();
        return view('dashboard',["Workspace"=>$Workspace]);
    }
	public function Charts(){
		return view('Charts');
	}
	public function WordCloud(){
		return view('WordCloud');
	}
	public function D3(){
		return view('d3');
	}
    public function Hosts()
    {
		$Search="";
		$NoVulns="checked";
		$Information="checked";
		$Low="checked";
		$Medium="checked";
		$High="checked";
		$Critical="checked";
		if(isset($_GET["Search"])){
			$Search=$_GET["Search"];
			if(!isset($_GET["NoVulns"])){
				$NoVulns="";
			}
			if(!isset($_GET["Information"])){
				$Information="";
			}
			if(!isset($_GET["Low"])){
				$Low="";
			}
			if(!isset($_GET["Medium"])){
				$Medium="";
			}
			if(!isset($_GET["High"])){
				$High="";
			}
			if(!isset($_GET["Critical"])){
				$Critical="";
			}
		}
		$Workspace=Workspace::where("Active",1)->first();
		$Hosts = Host::where("name","like","%".$Search."%")->orWhere("ip","like","%".$Search."%")->get();
		$Hosts=$Hosts->where("workspace_id",$Workspace->id)->sortBy("ip");
        return view('hosts',["Workspace"=>$Workspace,"Hosts"=>$Hosts,"Search"=>$Search,"NoVulns"=>$NoVulns,"Information"=>$Information,"Low"=>$Low,"Medium"=>$Medium,"High"=>$High,"Critical"=>$Critical]);
    }
    public function NewHost()
    {
		if(isset($_POST["name"],$_POST["ip"],$_POST["mac"],$_POST["os"],$_POST["description"])){
		$Workspace=Workspace::where("Active",1)->first();
		$Host = new Host;
		$Host->name = $_POST["name"];
		$Host->ip = $_POST["ip"];
		$Host->mac = $_POST["mac"];
		$Host->os = $_POST["os"];
		$Host->description = $_POST["description"];
		$Host->workspace_id = $Workspace->id;
		$Host->save();
        return view('hosttable',["Workspace"=>$Workspace,"Hosts"=>$Workspace->host->sortBy("ip"),"NoVulns"=>$_POST["NoVulns"],"Information"=>$_POST["Information"],"Low"=>$_POST["Low"],"Medium"=>$_POST["Medium"],"High"=>$_POST["High"],"Critical"=>$_POST["Critical"]]);
		}
		else{
			return "Error";
		}
    }
	public function ViewHost($ID){
		$Workspace=Workspace::where("Active",1)->first();
		$Host = Host::where("id",$ID)->first();
		return view("viewhost",["Workspace"=>$Workspace,'Host'=>$Host]);
	}
    public function EditHost()
    {
		if(isset($_POST["id"],$_POST["name"],$_POST["ip"],$_POST["mac"],$_POST["os"],$_POST["description"])){
		$Workspace=Workspace::where("Active",1)->first();
		$Host = Host::where("id",$_POST["id"])->first();
		$Host->name = $_POST["name"];
		$Host->ip = $_POST["ip"];
		$Host->mac = $_POST["mac"];
		$Host->os = $_POST["os"];
		$Host->description = $_POST["description"];
		$Host->workspace_id = $Workspace->id;
		$Host->save();
        return view('hostinformation',["Workspace"=>$Workspace,"Host"=>$Host]);
		}
		else{
			return "Error";
		}
    }
	public function DeleteHost($ID){
		$Host = Host::where("id",$ID)->first();
		foreach($Host->service as $Service){
		foreach($Service->vulnerability as $ThisVulnerability){
			foreach($ThisVulnerability->vulnfile as $ThisFile){
			unlink($ThisFile->upload->filepath);
			$ThisFile->upload->delete();
			}
			$ThisVulnerability->delete();
		}
		$Service->delete();
		}
		
			foreach($Host->hostfile as $ThisFile){
			unlink($ThisFile->upload->filepath);
			$ThisFile->upload->delete();
			}
		$Host->delete();
		return redirect()->route('Hosts');
	}
    public function Workspace()
    {
		$Workspace=Workspace::where("Active",1)->first();
        return view('workspace',["Workspace"=>$Workspace]);
    }
    public function NewService()
    {
		if(isset($_POST["host"],$_POST["name"],$_POST["port"],$_POST["version"],$_POST["status"],$_POST["web"],$_POST["description"])){
		$Workspace=Workspace::where("Active",1)->first();
		$Host = Host::where("id",$_POST['host'])->first();
		$Service = new Service;
		$Service->name = $_POST["name"];
		$Service->port = $_POST["port"];
		$Service->protocol = $_POST["protocol"];
		$Service->version = $_POST["version"];
		$Service->status = $_POST["status"];
		$Service->website = $_POST["web"];
		$Service->description = $_POST["description"];
		$Service->host_id = $Host->id;
		$Service->save();
        return view('servicetable',["Host"=>$Host]);
		}
		else{
			return "Error";
		}
    }
	
    public function ViewService($ServiceID)
    {
		$Workspace=Workspace::where("Active",1)->first();
		$Service = Service::where("id",$ServiceID)->first();
		$Host = $Service->host;
        return view('viewservice',["Workspace"=>$Workspace,"Host"=>$Host,"Service"=>$Service]);
    }
    public function NewCredential()
    {
		if(isset($_POST["host"],$_POST["username"],$_POST["password"],$_POST["description"])){
		$Workspace=Workspace::where("Active",1)->first();
		$Host = Host::where("id",$_POST['host'])->first();
		$cipher="AES-256-CFB";
		$ivlen = openssl_cipher_iv_length($cipher);
		$iv = openssl_random_pseudo_bytes($ivlen);
		$Credential = new Credential;
		$Credential->username = $_POST["username"];
		$Credential->description = $_POST["description"];
		$Credential->salt = base64_encode($iv);
		$EncryptionKey = hash('sha256',env('DB_ENCRYPTIONPASSWORD'));
		$Credential->password = openssl_encrypt($_POST["password"],$cipher,$EncryptionKey,$options=0,$iv);
		$Credential->host_id = $Host->id;
		$Credential->save();
        return view('credentialtable',["Host"=>$Host]);
		}
		else{
			return "Error";
		}
    }
    public function EditService()
    {
		if(isset($_POST["id"],$_POST["name"],$_POST["port"],$_POST["protocol"],$_POST["version"],$_POST["status"],$_POST["website"],$_POST["description"])){
		$Workspace=Workspace::where("Active",1)->first();
		$Service=Service::where("id",$_POST["id"])->first();
		$Host = $Service->host;
		$Service->name = $_POST["name"];
		$Service->port = $_POST["port"];
		$Service->protocol = $_POST["protocol"];
		$Service->version = $_POST["version"];
		$Service->status = $_POST["status"];
		$Service->website = $_POST["website"];
		$Service->description = $_POST["description"];
		$Service->host_id = $Host->id;
		$Service->save();
        return view('serviceinformation',["Workspace"=>$Workspace,"Host"=>$Host,"Service"=>$Service]);
		}
		else{
			return "Error";
		}
    }
	public function DeleteService($ID){
		$Service = Service::where("id",$ID)->first();
		$Host = $Service->host;
		foreach($Service->vulnerability as $ThisVulnerability){
			foreach($ThisVulnerability->vulnfile as $ThisFile){
			unlink($ThisFile->upload->filepath);
			$ThisFile->upload->delete();
			}
			$ThisVulnerability->delete();
		}
		$Service->delete();
		return redirect('../ViewHost/'.$Host->id);
	}
	public function AddVulnerability($ID){
		$Workspace=Workspace::where("Active",1)->first();		
		$Service = Service::where("id",$ID)->first();
		return view('addvulnerability',["Workspace"=>$Workspace,"Service"=>$Service]);
	}
	public function UpdateCVE(){
		$Workspace=Workspace::where("Active",1)->first();
		$MaxCVE = Vulnerabilitytemplate::where('id','>',0)->max('cve');
		if($MaxCVE==""){
			$MaxCVE="CVE-2002-0";
		}
		return view('UpdateCVE',["Workspace"=>$Workspace,"MaxCVE"=>$MaxCVE]);
	}
	public function CVELookup(){
		if(isset($_POST["query"])){
			$Results=json_decode("{}");
			$Results->Entries=[];
			$QueryResults = Vulnerabilitytemplate::where("name","like","%".$_POST["query"]."%")->orWhere("description","like","%".$_POST["query"]."%")->take(10)->get();
			$i=0;
			foreach($QueryResults as $ThisResult){
				$Results->Entries[$i]=json_decode("{}");
				$Results->Entries[$i]->Name=$ThisResult->name;
				$Results->Entries[$i]->Description=$ThisResult->description;
				$Results->Entries[$i]->CVE=$ThisResult->cve;
				$Results->Entries[$i]->Classification=$ThisResult->classification;
				$Results->Entries[$i]->References=$ThisResult->references;
				$Results->Entries[$i]->Confidentiality=$ThisResult->confidentiality;
				$Results->Entries[$i]->Integrity=$ThisResult->integrity;
				$Results->Entries[$i]->Availability=$ThisResult->availability;
				$i++;
			}
			return json_encode($Results);
		}
		else{
			return "Error";
		}
	}
	public function SaveVulnerability(){
		if(isset($_POST["Name"],$_POST["service"])){
			$Vulnerability = new Vulnerability;
			$Vulnerability->service_id=$_POST["service"];
			$Vulnerability->name=$_POST["Name"];
			$Vulnerability->description=$_POST["Description"];
			$Vulnerability->cve=$_POST["CVE"];
			$Vulnerability->classification=$_POST["Classification"];
			$Vulnerability->easeofresolution=$_POST["EaseOfResolution"];
			$Vulnerability->reference=$_POST["References"];
			$Vulnerability->resolution=$_POST["Resolution"];
			$Vulnerability->policyviolation=$_POST["Policy"];
			if(isset($_POST["Confidentiality"])&&$_POST["Confidentiality"]==1){
				$Vulnerability->confidentiality=$_POST["Confidentiality"];
			}
			else{
				$Vulnerability->confidentiality=0;
			}
			if(isset($_POST["Integrity"])&&$_POST["Integrity"]==1){
				$Vulnerability->integrity=$_POST["Integrity"];
			}
			else{
				$Vulnerability->integrity=0;
			}
			if(isset($_POST["Availability"])&&$_POST["Availability"]==1){
				$Vulnerability->availability=$_POST["Availability"];
			}
			else{
				$Vulnerability->availability=0;
			}
			$Vulnerability->data=$_POST["Data"];
			if(isset($_POST["Request"])){
				$Vulnerability->request=$_POST["Request"];
			}
			else{
				$Vulnerability->request="";
			}
			if(isset($_POST["Response"])){
				$Vulnerability->response=$_POST["Response"];
			}
			else{
				$Vulnerability->response="";
			}
			if(isset($_POST["Method"])){
				$Vulnerability->method=$_POST["Method"];
			}
			else{
				$Vulnerability->method="";
			}
			if(isset($_POST["ParamNames"])){
				$Vulnerability->paramname=$_POST["ParamNames"];
			}
			else{
				$Vulnerability->paramname="";
			}
			if(isset($_POST["ParamValues"])){
				$Vulnerability->params=$_POST["ParamValues"];
			}
			else{
				$Vulnerability->params="";
			}
			if(isset($_POST["Path"])){
				$Vulnerability->path=$_POST["Path"];
			}
			else{
				$Vulnerability->path="";
			}
			if(isset($_POST["StatusCode"])){
				$Vulnerability->statuscode=$_POST["StatusCode"];
			}
			else{
				$Vulnerability->statuscode="";
			}
			if(isset($_POST["Query"])){
				$Vulnerability->query=$_POST["Query"];
			}
			else{
				$Vulnerability->query="";
			}
			if(isset($_POST["Website"])){
				$Vulnerability->website=$_POST["Website"];
			}
			else{
				$Vulnerability->website="";
			}
			$Vulnerability->save();
			
		return redirect("../ViewService/".$_POST["service"]);
		}
	}
	public function UpdateCVEAjax(){
		$file_url = 'https://nvd.nist.gov/feeds/json/cve/1.0/nvdcve-1.0-'.$_POST["year"].'.json.gz';
		$destination_path = "../storage/app/public/CVE".$_POST["year"].".js.gs";

		$fp = fopen($destination_path, "w+");

		$ch = curl_init($file_url);
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);
		curl_exec($ch);
		$st_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		fclose($fp);

		if($st_code == 200){
			echo filesize($destination_path);
			
			$file_name = $destination_path;

			// Raising this value may increase performance
			$buffer_size = 4096; // read 4kb at a time
			$out_file_name = "../storage/app/public/CVE".$_POST["year"].".js";

			// Open our files (in binary mode)
			$file = gzopen($file_name, 'rb');
			$out_file = fopen($out_file_name, 'wb');
			// Keep repeating until the end of the input file
			while(!gzeof($file)) {
				// Read buffer-size bytes
				// Both fwrite and gzread and binary-safe
				fwrite($out_file, gzread($file, $buffer_size));
			}
			// Files are done, close files
			fclose($out_file);
			gzclose($file);
			$JSONText = file_get_contents($out_file_name);
			try{
			$JSON = json_decode($JSONText);
			}
			catch (exception $d){
				echo $d;
			}
			foreach($JSON->CVE_Items as $CVEItem){
				$Vulnerabilitytemplatetest = Vulnerabilitytemplate::where("cve",$CVEItem->cve->CVE_data_meta->ID)->count();
				if($Vulnerabilitytemplatetest==0){
					$Vulnerabilitytemplate=new Vulnerabilitytemplate;
					$Vulnerabilitytemplate->name = $CVEItem->cve->CVE_data_meta->ID;
					$Vulnerabilitytemplate->cve=$CVEItem->cve->CVE_data_meta->ID;
					$Vulnerabilitytemplate->description=$CVEItem->cve->description->description_data[0]->value;
					if(isset($CVEItem->impact->baseMetricV3)){
					$Vulnerabilitytemplate->classification=$CVEItem->impact->baseMetricV3->cvssV3->baseScore;
					$Vulnerabilitytemplate->confidentiality=($CVEItem->impact->baseMetricV3->cvssV3->confidentialityImpact=="NONE")?0:1;
					$Vulnerabilitytemplate->integrity=($CVEItem->impact->baseMetricV3->cvssV3->integrityImpact=="NONE")?0:1;
					$Vulnerabilitytemplate->availability=($CVEItem->impact->baseMetricV3->cvssV3->availabilityImpact=="NONE")?0:1;
					}
					elseif(isset($CVEItem->impact->baseMetricV2)){
					$Vulnerabilitytemplate->classification=$CVEItem->impact->baseMetricV2->cvssV2->baseScore;
					$Vulnerabilitytemplate->confidentiality=($CVEItem->impact->baseMetricV2->cvssV2->confidentialityImpact=="NONE")?0:1;
					$Vulnerabilitytemplate->integrity=($CVEItem->impact->baseMetricV2->cvssV2->integrityImpact=="NONE")?0:1;
					$Vulnerabilitytemplate->availability=($CVEItem->impact->baseMetricV2->cvssV2->availabilityImpact=="NONE")?0:1;
						
					}
					else{
						$Vulnerabilitytemplate->classification=0;
						$Vulnerabilitytemplate->confidentiality=0;
						$Vulnerabilitytemplate->integrity=0;
						$Vulnerabilitytemplate->availability=0;
					}
					$References="";
					foreach($CVEItem->cve->references->reference_data as $ThisReference){
						$References.=$ThisReference->url;
						$References.="\n";
					}
					$Vulnerabilitytemplate->references=$References;
					$Vulnerabilitytemplate->save();
				}
			}
		}
		else{
			echo $file_url;
		}
		return "Done";
	}
	
	public function ViewVulnerability($ID){
		$Workspace=Workspace::where("Active",1)->first();		
		$Vulnerability = Vulnerability::where("id",$ID)->first();
		return view('viewvulnerability',["Workspace"=>$Workspace,"Vulnerability"=>$Vulnerability]);
	}
	public function EditVulnerability(){
		if(isset($_POST["Name"],$_POST["id"])){
			$Vulnerability = Vulnerability::where("id",$_POST["id"])->first();
			$Vulnerability->name=$_POST["Name"];
			$Vulnerability->description=$_POST["Description"];
			$Vulnerability->cve=$_POST["CVE"];
			$Vulnerability->classification=$_POST["Classification"];
			$Vulnerability->easeofresolution=$_POST["EaseOfResolution"];
			$Vulnerability->reference=$_POST["References"];
			$Vulnerability->resolution=$_POST["Resolution"];
			$Vulnerability->policyviolation=$_POST["Policy"];
			if(isset($_POST["Confidentiality"])&&$_POST["Confidentiality"]==1){
				$Vulnerability->confidentiality=$_POST["Confidentiality"];
			}
			else{
				$Vulnerability->confidentiality=0;
			}
			if(isset($_POST["Integrity"])&&$_POST["Integrity"]==1){
				$Vulnerability->integrity=$_POST["Integrity"];
			}
			else{
				$Vulnerability->integrity=0;
			}
			if(isset($_POST["Availability"])&&$_POST["Availability"]==1){
				$Vulnerability->availability=$_POST["Availability"];
			}
			else{
				$Vulnerability->availability=0;
			}
			$Vulnerability->data=$_POST["Data"];
			if(isset($_POST["Request"])){
				$Vulnerability->request=$_POST["Request"];
			}
			else{
				$Vulnerability->request="";
			}
			if(isset($_POST["Response"])){
				$Vulnerability->response=$_POST["Response"];
			}
			else{
				$Vulnerability->response="";
			}
			if(isset($_POST["Method"])){
				$Vulnerability->method=$_POST["Method"];
			}
			else{
				$Vulnerability->method="";
			}
			if(isset($_POST["ParamNames"])){
				$Vulnerability->paramname=$_POST["ParamNames"];
			}
			else{
				$Vulnerability->paramname="";
			}
			if(isset($_POST["ParamValues"])){
				$Vulnerability->params=$_POST["ParamValues"];
			}
			else{
				$Vulnerability->params="";
			}
			if(isset($_POST["Path"])){
				$Vulnerability->path=$_POST["Path"];
			}
			else{
				$Vulnerability->path="";
			}
			if(isset($_POST["StatusCode"])){
				$Vulnerability->statuscode=$_POST["StatusCode"];
			}
			else{
				$Vulnerability->statuscode="";
			}
			if(isset($_POST["Query"])){
				$Vulnerability->query=$_POST["Query"];
			}
			else{
				$Vulnerability->query="";
			}
			if(isset($_POST["Website"])){
				$Vulnerability->website=$_POST["Website"];
			}
			else{
				$Vulnerability->website="";
			}
			$Vulnerability->save();
			
		return redirect("../ViewService/".$Vulnerability->service->id);
		}
	}
	public function ViewCredential($ID){
		$Workspace=Workspace::where("Active",1)->first();
		$Credential = Credential::where("id",$ID)->first();
		$cipher="AES-256-CFB";
		$ivlen = openssl_cipher_iv_length($cipher);
		$iv = base64_decode($Credential->salt);
		$EncryptionKey = hash('sha256',env('DB_ENCRYPTIONPASSWORD'));
		$Password = openssl_decrypt($Credential->password,$cipher,$EncryptionKey,$options=0,$iv);
		return view('viewcredential',["Workspace"=>$Workspace,"Credential"=>$Credential,"Password"=>$Password]);
	}
    public function SaveCredential()
    {
		if(isset($_POST["id"],$_POST["username"],$_POST["password"],$_POST["description"])){
		$Workspace=Workspace::where("Active",1)->first();
		$Credential=Credential::where("id",$_POST["id"])->first();
		$cipher="AES-256-CFB";
		$ivlen = openssl_cipher_iv_length($cipher);
		$iv = openssl_random_pseudo_bytes($ivlen);
		$Credential->username = $_POST["username"];
		$Credential->description = $_POST["description"];
		$Credential->salt = base64_encode($iv);
		$EncryptionKey = hash('sha256',env('DB_ENCRYPTIONPASSWORD'));
		$Credential->password = openssl_encrypt($_POST["password"],$cipher,$EncryptionKey,$options=0,$iv);
		$Credential->save();
		return redirect("../ViewHost/".$Credential->host->id);
		}
		else{
			return "Error";
		}
    }
    public function UploadVulnerabilityFile(Request $request)
    {
		if(isset($_POST["id"])){
		$RandomString = str_replace("/","",$_POST["id"].base64_encode(openssl_random_pseudo_bytes(16)));
		$Workspace=Workspace::where("Active",1)->first();
		$Vulnerability=Vulnerability::where("id",$_POST["id"])->first();
		$file = $request->file('file');
		$file->move("../storage/app/public/",$RandomString.$file->getClientOriginalName());
		$Upload = new Upload;
		$Upload->name=$file->getClientOriginalName();
		$Upload->filepath="../storage/app/public/".$RandomString.$file->getClientOriginalName();
		$Upload->save();
		$Vulnfile = new Vulnfile;
		$Vulnfile->vulnerability_id=$_POST["id"];
		$Vulnfile->upload_id=$Upload->id;
		$Vulnfile->save();
		return "{\"id\":\"".$Vulnfile->id."\", \"name\":\"".$Upload->name."\", \"uploadid\": \"".$Upload->id."\"}";
		}
		else{
			return "Error";
		}
    }
	public function DownloadFile($id)
	{
		$Download=Upload::where("id",$id)->first();
		return response()->download($Download->filepath,$Download->name);
	}
	public function DeleteVulnerabilityFile(){
		if(isset($_POST["id"])){
			$Vulnfile=Vulnfile::where("id",$_POST["id"])->first();
			unlink($Vulnfile->upload->filepath);
			$Vulnfile->upload->delete();
			return "Success";
			
		}
	}
	public function DeleteVulnerability($ID){
		$Vulnerability=Vulnerability::where("id",$ID)->first();
		$Service=$Vulnerability->service->id;
		foreach($Vulnerability->vulnfile as $ThisFile){
			unlink($ThisFile->upload->filepath);
			$ThisFile->upload->delete();
		}
		$Vulnerability->delete();
		return redirect("../ViewService/".$Service);
	}
	public function DeleteCredential($ID){
		$Credential=Credential::where("id",$ID)->first();
		$Host=$Credential->host->id;
		$Credential->delete();
		return redirect("../ViewHost/".$Host);
	}
    public function UploadHostFile(Request $request)
    {
		if(isset($_POST["id"])){
		$RandomString = str_replace("/","",$_POST["id"].base64_encode(openssl_random_pseudo_bytes(16)));
		$Workspace=Workspace::where("Active",1)->first();
		$Host=Host::where("id",$_POST["id"])->first();
		$file = $request->file('file');
		$file->move("../storage/app/public/",$RandomString.$file->getClientOriginalName());
		$Upload = new Upload;
		$Upload->name=$file->getClientOriginalName();
		$Upload->filepath="../storage/app/public/".$RandomString.$file->getClientOriginalName();
		$Upload->save();
		$Hostfile = new Hostfile;
		$Hostfile->host_id=$_POST["id"];
		$Hostfile->upload_id=$Upload->id;
		$Hostfile->save();
		return "{\"id\":\"".$Hostfile->id."\", \"name\":\"".$Upload->name."\", \"uploadid\": \"".$Upload->id."\"}";
		}
		else{
			return "Error";
		}
    }
	public function DeleteHostFile(){
		if(isset($_POST["id"])){
			$Hostfile=Hostfile::where("id",$_POST["id"])->first();
			unlink($Hostfile->upload->filepath);
			$Hostfile->upload->delete();
			return "Success";
			
		}
	}
	public function ViewWorkspace($ID){
		$Workspace=Workspace::where("Active",1)->first();
		$Workspace2=Workspace::where("id",$ID)->first();
		return view('viewworkspace',["Workspace"=>$Workspace,"Workspace2"=>$Workspace2]);
	}
    public function UploadWorkspaceFile(Request $request)
    {
		if(isset($_POST["id"])){
		$RandomString = str_replace("/","",$_POST["id"].base64_encode(openssl_random_pseudo_bytes(16)));
		$Workspace=Workspace::where("id",$_POST["id"])->first();
		$file = $request->file('file');
		$file->move("../storage/app/public/",$RandomString.$file->getClientOriginalName());
		$Upload = new Upload;
		$Upload->name=$file->getClientOriginalName();
		$Upload->filepath="../storage/app/public/".$RandomString.$file->getClientOriginalName();
		$Upload->save();
		$Workfile = new Workfile;
		$Workfile->workspace_id=$_POST["id"];
		$Workfile->upload_id=$Upload->id;
		$Workfile->save();
		if($_POST["plugin"]=="None"){
			
		}
		elseif($_POST["plugin"]=="Nmap"){
			$XML = simplexml_load_file($Upload->filepath);
			foreach($XML->host as $XMLHost){
				$IP="";
				$Mac="";
				foreach($XMLHost->address as $Address){
					$AddressType=$Address->attributes()->addrtype;
					if($AddressType=="ipv4"){
						$IP=$Address->attributes()->addr;
					}
					elseif($AddressType=="mac"){
						$Mac=$Address->attributes()->addr;
					}
				}
				$Name=(isset($XMLHost->hostnames)&&isset($XMLHost->hostnames[0]->hostname)&&isset($XMLHost->hostnames[0]->hostname[0]->attributes()->name))?$XMLHost->hostnames[0]->hostname[0]->attributes()->name:"";
				$OS=(isset($XMLHost->os)&&isset($XMLHost->os[0]->osmatch)&&isset($XMLHost->os[0]->osmatch[0]->attributes()->name))?$XMLHost->os[0]->osmatch[0]->attributes()->name:"";
				$Description="Found in Nmap scan";
				if($IP!=""){
					$HostCount = Host::where("ip",$IP)->where("workspace_id",$_POST["id"])->count();
					if($HostCount>0){
						$Host=Host::where("ip",$IP)->first();
						$Host->name=($Host->name=="")?$Name:$Host->name;
						$Host->mac=($Host->mac=="")?$Mac:$Host->mac;
						$Host->OS=($Host->OS=="")?$OS:$Host->OS;
						$Host->description=($Host->description=="")?$Description:$Host->description;
						$Host->save();
					}
					else{
						$Host=new Host;
						$Host->workspace_id=$_POST["id"];
						$Host->ip=$IP;
						$Host->name=$Name;
						$Host->mac=$Mac;
						$Host->OS=$OS;
						$Host->description=$Description;
						$Host->save();
					}
					$Host=Host::where("ip",$IP)->first();
					if(isset($XMLHost->ports)){
						foreach($XMLHost->ports[0]->port as $XMLPort){
							$ServiceName = (isset($XMLPort->service))?$XMLPort->service[0]->attributes()->name:"";
							$Port = (isset($XMLPort->attributes()->portid))?$XMLPort->attributes()->portid:"";
							$Protocol = (isset($XMLPort->attributes()->protocol))?$XMLPort->attributes()->protocol:"";
							$Version = (isset($XMLPort->service)&&isset($XMLPort->service[0]->attributes()->product))?$XMLPort->service[0]->attributes()->product:"";
							$Status = (isset($XMLPort->state[0]->attributes()->state))?$XMLPort->state[0]->attributes()->state:"";
							$Website = ($ServiceName=="http"||$ServiceName=="https")?1:0;
							$DescriptionPort = "Found in Nmap scan";
							if($Port!=""){
								$PortCount=Service::where("host_id",$Host->id)->where("port",$Port)->where("protocol",$Protocol)->count();
								if($PortCount>0){
									$Service=Service::where("host_id",$Host->id)->where("port",$Port)->where("protocol",$Protocol)->first();
									$Service->name=($Service->name=="")?$ServiceName:$Service->name;
									$Service->version=($Service->version=="")?$Version:$Service->version;
									$Service->status=($Service->status=="")?$Status:$Service->status;
									$Service->website=($Service->website==0)?$Website:$Service->website;
									$Service->description=($Service->description=="")?$DescriptionPort:$Service->description;
									$Service->save();
								}
								else{
									$Service=new Service;
									$Service->name=$ServiceName;
									$Service->version=$Version;
									$Service->status=$Status;
									$Service->website=$Website;
									$Service->description=$DescriptionPort;
									$Service->port=$Port;
									$Service->protocol=$Protocol;
									$Service->host_id=$Host->id;
									$Service->save();
								}
							}
						}
					}
				}
			}
		}
		elseif($_POST["plugin"]=="Nikto"){
			$XML = simplexml_load_file($Upload->filepath);
			$NiktoScan=$XML->niktoscan;
				if(isset($NiktoScan->scandetails)){
					$IP=$NiktoScan->scandetails[0]->attributes()->targetip;
					$HostName=$NiktoScan->scandetails[0]->attributes()->targethostname;
					$Port=$NiktoScan->scandetails[0]->attributes()->targetport;
					$Banner=$NiktoScan->scandetails[0]->attributes()->targetbanner;
					$Sitename=$NiktoScan->scandetails[0]->attributes()->sitename;
					$Description="\r\n\r\nBanner: ".$Banner."\r\nSite Name".$Sitename;
					$HTTP=explode(":",$Sitename)[0];
					$HostCount = Host::where("workspace_id",$_POST["id"])->where("ip",$IP)->count();
					if($HostCount>0){
						$Host=Host::where("workspace_id",$_POST["id"])->where("ip",$IP)->first();
						$Host->name=($Host->name=="")?$HostName:$Host->name;
						$Host->Description.=$Description;
						$Host->save();
					}
					else{
						$Host=new Host;
						$Host->workspace_id=$_POST["id"];
						$Host->name=$HostName;
						$Host->ip=$IP;
						$Host->mac="";
						$Host->OS="";
						$Host->Description=$Description;
						$Host->save();
					}
					$Host=Host::where("workspace_id",$_POST["id"])->where("ip",$IP)->first();
					$PortCount=Service::where("host_id",$Host->id)->where("port",$Port)->count();
					if($PortCount>0){
						$Service=Service::where("host_id",$Host->id)->where("port",$Port)->first();
						$Service->name=($Service->name=="")?$HTTP:$Service->name;
						$Service->version=($Service->version=="")?$Banner:$Service->version;
						$Service->website=1;
						$Service->save();
					}
					else{
						$Service = new Service;
						$Service->host_id=$Host->id;
						$Service->name=$HTTP;
						$Service->description="Found by Nikto";
						$Service->port=$Port;
						$Service->protocol="tcp";
						$Service->version=$Banner;
						$Service->status="open";
						$Service->website=1;
						$Service->save();
					}
					$Service=Service::where("host_id",$Host->id)->where("port",$Port)->first();
					foreach($NiktoScan->scandetails[0]->item as $NiktoVuln){
						$OSVDBID = $NiktoVuln->attributes()->osvdbid;
						$Name="OSVB-".$OSVDBID;
						$Classification=0;
						$easeofresolution=0;
						$Description=$NiktoVuln->description;
						$References="";
						$cve="";
						$resolution="";
						$policyviolation="";
						$availability=0;
						$confidentiality=0;
						$integrity=0;
						$data="";
						$request="";
						$response="";
						$method=$NiktoVuln->attributes()->method;
						$paramname="";
						$params="";
						$path=$NiktoVuln->uri;
						$statuscode="";
						$query="";
						$website=$Sitename;
						$Vulnerability = new Vulnerability;
						$Vulnerability->service_id=$Service->id;
						$Vulnerability->classification=$Classification;
						$Vulnerability->easeofresolution=$easeofresolution;
						$Vulnerability->name=$Name;
						$Vulnerability->description=$Description;
						$Vulnerability->reference=$References;
						$Vulnerability->cve=$cve;
						$Vulnerability->resolution=$resolution;
						$Vulnerability->policyviolation=$policyviolation;
						$Vulnerability->availability=$availability;
						$Vulnerability->confidentiality=$confidentiality;
						$Vulnerability->integrity=$integrity;
						$Vulnerability->data=$data;
						$Vulnerability->request=$request;
						$Vulnerability->response=$response;
						$Vulnerability->method=$method;
						$Vulnerability->paramname=$paramname;
						$Vulnerability->params=$params;
						$Vulnerability->path=$path;
						$Vulnerability->statuscode=$statuscode;
						$Vulnerability->query=$query;
						$Vulnerability->website=$website;
						$Vulnerability->save();
					}
				}
		}
		elseif($_POST["plugin"]=="OpenVAS"){
			$XML = simplexml_load_file($Upload->filepath);
			foreach($XML->report->results->result as $ThisResult){
				$Name=$ThisResult->name;
				$Host=$ThisResult->host;
				$Port=explode("/",$ThisResult->port)[0];
				$Protocol=explode("/",$ThisResult->port)[1];
				$Classification=$ThisResult->nvt->cvss_base;
				$CVE=$ThisResult->nvt->cve;
				if(count(explode("|",$ThisResult->nvt->tags))>3){
					$Description=explode("|",$ThisResult->nvt->tags)[3]."\n\n".$ThisResult->description;
				}
				else{
					$Description=$ThisResult->description;
					
				}
				$Resolution=explode("|",$ThisResult->nvt->tags)[2];
				$HostCount = Host::where("workspace_id",$_POST["id"])->where("ip",$Host)->count();
				if($HostCount==0){
					$ThisHost= new Host;
					$ThisHost->workspace_id=$_POST["id"];
					$ThisHost->ip=$Host;
					$ThisHost->name="";
					$ThisHost->mac="";
					$ThisHost->description="Found in OpenVAS scan";
					$ThisHost->OS="";
					$ThisHost->save();
				}
				$ThisHost = Host::where("workspace_id",$_POST["id"])->where("ip",$Host)->first();
				$ServiceCount=$ThisHost->service->where("port",$Port)->where("protocol",$Protocol)->count();
				if($ServiceCount==0){
					$ThisService=new Service;
					$ThisService->host_id=$ThisHost->id;
					$ThisService->port=$Port;
					$ThisService->protocol=$Protocol;
					$ThisService->name="";
					$ThisService->version="";
					$ThisService->status="";
					$ThisService->description="";
					$ThisService->website=0;
					$ThisService->save();
				}
				$ThisService=Service::where("host_id",$ThisHost->id)->where("port",$Port)->where("protocol",$Protocol)->first();
						$Vulnerability = new Vulnerability;
						$Vulnerability->service_id=$ThisService->id;
						$Vulnerability->classification=$Classification;
						$Vulnerability->easeofresolution=0;
						$Vulnerability->name=$Name;
						$Vulnerability->description=$Description;
						$Vulnerability->reference="";
						$Vulnerability->cve=$CVE;
						$Vulnerability->resolution=$Resolution;
						$Vulnerability->policyviolation="";
						$Vulnerability->availability=0;
						$Vulnerability->confidentiality=0;
						$Vulnerability->integrity=0;
						$Vulnerability->data="";
						$Vulnerability->request="";
						$Vulnerability->response="";
						$Vulnerability->method="";
						$Vulnerability->paramname="";
						$Vulnerability->params="";
						$Vulnerability->path="";
						$Vulnerability->statuscode="";
						$Vulnerability->query="";
						$Vulnerability->website="";
						$Vulnerability->save();
			}
			foreach($XML->report[0]->host as $ThisHost){
				$HostCount = Host::where("workspace_id",$_POST["id"])->where("ip",$ThisHost->ip)->count();
				$OS="";
			foreach($ThisHost->detail as $ThisDetail){
				if($ThisDetail->name=="best_os_txt"){
					$OS=$ThisDetail->value;
				}
			}
				if($HostCount==0){
					$Host=new Host;
					$Host->workspace_id=$_POST["id"];
					$Host->ip=$ThisHost->ip;
					$Host->OS=$OS;
					$Host->name="";
					$Host->mac="";
					$Host->description="Found in OpenVAS scan";
					$Host->save();
				}
				else{
					$Host=Host::where("workspace_id",$_POST["id"])->where("ip",$ThisHost->ip)->first();
					if($Host->OS=""){
					$Host->OS=$OS;
					}
				}
				
			}
		}
		return "{\"id\":\"".$Workfile->id."\", \"name\":\"".$Upload->name."\", \"uploadid\": \"".$Upload->id."\"}";
		}
		else{
			return "Error";
		}
    }
	public function DeleteWorkspaceFile(){
		if(isset($_POST["id"])){
			$Workfile=Workfile::where("id",$_POST["id"])->first();
			unlink($Workfile->upload->filepath);
			$Workfile->upload->delete();
			return "Success";
			
		}
	}
	public function SaveWorkspace(){
		if(isset($_POST["id"])){
			$Workspace=Workspace::where("id",$_POST["id"])->first();
			$Workspace->name=$_POST["name"];
			if($Workspace->Active==0&&isset($_POST["Active"])&&$_POST["Active"]==1){
				$Workspace2=Workspace::where("Active",1)->first();
				$Workspace2->Active=0;
				$Workspace2->save();
				$Workspace->Active=1;
			}
			$Workspace->save();
			
		}
		
		return redirect()->route('Workspace');
	}
	public function NewWorkspace(){
		if(isset($_POST["Name"])){
			$Workspace=new Workspace;
			$Workspace->name=$_POST["Name"];
			if(isset($_POST["Active"])&&$_POST["Active"]==1){
				$Workspace2=Workspace::where("Active",1)->first();
				$Workspace2->Active=0;
				$Workspace2->save();
				$Workspace->Active=1;
			}
			else{
				$Workspace->Active=0;
			}
			$Workspace->save();
			
		}
		
		return redirect()->route('Workspace');
	}
	public function DeleteWorkspace($ID){
		$Workspace2=Workspace::where("id",$ID)->first();
		if($Workspace2->Active==0){
			foreach($Workspace2->host as $Host){
				foreach($Host->service as $Service){
					foreach($Service->vulnerability as $ThisVulnerability){
						foreach($ThisVulnerability->vulnfile as $ThisFile){
							unlink($ThisFile->upload->filepath);
							$ThisFile->upload->delete();
						}
						$ThisVulnerability->delete();
					}
				}
				$Service->delete();
		
				foreach($Host->hostfile as $ThisFile){
					unlink($ThisFile->upload->filepath);
					$ThisFile->upload->delete();
				}
				$Host->delete();
			}
			$Workspace2->delete();
		}
		return redirect()->route('Workspace');
	}
	public function VulnerabilityTemplates(){
		$Workspace=Workspace::where("Active",1)->first();
		$Search="";
		if(isset($_GET["Search"])){
			$Search=$_GET["Search"];
		}
		$VulnerabilityTemplates=Vulnerabilitytemplate::where("name","like","%".$Search."%")->orWhere("description","like","%".$Search."%")->paginate(20);
		return view('vulnerabilitytemplate',["Search"=>$Search,"Workspace"=>$Workspace,"VulnerabilityTemplates"=>$VulnerabilityTemplates]);
	}
	public function NewVulnerabilityTemplate(){
		$Workspace=Workspace::where("Active",1)->first();
		return view('newvulnerabilitytemplate',["Workspace"=>$Workspace]);
	}
	public function SaveVulnerabilityTemplate(){
		$VulnerabilityTemplate = new Vulnerabilitytemplate;
		$VulnerabilityTemplate->name=$_POST["Name"];
		$VulnerabilityTemplate->cve=$_POST["CVE"];
		$VulnerabilityTemplate->description=$_POST["Description"];
		$VulnerabilityTemplate->classification=$_POST["Classification"];
		$VulnerabilityTemplate->references=$_POST["References"];
		if(isset($_POST["Confidentiality"])&&$_POST["Confidentiality"]==1){
			$VulnerabilityTemplate->confidentiality=1;
		}
		else{
			$VulnerabilityTemplate->confidentiality=0;
		}
		if(isset($_POST["Integrity"])&&$_POST["Integrity"]==1){
			$VulnerabilityTemplate->integrity=1;
		}
		else{
			$VulnerabilityTemplate->integrity=0;
		}
		if(isset($_POST["Availability"])&&$_POST["Availability"]==1){
			$VulnerabilityTemplate->availability=1;
		}
		else{
			$VulnerabilityTemplate->availability=0;
		}
		$VulnerabilityTemplate->save();
		return redirect()->route('VulnerabilityTemplates');
	}
	public function EditVulnerabilityTemplate($ID){
		$Workspace=Workspace::where("Active",1)->first();
		$VulnerabilityTemplate=Vulnerabilitytemplate::where("id",$ID)->first();
		return view('editvulnerabilitytemplate',["Workspace"=>$Workspace,"VulnerabilityTemplate"=>$VulnerabilityTemplate]);
	}
	public function SaveEditVulnerabilityTemplate(){
		$VulnerabilityTemplate = Vulnerabilitytemplate::where('id',$_POST["id"])->first();
		$VulnerabilityTemplate->name=$_POST["Name"];
		$VulnerabilityTemplate->cve=$_POST["CVE"];
		$VulnerabilityTemplate->description=$_POST["Description"];
		$VulnerabilityTemplate->classification=$_POST["Classification"];
		$VulnerabilityTemplate->references=$_POST["References"];
		if(isset($_POST["Confidentiality"])&&$_POST["Confidentiality"]==1){
			$VulnerabilityTemplate->confidentiality=1;
		}
		else{
			$VulnerabilityTemplate->confidentiality=0;
		}
		if(isset($_POST["Integrity"])&&$_POST["Integrity"]==1){
			$VulnerabilityTemplate->integrity=1;
		}
		else{
			$VulnerabilityTemplate->integrity=0;
		}
		if(isset($_POST["Availability"])&&$_POST["Availability"]==1){
			$VulnerabilityTemplate->availability=1;
		}
		else{
			$VulnerabilityTemplate->availability=0;
		}
		$VulnerabilityTemplate->save();
		return redirect()->route('VulnerabilityTemplates');
	}
	public function DeleteVulnerabilityTemplate($ID){
		$VulnerabilityTemplate=Vulnerabilitytemplate::where("id",$ID)->first();
		$VulnerabilityTemplate->delete();
		return redirect()->route('VulnerabilityTemplates');
	}
	public function Methodologies(){
		$AllMethodologies = Methodology::where("id",">",0)->get();
		$Workspace=Workspace::where("Active",1)->first();
		return view('methodologies',["Workspace"=>$Workspace,"AllMethodologies"=>$AllMethodologies]);
	}
	public function NewMethodology(){
		$Workspace=Workspace::where("Active",1)->first();
		$WorkspaceMethodology = new Workspacemethodology;
		$WorkspaceMethodology->workspace_id=$Workspace->id;
		$WorkspaceMethodology->methodology_id=$_POST["Methodology"];
		$WorkspaceMethodology->save();
		return redirect()->route('Methodologies');
	}
	public function EditMethodology($ID){
		$Workspace=Workspace::where("Active",1)->first();
		$WorkspaceMethodology=Workspacemethodology::where("id",$ID)->first();
		return view('editmethodology',["Workspace"=>$Workspace,"WorkspaceMethodology"=>$WorkspaceMethodology]);
		
	}
	public function UpdateAnswer(){
		$WorkspaceMethodology=Workspacemethodology::where("id",$_POST["workspacemethodologyid"])->first();
		$MAnswerCount = $WorkspaceMethodology->manswer->where("mquestion_id",$_POST["questionid"])->count();
		if($MAnswerCount>0){
			$MAnswer=$WorkspaceMethodology->manswer->where("mquestion_id",$_POST["questionid"])->first();
			$MAnswer->answer=$_POST["answer"];
			$MAnswer->save();
		}
		else{
			$Manswer = new Manswer;
			$Manswer->workspacemethodology_id=$_POST["workspacemethodologyid"];
			$Manswer->mquestion_id=$_POST["questionid"];
			$Manswer->answer=$_POST["answer"];
			$Manswer->completed=0;
			$Manswer->passed=0;
			$Manswer->save();
		}
		return "Success";
	}
	public function UpdateCompleted(){
		$WorkspaceMethodology=Workspacemethodology::where("id",$_POST["workspacemethodologyid"])->first();
		$MAnswerCount = $WorkspaceMethodology->manswer->where("mquestion_id",$_POST["questionid"])->count();
		if($MAnswerCount>0){
			$MAnswer=$WorkspaceMethodology->manswer->where("mquestion_id",$_POST["questionid"])->first();
			$MAnswer->completed=($_POST["completed"]=="true")?1:0;
			$MAnswer->save();
		}
		else{
			$Manswer = new Manswer;
			$Manswer->workspacemethodology_id=$_POST["workspacemethodologyid"];
			$Manswer->mquestion_id=$_POST["questionid"];
			$Manswer->answer="";
			$Manswer->completed=($_POST["completed"]=="true")?1:0;
			$Manswer->passed=0;
			$Manswer->save();
		}
		return "Success";
	}
	public function UpdatePassed(){
		$WorkspaceMethodology=Workspacemethodology::where("id",$_POST["workspacemethodologyid"])->first();
		$MAnswerCount = $WorkspaceMethodology->manswer->where("mquestion_id",$_POST["questionid"])->count();
		if($MAnswerCount>0){
			$MAnswer=$WorkspaceMethodology->manswer->where("mquestion_id",$_POST["questionid"])->first();
			$MAnswer->passed=($_POST["passed"]=="true")?1:0;
			$MAnswer->save();
		}
		else{
			$Manswer = new Manswer;
			$Manswer->workspacemethodology_id=$_POST["workspacemethodologyid"];
			$Manswer->mquestion_id=$_POST["questionid"];
			$Manswer->answer="";
			$Manswer->passed=($_POST["passed"]=="true")?1:0;
			$Manswer->completed=0;
			$Manswer->save();
		}
		return "Success";
	}
    public function UploadWorkspaceMethodologyFile(Request $request)
    {
		if(isset($_POST["workspacemethodologyid"])){
		$WorkspaceMethodology=Workspacemethodology::where("id",$_POST["workspacemethodologyid"])->first();
		$MAnswerCount = $WorkspaceMethodology->manswer->where("mquestion_id",$_POST["questionid"])->count();
		if($MAnswerCount==0){
			$Manswer = new Manswer;
			$Manswer->workspacemethodology_id=$_POST["workspacemethodologyid"];
			$Manswer->mquestion_id=$_POST["questionid"];
			$Manswer->answer="";
			$Manswer->passed=0;
			$Manswer->completed=0;
			$Manswer->save();
		}
		$Manswer=$WorkspaceMethodology->manswer->where("mquestion_id",$_POST["questionid"])->first();
		$RandomString = str_replace("/","",$Manswer->id.base64_encode(openssl_random_pseudo_bytes(16)));
		$Workspace=Workspace::where("Active",1)->first();
		$file = $request->file('file');
		$file->move("../storage/app/public/",$RandomString.$file->getClientOriginalName());
		$Upload = new Upload;
		$Upload->name=$file->getClientOriginalName();
		$Upload->filepath="../storage/app/public/".$RandomString.$file->getClientOriginalName();
		$Upload->save();
		$Mfile = new Mfile;
		$Mfile->manswer_id=$Manswer->id;
		$Mfile->upload_id=$Upload->id;
		$Mfile->save();
		return "{\"id\":\"".$Mfile->id."\", \"name\":\"".$Upload->name."\", \"uploadid\": \"".$Upload->id."\"}";
		}
		else{
			return "Error";
		}
    }
	public function DeleteWorkspaceMethodologyFile(){
		if(isset($_POST["id"])){
			$Mfile=Mfile::where("id",$_POST["id"])->first();
			unlink($Mfile->upload->filepath);
			$Mfile->upload->delete();
			return "Success";
			
		}
	}
	public function DeleteMethodology($ID){
		$WorkspaceMethodology=Workspacemethodology::where("id",$ID)->first();
		foreach($WorkspaceMethodology->manswer as $MAnswer){
			foreach($MAnswer->mfile as $MFile){
			unlink($MFile->upload->filepath);
			$MFile->upload->delete();
				
			}
			$MAnswer->delete();
		}
		$WorkspaceMethodology->delete();
		return redirect()->route('Methodologies');
	}
	public function DefaultReport(){
		$Workspace=Workspace::where("Active",1)->first();
		return view('defaultreport',["Workspace"=>$Workspace]);
	}
	public function MultiDeleteHost(){
		foreach($_POST as $Key=>$Value){
			if($Key!="_token"){
		$Host = Host::where("id",$Key)->first();
		foreach($Host->service as $Service){
		foreach($Service->vulnerability as $ThisVulnerability){
			foreach($ThisVulnerability->vulnfile as $ThisFile){
			unlink($ThisFile->upload->filepath);
			$ThisFile->upload->delete();
			}
			$ThisVulnerability->delete();
		}
		$Service->delete();
		}
		
			foreach($Host->hostfile as $ThisFile){
			unlink($ThisFile->upload->filepath);
			$ThisFile->upload->delete();
			}
		$Host->delete();
			}
		}
		return redirect()->route('Hosts');
	}
	
}
