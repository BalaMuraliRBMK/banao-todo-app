<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Task;
use Auth;

class TaskController extends Controller
{
    //get all tasks
    public function getAllTasks(Request $request)
    {
        $tasks = Task::where('user_id',$request->user_id)->orderBy('id', 'DESC')->get();
        return response()->json([
            'tasks' => $tasks
        ],200);
    }

    //add task
    public function addTask(Request $request)
    {
        $user = User::where('id',$request->user_id)->first();
        if($user){
            $task = Task::create([
                'task' => $request->task,
                'user_id' => $request->user_id,
            ]);
            return response()->json([
                'task' => $task,
                'status' => 1,
                'message' => 'Successfully Created a Task',
            ]);
        }
        else{
            return response()->json([
                'status' => 0,
                'message' => 'No  User Found',
            ],200);
        }
    }

    //update task
    public function updateTask(Request $request)
    {
        $task = Task::where('id',$request->id)->first();
        if($task){
            if($request->status == 'pending' || $request->status == 'Pending'){
                $task ['status'] = $request->status;
                $task->save();
                return response()->json([
                    'task' => $task,
                    'status' => 1,
                    'message' => 'Marked task as pending',
                ]);
            }
            else{
                $task ['status'] = $request->status;
                $task->save();
                return response()->json([
                    'task' => $task,
                    'status' => 1,
                    'message' => 'Marked task as done',
                ]);
            }
        }
        else{
            return response()->json([
                'status' => 'No Task Found',
                'code' => 404,
            ],200);
        }
    }
}
