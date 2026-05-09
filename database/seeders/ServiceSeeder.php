<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            ['name' => 'Ouverture de compte bancaire', 'description' => 'Création de nouveau compte bancaire'],
            ['name' => 'Dépôt et retrait', 'description' => 'Opérations de dépôt et retrait d\'argent'],
            ['name' => 'Transfert d\'argent', 'description' => 'Transferts bancaires et virements'],
            ['name' => 'Carte bancaire', 'description' => 'Demande et gestion de cartes bancaires'],
            ['name' => 'Prêt personnel', 'description' => 'Demande de prêt personnel'],
            ['name' => 'Assurance', 'description' => 'Souscription et gestion d\'assurances'],
            ['name' => 'Investissement', 'description' => 'Conseil en investissement et placement'],
            ['name' => 'Service client', 'description' => 'Support et assistance clientèle'],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
