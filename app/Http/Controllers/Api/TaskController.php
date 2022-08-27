<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Project;
use App\Models\Task;


class TaskController extends Controller
{
    public function index(Request $request) {
        $request->validate([
            'project_id' => 'required',
        ]);
        $user = auth()->user();
        $project = Project::findOrFail($request->project_id);
        if($user && $user->is_admin && $user->id == $project->created_by) {
            $tasks = $project->tasks()->get();
            return responseJson(1, 'All tasks', $tasks);
        }
        return responseJson(0, 'User not authorized');
    }

    public function create(Request $request) {
        $request->validate([
            'project_id' => 'required',
            'title' => 'required',
            'description' => 'required',
            'assigned_to' => 'required',
        ]);
        $user = auth()->user();
        $project = Project::findOrFail($request->project_id);
        if($user && $user->is_admin && $user->id == $project->created_by) {
            $project->tasks()->create($request->all());
            return responseJson(1, 'Task created succesfully');
        }
        return responseJson(0, 'User not authorized');
    }

    public function indexForUser(Request $request) {
        $user = auth()->user();
        $project = Project::findOrFail($request->project_id);
        if($user && !$user->is_admin) {
            $tasks = Task::where('assigned_to', $user->id)->get();
            return responseJson(1, 'All tasks', $tasks);
        }
        return responseJson(0, 'User not authorized');
    }
}
