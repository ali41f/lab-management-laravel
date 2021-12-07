<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\AvailableTest;
use App\Models\TestPerformed;
use Session;
use App\Models\Catagory;
use App\Models\Patient;
use App\Models\PatientCategory;
use DB;
use Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class PatientController extends Controller
{
    public function index()
    {
        return view('admin.patient.index');
    }
    /* Process ajax request */
    public function getPatients(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        // Total records
        $totalRecords = Patient::select('count(*) as allcount')->count();
        $totalRecordswithFilter = Patient::select('count(*) as allcount')->where('Pname', 'like', '%' . $searchValue . '%')->count();

        // Get records, also we have included search filter as well
        $records = Patient::orderBy($columnName, $columnSortOrder)
            ->where('patients.Pname', 'like', '%' . $searchValue . '%')
            ->orWhere('patients.id', 'like', '%' . $searchValue . '%')
            ->orWhere('patients.phone', 'like', '%' . $searchValue . '%')
            ->select('patients.*')
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();

        function actionView($id) {
            $str = '<a class="btn btn-xs btn-primary mr-1" href="'. route("patient-show", $id) .'">View</a>';
            
            $str = $str . '<a class="btn btn-xs btn-info" href="'. route("patient-edit", $id) .'">Edit</a>';
                        
            if(Auth::user()->role == 'admin'){
            $str = $str.'
            <form  method="POST" action="'. route("patient-delete", [$id]) .'" style="display: inline-block;">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="_token" value="'. csrf_token() .'">
                <input type="submit" class="btn btn-xs btn-danger show_confirm" value="'. trans('global.delete') .'">
            </form>
            ';
            }
            return $str;
        }

        foreach ($records as $record) {

            $data_arr[] = array(
                "id" => $record->id,
                "Pname" => $record->Pname,
                "phone" => $record->phone,
                "email" => $record->email,
                "dob" => \Carbon\Carbon::parse($record->dob)->diff(\Carbon\Carbon::now())->format('%y years %m months %d days'),
                "registration" =>  date('d-m-Y H:m:s', strtotime($record->start_time ?? '')),
                "action" => actionView($record->id),
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );

        echo json_encode($response);
    }

    public function create()
    {
        $patientCategorys = PatientCategory::all()->pluck('Pcategory', 'id')->prepend(trans('global.pleaseSelect'), '');
        return view('admin.patient.create',compact('patientCategorys'));
    }
    
    public function store(Request $request)
    {
        $this->validate($request,[
            'Pname' => 'required|max:120',
            'phone' => 'min:10|nullable|numeric',
            ]);
        $patient = new Patient();
        $patient->Pname = $request->Pname;
        $patient->patient_category_id  = $request->patient_category_id;
        $patient->gend = $request->gend;
        $patient->start_time = date('Y:m:d:H:i:s');

        if (!empty($request->age)) {
            $patient->dob = date("Y") - $request->age . "-01-01";
        } else if(!empty($request->dob)) {
            $patient->dob = $patient->dob = $request->dob;
        }

        if (!empty($request->email)) {
            $patient->email = $request->email;
        } else {
            $patient->email = '';
        }
        if (!empty($request->phone)) {
            $patient->phone = $request->phone;
        } else {
            $patient->phone = '';
        }
        $patient->save();
        return redirect()->route('create');
    }
    public function edit($id)
    { 
        $patients = Patient::findOrFail($id);
        $patientCategorys = PatientCategory::all()->pluck('Pcategory', 'id')->prepend(trans('global.pleaseSelect'), '');
        return view('admin.patient.edit', compact('patients','patientCategorys'));
    }
    public function update($id, Request $request)
   {
        $patient = Patient::findOrFail($id);
        // $input = $request->all();
        // $patient->fill($input)->save();
        if (!empty($request->Pname)) {
            $patient->Pname = $request->Pname;
        } else {
            $patient->Pname = '';
        }
        if (!empty($request->email)) {
            $patient->email = $request->email;
        } else {
            $patient->email = '';
        }
        if (!empty($request->phone)) {
            $patient->phone = $request->phone;
        } else {
            $patient->phone = '';
        }
        if (!empty($request->dob)) {
            $patient->dob = $request->dob;
        } else {
            $patient->dob = '';
        }
        if (!empty($request->patient_category_id)) {
            $patient->patient_category_id  = $request->patient_category_id ;
        } else {
            $patient->patient_category_id  = '';
        }
        if (!empty($request->gend)) {
            $patient->gend  = $request->gend ;
        } else {
            $patient->gend  = '';
        }
        $patient->save();
        return redirect()->route('patient-list');
    }
    public function show($id)
    {  
        $allTests = TestPerformed::where('test_performeds.patient_id', $id)
        ->join('available_tests', 'test_performeds.available_test_id', '=', 'available_tests.id')
        ->join('categories', '.available_tests.category_id', '=', 'categories.id')
        ->select('available_tests.name','available_tests.urgent_timehour','available_tests.stander_timehour',
        'test_performeds.created_at',"test_performeds.id",'categories.Cname',
        'test_performeds.status','test_performeds.fee','test_performeds.created_at','test_performeds.type')
        ->orderBy('id', 'DESC')
        ->get(); 
         $tests = $allTests->pluck('name');    
         $patient = Patient::findOrFail($id);
        return view('admin.patient.show', compact('patient','tests','allTests'));
    }
    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();
        return redirect()->route('patient-list');
    }

    public function view_multiple_report(Request $request){
        $tests=TestPerformed::whereIn("id",$request->report_ids)->with("availableTest")->get();
        $tests = TestPerformed::join('available_tests', 'available_tests.id', '=', 'test_performeds.available_test_id')->whereIn("test_performeds.id",$request->report_ids)->orderBy('available_tests.id')->select('test_performeds.*',"available_tests.category_id","available_tests.resultValueCount")->get();

//        dd($tests[0]);
        $getpatient=$tests[0]->patient;
        return view("admin.patient.multiple_reports",compact("tests","getpatient"));
    }
   
}
