<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class AdminClientsController extends Controller
{
    public function index()
    {
        $clients = Client::all();
        // dd($clients);
        return view('admin.admin-clients', compact('clients'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:15',
            'email' => 'nullable|email|max:255',
            'status' => 'required|in:Standard,Premium',
            'date' => 'required|date',
        ]);

        $data['avatarColor'] = $data['avatarColor'] ?? '#919090';

        Client::create($data);

        return redirect()->route('admin.clients');
    }

    public function update(Request $request, Client $client)
    {
        $data = $request->validate([
            'name' => 'required|string|max:15',
            'email' => 'nullable|email|max:255',
            'status' => 'required|in:Standard,Premium',
            'date' => 'required|date',
        ]);

        $client->update($data);

        return redirect()->route('admin.clients');
    }

    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('admin.clients');
    }
}
