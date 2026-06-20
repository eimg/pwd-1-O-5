<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index()
    {
        $this->authorizeCms();

        return view("cms.users.index", [
            "users" => User::withCount(["articles", "comments"])->latest()->paginate(15),
        ]);
    }

    public function edit(string $id)
    {
        $this->authorizeCms();

        return view("cms.users.edit", [
            "managedUser" => User::findOrFail($id),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $this->authorizeCms();

        $user = User::findOrFail($id);

        $request->validate([
            "name" => "required|max:255",
            "email" => ["required", "email", "max:255", Rule::unique("users")->ignore($user->id)],
            "role" => ["required", Rule::in(["admin", "user"])],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->save();

        return redirect("/cms/users")->with("info", "User is updated");
    }

    public function delete(string $id)
    {
        $this->authorizeCms();

        $user = User::findOrFail($id);

        if($user->id === auth()->id()) {
            return back()->with("info", "Cannot delete your own account");
        }

        if($user->articles()->exists() || $user->comments()->exists()) {
            return back()->with("info", "Cannot delete a user with articles or comments");
        }

        $user->delete();

        return redirect("/cms/users")->with("info", "User is deleted");
    }

    private function authorizeCms()
    {
        abort_unless(auth()->user()->isAdmin(), 403);
    }
}
