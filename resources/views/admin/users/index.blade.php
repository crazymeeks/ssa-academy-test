@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Users') }} <a href="{{route('users.create')}}" class="btn btn-primary">Add new User</a></div>

                <div class="card-body">
                    <table class="table user-listing">
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
                                    <a href="{{route('users.show', ['id' => $user->id])}}">View</a>
                                    <a href="{{route('users.edit', ['id' => $user->id])}}">Edit</a>
                                    @if(auth()->user()->id != $user->id)
                                    <a href="javascript:void(0);" class="action-delete" data-id="{{$user->id}}">Delete</a>
                                    @else
                                    <a  style="color: #999; cursor: not-allowed;" href="javascript:void(0);">Delete</a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            <?php
                            $total = $users->total() - 1;
                            $currentPage = $users->currentPage();
                            $previous = $currentPage == 1 ? sprintf('/users?page=%s', 1) : sprintf('/users?page=%s', ($currentPage - 1));
                            $next = $currentPage == $total ? sprintf('/users?page=%s', $total) : sprintf('/users?page=%s', ($currentPage + 1));
                            ?>
                            <li class="page-item"><a class="page-link" href="{{$previous}}">Previous</a></li>
                            @for($a = 0; $a < $total; $a++)
                            <li class="page-item"><a class="page-link {{$currentPage == ($a+1) ? 'active' : ''}}" href="/users?page={{$a+1}}">{{$a+1}}</a></li>
                            @endfor
                            <li class="page-item"><a class="page-link" href="{{$next}}">Next</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
