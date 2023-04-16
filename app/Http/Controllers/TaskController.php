<?php

namespace App\Http\Controllers;
use App\Models\Task;

use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::orderBy('priority')->paginate(10);

        return view('tasks.index', compact('tasks'));
    }
    

    public function store(Request $request)
    {
        // There was a id as auto increament that's why I couldn't add other columb as auto increament. This code will increase priority columb.
        $lastPriority = Task::max('priority');

        $task = new Task;
        $task->name = $request->taskName;
        $task->priority = $lastPriority + 1;
        $task->save();

        return response()->json(['success'=>'Task added successfully.']);
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return redirect()->back()->with('success', 'Task has been deleted!');
    }

    public function update(Request $request, Task $task)
    {
        $task->update(['name' => $request->name]);

        return response()->json(['success' => true]);
    }

    public function reorder(Request $request)
    {
        $taskIds = $request->input('task');
        foreach ($taskIds as $key => $id) {
            Task::where('id', $id)->update(['priority' => ($request->input('page') != null && $request->input('page') != 1) ? (($key + 1) + (($request->input('page') - 1) * 10)) : $key + 1]);
        }

        return response()->json(['success' => true]);
    }



}
