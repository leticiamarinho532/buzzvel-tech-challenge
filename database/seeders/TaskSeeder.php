<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // TODO: maybe remove this implentaion and use only facotories for seeding
    public function run(): void
    {
        for ($i = 0; $i < 5; $i++) {
            DB::table('tasks')->insert([
                'title' => Str::random(10),
                'description' => Str::random(20),
                'file' => 'storage/app/example.txt',
                'user_id' => 1,
                'completed' => (bool)random_int(0, 1),
                'completed_at' => (($i % 2) ? DB::raw('CURRENT_TIMESTAMP') : null),
                'created_at' => DB::raw('CURRENT_TIMESTAMP')
            ]);
        }
    }
}
