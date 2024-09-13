<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->allTaskList) {
            $tasks = Task::orderBy('created_at', 'ASC')->get();
        } else {
            // $tasks = Task::where('is_completed', 0)
            //     ->orderBy('created_at', 'ASC')
            //     ->paginate(10);
            $tasks = Task::where('is_completed', 0)
                ->orderBy('created_at', 'ASC')
                ->get();
    
            }
            return response()->json([
                'tasks' => $tasks
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->only('task'),[
            'task' => 'required|unique:tasks'
        ], [
            'task.required' => 'Task field is required',
            'task.unique' => 'Task already exists'
        ]);

        if($validator->fails()){
            return response()->json([
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $task = Task::create([
            'task' => $request->task
        ]);

        return response()->json($task, 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $task->update([
            'is_completed' => $request->is_completed
        ]);
        return response()->json([
            'message' => 'Task updated successfully'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json([
            'message' => 'Task deleted successfully'
        ], 200);
    }
}
