@if($users->total() > 1)
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
@endif