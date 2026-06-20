@extends("layouts.app")

@section("content")
    <div class="container" style="max-width: 700px">
        <h3>CMS</h3>

        <div class="list-group">
            <a href="{{ url("/cms/articles") }}" class="list-group-item list-group-item-action">
                Manage Articles
            </a>
            <a href="{{ url("/cms/categories") }}" class="list-group-item list-group-item-action">
                Manage Categories
            </a>
            <a href="{{ url("/cms/comments") }}" class="list-group-item list-group-item-action">
                Manage Comments
            </a>
            <a href="{{ url("/cms/users") }}" class="list-group-item list-group-item-action">
                Manage Users
            </a>
        </div>
    </div>
@endsection
