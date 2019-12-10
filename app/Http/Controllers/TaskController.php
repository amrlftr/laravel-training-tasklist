<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task; //class
use Mail;
use App\Mail\TaskCompleted;

class TaskController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(Request $request){
        $tasks = $request->user()->tasks()
            // ->where('id', '>', '1')
            ->get();

        return view('tasks.index', [
            'tasks' => $tasks,
        ]);
    }

    public function store(Request $request){
        $this->validate($request, [
            'name' => 'required|max:255',
        ]);

        $request->user()->tasks()->create([
            'name' => $request->name,
        ]);
    
        return redirect('/tasks');
    }

    public function destroy(Request $request, Task $task){
        $this->authorize('destroy', $task);

        $task->delete();

        return redirect('/tasks');
    }

    public function testEmail(){
        Mail::to("amirulfitri143@gmail.com")->send(new TaskCompleted());
    }
}
