@extends('layout.master')
@section('content')
    <div class="col-span-12">
        <form action="{{ route('support.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-12 lg:col-span-12">
                    <div class="card">
                        <div class="card-header">
    <h5 class="text-primary text-[28px] font-bold">Support</h5>
                            <span class="text-sm text-muted">If you're encountering an issue please don't hesitate to contact us.</span>
                        </div>
                        <div class="card-body">
                            <div class="grid grid-cols-12 gap-6">
                                <div class="col-span-12 sm:col-span-12">
                                    <div class="col-span-12 md:col-span-3">
                                        <label class="form-label text-primary text-[18px] font-bold">Subject</label>
                                        <input type="text" class="form-control" name="subject" id="subject" value="{{ old('subject') }}" required/>
                                        @if ($errors->has('subject'))
                                            <span class="invalid-feedback text-danger">
                                                <strong>{{ $errors->first('subject') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-span-12 sm:col-span-12">
                                    <div class="mb-3">
                                        <label class="form-label text-primary text-[18px] font-bold" for="description">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="6" required>{{ old('description') }}</textarea>
                                        @if ($errors->has('description'))
                                            <span class="invalid-feedback text-danger">
                                                <strong>{{ $errors->first('description') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer flex flex-row-reverse">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
