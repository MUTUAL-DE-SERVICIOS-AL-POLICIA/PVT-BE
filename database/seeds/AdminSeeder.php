<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\City;
use Muserpol\Models\Role;
use Muserpol\User;

class AdminSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $user = User::whereUsername('admin')->first();
    if (!$user) {
      $user = User::create([
        'username' => 'admin',
        'first_name' => 'Administrador',
        'last_name' => 'Administrador',
        'password' => Hash::make('admin'),
        'position' => 'Administrador',
        'status' => 'active',
        'city_id' => City::first()->id
      ]);
    }

    $role = Role::whereName('Administrador')->first();

    $user->roles()->sync($role);
  }
}
