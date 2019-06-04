<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/Dashboard', 'Controller@Dashboard')->name('Dashboard')->middleware('auth');
Route::get('/Hosts', 'Controller@Hosts')->name('Hosts')->middleware('auth');
Route::get('/Workspace', 'Controller@Workspace')->name('Workspace')->middleware('auth');
Route::post('/NewHost', 'Controller@NewHost')->name('NewHost')->middleware('auth');
Route::get('ViewHost/{HostID}','Controller@ViewHost')->middleware('auth');
Route::post('/EditHost', 'Controller@EditHost')->name('EditHost')->middleware('auth');
Route::get('DeleteHost/{HostID}','Controller@DeleteHost')->middleware('auth');
Route::post('/NewService', 'Controller@NewService')->name('NewService')->middleware('auth');
Route::get('ViewService/{ServiceID}','Controller@ViewService')->middleware('auth');
Route::post('/NewCredential', 'Controller@NewCredential')->name('NewCredential')->middleware('auth');
Route::post('/EditService', 'Controller@EditService')->name('EditService')->middleware('auth');
Route::get('DeleteService/{ServiceID}','Controller@DeleteService')->middleware('auth');
Route::get('/AddVulnerability/{ServiceID}','Controller@AddVulnerability')->middleware('auth');
Route::get('/UpdateCVE', 'Controller@UpdateCVE')->name('UpdateCVE')->middleware('auth');
Route::post('/UpdateCVEAjax', 'Controller@UpdateCVEAjax')->name('UpdateCVEAjax')->middleware('auth');
Route::post('/CVELookup', 'Controller@CVELookup')->name('CVELookup')->middleware('auth');
Route::post('/SaveVulnerability', 'Controller@SaveVulnerability')->name('SaveVulnerability')->middleware('auth');
Route::get('/ViewVulnerability/{VulnerabilityID}','Controller@ViewVulnerability')->middleware('auth');
Route::post('/EditVulnerability', 'Controller@EditVulnerability')->name('EditVulnerability')->middleware('auth');
Route::get('/ViewCredential/{CredentialID}','Controller@ViewCredential')->middleware('auth');
Route::post('/SaveCredential', 'Controller@SaveCredential')->name('SaveCredential')->middleware('auth');
Route::post('/UploadVulnerabilityFile', 'Controller@UploadVulnerabilityFile')->name('UploadVulnerabilityFile')->middleware('auth');
Route::get('/DownloadFile/{UploadID}', 'Controller@DownloadFile')->middleware('auth');
Route::post('/DeleteVulnerabilityFile', 'Controller@DeleteVulnerabilityFile')->name('DeleteVulnerabilityFile')->middleware('auth');
Route::get('/DeleteVulnerability/{VulnerabilityID}', 'Controller@DeleteVulnerability')->middleware('auth');
Route::get('/DeleteCredential/{CredentialID}', 'Controller@DeleteCredential')->middleware('auth');
Route::post('/UploadHostFile', 'Controller@UploadHostFile')->name('UploadHostFile')->middleware('auth');
Route::post('/DeleteHostFile', 'Controller@DeleteHostFile')->name('DeleteHostFile')->middleware('auth');
Route::get('/ViewWorkspace/{WorkspaceID}', 'Controller@ViewWorkspace')->middleware('auth');
Route::post('/UploadWorkspaceFile', 'Controller@UploadWorkspaceFile')->name('UploadWorkspaceFile')->middleware('auth');
Route::post('/DeleteWorkspaceFile', 'Controller@DeleteWorkspaceFile')->name('DeleteWorkspaceFile')->middleware('auth');
Route::post('/SaveWorkspace', 'Controller@SaveWorkspace')->name('SaveWorkspace')->middleware('auth');
Route::post('/NewWorkspace', 'Controller@NewWorkspace')->name('NewWorkspace')->middleware('auth');
Route::get('/DeleteWorkspace/{WorkspaceID}', 'Controller@DeleteWorkspace')->middleware('auth');
Route::get('/VulnerabilityTemplates', 'Controller@VulnerabilityTemplates')->name('VulnerabilityTemplates')->middleware('auth');
Route::get('/NewVulnerabilityTemplate', 'Controller@NewVulnerabilityTemplate')->name('NewVulnerabilityTemplate')->middleware('auth');
Route::post('/SaveVulnerabilityTemplate', 'Controller@SaveVulnerabilityTemplate')->name('SaveVulnerabilityTemplate')->middleware('auth');
Route::get('/EditVulnerabilityTemplate/{TemplateID}', 'Controller@EditVulnerabilityTemplate')->middleware('auth');
Route::post('/SaveEditVulnerabilityTemplate', 'Controller@SaveEditVulnerabilityTemplate')->name('SaveEditVulnerabilityTemplate')->middleware('auth');
Route::get('/DeleteVulnerabilityTemplate/{VulnerabilityTemplateID}', 'Controller@DeleteVulnerabilityTemplate')->middleware('auth');
Route::get('/Methodologies', 'Controller@Methodologies')->name('Methodologies')->middleware('auth');
Route::post('/NewMethodology', 'Controller@NewMethodology')->name('NewMethodology')->middleware('auth');
Route::get('/EditMethodology/{WorkspaceMethodologyID}', 'Controller@EditMethodology')->middleware('auth');
Route::post('/UpdateAnswer', 'Controller@UpdateAnswer')->name('UpdateAnswer')->middleware('auth');
Route::post('/UpdateCompleted', 'Controller@UpdateCompleted')->name('UpdateCompleted')->middleware('auth');
Route::post('/UpdatePassed', 'Controller@UpdatePassed')->name('UpdatePassed')->middleware('auth');
Route::post('/UploadWorkspaceMethodologyFile', 'Controller@UploadWorkspaceMethodologyFile')->name('UploadWorkspaceMethodologyFile')->middleware('auth');
Route::post('/DeleteWorkspaceMethodologyFile', 'Controller@DeleteWorkspaceMethodologyFile')->name('DeleteWorkspaceMethodologyFile')->middleware('auth');
Route::get('/DeleteMethodology/{WorkspaceMethodologyID}', 'Controller@DeleteMethodology')->middleware('auth');
Route::get('/loader.js', 'Controller@Charts')->middleware('auth');
Route::get('/wordcloud.js', 'Controller@WordCloud')->middleware('auth');
Route::get('/d3.js', 'Controller@D3')->middleware('auth');
Route::get('/DefaultReport', 'Controller@DefaultReport')->name('DefaultReport')->middleware('auth');