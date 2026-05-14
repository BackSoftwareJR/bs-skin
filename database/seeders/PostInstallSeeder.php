<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class PostInstallSeeder extends Seeder
{
    /**
     * Seeder da eseguire DOPO l'import di schema.sql
     * Si occupa di creare l'utente super admin e assegnare ruoli
     */
    public function run(): void
    {
        $this->command->info('🚀 Inizializzazione post-installazione SkinTemple...');

        // 1. Crea/aggiorna super admin user
        $adminEmail = env('SKINTEMPLE_ADMIN_EMAIL_PRIMARY', 'jrovera05@gmail.com');
        $adminPassword = env('SKINTEMPLE_ADMIN_PASSWORD', 'changeme123!');

        $user = User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'name' => 'Super Admin',
                'email_verified_at' => now(),
                'password' => Hash::make($adminPassword),
                'locale' => 'it',
                'is_active' => true,
            ]
        );

        $this->command->info("✅ Super admin creato/aggiornato: {$adminEmail}");

        // 2. Assegna ruolo super_admin (se esiste dalla migrazione/schema)
        try {
            $superAdminRole = Role::where('name', 'super_admin')->first();
            
            if ($superAdminRole) {
                $user->assignRole('super_admin');
                $this->command->info("✅ Ruolo 'super_admin' assegnato");
            } else {
                $this->command->warn("⚠️  Ruolo 'super_admin' non trovato - verifica che schema.sql sia stato importato correttamente");
            }
        } catch (\Exception $e) {
            $this->command->error("❌ Errore nell'assegnazione ruolo: " . $e->getMessage());
        }

        // 3. Avviso importante per sicurezza
        if ($adminPassword === 'changeme123!') {
            $this->command->warn("🔐 IMPORTANTE: Cambia la password del super admin al primo accesso da /admin/profile");
            $this->command->warn("    Email: {$adminEmail}");
            $this->command->warn("    Password temporanea: {$adminPassword}");
        }

        $this->command->info('✨ Inizializzazione completata con successo!');
    }
}