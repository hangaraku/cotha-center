<?php

namespace App\Http\Controllers;

use App\Models\UserProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = UserProject::where('user_id', Auth::id())
            ->with(['userModule.module'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|string|max:255',
            'url' => [
                'required',
                'url',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->type === 'Scratch') {
                        // Check if URL is from scratch.com domain
                        if (! preg_match('/^https?:\/\/(www\.)?scratch\.mit\.edu\/projects\/\d+\/?$/', $value)) {
                            $fail('URL Scratch harus dari domain scratch.mit.edu dan berakhir dengan angka (ID project).');
                        }
                    }
                },
            ],
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $thumbnailPath = $filename;
        }

        UserProject::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'url' => $request->url,
            'title' => $request->title,
            'description' => $request->description,
            'thumbnail' => $thumbnailPath,
            'views' => 0,
            'score' => 0,
        ]);

        return redirect()->route('projects.index')->with('success', 'Project berhasil disubmit!');
    }

    public function update(Request $request, $id)
    {
        $project = UserProject::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|string|max:255',
            'url' => [
                'required',
                'url',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->type === 'Scratch') {
                        // Check if URL is from scratch.com domain
                        if (! preg_match('/^https?:\/\/(www\.)?scratch\.mit\.edu\/projects\/\d+\/?$/', $value)) {
                            $fail('URL Scratch harus dari domain scratch.mit.edu dan berakhir dengan angka (ID project).');
                        }
                    }
                },
            ],
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $thumbnailPath = $project->thumbnail;
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail if exists
            if ($project->thumbnail && file_exists(public_path('uploads/'.$project->thumbnail))) {
                unlink(public_path('uploads/'.$project->thumbnail));
            }

            $file = $request->file('thumbnail');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $thumbnailPath = $filename;
        }

        $project->update([
            'type' => $request->type,
            'url' => $request->url,
            'title' => $request->title,
            'description' => $request->description,
            'thumbnail' => $thumbnailPath,
        ]);

        return redirect()->route('projects.index')->with('success', 'Project berhasil diupdate!');
    }

    public function destroy($id)
    {
        $project = UserProject::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // Delete thumbnail if exists
        if ($project->thumbnail && file_exists(public_path('uploads/'.$project->thumbnail))) {
            unlink(public_path('uploads/'.$project->thumbnail));
        }

        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project berhasil dihapus!');
    }

    public function incrementView($id)
    {
        $project = UserProject::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $project->increment('views');

        // For Scratch projects, validate the URL format first
        if ($project->type === 'Scratch') {
            // Check if URL format is valid for Scratch
            if (! preg_match('/^https?:\/\/(www\.)?scratch\.mit\.edu\/projects\/\d+\/?$/', $project->url)) {
                Log::info('Invalid Scratch URL format: '.$project->url);

                return response()->json([
                    'success' => true,
                    'views' => $project->views,
                    'redirect' => $project->url,
                    'message' => 'URL tidak valid untuk Scratch project',
                ]);
            }

            // If URL format is valid, proceed with embed (let iframe handle failures)
            Log::info('Valid Scratch URL format, proceeding with embed: '.$project->url);
        }

        return response()->json(['success' => true, 'views' => $project->views]);
    }

    /**
     * API endpoint to get student projects with detailed information
     */
    public function apiGetProjects(Request $request)
    {
        // Check API key
        $apiKey = $request->header('X-API-Key') ?? $request->query('api_key');
        if ($apiKey !== 'cothacotha') {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or missing API key',
            ], 401);
        }
        $query = UserProject::with([
            'user:id,name,email,profile_picture',
            'user.students:user_id,school,birthdate',
            'userModule.module:id,name,description,img_url,level_id',
            'userModule.module.level:id,name',
            'interactions.user:id,name,profile_picture',
        ])
            ->withCount(['likes', 'loves', 'stars'])
            ->orderBy('created_at', 'desc');

        // Optional: filter by user_id (student)
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Optional: filter by type
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $projects = $query->get()->map(function ($project) {
            $student = $project->user->students;
            $school = $student ? $student->school : null;
            $age = null;

            if ($student && $student->birthdate) {
                $age = \Carbon\Carbon::parse($student->birthdate)->age;
            }

            return [
                'id' => $project->id,
                'type' => $project->type,
                'url' => $project->url,
                'title' => $project->title,
                'description' => $project->description,
                'thumbnail' => $project->thumbnail ? asset('uploads/'.$project->thumbnail) : null,
                'views' => $project->views,
                'score' => $project->score,
                'created_at' => $project->created_at,
                'updated_at' => $project->updated_at,
                'user' => [
                    'id' => $project->user->id,
                    'name' => $project->user->name,
                    'email' => $project->user->email,
                    'profile_picture' => $project->user->profile_picture ? asset('uploads/'.$project->user->profile_picture) : null,
                    'school' => $school,
                    'age' => $age,
                ],
                'module' => $project->userModule && $project->userModule->module ? [
                    'id' => $project->userModule->module->id,
                    'name' => $project->userModule->module->name,
                    'description' => $project->userModule->module->description,
                    'img_url' => $project->userModule->module->img_url,
                    'level' => $project->userModule->module->level ? [
                        'id' => $project->userModule->module->level->id,
                        'name' => $project->userModule->module->level->name,
                    ] : null,
                ] : null,
                'interactions' => [
                    'likes_count' => $project->likes_count,
                    'loves_count' => $project->loves_count,
                    'stars_count' => $project->stars_count,
                    'total_count' => $project->likes_count + $project->loves_count + $project->stars_count,
                ],
                'interactions_detail' => $project->interactions->map(function ($interaction) {
                    return [
                        'id' => $interaction->id,
                        'type' => $interaction->type,
                        'user' => [
                            'id' => $interaction->user->id,
                            'name' => $interaction->user->name,
                            'profile_picture' => $interaction->user->profile_picture ? asset('uploads/'.$interaction->user->profile_picture) : null,
                        ],
                        'created_at' => $interaction->created_at,
                    ];
                }),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $projects,
            'count' => $projects->count(),
        ]);
    }

    /**
     * API endpoint to get a single student project with detailed information
     */
    public function apiGetProject(Request $request, $id)
    {
        // Check API key
        $apiKey = $request->header('X-API-Key') ?? $request->query('api_key');
        if ($apiKey !== 'cothacotha') {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or missing API key',
            ], 401);
        }
        $project = UserProject::with([
            'user:id,name,email,profile_picture',
            'user.students:user_id,school,birthdate',
            'userModule.module:id,name,description,img_url,level_id',
            'userModule.module.level:id,name',
            'interactions.user:id,name,profile_picture',
        ])
            ->withCount(['likes', 'loves', 'stars'])
            ->findOrFail($id);

        $student = $project->user->students;
        $school = $student ? $student->school : null;
        $age = null;

        if ($student && $student->birthdate) {
            $age = \Carbon\Carbon::parse($student->birthdate)->age;
        }

        $projectData = [
            'id' => $project->id,
            'type' => $project->type,
            'url' => $project->url,
            'title' => $project->title,
            'description' => $project->description,
            'thumbnail' => $project->thumbnail ? asset('uploads/'.$project->thumbnail) : null,
            'views' => $project->views,
            'score' => $project->score,
            'created_at' => $project->created_at,
            'updated_at' => $project->updated_at,
            'user' => [
                'id' => $project->user->id,
                'name' => $project->user->name,
                'email' => $project->user->email,
                'profile_picture' => $project->user->profile_picture ? asset('uploads/'.$project->user->profile_picture) : null,
                'school' => $school,
                'age' => $age,
            ],
            'module' => $project->userModule && $project->userModule->module ? [
                'id' => $project->userModule->module->id,
                'name' => $project->userModule->module->name,
                'description' => $project->userModule->module->description,
                'img_url' => $project->userModule->module->img_url,
                'level' => $project->userModule->module->level ? [
                    'id' => $project->userModule->module->level->id,
                    'name' => $project->userModule->module->level->name,
                ] : null,
            ] : null,
            'interactions' => [
                'likes_count' => $project->likes_count,
                'loves_count' => $project->loves_count,
                'stars_count' => $project->stars_count,
                'total_count' => $project->likes_count + $project->loves_count + $project->stars_count,
            ],
            'interactions_detail' => $project->interactions->map(function ($interaction) {
                return [
                    'id' => $interaction->id,
                    'type' => $interaction->type,
                    'user' => [
                        'id' => $interaction->user->id,
                        'name' => $interaction->user->name,
                        'profile_picture' => $interaction->user->profile_picture ? asset('uploads/'.$interaction->user->profile_picture) : null,
                    ],
                    'created_at' => $interaction->created_at,
                ];
            }),
        ];

        return response()->json([
            'success' => true,
            'data' => $projectData,
        ]);
    }
}
