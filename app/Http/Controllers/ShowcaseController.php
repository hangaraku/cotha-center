<?php

namespace App\Http\Controllers;

use App\Models\ProjectInteraction;
use App\Models\UserProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShowcaseController extends Controller
{
    public function index(Request $request)
    {
        $query = UserProject::with(['user', 'interactions', 'userModule.module'])
            ->orderBy('created_at', 'desc');

        // Filter by type if provided
        if ($request->has('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $projects = $query->paginate(12);

        // Get interaction counts for each project
        foreach ($projects as $project) {
            $project->likes_count = $project->likes()->count();
            $project->loves_count = $project->loves()->count();
            $project->stars_count = $project->stars()->count();

            // Check if current user has interacted with this project
            if (Auth::check()) {
                $project->user_liked = $project->likes()->where('user_id', Auth::id())->exists();
                $project->user_loved = $project->loves()->where('user_id', Auth::id())->exists();
                $project->user_starred = $project->stars()->where('user_id', Auth::id())->exists();
            } else {
                $project->user_liked = false;
                $project->user_loved = false;
                $project->user_starred = false;
            }
        }

        // Get search suggestions for users if search is active
        $userSuggestions = collect();
        if ($request->has('search') && $request->search) {
            $userSuggestions = \App\Models\User::where('name', 'like', "%{$request->search}%")
                ->whereHas('userProjects')
                ->withCount('userProjects')
                ->limit(5)
                ->get();
        }

        // Get students with their user and center information
        $studentsQuery = \App\Models\Student::with(['user.center', 'user.userProjects'])
            ->whereHas('user.userProjects')
            ->whereHas('user', function ($query) {
                $query->where('center_id', 1);
            });

        // Apply search to students if search parameter exists
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $studentsQuery->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%");
                })
                    ->orWhere('school', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        $students = $studentsQuery->get();

        return view('showcase.index', compact('projects', 'userSuggestions', 'students'));
    }

    public function userProfile($slug)
    {
        // Extract user ID from slug (format: name-slug-123)
        $userId = (int) substr($slug, strrpos($slug, '-') + 1);

        $user = \App\Models\User::with(['center', 'students', 'userProjects'])
            ->withCount('userProjects')
            ->findOrFail($userId);

        $projects = UserProject::with(['user', 'interactions', 'userModule.module'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // Get interaction counts for each project
        foreach ($projects as $project) {
            $project->likes_count = $project->likes()->count();
            $project->loves_count = $project->loves()->count();
            $project->stars_count = $project->stars()->count();

            // Check if current user has interacted with this project
            if (Auth::check()) {
                $project->user_liked = $project->likes()->where('user_id', Auth::id())->exists();
                $project->user_loved = $project->loves()->where('user_id', Auth::id())->exists();
                $project->user_starred = $project->stars()->where('user_id', Auth::id())->exists();
            } else {
                $project->user_liked = false;
                $project->user_loved = false;
                $project->user_starred = false;
            }
        }

        return view('showcase.user-profile', compact('user', 'projects'));
    }

    public function toggleInteraction(Request $request)
    {
        if (! Auth::check()) {
            return response()->json(['error' => 'Authentication required'], 401);
        }

        $request->validate([
            'project_id' => 'required|exists:user_projects,id',
            'type' => 'required|in:like,love,star',
        ]);

        $projectId = $request->project_id;
        $type = $request->type;
        $userId = Auth::id();

        // Check if interaction already exists
        $interaction = ProjectInteraction::where('user_project_id', $projectId)
            ->where('user_id', $userId)
            ->where('type', $type)
            ->first();

        if ($interaction) {
            // Remove interaction
            $interaction->delete();
            $action = 'removed';
        } else {
            // Add interaction
            ProjectInteraction::create([
                'user_project_id' => $projectId,
                'user_id' => $userId,
                'type' => $type,
            ]);
            $action = 'added';
        }

        // Get updated counts
        $project = UserProject::find($projectId);
        $likesCount = $project->likes()->count();
        $lovesCount = $project->loves()->count();
        $starsCount = $project->stars()->count();

        return response()->json([
            'action' => $action,
            'type' => $type,
            'counts' => [
                'likes' => $likesCount,
                'loves' => $lovesCount,
                'stars' => $starsCount,
            ],
            'user_interactions' => [
                'liked' => $project->likes()->where('user_id', $userId)->exists(),
                'loved' => $project->loves()->where('user_id', $userId)->exists(),
                'starred' => $project->stars()->where('user_id', $userId)->exists(),
            ],
        ]);
    }

    public function incrementView(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:user_projects,id',
        ]);

        $project = UserProject::find($request->project_id);
        $project->increment('views');

        return response()->json(['views' => $project->views]);
    }

    public function searchSuggestions(Request $request)
    {
        $query = $request->get('q');

        if (strlen($query) < 2) {
            return response()->json(['users' => []]);
        }

        $users = \App\Models\User::select('id', 'name', 'profile_picture')
            ->where('name', 'like', "%{$query}%")
            ->whereHas('userProjects')
            ->withCount('userProjects')
            ->limit(5)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'profile_picture' => $user->profile_picture,
                    'user_projects_count' => $user->user_projects_count,
                    'slug' => $user->slug,
                ];
            });

        return response()->json(['users' => $users]);
    }

    /**
     * Get user's classrooms for certificate selection.
     */
    public function getUserClassrooms($slug)
    {
        // Extract user ID from slug (format: name-slug-123)
        $userId = (int) substr($slug, strrpos($slug, '-') + 1);

        $user = \App\Models\User::findOrFail($userId);

        // Get classrooms the user is enrolled in
        $classrooms = $user->studentClassrooms()
            ->with(['classroom.classroomLevels.level'])
            ->whereHas('classroom')
            ->get()
            ->map(function ($studentClassroom) {
                $classroom = $studentClassroom->classroom;

                return [
                    'id' => $classroom->id,
                    'name' => $classroom->main_level_name ?? $classroom->name,
                    'original_name' => $classroom->name,
                ];
            });

        return response()->json(['classrooms' => $classrooms]);
    }

    /**
     * View certificate for a user in a specific classroom.
     */
    public function viewCertificate($userId, $classroomId)
    {
        $user = \App\Models\User::with(['students', 'center'])->findOrFail($userId);
        $classroom = \App\Models\Classroom::with(['classroomLevels.level'])->findOrFail($classroomId);

        // Verify the user is enrolled in this classroom
        $studentClassroom = \App\Models\StudentClassroom::where('user_id', $userId)
            ->where('classroom_id', $classroomId)
            ->first();

        if (! $studentClassroom) {
            abort(404, 'Student is not enrolled in this classroom.');
        }

        // Get the course name from main level
        $courseName = $classroom->main_level_name ?? $classroom->name;

        // Redirect to the report card route
        return redirect()->route('course-report-card', [
            'userId' => $userId,
            'classroomId' => $classroomId,
        ]);
    }
}
