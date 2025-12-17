# Teacher Access and Raise Hand System

## Overview
This document describes the implementation of two major features:
1. **Teacher Access Control**: All teachers assigned to a classroom now have full access to all levels/modules/units in that classroom
2. **Raise Hand System**: Students can raise their hands to ask for help, and teachers can view and manage these requests
3. **Home Page Fix**: Teachers can now see their assigned classrooms on the home page

## Teacher Access Control

### Changes Made

#### 1. Middleware Update (`app/Http/Middleware/CheckUserAccess.php`)
- Added teacher verification logic
- Teachers assigned to a classroom get full access to all modules and units
- Students still need proper `UserModule` and `UserUnit` records for access

```php
// Check if user is a teacher assigned to this classroom
$isTeacher = ClassroomTeacher::where('user_id', Auth::user()->id)
    ->where('classroom_id', $classroom)
    ->exists();

// If user is a teacher, grant full access to all modules and units
if ($isTeacher) {
    return $next($request);
}
```

#### 2. Sidebar Updates (`resources/views/components/sidebar.blade.php`)
- Added teacher access logic to both mobile and desktop sidebars
- Teachers can see all units and their resources/exercises
- Added "Raise Hands" link for teachers

```php
@php
    $isTeacher = \App\Models\ClassroomTeacher::where('user_id', auth()->user()->id)
        ->where('classroom_id', $classroom->id)
        ->exists();
    $hasUnitAccess = $isTeacher || auth()->user()->userUnits->where('unit_id', $currentUnit->id)->first();
@endphp
```

#### 3. Course Outline Updates (`resources/views/course/course_outline/course-outline.blade.php`)
- Teachers can access all modules regardless of `UserModule` status
- Teachers can access all units regardless of `UserUnit` status

#### 4. Home Page Updates (`app/Http/Controllers/CourseController.php`)
- Teachers can now see classrooms where they are assigned as teachers
- Combines both student and teacher classrooms
- Adds role information to distinguish between student and teacher access

```php
// Get classrooms where user is a student
$studentClassrooms = $user->classrooms;

// Get classrooms where user is a teacher
$teacherClassrooms = ClassroomTeacher::where('user_id', $user->id)
    ->with('classroom')
    ->get()
    ->pluck('classroom');

// Combine both collections and remove duplicates
$allClassrooms = $studentClassrooms->merge($teacherClassrooms)->unique('id');
```

#### 5. Home Page UI Updates (`resources/views/all_courses/courses/course_levels/course-levels.blade.php`)
- Added role badges to distinguish between teacher and student access
- Blue badge for teachers (👨‍🏫 Teacher)
- Green badge for students (👨‍🎓 Student)

### Benefits
- Teachers can preview all content before assigning to students
- Teachers can access any unit to help students during lessons
- No need to manually grant access to teachers for each module/unit
- Teachers can see their assigned classrooms on the home page
- Clear visual distinction between teacher and student roles

## Raise Hand System

### Database Structure

#### Table: `raise_hands`
```sql
CREATE TABLE raise_hands (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    classroom_id BIGINT NOT NULL,
    unit_id BIGINT NULL,
    is_raised BOOLEAN DEFAULT FALSE,
    raised_at TIMESTAMP NULL,
    lowered_at TIMESTAMP NULL,
    message TEXT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    UNIQUE KEY unique_user_classroom (user_id, classroom_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (classroom_id) REFERENCES classrooms(id) ON DELETE CASCADE,
    FOREIGN KEY (unit_id) REFERENCES units(id) ON DELETE CASCADE
);
```

### Models

#### 1. RaiseHand Model (`app/Models/RaiseHand.php`)
- Relationships with User, Classroom, and Unit
- Proper fillable attributes and casts
- Timestamps for raised_at and lowered_at

#### 2. User Model Update (`app/Models/User.php`)
- Added `raiseHands()` relationship

### Controllers

#### RaiseHandController (`app/Http/Controllers/RaiseHandController.php`)

**Methods:**
1. `toggle()` - Toggle raise hand status for students
2. `getClassroomRaisedHands()` - Get all raised hands for a classroom (teacher view)
3. `lowerHand()` - Lower a specific student's hand (teacher action)
4. `teacherView()` - Show teacher view page

### Routes

```php
// Raise hand routes
Route::post('/raise-hand/toggle', [RaiseHandController::class, 'toggle'])->name('raise-hand.toggle');
Route::get('/raise-hand/classroom/{classroomId}', [RaiseHandController::class, 'getClassroomRaisedHands'])->name('raise-hand.classroom');
Route::post('/raise-hand/{raiseHandId}/lower', [RaiseHandController::class, 'lowerHand'])->name('raise-hand.lower');
Route::get('/classroom/{classroom}/raise-hands', [RaiseHandController::class, 'teacherView'])->name('raise-hand.teacher-view');
```

### Frontend Implementation

#### 1. Student Raise Hand Button (`resources/views/lesson/lesson.blade.php`)
- Floating button with hand icon
- Modal for optional message input
- Real-time status updates
- AJAX calls to toggle raise hand status

#### 2. Teacher View (`resources/views/teacher/raise-hands.blade.php`)
- Real-time list of raised hands
- Student information display
- Optional message display
- Ability to lower hands
- Auto-refresh every 30 seconds

### Features

#### For Students:
- Click floating hand button to raise hand
- Optional message input for specific questions
- Visual feedback when hand is raised
- Can lower hand by clicking again

#### For Teachers:
- "Raise Hands" link in sidebar (only visible to teachers)
- Real-time view of all raised hands in classroom
- Student name, unit, and optional message display
- Ability to lower individual hands
- Auto-refresh functionality

### Security
- Teacher verification for all raise hand operations
- Classroom-specific access control
- CSRF protection on all AJAX calls
- Proper authorization checks

## Favicon Implementation

### Changes Made
1. **Layout Update** (`resources/views/layouts/app.blade.php`)
   - Added favicon links for multiple formats
   - Support for PNG, shortcut icon, and Apple touch icon

2. **Favicon File**
   - Copied `logo.png` to `public/favicon.png`
   - Used existing logo as favicon

```html
<link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
<link rel="shortcut icon" type="image/png" href="{{ asset('favicon.png') }}">
<link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">
```

## Usage Instructions

### For Teachers:
1. **Home Page**: Login and see all assigned classrooms with blue "👨‍🏫 Teacher" badges
2. **Access Any Classroom**: Click on any classroom card to access the course
3. **Raise Hands**: Click "Raise Hands" link in the sidebar when in a lesson
4. **View Requests**: See real-time list of students who raised their hands
5. **Help Students**: Click "Turunkan" to lower a student's hand after helping

### For Students:
1. Navigate to any lesson page
2. Click the floating hand button (bottom-right corner)
3. Optionally enter a message explaining your question
4. Click "Angkat Tangan" to raise hand
5. Click the button again to lower hand

## Technical Notes

### Database Constraints
- One active raise hand per student per classroom (unique constraint)
- Cascade deletes for related records
- Proper foreign key relationships

### Performance Considerations
- Auto-refresh every 30 seconds for teacher view
- Efficient queries with proper indexing
- Minimal AJAX payload

### Browser Compatibility
- Uses Alpine.js for reactive components
- Fetch API for AJAX calls
- Modern CSS with Tailwind

## Future Enhancements
1. Real-time notifications using WebSockets
2. Push notifications for teachers
3. Raise hand history and analytics
4. Integration with chat system
5. Priority levels for raise hands
6. Auto-lower hands after certain time period 