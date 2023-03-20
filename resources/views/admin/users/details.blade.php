@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="form-group">
               <a class="btn btn-primary" href="{{route('users.index')}}">Back to Users</a>
            </div>
            <div class="form-group">
                <label for="prefixname">Firstname</label>
                <select name="prefixname" class="form-control" id="prefixname">
                    <option value="{{$user->prefixname}}">{{$user->prefixname}}</option>
                </select>
            </div>
            <div class="form-group">
                <label for="firstname">Firstname</label>
                <input type="text" class="form-control" name="firstname" id="firstname" placeholder="Enter Firstname" value="{{$user->firstname}}">
            </div>
            <div class="form-group">
                <label for="lastname">Lastname</label>
                <input type="text" class="form-control" name="lastname" id="lastname" placeholder="Enter Lastname" value="{{$user->lastname}}">
            </div>
            <div class="form-group">
                <label for="suffixname">Suffix Name</label>
                <input type="text" class="form-control" id="suffixname" name="suffixname" placeholder="Enter Suffix" value="{{$user->suffixname}}">
            </div>
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp" placeholder="Enter email" value="{{$user->email}}">
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" name="username" id="username" placeholder="Enter username" value="{{$user->username}}">
            </div>
            <div class="form-group">
                <img src="/{{$user->avatar}}" style="width: 100px; height: auto;">
            </div>

            
        </div>
    </div>
</div>
@endsection
