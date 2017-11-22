@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('catalog.dashboard')</div>

                <div class="panel-body">
                    @if (session('status'))
                        @component('components.session')
                        @endcomponent
                    @endif

                    @lang('catalog.loggedIn')!
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
