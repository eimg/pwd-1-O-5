@extends("layouts.app")

@section("content")
    <div class="container" style="max-width: 600px">
        <h3>Edit Category</h3>

        @if($errors->any())
            <div class="alert alert-warning">
                @foreach ($errors->all() as $err)
                    <div>{{ $err }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ url("/cms/categories/update/$category->id") }}" method="post">
            @csrf
            @method("PUT")
            <input type="text" class="form-control mb-2" name="name" placeholder="Name" value="{{ old("name", $category->name) }}">
            <button class="btn btn-primary">Update Category</button>
            <a href="{{ url("/cms/categories") }}" class="btn btn-link">Cancel</a>
        </form>
    </div>
@endsection
