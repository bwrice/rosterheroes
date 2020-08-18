@extends('admin.admin')

@section('content')

    <div class="container">
        <div class="row my-4">
            <div class="col-12">
                <h1 class="text-center">{{ucwords(str_replace('-', ' ', $contentType))}}</h1>
                @if(\Illuminate\Support\Facades\Session::has('success'))
                    <div class="alert alert-success alert-dismissible">{{ \Illuminate\Support\Facades\Session::get('success') }}</div>
                @endif
            </div>
        </div>
        <div class="row">
            @yield('index')
            <div class="col-4 offset-4">
                <ul class="pagination">
                    @if($page > 3)
                        <li class="page-item"><a class="page-link" href="/admin/content/{{$contentType}}?page={{$page-3}}">{{$page - 3}}</a></li>
                    @endif
                    @if($page > 2)
                        <li class="page-item"><a class="page-link" href="/admin/content/{{$contentType}}?page={{$page-2}}">{{$page - 2}}</a></li>
                    @endif
                    @if($page > 1)
                        <li class="page-item"><a class="page-link" href="/admin/content/{{$contentType}}?page={{$page-1}}">{{$page - 1}}</a></li>
                    @endif
                    <li class="page-item disabled active"><a class="page-link" href="#">{{$page}}</a></li>
                    @if($page < $totalPages)
                        <li class="page-item"><a class="page-link" href="/admin/content/{{$contentType}}?page={{$page+1}}">{{$page + 1}}</a></li>
                    @endif
                    @if($page < $totalPages - 1)
                        <li class="page-item"><a class="page-link" href="/admin/content/{{$contentType}}?page={{$page+2}}">{{$page + 2}}</a></li>
                    @endif
                    @if($page < $totalPages - 2)
                        <li class="page-item"><a class="page-link" href="/admin/content/{{$contentType}}?page={{$page+3}}">{{$page + 3}}</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </div>

@endsection
