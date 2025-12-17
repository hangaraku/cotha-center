# Course Report Card Feature

## Overview
This feature provides a comprehensive course report card for students, displaying their performance metrics within a classroom. The report card shows various achievement and engagement indicators that teachers and students can reference.

## Database Changes

### Migration: `add_engagement_score_to_student_classrooms_table`
- **Table Modified**: `student_classrooms`
- **Column Added**: `engagement_score` (integer, nullable)
- **Purpose**: Allows teachers to assign a learning engagement score (1-10) for each student in their classroom

## Models Updated

### StudentClassroom Model
- Added `engagement_score` to fillable attributes
- This field is used to store the teacher's assessment of student engagement

## Routes

### New Route
```php
Route::get('/course-report-card/{userId}/{classroomId}', [ReportCardController::class, 'courseReportCard'])
    ->name('course-report-card');
```

**URL Pattern**: `/course-report-card/{userId}/{classroomId}`
- `userId`: The ID of the student
- `classroomId`: The ID of the classroom

**Example**: `/course-report-card/4/2`

## Controller: ReportCardController

### Main Method: `courseReportCard($userId, $classroomId)`
This method calculates and displays all metrics for the student's report card.

### Metrics Calculated

#### 1. Project Achievement
- **Definition**: Count of modules (projects) where the student has completed at least 5 units
- **Calculation**: 
  - Get all modules associated with the classroom through `classroom_levels`
  - For each module, count how many units the user has completed (from `user_units` table)
  - If count >= 5, increment the achievement counter
- **Display**: Shows as a number (e.g., "3" means 3 projects with >= 5 units completed)

#### 2. Learning Efficiency
- **Definition**: Ratio of student's completed units vs. classroom average
- **Calculation**:
  - Get all units from modules in the classroom
  - Count units completed by the specific student
  - Count total units completed by all students in the classroom
  - Calculate average: `totalUnitsAllStudents / numberOfStudents`
  - Display as ratio: `userUnits/averageUnits`
- **Display**: Shows as "25/30" (user completed 25, class average is 30)

#### 3. Learning Engagement
- **Definition**: Teacher-assigned score reflecting student's engagement level
- **Source**: From `student_classrooms.engagement_score` column
- **Range**: 1-10 (1 = low engagement, 10 = high engagement)
- **Display**: Shows the numeric score or "Not Set" if teacher hasn't assigned it yet
- **Editable By**: Teachers only (through Filament admin panel)

#### 4. Attendance Consistency
- **Definition**: Ratio of attended official meetings vs. total official meetings
- **Calculation**:
  - Get all "official" type sessions from `classroom_sessions` table
  - Count sessions where student was present (from `classroom_session_attendances`)
  - Display as ratio: `attendedCount/totalOfficialSessions`
- **Display**: Shows as "8/10" (attended 8 out of 10 official meetings)
- **Note**: Only counts "official" sessions, not "unofficial" ones

## Filament Admin Interface

### StudentClassroomsRelationManager Updates

#### New Table Column
- **Column Name**: "Engagement Score"
- **Visibility**: All teachers and admins
- **Features**:
  - Shows numeric score (1-10) or "Not Set"
  - Badge-style display with color coding:
    - Gray: Not set
    - Green: Score >= 8 (High engagement)
    - Yellow: Score 5-7 (Medium engagement)
    - Red: Score < 5 (Low engagement)

#### Edit Action Enhancement
Added a new form field to the edit action:
```php
Forms\Components\TextInput::make('engagement_score')
    ->label('Learning Engagement Score (1-10)')
    ->numeric()
    ->minValue(1)
    ->maxValue(10)
    ->helperText('Rate student engagement from 1 (low) to 10 (high)')
    ->nullable()
```

**How Teachers Use It**:
1. Go to Classroom in Filament admin
2. Navigate to "Student Classrooms" relation tab
3. Click "Edit" on any student
4. Enter engagement score (1-10)
5. Save

## Blade View Updates

### File: `resources/views/reports/course-report-card.blade.php`

Updated sections to display dynamic data:

#### Project Achievement
```blade
<div class="font-bold text-base text-gray-800">{{ $projectAchievement ?? 0 }}</div>
```

#### Learning Efficiency
```blade
<div class="font-bold text-base text-gray-800">{{ $learningEfficiency['ratio'] ?? '0/0' }}</div>
```

#### Learning Engagement
```blade
<div class="font-bold text-base text-gray-800">{{ $learningEngagement ?? 'Not Set' }}</div>
```

#### Attendance Consistency
```blade
<div class="font-bold text-base text-gray-800">{{ $attendanceConsistency['ratio'] ?? '0/0' }}</div>
```

## Data Flow

```
User Request → Route → Controller
                          ↓
                  ReportCardController
                          ↓
        ┌─────────────────┴─────────────────┐
        ↓                                     ↓
    Calculate Metrics               Get Relationships
        ↓                                     ↓
    Project Achievement             User, Classroom,
    Learning Efficiency             StudentClassroom
    Attendance Consistency                  ↓
        ↓                           engagement_score
        └──────────────┬────────────────────┘
                       ↓
              Pass to Blade View
                       ↓
          Display Report Card
```

## Key Database Relationships

### Tables Involved:
- `users` - Student information
- `classrooms` - Classroom information
- `student_classrooms` - Pivot table (includes engagement_score)
- `classroom_levels` - Links classrooms to levels
- `levels` - Contains modules
- `modules` - Contains units (projects)
- `units` - Individual learning units
- `user_units` - Tracks which units user completed
- `classroom_sessions` - Class meetings (type: official/unofficial)
- `classroom_session_attendances` - Attendance records

### Relationship Chain:
```
Classroom → classroom_levels → levels → modules → units
                                                    ↓
User → user_units ←─────────────────────────────┘
```

## Usage Examples

### For Students/Parents
Access report card:
```
https://yoursite.com/course-report-card/4/2
```
This shows student ID 4's report card for classroom ID 2.

### For Teachers
1. **Assign Engagement Score**:
   - Admin Panel → Classrooms → Select Classroom → Student Classrooms Tab
   - Edit student → Set engagement score (1-10)

2. **View Report Card**:
   - Share the report card URL with student/parent
   - Or integrate into student dashboard

## Future Enhancements (Optional)

- Add final grade calculation based on all metrics
- Generate PDF export functionality
- Add historical tracking of engagement scores
- Email report card to parents
- Student progress charts and graphs
- Comparison with classroom averages

## Technical Notes

- All calculations are performed in real-time when report card is accessed
- No caching implemented - metrics are always fresh
- Teacher-assigned engagement scores are the only manually entered data
- All other metrics are automatically calculated from system data
- The feature respects existing classroom and user relationships
- Compatible with existing attendance tracking system (official/unofficial sessions)
