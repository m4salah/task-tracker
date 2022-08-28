<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Project;
use App\Models\Task;


class TaskController extends Controller
{
    /**
     * Display a listing of the tasks.
     *
     * @param  int  $project_id
     */
    public function index(Request $request) {
        $request->validate([
            'project_id' => 'required',
        ]);
        $user = auth()->user();
        $project = Project::find($request->project_id);
        if(!$project) {
            return responseJson(0, 'Project not found');
        }
        if($user && $user->is_admin && $user->id == $project->created_by) {
            $tasks = $project->tasks;
            return responseJson(1, 'All tasks', $tasks);
        }
        return responseJson(0, 'User not authorized');
    }

    /**
     * create task for project
     *
     * @param int  $project_id
     * @param string  $title
     * @param string  $description
     * @param boolean  $assigned_to
     * @return json
     */
    public function create(Request $request) {
        $request->validate([
            'project_id' => 'required',
            'title' => 'required',
            'description' => 'required',
            'assigned_to' => 'required',
        ]);
        $user = auth()->user();
        $project = Project::find($request->project_id);
        if(!$project) {
            return responseJson(0, 'Project not found');
        }
        if($user && $user->is_admin && $user->id == $project->created_by) {
            $project->tasks()->create($request->all());
            return responseJson(1, 'Task created succesfully');
        }
        return responseJson(0, 'User not authorized');
    }


    /**
     * user tasks
     *
     * @param int  $project_id
     * @return json
     */
    public function userTasks(Request $request) {
        $user = auth()->user();
        if($user && !$user->is_admin) {
            $tasks = Task::where('assigned_to', $user->id)->get();
            return responseJson(1, 'All tasks', $tasks);
        }
        return responseJson(0, 'User not authorized');
    }


    /**
     * update task for project
     *
     * @param string  $title
     * @param string  $description
     * @param boolean  $assigned_to
     * @return json
     */
    public function update($id, Request $request)
    {
        $task = Task::find($id);
        $user = auth()->user();
        if(!$task) {
            return responseJson(0, 'Task not found');
        }
        $project = $task->project;
        if(!$user || !$user->is_admin || $user->id != $project->created_by) {
            return responseJson(0, 'User not authorized');
        }
        if($task->submitted){
            return responseJson(0, 'Can not update submitted task');
        }
        if($request->has('title') && $request->title != ''){
            $task->update([
                'title' => request('title'),
            ]);
        }
        if($request->has('description') && $request->description != ''){
            $task->update([
                'description' => request('description'),
            ]);
        }
        if($request->has('assigned_to') && $request->assigned_to != ''){
            $task->update([
                'assigned_to' => request('assigned_to'),
            ]);
        }
        return responseJson(1, 'Task updated successfully');
    }

    /**
     * submit task
     *
     * @param int  $id
     * @return json
     */
    public function submit($id) {
        $user = auth()->user();
        $task = Task::find($id);
        if(!$task) {
            return responseJson(0, 'Task not found');
        }
        if($task->submitted) {
            return responseJson(0, 'Task already submitted');
        }
        if($user && !$user->is_admin && $user->id == $task->assigned_to) {
            $task->submitted = 1;
            $task->save();
            return responseJson(1, 'Task submitted succesfully');
        }
        return responseJson(0, 'User not authorized');
    }

    /**
     * delete task for project
     *
     * @param int  $id
     * @return json
     */
    public function delete($id)
    {
        $task = Task::findO($id);
        if(!$task){
            return responseJson(1, 'Task not found');
        }
        $project = $task->project;
        $user = auth()->user();
        if ($user && $user->is_admin && $user->id == $project->created_by){
            $task->delete();
            return responseJson(1, 'Task deleted successfully');
        }
        return responseJson(1, 'Task not found');
    }
}
