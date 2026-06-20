@extends("layouts.app")

@section("content")
    <div class="container" style="max-width: 700px">
        <h3>Edit Article</h3>

        @if($errors->any())
            <div class="alert alert-warning">
                @foreach ($errors->all() as $err)
                    <div>{{ $err }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ url("/cms/articles/update/$article->id") }}" method="post" enctype="multipart/form-data">
            @csrf
            @method("PUT")
            <img src="{{ $article->featureImageUrl() }}" alt="{{ $article->title }}" class="img-fluid rounded mb-2">
            <input type="text" class="form-control mb-2" name="title" placeholder="Title" value="{{ old("title", $article->title) }}">
            <input type="file" class="form-control mb-2" name="feature_image" accept="image/*">
            @include("articles._markdown_editor", ["name" => "body", "value" => old("body", $article->body)])
            <select name="category_id" class="form-select mb-2">
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old("category_id", $article->category_id) == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <button class="btn btn-primary">Update Article</button>
            <a href="{{ url("/cms/articles") }}" class="btn btn-link">Cancel</a>
        </form>
    </div>
@endsection
