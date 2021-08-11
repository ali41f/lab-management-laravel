@extends('layouts.admin')
@section('content')
    <style>
        @media print {
            .noprint {
                display: none;
            }
        }
    </style>
    @php $previous_category_id="";
    $i = 0;

    @endphp

    @foreach($tests as $testPerformedsId)
        @php $i = $testPerformedsId->resultValueCount + $i;   @endphp
        <div style="{{($testPerformedsId->category_id!=$previous_category_id && $previous_category_id!='' || $i > 2) ? 'page-break-before: always':''}}" class="card">

        <!-- <div style="page-break-after: always;" class="card {{($testPerformedsId->category_id!=$previous_category_id && $previous_category_id!="") ? "page-break-before":""}}"> -->
            @if($testPerformedsId->category_id!=$previous_category_id)
                @include("admin.TestPerformed.partial_patient")
            @endif
            @include("admin.TestPerformed.partial_report")
        </div>
        @php
            if($i>2){
                $i= $testPerformedsId->resultValueCount;
                }
                $previous_category_id=$testPerformedsId->category_id;
        @endphp
    @endforeach

    <div style="background: white" class="col-md-12 mb-12 noprint text-center py-3">
        <button class="btn btn-primary" onclick="window.print()">Print Report</button>
    </div>


@endsection