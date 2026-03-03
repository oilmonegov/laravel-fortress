<?php

declare(strict_types=1);

namespace App\Http\Controllers;

class BadController
{
    public function search($request)
    {
        $name = $request->input('name');
        $results = DB::select("SELECT * FROM users WHERE name = '{$name}'");
        dd($results);
    }

    public function store($request)
    {
        $data = $request->all();
        User::create($data);
    }

    public function redirect($request)
    {
        return redirect($request->input('url'));
    }
}
