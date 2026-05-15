<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // --- CONTROL PLANTA ---
        $groupControlPlanta = 'Control Planta';
        
        Role::create(['name' => 'Costos', 'group' => $groupControlPlanta]);
        Role::create(['name' => 'Jefe Costos', 'group' => $groupControlPlanta]);
        Role::create(['name' => 'Auxiliar Planta', 'group' => $groupControlPlanta]);

        // --- MEZCLAS (dentro de Control Planta) ---
        $groupMezclas = 'Mezclas (Control Planta)';
        Role::create(['name' => 'Lider Mezclas', 'group' => $groupMezclas]);
        Role::create(['name' => 'Auxiliar Mezclas', 'group' => $groupMezclas]);

        // --- LOGISTICA (dentro de Control Planta) ---
        $groupLogistica = 'Logística (Control Planta)';
        Role::create(['name' => 'Lider Logística', 'group' => $groupLogistica]);
        Role::create(['name' => 'Auxiliar Logística', 'group' => $groupLogistica]);

        // --- USUARIOS GENERALES ---
        $groupGenerales = 'Usuarios Generales';
        Role::create(['name' => 'Básico', 'group' => $groupGenerales]);
        Role::create(['name' => 'Avanzado', 'group' => $groupGenerales]);

        // Admin Role (optional but recommended)
        $admin = Role::create(['name' => 'Administrador', 'group' => 'Sistema']);
    }
}
