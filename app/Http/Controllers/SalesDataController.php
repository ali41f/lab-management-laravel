<?php
namespace App\Http\Controllers;
use App\Models\AvailableTest;
use App\Models\TestPerformed;
use App\Models\Category;
use Carbon\Carbon;
use App\Models\Patient;
use Illuminate\Http\Request;
use DB;
class SalesDataController extends Controller
{
    public function index()
    {  
        return view('sales.index');
        
    }

    public function getDataBetweenTime(Request $request)
    {  
        $initialtime = $request->initialtime;
        $finaltime = $request->finaltime;
        $testname = $request->testname;
        $ref = $request->ref;
        $testPerformeds = TestPerformed::join('patients', 'test_performeds.patient_id', '=', 'patients.id')
        ->join('available_tests', 'test_performeds.available_test_id', '=', 'available_tests.id')
        ->where('available_tests.name', 'like', '%' . $testname)
        ->where('test_performeds.referred', 'like', '%' . $ref)
        ->select('test_performeds.*', 'patients.Pname', 'patients.id as Pid', 'available_tests.name',
                'available_tests.testFee', 'test_performeds.specimen')
        ->whereBetween('test_performeds.created_at', [$initialtime, $finaltime])->get();
        
        return view('sales.index', compact('testPerformeds', 'initialtime', 'finaltime', 'testname', 'ref' ));
    }
}