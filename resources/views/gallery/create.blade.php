@extends('layouts.master')

@section('title','image create')

@section('content')

 <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 mt-5">
                <form action="{{route('gallery.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" name="image" multiple
                                class="custom-file-input @error('image') is-invalid @enderror">
                            @error('image')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <label class="custom-file-label" for="inputGroupFile03">Choose file</label>
                        </div>
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">Upload</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

@endsection