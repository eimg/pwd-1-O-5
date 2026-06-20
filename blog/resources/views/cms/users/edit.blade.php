@extends("layouts.app")

@section("content")
    <div class="container" style="max-width: 700px">
        <h3>Edit User</h3>

        @if($errors->any())
            <div class="alert alert-warning">
                @foreach ($errors->all() as $err)
                    <div>{{ $err }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ url("/cms/users/update/$managedUser->id") }}" method="post">
            @csrf
            @method("PUT")
            <input type="text" class="form-control mb-2" name="name" placeholder="Name" value="{{ old("name", $managedUser->name) }}">
            <input type="email" class="form-control mb-2" name="email" placeholder="Email" value="{{ old("email", $managedUser->email) }}">
            <select name="role" class="form-select mb-2">
                <option value="user" @selected(old("role", $managedUser->role) === "user")>User</option>
                <option value="admin" @selected(old("role", $managedUser->role) === "admin")>Admin</option>
            </select>
            <button class="btn btn-primary">Update User</button>
            <a href="{{ url("/cms/users") }}" class="btn btn-link">Cancel</a>
        </form>
    </div>
@endsection
