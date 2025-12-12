<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    public function index()
    {
        $types = Type::all();
        return view('admin.data_rumah.type', compact('types'));
    }

    public function store(Request $request)
    {
        $request->validate(['nama' => 'required|string|max:255']);
        Type::create($request->only('nama'));

        return back()->with('success', 'Type rumah berhasil ditambahkan.');
    }

    public function update(Request $request, Type $type)
    {
        $request->validate(['nama' => 'required|string|max:255']);
        $type->update($request->only('nama'));

        return back()->with('success', 'Type rumah berhasil diperbarui.');
    }

    public function destroy(Type $type)
    {
        $type->delete();
        return back()->with('success', 'Type rumah berhasil dihapus.');
    }
}
