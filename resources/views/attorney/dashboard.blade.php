@extends('layout.master')
@section('content')
    <div class="col-span-12 lg:col-span-12">
        <div class="card">
            <div class="card-header">
                <div class="sm:flex items-center justify-between">
                    <h5>Tickets</h5>
                    <a href="{{ route('attorney.tickets.index') }}" class="btn btn-secondary">View All</a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered yajra-datatable">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Date Received</th>
                        <th>State</th>
                        <th>Company</th>
                        <th>Indicator</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($tickets as $ticket)
                        <tr>
                            <td>{{ $ticket->id }}</td>
                            <td>{{ $ticket->name }}</td>
                            <td>{{ $ticket->date_issued }}</td>
                            <td>{{ $ticket->state }}</td>
                            <td>{{ $ticket->company->name }}</td>
                            <td>{{ $ticket->indicator }}</td>
                            <td>
                                <a href="{{ route('attorney.tickets.show', $ticket->id) }}" class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                    <i class="ti ti-eye text-xl leading-none"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                <h4 class="my-4">
                                    You don't have any tickets
                                </h4>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('pre-scripts')
    <script src="{{ asset('js/plugins/apexcharts.min.js') }}"></script>
@endsection

@section('post-scripts')
    <script src="{{ asset('js/widgets/invites-goal-chart.js') }}"></script>
@endsection
