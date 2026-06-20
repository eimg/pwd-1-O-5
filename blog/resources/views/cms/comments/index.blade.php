@extends("layouts.app")

@section("content")
    <div class="container" style="max-width: 1000px">
        <h3>Manage Comments</h3>

        @if(session("info"))
            <div class="alert alert-info">{{ session("info") }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Comment</th>
                        <th>User</th>
                        <th>Article</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($comments as $comment)
                        <tr>
                            <td>{{ $comment->content }}</td>
                            <td>{{ $comment->user->name }}</td>
                            <td>{{ $comment->article->title }}</td>
                            <td class="text-end">
                                <a href="{{ url("/articles/detail/{$comment->article->id}") }}" class="btn btn-sm btn-outline-secondary">View</a>
                                <a href="{{ url("/cms/comments/edit/$comment->id") }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ url("/cms/comments/delete/$comment->id") }}" method="post" class="d-inline">
                                    @csrf
                                    @method("DELETE")
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-muted">No comments yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $comments->links() }}
    </div>
@endsection
