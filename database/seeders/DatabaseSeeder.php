<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Default settings
        if (DB::table('settings')->count() === 0) {
            DB::table('settings')->insert([
                'site_title' => 'Chittara Star Awards 2026',
                'tagline' => 'Most Awaited Sandalwood Awards Show',
                'about_text' => 'Chittara Star Awards 2026 stands as a grand celebration of excellence, honoring the finest talents across cinema, television, fashion, and business.',
            ]);
        }

        // Default admin user
        if (DB::table('admin_users')->count() === 0) {
            DB::table('admin_users')->insert([
                'username' => 'admin',
                'password' => Hash::make('admin123'),
            ]);
        }

        // Default nav links
        if (DB::table('nav_links')->count() === 0) {
            $navLinks = [
                ['label' => 'HOME', 'url' => '#home', 'display_order' => 1],
                ['label' => 'CHITTARA STAR AWARDS 2026', 'url' => '#about', 'display_order' => 2],
                ['label' => 'NOMINEES', 'url' => '#vote', 'display_order' => 3],
                ['label' => 'THE JURY', 'url' => '#jury', 'display_order' => 4],
                ['label' => 'PARTNERS', 'url' => '#partners', 'display_order' => 5],
                ['label' => 'VIDEOS', 'url' => '#videos', 'display_order' => 6],
                ['label' => 'PHOTOS', 'url' => '#photos', 'display_order' => 7],
            ];
            DB::table('nav_links')->insert($navLinks);
        }
    }
}
