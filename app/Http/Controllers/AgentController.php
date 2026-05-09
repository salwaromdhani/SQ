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
            ->when($request->role, function ($query, $role) {
                $query->where('role', $role);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('agents.index', compact('agents'));
    }

    public function create()
    {
        $services = $this->getActiveServices();

        return view('agents.create', compact('services'));
    }

    public function store(Request $request)
    {
        $data = $this->validateAgent($request);

        Agent::create($data);

        return redirect()
            ->route('agents.index')
            ->with('success', 'Agent ajouté avec succès.');
    }

    public function show(Agent $agent)
    {
        return view('agents.show', compact('agent'));
    }

    public function edit(Agent $agent)
    {
        $services = $this->getActiveServices();

        return view('agents.edit', compact('agent', 'services'));
    }

    public function update(Request $request, Agent $agent)
    {
        $data = $this->validateAgent($request, $agent->id);

        $agent->update($data);

        return redirect()
            ->route('agents.show', $agent)
            ->with('success', 'Agent mis à jour.');
    }

    public function destroy(Agent $agent)
    {
        $agent->delete();

        return redirect()
            ->route('agents.index')
            ->with('success', 'Agent supprimé.');
    }

    /**
     * Validation centralisée (clean code)
     */
    private function validateAgent(Request $request, $id = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:agents,email,' . $id],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', 'in:admin,agent,superviseur'],
            'service_id' => ['nullable', 'exists:services,id'],
        ]);
    }

    /**
     * Récupérer services actifs
     */
    private function getActiveServices()
    {
        return Service::where('active', 1)->get();
    }
}