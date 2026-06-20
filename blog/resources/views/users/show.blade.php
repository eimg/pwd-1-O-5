@extends("layouts.app")

@section("content")
    <div class="container profile-container">
        <div class="card profile-card mb-4 border-primary">
            <div class="profile-banner">
                <div class="profile-avatar">
                    {{ strtoupper(substr($profileUser->name, 0, 1)) }}
                </div>
            </div>
            <div class="card-body text-center pt-5">
                <h1 class="card-title profile-name mb-1">{{ $profileUser->name }}</h1>
                <div class="text-muted mb-3">{{ $profileUser->email }}</div>

                <div class="row text-center">
                    <div class="col">
                        <div class="fw-bold">{{ $profileUser->articles()->count() }}</div>
                        <div class="text-muted small">Articles</div>
                    </div>
                    <div class="col">
                        <div class="fw-bold">{{ $profileUser->created_at->format("M j, Y") }}</div>
                        <div class="text-muted small">Joined</div>
                    </div>
                </div>
            </div>
        </div>

        <h2 class="section-title mb-3">Articles by {{ $profileUser->name }}</h2>

        @forelse($articles as $article)
            <article class="card article-card mb-3">
                <img src="{{ $article->featureImageUrl() }}" alt="{{ $article->title }}" class="card-img-top article-card-image">
                <div class="card-body">
                    <h3 class="card-title article-title">{{ $article->title }}</h3>
                    <div class="article-meta">
                        <b>Category: </b> <a href="{{ url("/categories/{$article->category->id}") }}" class="text-decoration-none">{{ $article->category->name }}</a>,
                        <b>Comments: </b> {{ count($article->comments) }},
                        {{ $article->created_at->diffForHumans() }}
                    </div>
                    <div class="markdown-body article-body">
                        {!! $article->bodyHtml() !!}
                    </div>
                    <a href="{{ url("/articles/detail/$article->id") }}" class="card-link">View Detail</a>
                </div>
            </article>
        @empty
            <div class="alert alert-info">
                This user has not published any articles yet.
            </div>
        @endforelse

        <div class="mt-3">
            {{ $articles->links() }}
        </div>
    </div>
@endsection
