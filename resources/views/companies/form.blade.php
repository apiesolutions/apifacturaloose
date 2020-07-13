@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-6 col-md-12 pt-2 pt-md-0">
            <companies-form></companies-form>
        </div>
        <div class="col-lg-6 col-md-12">
            <certificates-index></certificates-index>
        </div>
        <div class="col-lg-6 col-md-12">
            <options-form></options-form>
        </div>
    </div>
@endsection