<style>
@media print {
    thead th{
        background-color: #dddddd !important;
        -webkit-print-color-adjust: exact;
        border-bottom: 2px solid #333;
    }
}
thead th{
    background-color: #ddd;
    -webkit-print-color-adjust: exact;
}

thead th:first-child{
    border-top-left-radius: 5px;
    border-bottom-left-radius: 5px;
}
thead th:last-child{
    border-top-right-radius: 5px;
    border-bottom-right-radius: 5px;
}
.pl-20{
    padding-left: 20px;
}
.testname{
    text-align: center;
}
</style>
<div class="card-body">
    <div class="pl-20 testname"><h3>{{ $testPerformedsId->availableTest->name }}</h3></div>
    <!-- <div class="pl-20"><h4>{{ $testPerformedsId->availableTest->category->Cname  }}</h4></div> -->
    <div class="card-body">
        <div class="table-responsive dont-break-inside">

            @if($testPerformedsId->availableTest->type==1)
                <table class="table table-borderless">
                    <thead>
                    <tr>
                        <th>Test</th>
                        <th>Unit</th>
                        <th>Result</th>

                        @php $x=1; @endphp
                        @foreach($getpatient->testPerformed->where("available_test_id",$testPerformedsId->availableTest->id)->where("id","<",$testPerformedsId->id)->sortByDesc('created_at')->take(2) as $old_test)
                            <th>History {{$x}}</th>
                            @php $x++; @endphp
                        @endforeach
                        <th>REFERENCE RANGE</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($testPerformedsId->testReport as $testReport)
                        <tr>
                            <td class="text-capitalize">{{$testReport->report_item->title}}</td>
                            <td class="">{{$testReport->report_item->unit}}</td>
                            <td>{{ $testReport->value }}</td>
                            @foreach($getpatient->testPerformed->where("available_test_id",$testPerformedsId->availableTest->id)->where("id","<",$testPerformedsId->id)->sortByDesc('created_at')->take(2) as $old_test)
                                @php
                                    if(!$old_test->testReport->where("test_report_item_id",$testReport->test_report_item_id)->first())
                                    {
                                        $xyz = '';
                                    }else{
                                        $xyz = $old_test->testReport->where("test_report_item_id",$testReport->test_report_item_id)->first()->value . " (". date('d-m-Y', strtotime($old_test->created_at)) .")";
                                    }
                                @endphp
                                <td>{{ $xyz }}</td>
                            @endforeach
                            <td>({{$testReport->report_item->normalRange}}){{$testReport->report_item->unit}}</td>


                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @elseif($testPerformedsId->availableTest->type==2)
                @php echo $testPerformedsId->editor; @endphp
            @endif
            @if($testPerformedsId->comments != '')
                <hr>
                <div class="col-md-9"><h4>Dr Comments </h4></div>
                <div class="col-md-9"><h6>{{ $testPerformedsId->comments }}</h6></div>
            @endif

        </div>
    </div>
</div>
