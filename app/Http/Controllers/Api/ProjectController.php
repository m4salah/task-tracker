<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Project;

class ProjectController extends Controller
{
    /**
     * Display a listing of the projects.
     *
     * @return json
     */
    public function index() {
        $user = auth()->user();
        if($user && $user->is_admin) {
            $projects = $user->projects()->get();
            return responseJson(1, 'All projects admin created', $projects);
        }
        return responseJson(0, 'User not authorized');
    }

    /**
     * create project
     *
     * @param string  $title
     * @param string  $description
     * @return json
     */
    public function create(Request $request) {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);
        $user = auth()->user();
        if($user && $user->is_admin) {
            $user->projects()->create($request->all());
            return responseJson(1, 'project created succefully');
        }
        return responseJson(0, 'User not authorized');
    }

    /**
     * Update specified project
     *
     * @return json
     */
    public function update($id, Request $request)
    {
        $project = Project::find($id);
        $user = auth()->user();
        if(!$project) {
            return responseJson(0, 'Project not found');
        }
        if ($user && $user->is_admin && $user->id == $project->created_by){
            if($request->has('title') && $request->title != ''){
                $project->update([
                    'title' => request('title'),
                ]);
            }
            if($request->has('description') && $request->description != ''){
                $project->update([
                    'description' => request('description'),
                ]);
            }
            return responseJson(1, 'Project updated successfully');
        }
        return responseJson(0, 'Project not found');
    }

    /**
     * Delete specified project
     *
     * @return json
     */
    public function delete($id)
    {
        $project = Project::find($id);
        $user = auth()->user();
        if(!$project) {
            return responseJson(0, 'Project not found');
        }
        if ($user && $user->is_admin && $user->id == $project->created_by){
            if(count($project->tasks) > 0) {
                return responseJson(0, 'Project has tasks, delete them first');
            }
            $project->delete();
            return responseJson(1, 'Project deleted successfully');
        }
        return responseJson(1, 'Not authorized');
    }
}
