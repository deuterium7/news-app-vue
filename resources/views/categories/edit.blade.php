@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="category-update">
                <h3 style="text-align: center;">@lang('catalog.updateCategory')</h3>
                {!! Form::open(['route' => ['categories.update', $category], 'method' => 'PUT']) !!}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="form-group">
                    <label for="name">@lang('catalog.nameCategory')</label>
                    {{ Form::text('name', $category->name, ['class'=>'form-control']) }}
                </div>
                <div class="form-group">
                    <div style="text-align: center">
                        <input type="submit" value="{{ trans('catalog.update') }}" class="btn btn-primary">
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
