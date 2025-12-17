<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Filament\Resources\UserClassroomScheduleSessionStatusResource;
use App\Models\Admin;
use App\Models\AdminRole;
use App\Models\Classroom;
use App\Models\Center;
use App\Models\ClassroomLevel;
use App\Models\ClassroomTeacher;
use App\Models\ClassroomType;
use App\Models\ExerciseType;
use App\Models\Level;
use App\Models\Module;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserClassroomScheduleSession;
use App\Models\UserClassroomScheduleSessionStatus;
use App\Models\UserModuleScore;
use App\Models\UserModuleStatus;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        Center::create([
            'name' => 'Cotha'
        ]);

        Center::create([
            'name' => 'Webdev'
        ]);
    
        Center::create([
            'name' => 'CTCenter',
            'logo_url' => 'https://i.ibb.co/6Z6wwnP/Whats-App-Image-2023-10-06-at-11-38-24-removebg-preview.png'
        ]);
        User::create(
            [
                'name' => 'Super Admin Cotha',
                'email' => 'cotha@comfypace.com',
                'password' => 'cotha2023',
                'center_id' => 1
            ]
        );


        User::create(
            [
                'name' => 'Super Admin Webdev',
                'email' => 'webdev@comfypace.com',
                'password' => 'webdev2023',
                'center_id' => 2
            ]
        );


        User::create(
            [
                'name' => 'Super Admin CTCenter',
                'email' => 'ctcenter@comfypace.com',
                'password' => 'ctcenter2023',
                'center_id' => 3
            ]
        );

        

        AdminRole::create([
            'name' => 'Super Admin'
        ]);

        AdminRole::create([
            'name' => 'Teacher'
        ]);
        
        Admin::create([
            'user_id' => User::first()->id,
            'role_id' => AdminRole::where('name','=','Teacher')->first()->id
        ]);

        Admin::create([
            'user_id' => User::first()->id+1,
            'role_id' => AdminRole::where('name','=','Teacher')->first()->id
        ]);
  

        Admin::create([
            'user_id' => User::first()->id+2,
            'role_id' => AdminRole::where('name','=','Teacher')->first()->id
        ]);
        AdminRole::create([
            'name' => 'Supervisor'
        ]);

        ClassroomType::create([
            'name' => 'Big Class'
        ]);
        
        ClassroomType::create([
            'name' => 'Private'
        ]);

        ClassroomType::create([
            'name' => 'Semi Private'
        ]);

        ExerciseType::create([
            'name' => 'Multiple Choice',
        ]);

        UserClassroomScheduleSessionStatus::create([
            'name'=>'Absent'
        ]);

        UserClassroomScheduleSessionStatus::create([
            'name'=>'Present'
        ]);

        UserClassroomScheduleSessionStatus::create([
            'name'=>'Late'
        ]);

        UserClassroomScheduleSessionStatus::create([
            'name'=>'Umarked'
        ]);

        UserModuleScore::create([
            'name'=>'Excellent'
        ]);

        UserModuleScore::create([
            'name'=>'Unmarked'
        ]);
        UserModuleScore::create([
            'name'=>'Very Good'
        ]);

        UserModuleScore::create([
            'name'=>'Good'
        ]);

        UserModuleScore::create([
            'name'=>'Fair'
        ]);

        UserModuleStatus::create([
            'name'=>'Avaliable'
        ]);

        UserModuleStatus::create([
            'name'=>'Restricted'
        ]);

        Classroom::create([
            'classroom_type_id' => ClassroomType::first()->id,
            'name' => 'Classroom Test Purpose Cotha',
            'start_date' => now(),
            'end_date' => now()->addDays(20),
            'total_credit' => 10,
            'center_id' => 1
        ]);

        Classroom::create([
            'classroom_type_id' => ClassroomType::first()->id,
            'name' => 'Classroom Test Purpose Webdev',
            'start_date' => now(),
            'end_date' => now()->addDays(20),
            'total_credit' => 10,
            'center_id' => 2
        ]);

        ClassroomTeacher::create([
            'user_id' => Admin::first()->user_id,
            'classroom_id'=> Classroom::first()->id,
        ]);

        Level::create([
            'img_url' => 'https://source.unsplash.com/random/?classroom',
            'name' => 'Dummy Level Cotha',
            'description' => 'This is a dummy level',
            'center_id' => 1
        ]);

        Level::create([
            'img_url' => 'https://source.unsplash.com/random/?classroom',
            'name' => 'Dummy Level',
            'description' => 'This is a dummy level WebDev',
            'center_id' => 2
        ]);



        ClassroomLevel::create([
            'level_id' => Level::first()->id,
            'classroom_id' => Classroom::first()->id,
            'is_active' => true
        ]);

        Module::create([
            'name' => 'Module 1',
            'description' => 'Module 1',
            'level_id' => Level::first()->id,
            'img_url' => 'https://source.unsplash.com/random/?classroom'
        ]);

        Unit::create([
            'name' => 'Unit 1',
            'module_id' => Module::first()->id,
            'description' => 'this is a dummy unit',
            'point' => 200,
            'img_url' => 'https://www.youtube.com/watch?v=1ksbLuugP-M'
        ]);

        
    }
}
