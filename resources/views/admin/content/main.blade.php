<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <title>Roster Heroes Admin</title>
</head>
<body>
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

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>
