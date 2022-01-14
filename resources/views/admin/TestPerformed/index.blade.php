@extends('layouts.admin')
    @section('content')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route("create") }}">
                  Performed New Test
                </a>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                List of Performed Tests
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="performedTable" class="table table-bordered table-striped table-hover datatable datatable-Event">
                    <thead>
                        <tr>
                            <th>Test ID</th>
                            <th>Name</th>
                            <th>Patient</th>
                            <th>Specimen</th>
                            <th>Ref by</th>
                            <th>Testcreated</th>
                            <th>Status</th>
                            <th>SMS</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    
                </table>
                <form class="d-none" id="report" method="post" action="{{route("patient-view-multiple-report")}}">
                    @csrf
                    <div id="form_block">

                    </div>
                </form>
            </div>
        </div>
    </div>



<script type="text/javascript">

        function searchTable()
        {
            // Setup - add a text input to each footer cell
            $('#performedTable thead tr').clone(true).appendTo( '#performedTable thead' );

            $('#performedTable thead tr:eq(1) th').each( function (i) {
                if( i==2 || i==3 || i==4|| i==5){
                    var title = $(this).text();
                    if(i==5){
                        $(this).html( '<input type="text" style="width:90px;" placeholder="Search" />' );
                    }else if(i==2){
                        $(this).html( '<input type="text" style="width:100px;" placeholder="Search" />' );
                    }else{
                        $(this).html( '<input type="text" style="width:100%;" placeholder="Search" />' );
                    }
                    $( 'input', this ).on( 'keyup change', function () {
                        if ( table.column(i).search() !== this.value ) {
                            table.column(i).search( this.value ).draw();
                        }
                    });
                }else{
                $(this).html( '' );
                }
            });
            
        }

        $(function () {
            

            //searchTable();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#performedTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{route('testperformeds.getTests')}}",
                    method: 'POST'
                },
                pageLength: 100,
                "order": [[ 0, "desc" ]],
                columns: [
                    { data: 'id' },
                    { data: 'Name' },
                    { data: 'patient_id' },
                    { data: 'Specimen' },
                    { data: 'referred' },
                    { data: 'created_at' },
                    { data: 'Status' },
                    { data: 'sms' },
                    { data: 'Action' },
                ]
            });
        

            $(document).on('click','.show_confirm',function() {
                var url = $(this).attr('rel');
                if(confirm("Are you sure you want to delete this?")){
                window.location.href = url
                }
                else{
                    return false;
                }
            })

        
        })

    </script>
@endsection