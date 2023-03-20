@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Trash') }} <a href="{{route('users.index')}}">Back to List of Users</a></div>

                <div class="card-body">
                    <table class="table user-trash">
                        <thead>
                            <tr>
                                <th>Fullname</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{$user->fullname}}</td>
                                <td>{{$user->email}}</td>
                                <td>
                                    <a href="javascript:void(0);" class="action-restore" data-id="{{$user->id}}">Restore</a>
                                    <a href="javascript:void(0);" class="action-permanently-delete" data-id="{{$user->id}}">Delete Permanently</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @include('admin.users.pagination')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
