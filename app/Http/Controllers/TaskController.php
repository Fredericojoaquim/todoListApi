<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {
    $tasks = Auth::user()->tasks()->get();

    return response()->json($tasks);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'required|in:pendente,em_andamento,concluido'
        ]);

        $task = Auth::user()->tasks()->create($validated);
        return response()->json($task, 201);
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);
        return $task;
    }

    public function update(Request $request, Task $task)
    {
        
       // $this->authorize('update', $task);
       
        $validated = $request->validate([
            'title' => 'sometimes|string',
            'description' => 'nullable|string',
            'status' => 'in:pendente,em_andamento,concluido'
        ]);

        $task->update($validated);
        return $task;
    }

    public function destroy($id)
    {
        $task = Auth::user()->tasks()->find($id);

      

        if (!$task) {
            return response()->json(['message' => 'Tarefa não encontrada ou não pertence ao usuário'], 404);
        }
    
        $task->delete();
    
        return response()->json(['message' => 'Tarefa apagada com sucesso!']);
    }


    public function updateStatus(Request $request, $id)
   {
    $request->validate([
        'status' => 'required|string|in:pendente,em_andamento,concluído',
    ]);

    $task = Auth::user()->tasks()->find($id);
    

    if (!$task) {
        return response()->json(['message' => 'Tarefa não encontrada ou não pertence ao usuário'], 404);
    }

    $task->status = $request->status;
    $task->save();

    return response()->json(['message' => 'Status da tarefa atualizado com sucesso!', 'task' => $task]);
    }

    




}





