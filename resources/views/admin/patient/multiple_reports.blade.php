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
    $iteration = 0;
    $verified = true;
    @endphp
    <div id="canvas_div_pdf">
    @foreach($tests as $testPerformedsId)
        @php
            if($testPerformedsId->status != "verified"){
                $verified = false;
            }
            $i = $testPerformedsId->resultValueCount + $i;
            $nextpage = ($testPerformedsId->category_id!=$previous_category_id && $previous_category_id!='' || $i > 5)
        @endphp
        <div style="{{$nextpage ? 'page-break-before: always':''}}" class="card {{ $nextpage ? 'beforeClass' : '' }}">

        <!-- <div style="page-break-after: always;" class="card {{($testPerformedsId->category_id!=$previous_category_id && $previous_category_id!="") ? "page-break-before":""}}"> -->

            @if($iteration == 0 || $nextpage)
                @include("admin.TestPerformed.partial_patient")
            @endif
            @include("admin.TestPerformed.partial_report")
        </div>
        @php
            if($nextpage){
                $i= $testPerformedsId->resultValueCount;
            }
            $previous_category_id=$testPerformedsId->category_id;
            $iteration++;
        @endphp
    @endforeach
    </div>

    @if($verified)
    <div style="background: white" class="col-md-12 mb-12 noprint text-center py-3">
        <button class="btn btn-primary" onclick="window.print()">Print Report</button>
    </div>
    @endif
    <script src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>

    <script>
        /*
        (async () => {

            var element = document.getElementById('canvas_div_pdf');
            var opt = {
                pagebreak: { before: '.beforeClass' },
                image: {type: 'jpeg', quality: 1}
            };
            let pdfData;
            let timestamp = + new Date();
            
            // await html2pdf().set(opt).from(element).toPdf().get('pdf').then(function (pdf) {
            //     var totalPages = pdf.internal.getNumberOfPages();
            //     // page numbers
            //     for (i = 1; i <= totalPages; i++) {
            //         pdf.setPage(i);
            //         pdf.setFontSize(10);
            //         pdf.setTextColor(150);
            //         pdf.text('Page ' + i + ' of ' + totalPages, pdf.internal.pageSize.getWidth() - 30, pdf.internal.pageSize.getHeight() - 10);
            //     }
            // }).save(timestamp+'.pdf')

            const pdf = await html2pdf().set(opt).from(element).toPdf().get('pdf').output('blob')

            const text = await (new Response(pdf)).text();
            //console.log(text)


            // await fetch(timestamp+'.pdf')
            //     .then(response => response.text())
            //     .then(text => {
            //         console.log(text)
            //         pdfData = text
            //     })
            

         
            const rawResponse = await fetch('api/pdf', {
                method: 'POST',
                headers: {
                'Accept': 'application/pdf',
                'Content-Type': 'application/pdf'
                },
                body: pdf
            });
            //const content = await rawResponse.json();
            console.log(rawResponse)
        })();

        */


    </script>

@endsection