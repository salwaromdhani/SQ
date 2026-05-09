<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of services
     */
    public function index(Request $request)
    {
        $services = Service::withCount('tickets')
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('services.index', compact('services'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('services.create');
    }

    /**
     * Store new service
     */
    public function store(Request $request)
    {
        $data = $this->validateService($request);

        Service::create($data);

        return redirect()
            ->route('services.index')
            ->with('success', 'Service créé avec succès');
    }

    /**
     * Show single service
     */
    public function show(Service $service)
    {
        return view('services.show', compact('service'));
    }

    /**
     * Show edit form
     */
    public function edit(Service $service)
    {
        return view('services.edit', compact('service'));
    }

    /**
     * Update service
     */
    public function update(Request $request, Service $service)
    {
        $data = $this->validateService($request);

        $service->update($data);

        return redirect()
            ->route('services.index')
            ->with('success', 'Service mis à jour avec succès');
    }

    /**
     * Delete service
     */
    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()
            ->route('services.index')
            ->with('success', 'Service supprimé avec succès');
    }

    /**
     * Centralized validation (clean code)
     */
    private function validateService(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'active' => ['boolean'],
        ]);
    }
}