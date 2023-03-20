@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
        <form enctype="multipart/form-data" action="{{$user->id ? route('users.update') : route('users.post.create')}}" method="POST">
            @csrf
            @if($user->id)
            @method('PUT')
            <input type="hidden" name="id" value="{{$user->id}}">
            @endif
            <div class="form-group">
                <label for="prefixname">Firstname</label>
                <select name="prefixname" class="form-control" id="prefixname">
                    <option value="Mr" {{$user->prefixname == 'Mr' ? 'selected' : null}}>Mr</option>
                    <option value="Mrs" {{$user->prefixname == 'Mrs' ? 'selected' : null}}>Mrs</option>
                    <option value="Ms" {{$user->prefixname == 'Ms' ? 'selected' : null}}>Ms</option>
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
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" name="username" id="username" placeholder="Enter username" value="{{$user->username}}">
            </div>
            <div class="form-group">
                <label for="photo">Photo</label>
                <input type="file" class="form-control" id="photo" name="photo" placeholder="Enter username">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Password">
            </div>
                <button type="submit" class="btn btn-primary">Submit</button>
                <a class="btn btn-primary" href="{{route('users.index')}}">Back</a>
            </form>
        </div>
    </div>
</div>
@endsection
