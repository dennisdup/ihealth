<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;


class DepartmentController extends Controller
{
    public function index(){
        $results = $this->getName();
        $resultsarr = json_decode($results);
        $combined = $resultsarr->results;
        echo json_encode($combined[0]);
    }
    
    public function getUser(){
        $results = [];
        $userdata = DB::table('staff') 
        ->join('departments', 'staff.deptid', '=', 'departments.deptid')
        ->select('staff.*', 'departments.deptname')
        ->where('staff.staffid', 28)
        ->get();
        $departments = DB::table('departments')->get();
        $staffs = DB::table('staff')->get();
        $results = array( 'user'=>$userdata,'departments'=>$departments,'staffs'=>$staffs );
        echo json_encode($results);
    }

    public function populateStaff(){
        $results = $this->getName();
        $resultsarr = json_decode($results);
        $combined = $resultsarr->results;

        for($i=1;$i<=6;$i++ ){ // 6 departments
            for($j=1;$j<=rand(3,10);$j++){ // 3-10staff members
                DB::table('staff')->insert(
                    [
                        'name' => $combined[ rand(0,499 ) ]->name->title.' '.$combined[ rand(0,499 ) ]->name->first
                        ." ".$combined[ rand(0,499 ) ]->name->last, 
                    'deptid' => $i 
                    ]
                );
            }            
        }
        $staff = DB::table('staff')->get();
        echo json_encode( array($staff) );
    }

    public function populatePatients(){
        $results = $this->getName();
        $resultsarr = json_decode($results);
        $combined = $resultsarr->results;

        for($i=1;$i<=5000;$i++ ){ 
            DB::table('patients')->insert(
                ['name' => $combined[ rand(0,4999 ) ]->name->title.' '.$combined[ rand(0,4999 ) ]->name->first
                        ." ".$combined[ rand(0,4999 ) ]->name->last,
                'cardnumber' => "pat-".$i 
                ]
            );           
        }

        $patients = DB::table('patients')->get();
        echo json_encode( array($patients) );
    }

    public function visitsPopulate(){
        $patients = DB::table('patients')->get();
    }

    public function getPatient($cardnumber){
        $results = [];
        $userdata = DB::table('patients') 
        ->where('cardnumber', $cardnumber)
        ->get();
        $results = array( 'patient'=>$userdata );
        echo json_encode($results);
    }
    public function bookPatient(Request $request){
        $patientid = $request->input('patientid');
        $deptid = $request->input('deptid');
        $staffid = $request->input('staffid');
        $notes = $request->input('notes');
        $receptionid = $request->input('receptionid');
        DB::table('visits')->insert(
            [
            'patientid' => $patientid,
            'deptid' => $deptid,
            'notes' => $notes,
            'receptionid' => $receptionid,
            'status' => '1'
            ]
        );    
        $visitid = DB::getPdo()->lastInsertId();;   
        DB::table('treatments')->insert(
            [
            'visitid' => $visitid,
            'patientid' => $patientid,
            'staffid' => $staffid,
            'deptid' => $deptid,
            'referralid' => $receptionid,
            'docnotes' => '',
            'first-stop' => '1'
            ]
        );    
        $treatmentid = DB::getPdo()->lastInsertId();;   
        echo json_encode( array('treatmentid'=>$treatmentid, 'visitid'=>$visitid ) );
    }
    public function recordTreatment(Request $request){
        $visitid = $request->input('visitid');
        $patientid = $request->input('patientid');
        $deptid = $request->input('deptid');
        $staffid = $request->input('staffid');
        $referralid = $request->input('staffid');
        DB::table('treatments')->insert(
            [
            'visitid' => $visitid,
            'patientid' => $patientid,
            'staffid' => $staffid,
            'deptid' => $deptid,
            'referralid' => $receptionid,
            'docnotes' => '',
            'first-stop' => '0'
            ]
        );    
        $treatmentid = DB::getPdo()->lastInsertId();;   
        echo json_encode( array('treatmentid'=>$treatmentid ) );
    }
    public function getVisits(){
        $results = [];
        $visitdata = DB::table('visits') 
        ->join('patients', 'visits.patientid', '=', 'patients.patientid')
        ->join('staff', 'visits.receptionid', '=', 'staff.staffid')
        ->select('patients.name as patientname','patients.cardnumber', 'staff.staffname','visits.notes','visits.visitid')
        ->get();
        $results = array( 'visits'=>$visitdata);
        echo json_encode($results);
    }
    public function getName(){
        $session = curl_init();
        curl_setopt($session, CURLOPT_URL, "https://randomuser.me/api/?results=5000&nat=us");
        curl_setopt($session, CURLOPT_HTTPGET, 1); 
        curl_setopt($session, CURLOPT_HEADER, false);
        curl_setopt($session, CURLOPT_HTTPHEADER, array('Accept: application/xml', 'Content-Type: application/xml'));
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($session,CURLOPT_SSL_VERIFYPEER,false);
        $response = curl_exec($session);
        $err = curl_error($session);
        curl_close($session);  
        if ($err) {
          $error_curl = array(
                              'errors' => "cURL Error #:" . $err //error handling for curl errors
                           );
          $response = json_encode($error_curl);
        }
        return $response;
    }

}
