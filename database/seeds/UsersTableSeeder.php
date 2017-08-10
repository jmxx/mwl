<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class UsersTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('users')->insert([
      'name' => 'Juan Manuel Reynoso',
      'username' => 'jmxx',
      'email' => 'jmxx@mwl.mx',
      'password' => bcrypt('pass1234'),
      'created_at' => new DateTime,
      'updated_at' => new DateTime
    ]);
  }

}
