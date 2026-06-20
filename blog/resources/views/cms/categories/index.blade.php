@extends("layouts.app")

@section("content")
    <div class="container" style="max-width: 600px">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Categories</h3>
            <a href="{{ url("/cms/categories/add") }}" class="btn btn-primary btn-sm">Add Category</a>
        </div>

        @if(session("info"))
            <div class="alert alert-info">
                {{ session("info") }}
            </div>
        @endif

        <ul class="list-group">
            @forelse($categories as $category)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>{{ $category->name }}</span>
                    <span class="d-flex gap-2">
                        <a href="{{ url("/cms/categories/edit/$category->id") }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form action="{{ url("/cms/categories/delete/$category->id") }}" method="post">
                            @csrf
                            @method("DELETE")
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </span>
                </li>
            @empty
                <li class="list-group-item text-muted">No categories yet.</li>
            @endforelse
        </ul>
    </div>
@endsection
