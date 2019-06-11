<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

//get all data for user and app load
Route::get('/user', 'DepartmentController@getUser');
//generating dummy content
Route::get('/staffgenerate', 'DepartmentController@populateStaff');
Route::get('/patientsgenerate', 'DepartmentController@populatePatients');
//get patient
Route::get('/getpatient/{cardnumber}', 'DepartmentController@getPatient');
// Get all vistis
Route::get('/visits', 'DepartmentController@getVisits');

//post requests

//Register check in 
Route::post('/book', 'DepartmentController@bookPatient');


