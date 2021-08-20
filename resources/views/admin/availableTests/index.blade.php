@extends('layouts.admin')
@section('content')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("available-test-create") }}">
                Add New Test
            </a>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            List of Available Tests
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover datatable datatable-Event">
                    <thead>
                    <tr>
                        <th>
                            Id
                        </th>
                        <th>
                            Category
                        </th>
                        <th>
                            Name
                        </th>
                        <th>
                            Test Fee
                        </th>
                        <th>
                            Urgent Fee
                        </th>
                        <th>
                        Stander Completion Time
                        </th>
                        <th>
                        Urgent Completion Time
                        </th>
                        <th>
                            Action
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($availableTests as $key => $availableTest)
                        <tr data-entry-id="{{ $availableTest->id }}">
                            <td>
                                {{ $availableTest->id ?? '' }}
                            </td>
                            <td>
                                {{ $availableTest->category->Cname ?? '' }}
                            </td>
                            <td>
                                {{ $availableTest->name ?? '' }}
                            </td>
                            <td>
                                {{ $availableTest->testFee ?? '' }}
                            </td>

                            <td>
                                {{ $availableTest->urgentFee ?? '' }}
                            </td>
                            <td>
                            @php
                            $seconds = $availableTest->stander_timehour;
                            $days = floor($seconds / (3600*24));
                            $hours = floor(($seconds / 3600) - $days*24);
                            $mins = floor($seconds / 60 % 60);
                            $secs = floor($seconds % 60);

                            echo $days ? $days . " days " : "";
                            echo $hours ? $hours . " hours " : "";
                            echo $mins ? $mins . " mins " : "";
                            echo $secs ? $secs . " secs" : "";

                            @endphp
                            
                            <!-- @if($availableTest->stander_timehour <= 0)
                                {{ $availableTest->stander_timehour  }}-Second
                              @elseif($availableTest->stander_timehour <= 1)
                                {{ $availableTest->stander_timehour  }}-Second
                              @elseif ($availableTest->stander_timehour <= 59 )
                               {{ $availableTest->stander_timehour  }}-Seconds
                               @elseif ($availableTest->stander_timehour <= 60)
                                {{ $availableTest->stander_timehour/60  }}-Minute
                              @elseif ($availableTest->stander_timehour > 60 && $availableTest->stander_timehour <= 3540)
                                {{ $availableTest->stander_timehour/60  }}-Minutes
                              @elseif ($availableTest->stander_timehour <= 3600)
                                {{ $availableTest->stander_timehour/3600  }}-Hour
                              @elseif ($availableTest->stander_timehour >= 3600 && $availableTest->stander_timehour <= 86399)
                                {{ $availableTest->stander_timehour/3600  }}-Hours
                              @elseif ($availableTest->stander_timehour <= 86400)
                                {{ $availableTest->stander_timehour/86400  }}-Day
                              @elseif ($availableTest->stander_timehour >= 86400)
                                {{ $availableTest->stander_timehour/86400  }}-Days
                              @else
                                {{ $availableTest->stander_timehour/86400  }}-Day
                            @endif  -->
                            </td>

                            <td>
                                @php
                                    $seconds =  $availableTest->urgent_timehour;
                                    $days = floor($seconds / (3600*24));
                                    $hours = floor(($seconds / 3600) - $days*24);
                                    $mins = floor($seconds / 60 % 60);
                                    $secs = floor($seconds % 60);

                                    echo $days ? $days . " days " : "";
                                    echo $hours ? $hours . " hours " : "";
                                    echo $mins ? $mins . " mins " : "";
                                    echo $secs ? $secs . " secs" : "";
                                @endphp
                            <!-- @if($availableTest->urgent_timehour <= 0)
                                {{ $availableTest->urgent_timehour  }}-Second
                              @elseif($availableTest->urgent_timehour <= 1)
                                {{ $availableTest->urgent_timehour  }}-Second
                              @elseif ($availableTest->urgent_timehour <= 59 )
                               {{ $availableTest->urgent_timehour  }}-Seconds
                               @elseif ($availableTest->urgent_timehour <= 60)
                                {{ $availableTest->urgent_timehour/60  }}-Minute
                              @elseif ($availableTest->urgent_timehour > 60 && $availableTest->urgent_timehour <= 3540)
                                {{ $availableTest->urgent_timehour/60  }}-Minutes
                              @elseif ($availableTest->urgent_timehour <= 3600)
                                {{ $availableTest->urgent_timehour/3600  }}-Hour
                              @elseif ($availableTest->urgent_timehour >= 3600 && $availableTest->urgent_timehour <= 86399)
                                {{ $availableTest->urgent_timehour/3600  }}-Hours
                              @elseif ($availableTest->urgent_timehour <= 86400)
                                {{ $availableTest->urgent_timehour/86400  }}-Day
                              @elseif ($availableTest->urgent_timehour >= 86400)
                                {{ $availableTest->urgent_timehour/86400  }}-Days
                              @else
                                {{ $availableTest->urgent_timehour/86400  }}-Day
                            @endif  -->
                            </td>
                            <td> 
                                <a class="btn btn-xs btn-primary" href="{{ route('availabel-tests-show', $availableTest->id) }}">
                                    {{ trans('global.view') }}
                                </a>
                                @if(auth::check() && Auth::user()->role == 'admin' || Auth::user()->role == 'manager')

                                <a class="btn btn-xs btn-info" href="{{ route('availabel-tests-edit', $availableTest->id) }}">
                                    {{ trans('global.edit') }}
                                </a>
                                <form method="POST" action="{{ route("avaiable-test-delete", [$availableTest->id]) }}" onsubmit="return confirm('{{ trans('Are You Sure to Deleted  ?') }}');" style="display: inline-block;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <script type="text/javascript">

        function searchTable()
        {
            console.log("search funtion");
            // Setup - add a text input to each footer cell
            $('.datatable thead tr').clone(true).appendTo( '.datatable thead' );

            $('.datatable thead tr:eq(1) th').each( function (i) {
                if(i==1 || i==2 || i==0){
                var title = $(this).text();
                $(this).html( '<input type="text" style="width:100%;" placeholder="Search" />' );
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
            
            searchTable();

            table = $('.datatable').DataTable({
                orderCellsTop: true,
                fixedHeader: true,
            });

            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });
        })

    </script>

@endsection