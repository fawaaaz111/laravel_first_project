@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}

                    <br><br><br>

                    <a href="/pkproject/public/posts" class="btn btn-primary">Create Post</a>
                    <br><br>
                    <h3>Your Blog Posts</h3>

                    <br><br>

                    @if (count($posts) > 0)
                    <table class="table table-striped">
                        <tr>
                            <th>Title</th>
                            <th></th>
                            <th></th>
                        </tr>
                        @foreach ($posts as $post)
                            
                        @endforeach
                        <tr>
                            <th>{{ $post->title }}</th>
                            <th>
                                <a href="/pkproject/public/posts/{{ $post->id }}/edit" class="btn btn-secondary">Edit</a>
                            </th>
                            <th></th>
                        </tr>
                    </table>
                    @else
                        <p>You have no posts</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
