@extends('layouts.admin')
@section('content')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("patient-create") }}">
            Register New Patient
            </a>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            List of All Patients
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="patientTable"class=" table table-bordered table-striped table-hover datatable datatable-Event">
                    <thead>
                        <tr>
                            <th>MR ID</th>
                            <th>Name</th>
                            <th>phone</th>
                            <th>email</th>
                            <th>Age</th>
                            <th>Registration</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>



<script type="text/javascript">
    
    function searchTable()
    {
        console.log("search funtion");
        // Setup - add a text input to each footer cell
        $('#patientTable thead tr').clone(true).appendTo( '#patientTable thead' );

        $('#patientTable thead tr:eq(1) th').each( function (i) {
            if(i==1 || i==2 || i==3 || i == 0){
              var title = $(this).text();
              console.log(i);
              $(this).html( '<input type="text" placeholder="Search" />' );
              $( 'input', this ).on( 'keyup change', function () {
                  if ( table.column(i).search() !== this.value ) {
                    table.column(i).search( this.value ).draw();
                  }
              });
            }else{
              $(this).html( '' );
            }
        });

        table = $('#patientTable').DataTable({
            orderCellsTop: true,
            fixedHeader: true
        });
        
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $( document ).ready(function() {
        
        // DataTable
        $('#patientTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{route('patients.getPatients')}}",
                method: 'POST'
            },
            pageLength: 100,
            "order": [[ 0, "desc" ]],
            columns: [
                { data: 'id' },
                { data: 'Pname' },
                { data: 'phone' },
                { data: 'email' },
                { data: 'dob' },
                { data: 'registration' },
                { data: 'action' },
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
    });
</script>


@endsection

