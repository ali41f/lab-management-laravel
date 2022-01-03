<style>
    @media print {
        thead th {
            background-color: #dddddd !important;
            -webkit-print-color-adjust: exact;
            border-bottom: 2px solid #333;
        }
    }

    thead th {
        background-color: #ddd;
        -webkit-print-color-adjust: exact;
        line-height: 25px;
    }

    .table td {
        padding: .75rem;
        vertical-align: top;
        border-top: 1px solid #c8ced3;
        border-bottom: 1px solid #c8ced3;
    }

    thead th:nth-child(2n - 1) {
        padding-left: 10px;
        padding-right: 10px;
    }

    .table td:nth-child(2n - 1) {
        padding-left: 10px;
        padding-right: 10px;
    }

    thead th:last-child {
        
    }

    .report_body{
        font-size: 23px;
        padding: .3rem 1rem;
    }

    .normalrangetd{
        font-size: 20px;
    }

    .table th {
        padding: .35rem .15rem;
    }

    .table td {
        padding: .15rem;
    }

    .table th.refrangeth{
        width:260px;
    }

    .pl-20 {
        padding-left: 20px;
    }

    .table p{
        margin-bottom: .1rem;
    }

    .testname {
        text-align: center;
    }

    .widal td {
        text-align: center;
        font-weight: bold;
    }
    
    .editordiv p{
        line-height: 1.2;
        margin-bottom: 0.3rem;
    }
</style>
<div class="report_body">
    <div class="pl-20 mb-3 testname text-capitalize"><h2>{{$testPerformedsId->testReport->count() == 1 ? '' : str_replace("*", "", $testPerformedsId->availableTest->name)}}</h2></div>
<!-- <div class="pl-20"><h4>{{ $testPerformedsId->availableTest->category->Cname  }}</h4></div> -->
    <div>
        <div class="table-responsive dont-break-inside">

            @if($testPerformedsId->availableTest->type==1 || $testPerformedsId->availableTest->type==5 || $testPerformedsId->availableTest->type==6)
                @if($testPerformedsId->availableTest->type==6)
                    <h4 class="text-capitalize text-center">{{$testPerformedsId->heading0->value}}</h4>
                @endif
                <table class="table table-borderless">
                    <thead>
                    <tr>
                        <th width="270">Test</th>
                        <th>Unit</th>
                        <th>Result</th>

                        @php $x=1; @endphp
                        @foreach($getpatient->testPerformed->where("available_test_id",$testPerformedsId->availableTest->id)->where("id","<",$testPerformedsId->id)->sortByDesc('id')->take(2) as $old_test)
                            <th><span style="font-size: 15px; display: block;">({{date('d-m-Y', strtotime($old_test->created_at))}}) </span>History </th>
                            @php $x++; @endphp
                        @endforeach
                        <th class="refrangeth">Reference Range</th>
                    </tr>
                    </thead>
                   
                    <tbody>
                    @foreach($testPerformedsId->testReport->where("table_num",1)->sortBy("order") as $testReport)
                        <tr>
                            <td>{{$testReport->report_item->title}}</td>
                            <td class="">{{$testReport->report_item->unit}}</td>
                            <td>{{ $testReport->value }}</td>
                            @foreach($getpatient->testPerformed->where("available_test_id",$testPerformedsId->availableTest->id)->where("id","<",$testPerformedsId->id)->sortByDesc('id')->take(2) as $old_test)
                                @php
                                    if(!$old_test->testReport->where("test_report_item_id",$testReport->test_report_item_id)->first() || $old_test->testReport->where("test_report_item_id",$testReport->test_report_item_id)->first() == '')
                                    {
                                        $xyz = '';
                                    }else{
                                        $xyz = $old_test->testReport->where("test_report_item_id",$testReport->test_report_item_id)->first()->value;
                                    }
                                @endphp
                                <td>{{ $xyz }}</td>
                            @endforeach
                            <td class="normalrangetd">@php echo $testReport->report_item->normalRange @endphp</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                @if($testPerformedsId->availableTest->type==5 || $testPerformedsId->availableTest->type==6)
                    <h4 class="text-capitalize text-center">{{$testPerformedsId->heading->value}}</h4>
                    <table class="table table-borderless">
                        <thead>
                        <tr>
                            <th width="270">Test</th>
                            <th>Unit</th>
                            <th>Result</th>

                            @php $x=1; @endphp
                            @foreach($getpatient->testPerformed->where("available_test_id",$testPerformedsId->availableTest->id)->where("id","<",$testPerformedsId->id)->sortByDesc('id')->take(2) as $old_test)
                                <th><span style="font-size: 15px">({{date('d-m-Y', strtotime($old_test->created_at))}})</span>  <br />History</th>
                                @php $x++; @endphp
                            @endforeach
                            <th class="refrangeth">REFERENCE RANGE</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($testPerformedsId->testReport->where("table_num",2)->sortBy("order") as $testReport)
                            <tr>
                                <td>{{$testReport->report_item->title}}</td>
                                <td class="">{{$testReport->report_item->unit}}</td>
                                <td>{{ $testReport->value }}</td>
                                @foreach($getpatient->testPerformed->where("available_test_id",$testPerformedsId->availableTest->id)->where("id","<",$testPerformedsId->id)->sortByDesc('id')->take(2) as $old_test)
                                    @php
                                        if(!$old_test->testReport->where("test_report_item_id",$testReport->test_report_item_id)->first())
                                        {
                                            $xyz = '';
                                        }else{
                                            $xyz = $old_test->testReport->where("test_report_item_id",$testReport->test_report_item_id)->first()->value;
                                        }
                                    @endphp
                                    <td>{{ $xyz }}</td>
                                @endforeach
                                <td class="normalrangetd">@php echo $testReport->report_item->normalRange @endphp</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
                
                @if($testPerformedsId->availableTest->type==6)
                
                    <h4 class="text-capitalize text-center">{{$testPerformedsId->heading2->value}}</h4>
                    <table class="table table-borderless">
                        <thead>
                        <tr>
                            <th width="270">Test</th>
                            <th>Unit</th>
                            <th>Result</th>

                            @php $x=1; @endphp
                            @foreach($getpatient->testPerformed->where("available_test_id",$testPerformedsId->availableTest->id)->where("id","<",$testPerformedsId->id)->sortByDesc('id')->take(2) as $old_test)
                                <th><span style="font-size: 15px">({{date('d-m-Y', strtotime($old_test->created_at))}})</span> <br />History</th>
                                @php $x++; @endphp
                            @endforeach
                            <th class="refrangeth">REFERENCE RANGE</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($testPerformedsId->testReport->where("table_num",3)->sortBy("order") as $testReport)
                            <tr>
                                <td>{{$testReport->report_item->title}}</td>
                                <td class="">{{$testReport->report_item->unit}}</td>
                                <td>{{ $testReport->value }}</td>
                                @foreach($getpatient->testPerformed->where("available_test_id",$testPerformedsId->availableTest->id)->where("id","<",$testPerformedsId->id)->sortByDesc('id')->take(2) as $old_test)
                                    @php
                                        if(!$old_test->testReport->where("test_report_item_id",$testReport->test_report_item_id)->first())
                                        {
                                            $xyz = '';
                                        }else{
                                            $xyz = $old_test->testReport->where("test_report_item_id",$testReport->test_report_item_id)->first()->value;
                                        }
                                    @endphp
                                    <td>{{ $xyz }}</td>
                                @endforeach
                                <td class="normalrangetd">@php echo $testReport->report_item->normalRange @endphp</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif


            @elseif($testPerformedsId->availableTest->type==2)
                <div class="editordiv">
                    @php echo $testPerformedsId->editor; @endphp
                </div>
            @elseif($testPerformedsId->availableTest->type==3)
                <div class="row col-12">

                    <div class="col-4"><h5>Prothrombin Time</h5></div>
                    <div class="col-8">

                        <div class="row col-md-6 mb-6">
                            <div class="col-6 font-weight-bold">Test:</div>
                            <div class="col-6">{{( isset($testPerformedsId->widal) && $testPerformedsId->widal->where("type", "pro_test_time")->first()) ? $testPerformedsId->widal->where("type", "pro_test_time")->first()->value : ""}}</div>
                        </div>

                        <div class="row col-md-6 mb-6">
                            <div class="col-6 font-weight-bold">Control:</div>
                            <div class="col-6">{{( isset($testPerformedsId->widal) && $testPerformedsId->widal->where("type", "pro_control_time")->first()) ? $testPerformedsId->widal->where("type", "pro_control_time")->first()->value : ""}}</div>
                        </div>
                        <p class="font-xs">Difference upto 4 is normal</p>
                    </div>
                    <div class="offset-4 col-8">
                        <hr class="m-0">
                    </div>

                    <div class="col-4"><h5>A.P.T.T</h5></div>
                    <div class="col-8">
                        <div class="row col-md-6 mb-6">
                            <div class="col-6 font-weight-bold">Test:</div>
                            <div class="col-6">{{( isset($testPerformedsId->widal) && $testPerformedsId->widal->where("type", "aptt_test_time")->first()) ? $testPerformedsId->widal->where("type", "aptt_test_time")->first()->value : ""}}</div>
                        </div>
                        <div class="row col-md-6 mb-6">
                            <div class="col-6 font-weight-bold">Control:</div>
                            <div class="col-6">{{( isset($testPerformedsId->widal) && $testPerformedsId->widal->where("type", "aptt_control_time")->first()) ? $testPerformedsId->widal->where("type", "aptt_control_time")->first()->value : ""}}</div>
                        </div>
                        <p class="font-xs">Difference upto 7 is normal</p>
                    </div>
                    <div class="offset-4 col-8">
                        <hr class="m-0">
                    </div>
                </div>
            @elseif($testPerformedsId->availableTest->type==4)
                <div class="widal col-md-8 mb-8 offset-2">
                    <table border="1" class="w-100">
                        <tr>
                            <td></td>
                            <td>1:20</td>
                            <td>1:40</td>
                            <td>1:80</td>
                            <td>1:160</td>
                            <td>1:320</td>
                        </tr>
                        <tr>
                            <td>TO</td>
                            <td>{{ (isset($testPerformedsId->widal) && $testPerformedsId->widal->where("type", "to_1")->first() && $testPerformedsId->widal->where("type", "to_1")->first()->value == "true") ? "+" : "-"}}</td>
                            <td>{{ (isset($testPerformedsId->widal) && $testPerformedsId->widal->where("type", "to_2")->first() && $testPerformedsId->widal->where("type", "to_2")->first()->value == "true") ? "+" : "-"}}</td>
                            <td>{{ (isset($testPerformedsId->widal) && $testPerformedsId->widal->where("type", "to_3")->first() && $testPerformedsId->widal->where("type", "to_3")->first()->value == "true") ? "+" : "-"}}</td>
                            <td>{{ (isset($testPerformedsId->widal) && $testPerformedsId->widal->where("type", "to_4")->first() && $testPerformedsId->widal->where("type", "to_4")->first()->value == "true") ? "+" : "-"}}</td>
                            <td>{{ (isset($testPerformedsId->widal) && $testPerformedsId->widal->where("type", "to_5")->first() && $testPerformedsId->widal->where("type", "to_5")->first()->value == "true") ? "+" : "-"}}</td>
                        </tr>
                        <tr>
                            <td>TH</td>
                            <td>{{ (isset($testPerformedsId->widal) && $testPerformedsId->widal->where("type", "th_1")->first() && $testPerformedsId->widal->where("type", "th_1")->first()->value == "true") ? "+" : "-"}}</td>
                            <td>{{ (isset($testPerformedsId->widal) && $testPerformedsId->widal->where("type", "th_2")->first() && $testPerformedsId->widal->where("type", "th_2")->first()->value == "true") ? "+" : "-"}}</td>
                            <td>{{ (isset($testPerformedsId->widal) && $testPerformedsId->widal->where("type", "th_3")->first() && $testPerformedsId->widal->where("type", "th_3")->first()->value == "true") ? "+" : "-"}}</td>
                            <td>{{ (isset($testPerformedsId->widal) && $testPerformedsId->widal->where("type", "th_4")->first() && $testPerformedsId->widal->where("type", "th_4")->first()->value == "true") ? "+" : "-"}}</td>
                            <td>{{ (isset($testPerformedsId->widal) && $testPerformedsId->widal->where("type", "th_5")->first() && $testPerformedsId->widal->where("type", "th_5")->first()->value == "true") ? "+" : "-"}}</td>
                        </tr>
                        <tr>
                            <td>AO</td>
                            <td>{{ (isset($testPerformedsId->widal) && $testPerformedsId->widal->where("type", "ao_1")->first() && $testPerformedsId->widal->where("type", "ao_1")->first()->value == "true") ? "+" : "-"}}</td>
                            <td>{{ (isset($testPerformedsId->widal) && $testPerformedsId->widal->where("type", "ao_2")->first() && $testPerformedsId->widal->where("type", "ao_2")->first()->value == "true") ? "+" : "-"}}</td>
                            <td>{{ (isset($testPerformedsId->widal) && $testPerformedsId->widal->where("type", "ao_3")->first() && $testPerformedsId->widal->where("type", "ao_3")->first()->value == "true") ? "+" : "-"}}</td>
                            <td>{{ (isset($testPerformedsId->widal) && $testPerformedsId->widal->where("type", "ao_4")->first() && $testPerformedsId->widal->where("type", "ao_4")->first()->value == "true") ? "+" : "-"}}</td>
                            <td>{{ (isset($testPerformedsId->widal) && $testPerformedsId->widal->where("type", "ao_5")->first() && $testPerformedsId->widal->where("type", "ao_5")->first()->value == "true") ? "+" : "-"}}</td>
                        </tr>
                        <tr>
                            <td>AH</td>
                            <td>{{ (isset($testPerformedsId->widal) && $testPerformedsId->widal->where("type", "ah_1")->first() && $testPerformedsId->widal->where("type", "ah_1")->first()->value == "true") ? "+" : "-"}}</td>
                            <td>{{ (isset($testPerformedsId->widal) && $testPerformedsId->widal->where("type", "ah_2")->first() && $testPerformedsId->widal->where("type", "ah_2")->first()->value == "true") ? "+" : "-"}}</td>
                            <td>{{ (isset($testPerformedsId->widal) && $testPerformedsId->widal->where("type", "ah_3")->first() && $testPerformedsId->widal->where("type", "ah_3")->first()->value == "true") ? "+" : "-"}}</td>
                            <td>{{ (isset($testPerformedsId->widal) && $testPerformedsId->widal->where("type", "ah_4")->first() && $testPerformedsId->widal->where("type", "ah_4")->first()->value == "true") ? "+" : "-"}}</td>
                            <td>{{ (isset($testPerformedsId->widal) && $testPerformedsId->widal->where("type", "ah_5")->first() && $testPerformedsId->widal->where("type", "ah_5")->first()->value == "true") ? "+" : "-"}}</td>
                        </tr>
                        <tr>
                            <td>BO</td>
                            <td>{{ (isset($testPerformedsId->widal) && $testPerformedsId->widal->where("type", "bo_1")->first() && $testPerformedsId->widal->where("type", "bo_1")->first()->value == "true") ? "+" : "-"}}</td>
                            <td>{{ (isset($testPerformedsId->widal) && $testPerformedsId->widal->where("type", "bo_2")->first() && $testPerformedsId->widal->where("type", "bo_2")->first()->value == "true") ? "+" : "-"}}</td>
                            <td>{{ (isset($testPerformedsId->widal) && $testPerformedsId->widal->where("type", "bo_3")->first() && $testPerformedsId->widal->where("type", "bo_3")->first()->value == "true") ? "+" : "-"}}</td>
                            <td>{{ (isset($testPerformedsId->widal) && $testPerformedsId->widal->where("type", "bo_4")->first() && $testPerformedsId->widal->where("type", "bo_4")->first()->value == "true") ? "+" : "-"}}</td>
                            <td>{{ (isset($testPerformedsId->widal) && $testPerformedsId->widal->where("type", "bo_5")->first() && $testPerformedsId->widal->where("type", "bo_5")->first()->value == "true") ? "+" : "-"}}</td>
                        </tr>
                        <tr>
                            <td>BH</td>
                            <td>{{ (isset($testPerformedsId->widal) && $testPerformedsId->widal->where("type", "bh_1")->first() && $testPerformedsId->widal->where("type", "bh_1")->first()->value == "true") ? "+" : "-"}}</td>
                            <td>{{ (isset($testPerformedsId->widal) && $testPerformedsId->widal->where("type", "bh_2")->first() && $testPerformedsId->widal->where("type", "bh_2")->first()->value == "true") ? "+" : "-"}}</td>
                            <td>{{ (isset($testPerformedsId->widal) && $testPerformedsId->widal->where("type", "bh_3")->first() && $testPerformedsId->widal->where("type", "bh_3")->first()->value == "true") ? "+" : "-"}}</td>
                            <td>{{ (isset($testPerformedsId->widal) && $testPerformedsId->widal->where("type", "bh_4")->first() && $testPerformedsId->widal->where("type", "bh_4")->first()->value == "true") ? "+" : "-"}}</td>
                            <td>{{ (isset($testPerformedsId->widal) && $testPerformedsId->widal->where("type", "bh_5")->first() && $testPerformedsId->widal->where("type", "bh_5")->first()->value == "true") ? "+" : "-"}}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-12 mt-3">
                    <p style="text-align: center;"><b>Result : </b> {{(isset($testPerformedsId->widal) && $testPerformedsId->widal->where("type", "widal_result")->first()) ? $testPerformedsId->widal->where("type", "widal_result")->first()->value : ""}}</p>
                </div>
            @endif
            @if($testPerformedsId->comments != '')
                <div class="col-md-12 mt-4"><h6>@php echo $testPerformedsId->comments @endphp</h6></div>
            @endif

        </div>
    </div>
</div>
