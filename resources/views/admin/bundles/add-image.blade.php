@extends('layouts.width6')

@section('content')
    <div class="card">
        <div class="card-header-flex">
            <div class="card-header-text">
                <i class="fas fa-box-open"></i>
                Add Image
            </div>
        </div>

        <div class="card-body">
            <form action="/admin/bundles/{{ $bundle->id }}/add-image" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="input-group-row">
                    <label for="image" class="col-md-4 col-form-label text-md-end">Image</label>

                    <div class="col-md-6">
                        <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" value="{{ old('image') }}" autocomplete="image" autofocus>

                        @error('image')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="input-group-row mb-0">
                    <div class="col-md-3 offset-md-4">
                        <button type="submit" class="big-button-primary">
                            Submit
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
