<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SiteContatoSeeder extends Seeder
{
    public function run()
    {
        \App\Models\SiteContato::factory()->count(100)->create();
    }
}
