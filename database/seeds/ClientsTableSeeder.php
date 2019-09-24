<?php

use Illuminate\Database\Seeder;

class ClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clients = ['ahmed', 'mohamed'];

        foreach ($clients as $client) {
            \App\Client::create([
                'name'      => $client,
                'phone'     => '01117641613',
                'address'   => 'Cairo El-zaitoyn'
            ]);
        }
    }
}
