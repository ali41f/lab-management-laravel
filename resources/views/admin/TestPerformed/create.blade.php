@extends('layouts.admin')
@section('content')
    <script src="https://cdn.ckeditor.com/4.16.1/standard/ckeditor.js"></script>
    <style>
        hr {
            border-top: 1px solid rgb(47 53 58);
        }

        .hr1 {
            border-top: 1px dashed #777;
        }
        #parent {
    display:flex;
    width: 1100px;
}

#parent input {
  
    width:200px;
    /* margin:20px; */
    margin-left:10px;
}



#parent p {
    /* width:100%; */
    margin-top:10px;
    margin-left:10px;

}

#parent button {
    width:100px;
    height:35px;
    /* margin-top:10px; */
    margin-left:10px;
    /* padding-top: -2px; */

}


    </style>
  
    <div class="card">
        <div class="card-header">
            Performed New Test
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('test-performed') }}" id="test_form" enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label class="required" for="patient_id">Select Patient Name</label>
                            <select class="form-control select2 {{ $errors->has('patients') ? 'is-invalid' : '' }}" name="patient_id" id="patient_id" required>
                                @foreach($patientNames as $id => $patientName)
                                    <option value="{{ $id }}">{{ $patientName }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('user'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('user') }}
                                </div>
                            @endif
                            <span class="help-block"></span>
                        </div>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label class="" for="referred">Referred By</label>
                            <input class="form-control {{ $errors->has('referred') ? 'is-invalid' : '' }}" type="text" name="referred" id="referred" value="{{ old('referred', '') }}">
                            @if($errors->has('referred'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('referred') }}
                                </div>
                            @endif
                            <span class="help-block"></span>
                        </div>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div>

                </div>


                <div id="test_block">
                    <div class="form-row test_form_div">

                        <div class="col-md-3 col-6 mb-3">
                            <div class="form-group">
                                <label for="available_test_id" class="required">Select Test Name</label>
                                <select class="form-control select2  {{ $errors->has('available_tests') ? 'is-invalid' : '' }}" onchange="set_test_form(this)" name="available_test_id[]" id="available_test_id">
                                    @foreach($availableTests as $id => $availableTest)
                                        <option value="{{ $id }}">{{ $availableTest }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('available_tests'))
                                    <div class="invalid-feedback" >
                                        {{ $errors->first('available_tests') }}
                                    </div>
                                @endif
                                <!-- ziyad -->
                                <small id="name_error" style="color: red"></small>
                                <!-- ziyad -->
                            </div>
                        </div>

                        <div class="col-md-3 col-6 mb-3">
                            <div class="form-group">
                                <label class="">Select Status</label>
                                <select class="form-control  {{ $errors->has('Sname_id') ? 'is-invalid' : '' }}" name="Sname_id[]" disabled>
                                    @foreach($stat as $id => $sta)
                                        @if ($id == 1)
                                            <option value="{{ $id }}" selected>{{ $sta }}</option>
                                        @else
                                            <option value="{{ $id }}">{{ $sta }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                

                                @if($errors->has('Sname_id'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('Sname_id') }}
                                    </div>
                                @endif
                                <span class="help-block"></span>
                            </div>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>

                        <div class="col-md-3 col-6 mb-3">
                            <div class="form-group">
                                <label for="fee_final">Charged Fee</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">Rs.</span>
                                    </div>
                                    <input class="form-control" type="number" name="fee_final[]" id="fee_final" value="">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-6 mb-3">
                            <div class="form-group">
                                <label for="type" class="required">Test Type</label>
                                <select class="form-control"  name="type[]" id="type">
                                    <option value="standard" selected>Standard<p class="standard_fee"></p></option>
                                    <option value="urgent">Urgent<p class="urgent_fee"></p></option>
                                </select>
                            </div>
                        </div>

                        <!--My code-->
                    <div class="col-md-3 mb-3">
                         <button type = "button" class="btn btn-success add_btn " id=""  onclick="testVariable()">Add</button> <br />
                       <div class = "mt-4" id="all">
                         
                        </div>
                       
                        
                      </div>
                       <!--ends-->

                        <div class="col-md-12 test_form">

                            @foreach($allAvailableTests as $test)

                                <div class="form-row" id="test{{$test->id}}" class="tests">
                                    <!-- <div class="col-md-12"><h4>{{$test->name}} result values</h4></div> -->
                                    @if($test->type==1)
                                        @foreach($test->TestReportItems->where("status","active") as $report_item)
                                            <!-- <div class="col-md-3 mb-3">
                                                <div class="form-group">
                                                    <label class="required text-capitalize">{{$report_item->title}} ({{$report_item->normalRange}}){{$report_item->unit}}</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="inputGroupPrepend">{{$report_item->unit}}</span>
                                                        </div> -->
                                                        <input type="hidden" name="testResult{{$report_item->id}}[]" value="">
                                                    <!-- </div>
                                                </div>
                                            </div> -->
                                        @endforeach
                                    @else
                                        <!-- <div class="col-md-12 mb-12">
                                            <div class="form-group">
                                                <div class="input-group"> -->
                                                    <input name="ckeditor[]" type="hidden" value="">
                                                <!-- </div>
                                            </div>
                                        </div> -->
                                    @endif
                                </div>


                                <script>
                                    var test{{$test->id}}= document.getElementById("test{{$test->id}}").outerHTML;
                                    document.getElementById("test{{$test->id}}").outerHTML = "";
                                    var test{{$test->id}}_standard ={{$test->testFee}};
                                    var test{{$test->id}}_urgent ={{$test->urgentFee}};
                                </script>

                            @endforeach
                        </div>

                        <!-- <div class="form-group shadow-textarea col-md-12">
                            <label>Comment</label>
                            <textarea class="form-control" rows="2" placeholder="Write comments  here..." name="comments"></textarea>

                        </div> -->

                    </div>
                </div>
<!--        
                <div class="row">
                    <div class="col-md-12">
                        <p onclick="add_report()" class="btn btn-success">Add Test</p>
                     <hr>
                    </div> -->

                    <div class="col-md-3 mb-3">
                        <button class="btn btn-primary btn-lg save_btn" type="submit">Save</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="alert alert-danger error_msg text-center" style="display: none"></div>
    </div>

   
     

    <script>

        var testHTML = document.getElementById("test_block").getElementsByClassName("test_form_div")[0].outerHTML;

        function set_test_form(select) {
            if (select.value) {
                select.parentElement.parentElement.parentNode.getElementsByClassName("test_form")[0].innerHTML = eval("test" + select.value);
                select.parentElement.parentElement.parentNode.getElementsByTagName("select")[2].getElementsByTagName("option")[1].innerText = "Urgent" + "(" + eval("test" + select.value + "_urgent") + ")";
                select.parentElement.parentElement.parentNode.getElementsByTagName("select")[2].getElementsByTagName("option")[0].innerText = "Standard" + "(" + eval("test" + select.value + "_standard") + ")";

                let st      = select.parentElement.parentElement.parentNode.getElementsByTagName("select")[2].getElementsByTagName("option")[0].innerText,
                    ur      = select.parentElement.parentElement.parentNode.getElementsByTagName("select")[2].getElementsByTagName("option")[1].innerText,
                    st_num  = st.replace(/\D/g,''),
                    ur_num  = ur.replace(/\D/g,''),
                    add_btn = document.querySelector('.add_btn');
                    
                add_btn.id=st_num
                add_btn.classList.add(ur_num)
            } else {
                select.parentElement.parentElement.parentNode.getElementsByClassName("test_form")[0].innerHTML = "";
                select.parentElement.parentElement.parentNode.getElementsByClassName("urgent_fee")[0].innerText = "";
                select.parentElement.parentElement.parentNode.getElementsByClassName("standard_fee")[0].innerText = "";
            }


            if (select.parentElement.parentElement.parentElement.getElementsByClassName("ckeditor")[0]) {
                CKEDITOR.replace(select.parentElement.parentElement.parentElement.getElementsByClassName("ckeditor")[0], {
                    width: '100%',
                });
            }


        }
       /*
        function add_report() {
            document.getElementById("test_block").insertAdjacentHTML('beforeend', testHTML);
            $('.select2').select2();
        }
       
*/
    
    //show input data
    let add_btn=document.querySelector('.add_btn');
    add_btn.onclick=function(e){
        //'ziyad'
        let all              = document.getElementById('all'),
            type             = document.getElementById("type").value,
            fee              = document.getElementById('fee_final').value,
            name             = document.getElementById('available_test_id'),
            name_value       = document.getElementById('available_test_id').value,
            text             = name.options[name.selectedIndex].text,
            error_ele        = document.getElementById('name_error'),
            random_num       = Math.floor((Math.random() * 10000) + 1);

        

        if (! fee) {
            if (type == 'standard') {
                let st=e.target.id;
                fee=st
            }else{
                let ur=e.target.classList[3];
                fee=ur
            }
        }

        if (! name_value ) {
            error_ele.textContent='you should select test name'
            return
        }
        
        let final_fee=0
        
        let final_fee_ele=document.getElementsByClassName('final_fees')
        if (final_fee_ele) {
            for (let i = 0; i < final_fee_ele.length; i++) {
                final_fee_ele[i].remove()
                
            }
        }

        all.insertAdjacentHTML('beforeend',
        
            '<div id="parent" class="ele'+random_num+'">'+
                '<input    value="'+name_value+'" name="available_test_ids[]" style="display:none"  >'+'</input><br/>'+
                    '<p>Test Name </p>'+'<input readonly   value="'+text+'" class="name  form-control" id="'+name_value+'">'+'</input>'+
                        
                    '<p>Test Type</p>'+'<input readonly class="type form-control" name="types[]" value="'+type+'">'+'</input>'+
                    '<p>Charged Fee </p>'+'<input readonly class="fees form-control" name="fees[]" id="fees_id'+random_num+'" value="'+fee+'">'+'</input>'+
                    
                    
                    '<small class="concession_error'+random_num+'" style="color:red">'+'</small>'+
                    '<button type="button"  class=" btn'+random_num+' btn btn-danger" id="'+random_num+'">Remove Test</button>'+
                    
            '</div>'
        )

            document.getElementById('fee_final').value=''
            document.getElementById("type").value='standard'
            document.getElementById('available_test_id').value=''

            let total_fees=document.getElementsByClassName('fees');
            for (let i = 0; i < total_fees.length; i++) {
                let single_fee=Number(total_fees[i].value)
                final_fee+=single_fee
            }

            let element=document.getElementsByClassName('ele'+random_num)[0]
            element.insertAdjacentHTML('afterend',
                '<div class="final_fees">'+
                    '<p>Concession </p>'+'<input  class=" form-control"  id="Concession" value="">'+'</input>'+
                    '<small style="color:red" id="Concession_err"></small>'+
                    '<button type="button"  class="submit_btn  btn btn-primary btn_submit'+random_num+' '+final_fee+'" id="'+random_num+'">Get Final Fee</button>'+
                '</div>'
            )
        
        let submit_btn=document.getElementsByClassName('submit_btn')[0]
        submit_btn.onclick=function(e){
            let elements=document.getElementsByClassName('final_fees')[0]
            let final_fee=e.target.classList[4];
            let Concession=Number(document.getElementById('Concession').value)
            let final_fee_ele=document.getElementsByClassName('all_final_fee')[0] 

            if (final_fee_ele) {
                final_fee_ele.remove()
            }

            if (final_fee <= Concession) {
                document.getElementById('Concession_err').textContent='Concession should be less than total fee'
                return
            }

            let con_fee=final_fee - Concession

            elements.insertAdjacentHTML('beforeend',
                '<div class="all_final_fee">'+
                    '<span  class="final_fee "style="font-weight:bold;font-size:large;position:relative;top:5px">Final Fee :$ </span>'+
                    '<span style="font-weight:bold;font-size:large;position:relative;top:5px" class="final_fee_class">'+con_fee+'</span><br/>'+
                '</div>'
            )
        }

        //delete record
        let delete_btn=document.querySelector('.btn'+random_num);
        delete_btn.onclick=function(e){
            let id                        = e.target.id,
                test_elements             = document.getElementsByClassName('ele'+id)[0],
                fee_num               = Number(document.getElementById('fees_id'+id).value),
                final_charged_fee         = document.querySelector('.final_fee_class'),
                 final=document.getElementsByClassName('submit_btn')[0].classList[4],
                mod_fee=final - fee_num;
                console.log(mod_fee)
                document.getElementsByClassName('submit_btn')[0].classList.remove(''+final)
                document.getElementsByClassName('submit_btn')[0].classList.add(''+mod_fee)

            if (final_charged_fee) {
                final_charged_fee_num = Number(final_charged_fee.textContent)
                let modified_final_fee        = final_charged_fee - con_fee_num;
                document.querySelector('.final_fee_class').textContent=modified_final_fee;
            }

            
            test_elements.remove();
        }
    }
                //'ziyad'
    
    /*
    function testVariable() {
        // var originalForm = $("#form").html();
        var strText = document.getElementById("type").value;          
        var strText1 = document.getElementById("available_test_id");
        var text= strText1.options[strText1.selectedIndex].text;
        var strText2 = document.getElementById("fee_final").value;
        // $("#form").html(originalForm); 
        // var result = strText + ' ' + text +' ' + strText2;
        document.getElementById('testType').textContent = 'Test Type:' + ' ' + strText;
        document.getElementById('testFee').textContent = 'Test Fee:' +  ' ' + strText2;
        document.getElementById('testName').textContent = 'Test Name:' +  ' ' + text;
        var remove = document.getElementById('remove');
        remove.textContent = 'Remove Test';
     
       
        
   

         
    }
    */
</script>
@endsection
