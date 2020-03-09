@extends('layouts.app')

@section('title', 'Vatbook')

@section('content')
<h1 class="h3 mb-4 text-gray-800">Vatbook</h1>

<div class="row">
    <div class="col-xl-12 col-md-12 mb-12">
        <div class="card shadow mb-4">
            <div class="card-header bg-primary py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-white">Booked Sessions</h6> 
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-sm table-hover table-leftpadded mb-0" width="100%" cellspacing="0"
                        data-toggle="table"
                        data-pagination="true"
                        data-strict-search="true"
                        data-filter-control="true">
                        <thead class="thead-light">
                            <tr>
                                <th data-sortable="true" data-filter-control="select">Date</th>
                                <th data-filter-control="select">Start (Zulu)</th>
                                <th data-filter-control="select">End (Zulu)</th>
                                <th data-sortable="true" data-filter-control="select">Position</th>
                                <th data-sortable="true" data-filter-control="select">FIR</th>
                                <th data-sortable="true" data-filter-control="select">Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $booking)
                            <tr>
                                <td> 
                                    {{ date('F d, Y', strtotime($booking[0]->time_start)) }}        
                                </td>
                                <td>
                                    {{ date('H:i', strtotime($booking[0]->time_start)) }}z
                                </td>
                                <td>
                                    {{ date('H:i', strtotime($booking[0]->time_end)) }}z
                                </td>
                                <td>
                                    {{ $booking[0]->callsign }} ({{ $booking[1] }})
                                </td>
                                <td>
                                    {{ $booking[2] }}
                                </td>
                                <td>
                                    {{ $booking[0]->name }} ({{ $booking[0]->cid }})
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
        <div class="align-items-left">
            <a href="{{ route('vatbook.create') }}" class="btn btn-success">Add Booking</a>
        </div>
    </div>
    
</div>

@endsection

@section('js')
<script>
    //Activate bootstrap tooltips
    $(document).ready(function() {
        $('div').tooltip();
    })
</script>
@endsection