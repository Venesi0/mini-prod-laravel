<?php

namespace App\Http\Controllers;

use App\Models\Collaborator;
use Illuminate\Http\Request;

class CollaboratorsController extends Controller
{
    public function index()
    {
        $collaborators = Collaborator::all();
        return view('collaborators', compact('collaborators'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name' => 'required|string|max:150',
            'email' => 'required|email|max:255',
            'position' => 'required|string|max:120',
            'avatar_color' => 'nullable|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
        ]);

        Collaborator::create($data);

        return redirect()->route('collaborators');
    }

    public function storeApi(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:150',
            'email' => 'required|email|max:255',
            'position' => 'required|string|max:120',
            'avatar_color' => 'nullable|regex:/^#([A-Fa-f0-9]{6})$/',
        ]);

        $collaborator = Collaborator::create($validated);

        return response()->json([
            'message' => 'Collaborateur added with success.',
            'collaborator' => [
                'id' => $collaborator->id_collab,
                'full_name' => $collaborator->full_name,
                'email' => $collaborator->email,
                'position' => $collaborator->position,
                'avatar_color' => $collaborator->avatar_color,
                'index_url' => route('collaborators', $collaborator->id_collab),
                'destroy_url' => route('collaborators.destroy', $collaborator->id_collab),
            ],
        ], 201);
    }

    public function update(Request $request, Collaborator $collaborator)
    {
        $data = $request->validate([
            'work_status' => 'required|in:Available,On project,Vacation / away',
            'position' => 'required|string|max:120',
        ]);

        $collaborator->update($data);

        return redirect()->route('collaborators');
    }

    public function destroy(Collaborator $collaborator)
    {
        $collaborator->delete();

        return redirect()->route('collaborators');
    }
}
