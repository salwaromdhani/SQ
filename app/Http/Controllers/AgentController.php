<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Service;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function index(Request $request)
    {
        $agents = Agent::with('service')
            ->when($request->role, function ($query) use ($request) {
                $query->where('role', $request->role);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('agents.index', compact('agents'));
    }

    public function create()
    {
        $services = Service::where('active', 1)->get();
        return view('agents.create', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:agents,email',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,agent,superviseur',
            'service_id' => 'nullable|exists:services,id',
        ]);

        Agent::create($validated);

        return redirect()->route('agents.index')
            ->with('success', 'Agent ajouté avec succès.');
    }

    public function show(Agent $agent)
    {
        return view('agents.show', compact('agent'));
    }

    public function edit(Agent $agent)
    {
        $services = Service::where('active', 1)->get();
        return view('agents.edit', compact('agent', 'services'));
    }

    public function update(Request $request, Agent $agent)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:agents,email,' . $agent->id,
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,agent,superviseur',
            'service_id' => 'nullable|exists:services,id',
        ]);

        $agent->update($validated);

        return redirect()->route('agents.show', $agent)
            ->with('success', 'Agent mis à jour.');
    }

    public function destroy(Agent $agent)
    {
        $agent->delete();

        return redirect()->route('agents.index')
            ->with('success', 'Agent supprimé.');
    }
}