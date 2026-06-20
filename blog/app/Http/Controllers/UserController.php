<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        $articles = $user->articles()
            ->with(["category", "comments"])
            ->latest()
            ->paginate(5);

        return view("users.show", [
            "profileUser" => $user,
            "articles" => $articles,
        ]);
    }
}
