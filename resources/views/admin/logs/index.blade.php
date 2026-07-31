@extends('layout.master')
@section('content')
    <div class="col-span-12 lg:col-span-12">
        <div class="card">
            <div class="card-body">
                <div class="flex items-center justify-between mb-3">
                    <h5 class="mb-0">Logs</h5>
                    <div class="dropdown">
                        <a
                            class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary dropdown-toggle arrow-none"
                            href="#"
                            data-pc-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                        >
                            <i class="ti ti-dots-vertical text-lg leading-none"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="#">Today</a>
                            <a class="dropdown-item" href="#">Weekly</a>
                            <a class="dropdown-item" href="#">Monthly</a>
                        </div>
                    </div>
                </div>
                <ul class="rounded-lg *:py-3 divide-y divide-inherit border-theme-border dark:border-themedark-border">
                    @foreach($logs as $log)
                        <li class="list-group-item">
                            <div class="flex items-center">
                                <div class="shrink-0">
                                    <span class="fa fa-info"></span>
                                </div>
                                <div class="grow mx-3">
                                    <h6 class="mb-0">{{ $log->description }}</h6>
                                </div>
                                <div class="shrink-0">
                                    <p class="mb-0 text-muted">
                                        {{ \Carbon\Carbon::parse($log->created_at)->format('M j, Y | g:i A') }}
                                    </p>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="card-footer">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
@endsection
