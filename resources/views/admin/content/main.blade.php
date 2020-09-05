@extends('admin.admin')

@section('content')

    <div class="container">
        <div class="row m-4">
            <div class="col-12">
                <h1 class="display-4 text-center my-6">Create and Sync Content</h1>
                @if(\Illuminate\Support\Facades\Session::has('success'))
                    <div class="alert alert-success alert-dismissible">{{ \Illuminate\Support\Facades\Session::get('success') }}</div>
                @endif
            </div>
        </div>
        <div class="row">
            @foreach($contentViewModels as $viewModel)
                <?php /** @var \App\Admin\Content\ViewModels\ContentViewModel $viewModel */ ?>
                <div class="col-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{$viewModel->getTitle()}}</h5>
                            <h6 class="card-subtitle mb-2 text-muted">Total: {{$viewModel->totalCount()}}</h6>
                            <h6 class="card-subtitle mb-2 text-muted">Out of Sync: {{$viewModel->outOfSynCount()}}</h6>
                            <h6 class="card-subtitle mb-2 text-muted">Last Updated: {{$viewModel->lastUpdated()->diffForHumans()}}</h6>

                            <a href="{{\App\Facades\Content::viewURL($viewModel)}}" class="btn btn-primary btn-block mb-2">View</a>
                            <a href="{{\App\Facades\Content::createURL($viewModel)}}" class="btn btn-outline-primary btn-block mb-2">Create</a>
                            <form method="post" action="{{\App\Facades\Content::syncURL($viewModel)}}">
                                @csrf
                                <button type="submit" class="btn btn-outline-info btn-block" {{ $viewModel->outOfSynCount() > 0 ? '' : 'disabled' }}>Sync</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

@endsection
