@extends("layouts.app")

@section("content")
    <div class="container" style="max-width: 1000px">
        <h3>Manage Articles</h3>

        @if(session("info"))
            <div class="alert alert-info">{{ session("info") }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>Comments</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($articles as $article)
                        <tr>
                            <td>{{ $article->title }}</td>
                            <td>{{ $article->user->name }}</td>
                            <td>{{ $article->category->name }}</td>
                            <td>{{ count($article->comments) }}</td>
                            <td class="text-end">
                                <a href="{{ url("/articles/detail/$article->id") }}" class="btn btn-sm btn-outline-secondary">View</a>
                                <a href="{{ url("/cms/articles/edit/$article->id") }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ url("/cms/articles/delete/$article->id") }}" method="post" class="d-inline">
                                    @csrf
                                    @method("DELETE")
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-muted">No articles yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $articles->links() }}
    </div>
@endsection
