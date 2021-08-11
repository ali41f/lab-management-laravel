<?php

namespace App\Http\Controllers;

use App\Models\AvailableTest;
use App\Models\TestPerformed;
use App\Models\Category;
use App\Models\TestReport;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class HomeController extends Controller
{
    public function index()
    {
        $todayDate = Carbon::today();
        // dd($todayDate);

        $data = DB::table('test_performeds')->where('Sname_id', '=', '2')->get();
        $today = $data->where('created_at', '>=', $todayDate)->count();
        // dd($today);
        $thisWeekPatient = $data->where('created_at', '>=', Carbon::now()->subDays(7))->count();
        $thisMongthPatient = $data->where('created_at', '>=', Carbon::now()->subDays(30))->count();

        $allPerformedToday = TestReport::join('test_report_items', 'test_reports.test_report_item_id', '=', 'test_report_items.id')
            ->join('test_performeds', 'test_reports.test_performed_id', '=', 'test_performeds.id')
            ->join('available_tests', 'test_performeds.available_test_id', '=', 'available_tests.id')
            ->join('patients', 'test_performeds.patient_id', '=', 'patients.id')
            ->select('available_tests.name', 'patients.Pname', 'patients.phone','test_performeds.id',
                'test_performeds.created_at', 'test_reports.value', 'test_report_items.firstCriticalValue', 'test_report_items.finalCriticalValue')
            ->get();

        $ids_added_as_critical=array();
        $criticalTestToday = array();
        foreach ($allPerformedToday as $performed) {
            if (($performed->value <= $performed->firstCriticalValue || $performed->value >= $performed->finalCriticalValue) && $performed->created_at > Carbon::today()) {
                if (!in_array($performed->id,$ids_added_as_critical)){
                    array_push($ids_added_as_critical, $performed->id);
                    array_push($criticalTestToday, $performed);
                }
            }
        }
        $todayDelayeds = TestPerformed::where([
            ['created_at', '>=', $todayDate],
            ['Sname_id', '!=', '2'],
            ['Sname_id', '!=', '4'],

        ])->latest()->get();

        $testPerformeds = TestPerformed::where('created_at', '>=', $todayDate)->get();

        $availableTestNameAndCountTests = AvailableTest::withCount(['testPerformed'])
            ->orderBy('test_performed_count', 'desc')
            ->get();

        $test = DB::table('test_performeds')
            ->get('id');
        $distincrCatagory2 = $test->count();

        $distincrCatagory = Category::distinct()->get();
        $test = DB::table('test_performeds')
            ->get('id');
        $distincrCatagory2 = $test->count();

        return view('home', compact('today', 'thisWeekPatient', 'thisMongthPatient',
            'distincrCatagory2', 'todayDelayeds', 'criticalTestToday', 'testPerformeds', 'availableTestNameAndCountTests'));
    }
}