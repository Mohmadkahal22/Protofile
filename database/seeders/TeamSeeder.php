<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teamMembers = [
            [
                'first_name' => 'Majd',
                'last_name' => 'Bayer',
                'phone' => '+966500000001',
                'email' => 'majd.bayer@example.com',
                'photo' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=200&q=80',
                'github_url' => 'https://github.com/majdbayer',
                'cv_file' => 'https://docs.google.com/document/d/1VE-6TenLolMeUrTKCTkbxXggtXVbawbl/edit?usp=drive_link&ouid=101154777687743530485&rtpof=true&sd=true', // You can upload this later
                'specialization' => 'Backend Development',
                'position' => 'Senior Backend Engineer',
            ],
            [
                'first_name' => 'Mohamad',
                'last_name' => 'Kahal',
                'phone' => '+966500000002',
                'email' => 'mohamad.kahal@example.com',
                'photo' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=200&q=80',
                'github_url' => 'https://github.com/mohamadkahal',
                'cv_file' => null, // You can upload this later
                'specialization' => 'Frontend Development',
                'position' => 'Senior Frontend Engineer',
            ],
        ];

        foreach ($teamMembers as $member) {
            Team::create($member);
        }

        $this->command->info(count($teamMembers) . ' team members created successfully!');
    }
}