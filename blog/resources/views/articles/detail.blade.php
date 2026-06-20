@extends("layouts.app")

@section("content")
    <div class="container article-detail-container">

        @if(session("info"))
            <div class="alert alert-info">
                {{ session("info") }}
            </div>
        @endif

        <article class="card article-card article-detail-card mb-3 border-primary">
            <img src="{{ $article->featureImageUrl() }}" alt="{{ $article->title }}" class="card-img-top article-card-image">
            <div class="card-body">
                <h1 class="card-title article-detail-title">{{ $article->title }}</h1>
                <div class="article-meta">
                    <a href="{{ url("/users/{$article->user->id}") }}" class="text-success fw-bold text-decoration-none">{{ $article->user->name }}</a>,
                    <b>Category: </b> <a href="{{ url("/categories/{$article->category->id}") }}" class="text-decoration-none">{{ $article->category->name }}</a>,
                    {{ $article->created_at->diffForHumans() }}
                </div>
                <div class="markdown-body article-body">
                    {!! $article->bodyHtml() !!}
                </div>

                @can("delete-article", $article)
                    <a href="{{ url("/articles/edit/$article->id") }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <a href="{{ url("/articles/delete/$article->id") }}"        class="btn btn-sm btn-outline-danger">Delete</a>
                @endcan
            </div>
        </article>

        <ul class="list-group comments-list mt-4">
            <li class="list-group-item active">
                Comments ({{ count($article->comments) }})
            </li>
            @foreach($article->comments as $comment)
                <li class="list-group-item">
                    @can("delete-comment", $comment)
                        <a href="{{ url("/comments/delete/$comment->id") }}" class="btn-close float-end"></a>
                    @endcan

                    <a href="{{ url("/users/{$comment->user->id}") }}" class="text-success fw-bold text-decoration-none">{{ $comment->user->name }}</a> -
                    {{ $comment->content }}
                </li>
            @endforeach
        </ul>
        
        @auth
            <form action="{{ url("/comments/create") }}" method="post">
                @csrf
                <input type="hidden" name="article_id" value="{{ $article->id }}">
                <textarea name="content" class="form-control my-2"></textarea>
                <button class="btn btn-secondary">Add Comment</button>
            </form>
        @endauth
    </div>
@endsection
