@extends('layouts.dashboard.app')
@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <h1> @lang('site.users') </h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a> </li>
                <li><a href="{{ route('dashboard.users.index') }}"><i class="fa fa-user"></i>@lang('site.users')</a> </li>
                <li class="active"><i class="fa fa-user"></i> @lang('site.edit')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title"> @lang('site.edit') </h3>
                </div>
                <div class="box-body">

                    @include('partials._errors')
                    <form action="{{ route('dashboard.users.update', $user->id) }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        {{ method_field('put') }}

                        <div class="form-group">
                            <label> @lang('site.first_name') </label>
                            <input type="text" name="first_name" class="form-control" value="{{ $user->first_name }}" >
                        </div>

                        <div class="form-group">
                            <label> @lang('site.last_name') </label>
                            <input type="text" name="last_name" class="form-control" value="{{ $user->last_name }}" >
                        </div>

                        <div class="form-group">
                            <label> @lang('site.email') </label>
                            <input type="email" name="email" class="form-control" value="{{ $user->email }}" >
                        </div>

                        <div class="form-group">
                            <label> @lang('site.image') </label>
                            <input type="file" name="image" class="form-control image">
                        </div>

                        <div class="form-group">
                            <img src="{{  $user->ImagePath }}" style="width: 100px" class="img-thumbnail img-preview" alt="User Image">
                        </div>

                        <div class="form-group">
                            <label>@lang('site.permissions')</label>
                            <div class="nav-tabs-custom">

                                @php
                                    $models = ['users', 'categories', 'products', 'clients', 'orders'];
                                    $maps = ['create', 'read', 'update', 'delete'];
                                @endphp

                                <ul class="nav nav-tabs">
                                    @foreach ($models as $index=>$model)
                                        <li class="{{ $index == 0 ? 'active' : '' }}">
                                            <a href="#{{ $model }}" data-toggle="tab">@lang('site.' . $model)</a>
                                        </li>
                                    @endforeach
                                </ul>

                                <div class="tab-content">

                                    @foreach ($models as $index=>$model)

                                        <div class="tab-pane {{ $index == 0 ? 'active' : '' }}" id="{{ $model }}">

                                            @foreach ($maps as $map)
                                                <label>
                                                    <input type="checkbox" {{ $user->hasPermission($map . '_' . $model) ? 'checked' : '' }}
                                                    name="permissions[]" value="{{ $map . '_' . $model }}">
                                                    @lang('site.' . $map)
                                                </label>
                                            @endforeach

                                        </div>

                                    @endforeach

                                </div><!-- end of tab content -->

                            </div><!-- end of nav tabs -->

                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"> <i class="fa fa-edit"></i> @lang('site.edit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection
