<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed wards
        DB::statement('EXEC sp_seed_wards');

        // Check if admin already exists
        $existingAdmin = DB::select('SELECT TOP 1 * FROM [USER] WHERE email = N\'admin@hospital.com\'');
        
        if (empty($existingAdmin)) {
            // Create admin user
            $adminPassword = bcrypt('password');
            DB::statement('EXEC sp_create_admin_account 
                @first_name = N\'Admin\',
                @last_name = N\'User\',
                @contact_number = N\'09123456789\',
                @email = N\'admin@hospital.com\',
                @password = N\'' . $adminPassword . '\',
                @admin_level = N\'Super Admin\'
            ');
        }

        // Check if nurse already exists
        $existingNurse = DB::select('SELECT TOP 1 * FROM [USER] WHERE email = N\'nurse@hospital.com\'');

        // Get first ward
        $wards = DB::select('EXEC sp_read_all_wards');
        if (!empty($wards) && empty($existingNurse)) {
            $wardId = $wards[0]->WardID;

            // Create sample nurse
            $nursePassword = bcrypt('password');
            DB::statement('EXEC sp_create_nurse_account
                @first_name = N\'John\',
                @last_name = N\'Nurse\',
                @contact_number = N\'09876543210\',
                @email = N\'nurse@hospital.com\',
                @password = N\'' . $nursePassword . '\',
                @WardID = ' . $wardId . ',
                @license_number = N\'RN-12345\',
                @role = N\'Registered Nurse\'
            ');
        }

        // Create multiple sample patients with varying admission dates throughout the month
        $patientNames = [
            ['John', 'Smith'],
            ['Mary', 'Johnson'],
            ['Robert', 'Williams'],
            ['Patricia', 'Brown'],
            ['Michael', 'Jones'],
            ['Jennifer', 'Garcia'],
            ['David', 'Miller'],
            ['Linda', 'Davis'],
            ['Richard', 'Rodriguez'],
            ['Barbara', 'Martinez'],
            ['Joseph', 'Hernandez'],
            ['Susan', 'Lopez'],
            ['Thomas', 'Gonzalez'],
            ['Jessica', 'Wilson'],
            ['Charles', 'Anderson'],
        ];

        $bloodTypes = ['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-'];
        
        foreach ($patientNames as $index => $name) {
            if (!empty($wards)) {
                // Check if patient already exists (prevent duplicates)
                $contactNumber = '0955' . str_pad($index, 7, '0', STR_PAD_LEFT);
                $existingPatient = DB::select('SELECT TOP 1 PatientID FROM [PATIENT] WHERE contact_Number = N\'' . $contactNumber . '\'');
                
                if (empty($existingPatient)) {
                    // Distribute patients across wards
                    $wardId = $wards[$index % count($wards)]->WardID;
                    
                    // Create admission dates spread throughout the current month
                    $admissionDate = Carbon::now()->subDays($index % 28)->format('Y-m-d');

                    // Create sample patient
                    DB::statement('EXEC sp_create_patient_record
                        @WardID = ' . $wardId . ',
                        @first_name = N\'' . $name[0] . '\',
                        @last_name = N\'' . $name[1] . '\',
                        @dateOfBirth = N\'' . Carbon::now()->subYears(20 + ($index % 50))->format('Y-m-d') . '\',
                        @Sex = N\'' . ($index % 2 == 0 ? 'Male' : 'Female') . '\',
                        @contact_Number = N\'' . $contactNumber . '\',
                        @Guardian = N\'' . ($index % 2 == 0 ? 'Jane Smith' : 'John Doe') . '\',
                        @guardian_Number = N\'0956' . str_pad($index, 7, '0', STR_PAD_LEFT) . '\',
                        @address = N\'' . ($index + 1) . ' Main Street, City\',
                        @height = ' . (150 + ($index % 40)) . ',
                        @weight = ' . (50 + ($index % 50)) . ',
                        @blood_type = N\'' . $bloodTypes[$index % count($bloodTypes)] . '\',
                        @roomNumber = N\'' . (101 + ($index % 10)) . '\',
                        @bedNumber = N\'' . chr(65 + ($index % 4)) . '\',
                        @admission_date = N\'' . $admissionDate . '\',
                        @attending_physician = N\'Dr. ' . ($index % 3 == 0 ? 'Smith' : ($index % 3 == 1 ? 'Johnson' : 'Williams')) . '\'
                    ');

                    // Query for the newly created patient by unique contact number
                    $patients = DB::select('SELECT TOP 1 PatientID FROM [PATIENT] WHERE contact_Number = N\'' . $contactNumber . '\'');
                    
                    if (!empty($patients)) {
                        $patientId = $patients[0]->PatientID;
                        
                        // Check if a chart already exists for this patient
                        $existingChart = DB::select('SELECT TOP 1 ChartID FROM [CHART] WHERE PatientID = ' . $patientId);
                        
                        // Only create chart if none exists
                        if (empty($existingChart)) {
                            // Get nurse ID (from the seeded nurse account)
                            $nurse = DB::select('SELECT TOP 1 userID FROM [USER] WHERE email = N\'nurse@hospital.com\'');
                            if (!empty($nurse)) {
                                $nurseId = $nurse[0]->userID;
                                
                                // Create a chart for this patient
                                DB::statement('EXEC sp_create_chart_record
                                    @PatientID = ' . $patientId . ',
                                    @NurseID = ' . $nurseId . '
                                ');
                            }
                        }
                    }
                }
            }
        }

        echo "✔ Seeding completed!\n";
        echo "Demo Credentials:\n";
        echo "Admin: admin@hospital.com / password\n";
        echo "Nurse: nurse@hospital.com / password\n";
    }
}

