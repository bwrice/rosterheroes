@extends('admin.admin')

@section('content')

    <div class="container">
        <div class="row m-4">
            <div class="col-12">
                <h1 class="display-4 text-center my-6">Create and Sync Content</h1>
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

                            <button type="button" class="btn btn-primary btn-block">Create Attack</button>
                            <button type="button" class="btn btn-outline-info btn-block" {{ $viewModel->totalCount() === $viewModel->outOfSynCount() ? 'disabled' : '' }}>Sync Attacks</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

@endsection
