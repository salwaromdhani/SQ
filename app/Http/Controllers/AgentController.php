<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Service;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    /**
     * Afficher la liste des agents
     */
    public function index(Request $request)
    {
        $agents = Agent::with('service')
            ->when($request->filled('role'), function ($query) use ($request) {
                $query->where('role', $request->role);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('agents.index', [
            'agents' => $agents
        ]);
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $services = Service::where('active', true)
            ->orderBy('name')
            ->get();

        return view('agents.create', compact('services'));
    }

    /**
     * Enregistrer un nouvel agent
     */
    public function store(Request $request)
    {
        $validated = $this->validateAgent($request);

        try {
            Agent::create($validated);

            return redirect()
                ->route('agents.index')
                ->with('success', 'Agent créé avec succès.');
        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la création de l’agent.');
        }
    }

    /**
     * Afficher les détails d’un agent
     */
    public function show(Agent $agent)
    {
        $agent->load('service');

        return view('agents.show', compact('agent'));
    }

    /**
     * Afficher le formulaire de modification
     */
    public function edit(Agent $agent)
    {
        $services = Service::where('active', true)
            ->orderBy('name')
            ->get();

        return view('agents.edit', compact('agent', 'services'));
    }

    /**
     * Mettre à jour un agent
     */
    public function update(Request $request, Agent $agent)
    {
        $validated = $this->validateAgent($request, $agent->id);

        try {
            $agent->update($validated);

            return redirect()
                ->route('agents.show', $agent)
                ->with('success', 'Agent mis à jour avec succès.');
        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour.');
        }
    }

    /**
     * Supprimer un agent
     */
    public function destroy(Agent $agent)
    {
        try {
            $agent->delete();

            return redirect()
                ->route('agents.index')
                ->with('success', 'Agent supprimé avec succès.');
        } catch (\Exception $e) {

            return back()
                ->with('error', 'Impossible de supprimer cet agent.');
        }
    }

    /**
     * Validation centralisée
     */
    private function validateAgent(Request $request, $agentId = null)
    {
        return $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:agents,email,' . $agentId,
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,agent,superviseur',
            'service_id' => 'nullable|exists:services,id',
        ]);
    }
}