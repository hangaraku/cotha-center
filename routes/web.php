<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\RaiseHandController;
use App\Http\Controllers\ShowcaseController;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are added by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [CourseController::class, 'index'])->name('home');

// Showcase routes (public access)
Route::get('/showcase', [ShowcaseController::class, 'index'])->name('showcase.index');
Route::get('/showcase/user/{slug}', [ShowcaseController::class, 'userProfile'])->name('showcase.user-profile');
Route::get('/showcase/user/{slug}/classrooms', [ShowcaseController::class, 'getUserClassrooms'])->name('showcase.user-classrooms');
Route::get('/showcase/certificate/{userId}/{classroomId}', [ShowcaseController::class, 'viewCertificate'])->name('showcase.certificate');
Route::get('/showcase/search-suggestions', [ShowcaseController::class, 'searchSuggestions'])->name('showcase.search-suggestions');

Route::post('/authenticate', [LoginController::class, 'authenticate'])->name('authenticate');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/register/student', [LoginController::class, 'register'])->name('registerstudent');
Route::get('/uploadusingCSV', function () {
    $file = public_path('students.csv'); // Change the path to your CSV file

    if (! file_exists($file)) {
        return 'CSV file not found.';
    }

    $handle = fopen($file, 'r');
    $header = null; // Initialize the $header variable

    if ($handle !== false) {
        while (($data = fgetcsv($handle, 1000, ',')) !== false) {
            // Assuming the first row contains headers
            if (! $header) {
                $header = $data;
            } else {
                $row = array_combine($header, $data);

                // Create a User object
                $user = new User([
                    'center_id' => $row['center_id'],
                    'name' => $row['name'],
                    'email' => $row['email'],
                    'password' => $row['password'],
                ]);
                $user->save();
                $birthdate = DateTime::createFromFormat('m/d/Y', $row['birthdate'])->format('Y-m-d');

                // Create a Student object
                $student = new Student([
                    'user_id' => $user->id,
                    'city' => $row['city'],
                    'school' => $row['school'],
                    'birthdate' => $birthdate,
                    'phone' => $row['phone'],
                    'note' => $row['note'],
                ]);
                $student->save();
            }
        }
        fclose($handle);
    }

    return 'CSV import complete.';

});
Route::get('/login', fn () => view('login'))->name('login');
Route::get('/register', fn () => view('register'))->name('register');

Route::get('/register/{center}', fn () => view('register'))->name('register.center');

Route::get('/classroom/{classroom}/level/{level}', [ClassroomController::class, 'showLevel'])->name('classroom.level');
Route::get('/classroom/{classroom}', [ClassroomController::class, 'redirectToLatestLevel'])->name('classroom.redirect');

Route::get('/rewards', [\App\Http\Controllers\RewardController::class, 'index'])->name('rewards.index');

Route::middleware(['auth'])->group(function () {
    Route::get('/classroom/{id}', [ClassroomController::class, 'show'])->name('course');

    Route::get('/lesson/{classroom}/{id}/{unitId}', [ModuleController::class, 'show'])
        ->name('lesson')
        ->middleware('check.user.access');

    // Profile routes
    Route::get('/profile/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/password', [\App\Http\Controllers\ProfileController::class, 'password'])->name('profile.password');
    Route::post('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Projects routes
    Route::get('/projects', [\App\Http\Controllers\ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/create', [\App\Http\Controllers\ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [\App\Http\Controllers\ProjectController::class, 'store'])->name('projects.store');
    Route::put('/projects/{id}', [\App\Http\Controllers\ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{id}', [\App\Http\Controllers\ProjectController::class, 'destroy'])->name('projects.destroy');
    Route::post('/projects/{id}/increment-view', [\App\Http\Controllers\ProjectController::class, 'incrementView'])->name('projects.increment-view');

    // Showcase interaction routes (authenticated)
    Route::post('/showcase/toggle-interaction', [ShowcaseController::class, 'toggleInteraction'])->name('showcase.toggle-interaction');
    Route::post('/showcase/increment-view', [ShowcaseController::class, 'incrementView'])->name('showcase.increment-view');

    Route::get('/{classroom}/exercise/{id}', [ExerciseController::class, 'show'])->name('exercise');
    Route::post('/exercisesave', [ExerciseController::class, 'saveAnswer'])->name('saveAnswer');
    Route::get('/development/dont', [ExerciseController::class, 'development']);
    // User Rewards routes with rate limiting to prevent abuse
    Route::post('/user-rewards', [\App\Http\Controllers\UserRewardController::class, 'store'])
        ->name('user-rewards.store')
        ->middleware('throttle:10,1'); // Max 10 orders per minute
    Route::get('/user-rewards', [\App\Http\Controllers\UserRewardController::class, 'index'])->name('user-rewards.index');
    Route::post('/user-rewards/{order}/cancel', [\App\Http\Controllers\UserRewardController::class, 'cancel'])
        ->name('user-rewards.cancel')
        ->middleware('throttle:5,1'); // Max 5 cancellations per minute

    // Account routes
    Route::resource('accounts', AccountController::class);

    // Raise hand routes
    Route::post('/raise-hand/toggle', [RaiseHandController::class, 'toggle'])->name('raise-hand.toggle');
    Route::get('/raise-hand/classroom/{classroomId}', [RaiseHandController::class, 'getClassroomRaisedHands'])->name('raise-hand.classroom');
    Route::post('/raise-hand/{raiseHandId}/lower', [RaiseHandController::class, 'lowerHand'])->name('raise-hand.lower');
    Route::get('/classroom/{classroom}/raise-hands', [RaiseHandController::class, 'teacherView'])->name('raise-hand.teacher-view');
});

// Report Card Routes
use App\Http\Controllers\ReportCardController;

Route::get('/course-report-card/{userId}/{classroomId}', [ReportCardController::class, 'courseReportCard'])->name('course-report-card');

// Temporary route to preview the report card
Route::get('/report-card-test', function () {
    return view('reports.course-report-card-test');
});
