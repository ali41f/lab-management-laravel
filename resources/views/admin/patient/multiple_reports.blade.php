@extends('layouts.admin')
@section('content')
    <style>
         @media print {
            .noprint {
                display: none !important;
            }
        }
        
        .notificationbar{
            position: fixed;
            top: 100px;
            left: 45%;
            display: none;
            z-index: 888;
        }
        
        .webfooter{
            display: flex;
            margin-top: 20px;
        }
        .webfooter-item{
            flex-grow: 1;
            font-size: 16px;
            font-weight: bold;
            line-height: 1.2;
        }
        .drname{
            font-size: 20px;
        }
        .abu{
            padding: 5px 5px 5px 25px;
            color: #273582;
            text-align: left;
        }
        .bhai{
            padding: 5px 25px 5px 5px;
            color: #273582;
            text-align: right;
        }
        .webheader{
            position: absolute;
            top: 0;
            width: 100%;
            background-color: #a9d4d1;
            height: 125px;
        }
        .webheader-item{
            font-size: 40px;
            line-height: 1;
            font-weight: bold;
        }
        .webheader-item img{
            margin-left:auto;
            margin-right:auto;
        }
        .usamalabeng{
            padding: 20px 0 0 0;
            color: #aa3b47;
            text-align: center;
        }
        .usamalaburdu{
            padding: 24px 25px 0 0;
            color: #273582;
            text-align: right;
            font-size: 25px;
        }
        .abfooter{
            background-color: #a9d4d1;
            text-align: center;
            padding: 7px 25px;
            line-height: 1.3;
        }
    </style>
    @php $previous_category_id="";
    $i = 0;
    $iteration = 0;
    $verified = true;
    $testsarray = '';
    @endphp
    <div class="card" style="background: white; font-size: 16px;">
        <div id="widgetreport">
            
            <div class="webheader noprint">
                <div class="webheader-item usamalabeng">USAMA Clinical & PCR Laboratory<br /><span style="font-size: 18px; color: #2e6e69">Phone number: 068-5889116. Address: Hospital Road, Rahim Yar Khan</span></div>
                <img style="opacity: .04; border-radius: 50%; top: 0px; position: absolute; left:0;" class="card-img-top card d-flex" src="{{ asset('images/lablogo.png') }}"/>
            </div>
            
            
            @foreach($tests as $testPerformedsId)
                
                @php
                    $testsarray = $testsarray.",".$testPerformedsId->id;
                    if($testPerformedsId->status != "verified"){
                        $verified = false;
                    }
                    $i = $testPerformedsId->resultValueCount + $i;
                    // $nextpage = ($testPerformedsId->category_id!=$previous_category_id && $previous_category_id!='' || $i > 7)
                    $nextpage = ($i > 5);
                @endphp
                <div style="{{$nextpage ? 'page-break-before: always':''}}" class="{{ $nextpage ? 'beforeClass' : '' }}">

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
            <div class="testscommalist d-none">{{$testsarray}}</div>
            <div class="webfooter noprint">
                <div class="webfooter-item abu"><strong class="drname">Dr. Usama Rehman</strong><br />Assistant Professor<br />MBBS, FCPS (Histopathology)<br />Consultant Histopathology</div>
                <div class="webfooter-item bhai"><strong class="drname">Prof. Dr. Abdul Hakeem Ch.</strong><br />MBBS, DCP<br />M.Phil (Microbiology)<br />Consultant Pathology</div>
            </div>
            <div class="abfooter noprint">
                <strong>Proviso:</strong> This report is prepared with utmost care and professional expertise; however no legal responsibility or financial liability is accepted for any unavoidable errors and omissions because of lack of identity of the person. Therefore report is not valid for claims and compensations or presentation in the court of law.<br />This is computer generated report. It doesn't require any signature or stamp.
            </div>
        </div>
    </div>
    @if($verified)
    <div style="background: white" class="col-md-12 mb-12 noprint text-center py-3">
            <button class="btn btn-primary" onclick="window.print()">Print Report</button>
            <button class="btn btn-success ml-4 btnsave">Send message</button>
    </div>
    @endif
    <div class="alert alert-success notificationbar" role="alert">Report has been sent successfully.</div>
    <div class="alert alert-danger notificationbar" role="alert"></div>
    
    <script type="text/javascript" src="../../../js/html2canvas.min.js"></script>

    <script>

$(function () {
    let tests_arr = $('.testscommalist').text().split(',').filter(item => item);
    

        $(".btnsave").click(function() { // send message
             $(".btnsave").text('Sending ...');

             tests_arr = $('.testscommalist').text().split(',').filter(item => item);
 
             let phonenum = $('.phonenum').attr('phone');
             if(!/^\d+$/.test(phonenum)) return;
             if(phonenum[0] == '0') phonenum = phonenum.replace('0', '92');
             //console.log(phonenum);
             if(phonenum.length != 12){
                 $('.alert-danger').text("Phone number is incorrect.");
                 $('.alert-danger').fadeIn(300).delay(5000).fadeOut(1000);
                 $(".btnsave").text('Send message');
                 return;
             }
             
             
             html2canvas($("#widgetreport")[0]).then((canvas) => {
                 var dataURL = canvas.toDataURL();
                 $.ajax({
                     url: 'http://usamalab.com/api/ali',
                     type: 'POST',
                     data: {
                        image: dataURL,
                        patientname: $('.patientname').attr('patientname'),
                        phone: phonenum,
                        mrid: $('.patientmrid').attr('mrid')
                     },
                     success: function(response) {
                         $(".btnsave").text('Send message');
                         if(response == "success"){
                            
                            $.ajax({
                                url: '/api/smsstatus',
                                type: 'post',
                                data: {
                                    testslist: tests_arr,
                                },
                                success: function(response) {
                                    $(".btnsave").text('Send message');
                                    if(response == "done"){
                                        $('.alert-success').fadeIn(300).delay(5000).fadeOut(1000);
                                    }else if(response == "notdone"){
                                        $('.alert-danger').text("Done but status not changed");
                                        $('.alert-danger').fadeIn(300).delay(5000).fadeOut(1000);
                                    }
                                },
                                error: function() {
                                    $(".btnsave").text('Send message');
                                    $('.alert-danger').text("An error has occurred in changing status.");
                                    $('.alert-danger').fadeIn(300).delay(5000).fadeOut(1000);
                                }
                            });
                             //$('.alert-success').fadeIn(300).delay(5000).fadeOut(1000);
                         }else if(response == "notsaved"){
                             $('.alert-danger').text("File is not uploaded.");
                             $('.alert-danger').fadeIn(300).delay(5000).fadeOut(1000);
                         }else if(response == "notsent"){
                             $('.alert-danger').text("SMS is not sent. Maybe incorrect phone number.");
                             $('.alert-danger').fadeIn(300).delay(5000).fadeOut(1000);
                         }
                     },
                     error: function() {
                         $(".btnsave").text('Send message');
                         $('.alert-danger').text("An error has occurred.");
                         $('.alert-danger').fadeIn(300).delay(5000).fadeOut(1000);
                     }
                 });
             });
             
         }); 
     }); 
    </script>

@endsection