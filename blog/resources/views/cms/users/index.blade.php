@extends("layouts.app")

@section("content")
    <div class="container" style="max-width: 1000px">
        <h3>Manage Users</h3>

        @if(session("info"))
            <div class="alert alert-info">{{ session("info") }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Articles</th>
                        <th>Comments</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role }}</td>
                            <td>{{ $user->articles_count }}</td>
                            <td>{{ $user->comments_count }}</td>
                            <td class="text-end">
                                <a href="{{ url("/users/$user->id") }}" class="btn btn-sm btn-outline-secondary">View</a>
                                <a href="{{ url("/cms/users/edit/$user->id") }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ url("/cms/users/delete/$user->id") }}" method="post" class="d-inline">
                                    @csrf
                                    @method("DELETE")
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-muted">No users yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $users->links() }}
    </div>
@endsection
