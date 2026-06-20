@extends("layouts.app")

@section("content")
    <div class="container" style="max-width: 600px">

        @if($errors->any())
            <div class="alert alert-warning">
                @foreach ($errors->all() as $err)
                    {{ $err }}
                @endforeach
            </div>
        @endif

        <form action="{{ url("/articles/create") }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="text" class="form-control mb-2" name="title" placeholder="Title" value="{{ old("title") }}">
            <input type="file" class="form-control mb-2" name="feature_image" accept="image/*">
            @include("articles._markdown_editor", ["name" => "body", "value" => old("body")])
            <select name="category_id" class="form-select mb-2">
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old("category_id") == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <button class="btn btn-primary">Add Article</button>
        </form>
    </div>
@endsection
