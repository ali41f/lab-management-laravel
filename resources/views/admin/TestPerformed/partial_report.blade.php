<div class="card-body">
    <div class="col-md-9"><h3>{{ $testPerformedsId->availableTest->category->Cname }}</h3></div>
    <div class="col-md-9"><h4>{{ $testPerformedsId->availableTest->name }}</h4></div>
    <div class="card-body">
        <hr>
        <div class="table-responsive dont-break-inside">

            @if($testPerformedsId->availableTest->type==1)
                <table class="table table-borderless">
                    <thead>
                    <tr>
                        <th style="background-color:gray;">Test</th>
                        <th style="background-color:gray;">Unit</th>
                        <th style="background-color:gray;">Result</th>

                        @php $x=1; @endphp
                        @foreach($getpatient->testPerformed->where("available_test_id",$testPerformedsId->availableTest->id)->where("id","<",$testPerformedsId->id)->sortByDesc('created_at')->take(2) as $old_test)
                            <th style="background-color:gray;">History {{$x}}</th>
                            @php $x++; @endphp
                        @endforeach
                        <th style="background-color:gray;">REFERENCE RANGE</th>
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
