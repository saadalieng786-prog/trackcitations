@extends('layout.master')

@section('content')
    @php $portal = auth()->user()->portalRoutePrefix(); @endphp
    <div class="col-span-12">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900 m-0 tracking-tight">Salesforce Sync Log</h1>
                <p class="text-sm text-slate-500 mt-1 mb-0">Latest fetch results for contacts, attachments, and files.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route($portal.'.salesforce.index') }}" class="btn btn-outline-secondary btn-sm whitespace-nowrap">Back to Salesforce</a>
                <form action="{{ route($portal.'.salesforce.sync') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-sm whitespace-nowrap">Run Sync Again</button>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success mb-4">{{ session('success') }}</div>
        @endif

        <div class="card overflow-hidden">
            <div class="card-header">
                <h5 class="mb-0">salesforce-sync.log</h5>
            </div>
            <div class="card-body p-0">
                <pre class="m-0 p-4 text-xs leading-relaxed overflow-auto bg-slate-50 dark:bg-transparent" style="max-height: 70vh; white-space: pre-wrap; word-break: break-word;">{{ $log }}</pre>
            </div>
        </div>
    </div>
@endsection
