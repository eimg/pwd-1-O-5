@extends("layouts.app")

@section("content")
    <div class="container page-container">
        @if(session("info"))
            <div class="alert alert-info">
                {{ session("info") }}
            </div>
        @endif

        <div class="row g-4 align-items-start">
            <div class="col-lg-8">
                @if($selectedCategory)
                    <div class="alert alert-primary">
                        Browsing {{ $selectedCategory->name }}
                    </div>
                @endif

                @forelse($articles as $article)
                    <article class="card article-card mb-3">
                        <img src="{{ $article->featureImageUrl() }}" alt="{{ $article->title }}" class="card-img-top article-card-image">
                        <div class="card-body">
                            <h2 class="card-title article-title">{{ $article->title }}</h2>
                            <div class="article-meta">
                                <a href="{{ url("/users/{$article->user->id}") }}" class="text-success fw-bold text-decoration-none">{{ $article->user->name }}</a>,
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
                        No articles found.
                    </div>
                @endforelse

                <div class="mt-3">
                    {{ $articles->links() }}
                </div>
            </div>

            <aside class="col-lg-4">
                <div class="list-group category-browser">
                    <a href="{{ url("/articles") }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center @if(!$selectedCategory) active @endif">
                        <span>All Categories</span>
                        <span class="badge bg-secondary rounded-pill">{{ $categories->sum("articles_count") }}</span>
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ url("/categories/$category->id") }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center @if($selectedCategory && $selectedCategory->id == $category->id) active @endif">
                            <span>{{ $category->name }}</span>
                            <span class="badge bg-secondary rounded-pill">{{ $category->articles_count }}</span>
                        </a>
                    @endforeach
                </div>
            </aside>
        </div>
    </div>
@endsection
