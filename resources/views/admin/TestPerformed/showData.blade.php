@extends('layouts.admin')
@section('content')
    <style>
        .hr {
            border-style: solid;
            border-color: black;
            }
        .hr1 {
            border-top: 1px dashed #777;
            }
    </style>
    <div class="card">
        <div class="card-header"> 
            Test Detail
        </div>
        <div class="card-body">
            <div class="form-group">
                <div class="row">
                    <div class="col">
                        <b> <label for="user_id">Test Name</label></b>
                        <p>{{ $testPerformedsId->availableTest->name ?? '' }}</p>
                    </div>
                    <div class="col">
                        <b> <label for="user_id">Patient Name</label></b>
                        <p>{{ $testPerformedsId->patient->Pname ?? '' }}</p>
                    </div>
                    <div class="col">
                        <b> <label for="user_id">Type</label></b>
                        <p>{{ $testPerformedsId->type ?? '' }}</p>
                    </div>
                    <div class="col">
                        <b> <label for="user_id">Referred By</label></b>
                        <p>{{ $testPerformedsId->referred ?? '' }}</p>
                    </div>
                    <div class="col">
                        <b> <label for="user_id">Status</label></b>
                        @if($testPerformedsId->type === "urgent")
                            @if (\Carbon\Carbon::now()->timestamp < $testPerformedsId->availableTest->urgent_timehour + $testPerformedsId->created_at->timestamp && $testPerformedsId->Sname_id =='2')
                                <div><button class="btn btn-xs btn-success">Verified</button></div>
                                @elseif (\Carbon\Carbon::now()->timestamp < $testPerformedsId->availableTest->urgent_timehour + $testPerformedsId->created_at->timestamp )
                                <div><button class="btn btn-xs btn-info">In Process</button></div>
                                @elseif ($testPerformedsId->Sname_id =='2')
                                <div><button class="btn btn-xs btn-success">Verified</button></div>
                                @elseif (\Carbon\Carbon::now()->timestamp > $testPerformedsId->availableTest->urgent_timehour + $testPerformedsId->created_at->timestamp)
                                <div><button class="btn btn-xs btn-danger">Delayed</button></div>
                                @else
                                <div><button class="btn btn-xs btn-info">Delayedddddd</button></div>
                            @endif
                        @endif
                        @if($testPerformedsId->type === 'standard')
                            @if (\Carbon\Carbon::now()->timestamp < $testPerformedsId->availableTest->stander_timehour + $testPerformedsId->created_at->timestamp && $testPerformedsId->Sname_id =='2')
                                <div><button class="btn btn-xs btn-success">Verified</button></div>
                                @elseif (\Carbon\Carbon::now()->timestamp < $testPerformedsId->availableTest->stander_timehour + $testPerformedsId->created_at->timestamp )
                                <div><button class="btn btn-xs btn-info">In Process</button></div>
                                @elseif ($testPerformedsId->Sname_id =='2')
                                <div><button class="btn btn-xs btn-success">Verified</button></div>
                                @elseif (\Carbon\Carbon::now()->timestamp > $testPerformedsId->availableTest->stander_timehour + $testPerformedsId->created_at->timestamp)
                                <div><button class="btn btn-xs btn-danger">Delayed</button></div>
                                @else
                                <div><button class="btn btn-xs btn-info">Delayed</button></div>
                            @endif
                        @endif
                    </div>
                </div>
                <hr class="hr">
                <div>
                    <h3>Report Items</h3>
                </div>
                @foreach($availableTestId->TestReportItems->where("status","active") as $TestReportItem)
                    <div class="text-capitalize"><h4>{{$TestReportItem->title}}</h4></div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                    <b><label>Normal Range</label></b>
                                    <p>({{ $TestReportItem->normalRange }}){{ $TestReportItem->unit ?? '' }}</p>
                                </div>
                                <div class="col">
                                    <b><label>Critical Values</label></b>
                                    <p>{{ $TestReportItem->firstCriticalValue }}{{ $TestReportItem->unit ?? '' }}-{{ $TestReportItem->finalCriticalValue }}{{ $TestReportItem->unit ?? '' }}</p>
                                </div>
                                <div class="col">
                                    <b><label>Result Values</label></b>
                                    <p>{{ $TestReportItem->TestReport->value }}</p>
                                </div>
                                <div class="col">
                                    <b><label></label></b>
                                    <p></p>
                                </div>
                                <div class="col">
                                    <b><label></label></b>
                                    <p></p>
                                </div>
                            </div>
                        </div>
                        <hr class="hr1">
                @endforeach
                <hr class="hr">
                <div>
                    <h3>Inventory Used</h3>
                </div>
                @foreach($availableTestId->available_test_inventories as $test_inventories)
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <b><label></label></b>
                                <p>{{ $test_inventories->inventory->inventoryName ?? '' }}&nbsp = &nbsp {{ $test_inventories->itemUsed }}{{ $test_inventories->inventory->inventoryUnit }}</p>
                            </div>
                        </div>
                    </div>
                    <hr class="hr1">
                @endforeach
                <hr class="hr">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a class="btn btn-ls btn-primary" href="{{ route('test-performed-back') }}">Back</a>                       
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
