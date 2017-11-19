<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $this->call(Proveedores::class);
      $this->call(Articulos::class);
      $this->call(Users::class);
      $this->call(Clientes::class);
      $this->call(Facturas::class);
      $this->call(rootSeeder::class);
      $this->call(FacturasProductos::class);
      $this->call(plan_pago::class);
      $this->call(Proveedores::class);
      $this->call(usuario::class);
      $this->call(Empresas::class);
      $this->call(Contratos::class);
      $this->call(Empleados::class);
      $this->call(PlanDePagos::class);
      $this->call(Deudas::class);
    }
}
