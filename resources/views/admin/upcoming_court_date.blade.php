@extends('layout.master')
@section('content')
    




    @php
    $chunks = $upComingCourtDates->chunk(15);
@endphp

    @foreach($chunks as $chunk)
        <div class="col-span-6">
            <div class="card">
                <div class="card-body">
                    <!--<h5 class="mb-4">Upcoming court dates</h5>-->
                    @forelse($chunk as $upComingCourtDate)
                        @php
                            $date = \Carbon\Carbon::parse($upComingCourtDate->court_date);
                            $day = $date->format('M d, D');
                            $time = $date->format('H:i');
                        @endphp
                        <div class="card overflow-hidden mb-2">
                            <div class="card-body !px-3 !py-2 border-l-4 border-danger-500">
                                <h6 class="mb-1">{{ $upComingCourtDate->name }}</h6>
                                <p class="mb-1"><i class="ti ti-calendar"></i> {{ $day }}</p>
                                <p class="mb-0"><span class="ti ti-calendar-time"></span> {{ $time }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="card overflow-hidden mb-2">
                            <div class="card-body !px-3 !py-2 border-l-4 border-danger-500">
                                No upcoming court dates!
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @endforeach






@endsection

@section('pre-scripts')
{{--    <script src="{{ asset('js/plugins/apexcharts.min.js') }}"></script>--}}
@endsection

@section('post-scripts')
{{--    <script src="{{ asset('js/widgets/invites-goal-chart.js') }}"></script>--}}
@endsection