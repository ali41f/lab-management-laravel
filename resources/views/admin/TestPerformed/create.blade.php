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
    </style>
    <div class="card">
        <div class="card-header">
            Performed New Test
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('test-performed') }}" enctype="multipart/form-data">
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
                                <label class="required">Select Test Name</label>
                                <select class="form-control select2  {{ $errors->has('available_tests') ? 'is-invalid' : '' }}" onchange="set_test_form(this)" name="available_test_id[]" required>
                                    @foreach($availableTests as $id => $availableTest)
                                        <option value="{{ $id }}">{{ $availableTest }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('available_tests'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('available_tests') }}
                                    </div>
                                @endif
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
                                <label class="required">Test Type</label>
                                <select class="form-control" name="type[]">
                                    <option value="standard">Standard<p class="standard_fee"></p></option>
                                    <option value="urgent">Urgent<p class="urgent_fee"></p></option>
                                </select>
                            </div>
                        </div>


                        <div class="col-md-12 test_form">

                            @foreach($allAvailableTests as $test)

                                <div class="form-row" id="test{{$test->id}}" class="tests">
                                    <div class="col-md-12"><h4>{{$test->name}} result values</h4></div>
                                    @if($test->type==1)
                                        @foreach($test->TestReportItems->where("status","active") as $report_item)
                                            <div class="col-md-3 mb-3">
                                                <div class="form-group">
                                                    <label class="required text-capitalize">{{$report_item->title}} ({{$report_item->normalRange}}){{$report_item->unit}}</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="inputGroupPrepend">{{$report_item->unit}}</span>
                                                        </div>
                                                        <input class="form-control" type="number" name="testResult{{$report_item->id}}[]" value="">
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="col-md-12 mb-12">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <textarea class='w-100 col-12 ckeditor' name="ckeditor[]"></textarea>
                                                </div>
                                            </div>
                                        </div>
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

                        <div class="form-group shadow-textarea col-md-12">
                            <label>Comment</label>
                            <textarea class="form-control" rows="2" placeholder="Write comments  here..." name="comments[]"></textarea>

                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <p onclick="add_report()" class="btn btn-success">Add Test</p>
                        <hr>
                    </div>

                    <div class="col-md-3 mb-3">
                        <button class="btn btn-primary btn-lg" type="submit">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>

        var testHTML = document.getElementById("test_block").getElementsByClassName("test_form_div")[0].outerHTML;

        function set_test_form(select) {
            if (select.value) {
                select.parentElement.parentElement.parentNode.getElementsByClassName("test_form")[0].innerHTML = eval("test" + select.value);
                select.parentElement.parentElement.parentNode.getElementsByTagName("select")[2].getElementsByTagName("option")[1].innerText = "Urgent" + "(" + eval("test" + select.value + "_urgent") + ")";
                select.parentElement.parentElement.parentNode.getElementsByTagName("select")[2].getElementsByTagName("option")[0].innerText = "Standard" + "(" + eval("test" + select.value + "_standard") + ")";
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

        function add_report() {
            document.getElementById("test_block").insertAdjacentHTML('beforeend', testHTML);
            $('.select2').select2();
        }

    </script>
@endsection
