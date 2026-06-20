@extends("layouts.app")

@section("content")
    <div class="container" style="max-width: 700px">
        <h3>Edit Comment</h3>

        @if($errors->any())
            <div class="alert alert-warning">
                @foreach ($errors->all() as $err)
                    <div>{{ $err }}</div>
                @endforeach
            </div>
        @endif

        <div class="text-muted mb-2">
            {{ $comment->user->name }} on
            <a href="{{ url("/articles/detail/{$comment->article->id}") }}">{{ $comment->article->title }}</a>
        </div>

        <form action="{{ url("/cms/comments/update/$comment->id") }}" method="post">
            @csrf
            @method("PUT")
            <textarea name="content" class="form-control mb-2" rows="5">{{ old("content", $comment->content) }}</textarea>
            <button class="btn btn-primary">Update Comment</button>
            <a href="{{ url("/cms/comments") }}" class="btn btn-link">Cancel</a>
        </form>
    </div>
@endsection
