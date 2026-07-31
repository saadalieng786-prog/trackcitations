@extends('layout.master')
@section('content')
    <div class="col-span-12">
        <form action="{{ route(auth()->user()->portalRoutePrefix().'.violations.update', $violation->id) }}" method="POST">
            @method('PUT')
            @csrf
            <div class="tab-content">
                <div class="block tab-pane" id="citation">
                    <div class="grid grid-cols-12 gap-6">
                        <div class="col-span-12 lg:col-span-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="text-primary text-[28px] font-bold">Citation Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="grid grid-cols-12 gap-6">
                                        <div class="col-span-12">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold" for="violation">Citation</label>
                                                <input type="text"  name="violation" id="violation" class="form-control" value="{{ old('violation', $violation->violation) }}" required autofocus />
                                                @if ($errors->has('violation'))
                                                    <span class="invalid-feedback text-danger">
                                                            <strong>{{ $errors->first('violation') }}</strong>
                                                        </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 text-right">
                    <button type="reset" class="btn btn-outline-secondary mx-1">Cancel</button>
                    <button type="submit" class="btn btn-primary mx-1">Update Citation</button>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('post-scripts')
@endsection
@section('css')

@endsection
