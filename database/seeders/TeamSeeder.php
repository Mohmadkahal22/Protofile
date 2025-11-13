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
                'phone' => '+96395027218',
                'email' => 'majd.bayer@example.com',
                'photo' => 'https://drive.google.com/file/d/1Cx-Am8xV6TkistDc9jM18wbaQ0x_6ZGz/view?usp=drive_link',
                'github_url' => 'https://github.com/majdbayer',
                'cv_file' => 'https://docs.google.com/document/d/1VE-6TenLolMeUrTKCTkbxXggtXVbawbl/edit?usp=drive_link&ouid=101154777687743530485&rtpof=true&sd=true', // You can upload this later
                'specialization' => 'software Development',
                'position' => 'Senior Backend Engineer',
            ],
            [
                'first_name' => 'Mohamad',
                'last_name' => 'Kahal',
                'phone' => '+966500000002',
                'email' => 'mohamad.kahal@example.com',
                'photo' => 'https://drive.google.com/file/d/1t_RKtQTx-twQfsYSA2B4sifj-K1jG51q/view?usp=drive_link',
                'github_url' => 'https://github.com/mohamadkahal',
                'cv_file' => 'https://drive.google.com/file/d/1qP8EEuN2QxWjOJPlzvPGcjmYZt6jGiJg/view?usp=drive_link', // You can upload this later
                'specialization' => 'software Development',
                'position' => 'Senior Frontend Engineer',
            ],
        ];

        foreach ($teamMembers as $member) {
            Team::create($member);
        }

        $this->command->info(count($teamMembers) . ' team members created successfully!');
    }
}