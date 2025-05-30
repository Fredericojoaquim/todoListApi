<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;

class TaskController extends Controller
{


     /**
     * @OA\Get(
     *     path="/api/tasks",
     *     tags={"Tasks"},
     *     summary="Lista as tarefas do usuário autenticado",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de tarefas",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Task")
     *         )
     *     )
     * )
     */
    public function index()
    {
    $tasks = Auth::user()->tasks()->get();

    return response()->json($tasks);
    }


     /**
     * @OA\Post(
     *     path="/api/tasks",
     *     tags={"Tasks"},
     *     summary="Cria uma nova tarefa associada ao usuário autenticado",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title","status"},
     *             @OA\Property(property="title", type="string", example="Comprar pão"),
     *             @OA\Property(property="description", type="string", example="Ir à padaria amanhã"),
     *             @OA\Property(property="status", type="string", enum={"pendente", "em andamento", "concluído"}, example="pendente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tarefa criada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação"
     *     )
     * )
     */
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


     /**
     * @OA\Put(
     *     path="/api/tasks/{task}",
     *     tags={"Tasks"},
     *     summary="Atualiza uma tarefa existente",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="task",
     *         in="path",
     *         description="ID da tarefa a ser atualizada",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Comprar pão integral"),
     *             @OA\Property(property="description", type="string", example="Ir à padaria amanhã cedo"),
     *             @OA\Property(property="status", type="string", enum={"pendente", "em andamento", "concluído"}, example="em andamento")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tarefa atualizada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Não autorizado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tarefa não encontrada"
     *     )
     * )
     */

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



     /**
     * @OA\Delete(
     *     path="/api/tasks/{task}",
     *     tags={"Tasks"},
     *     summary="Remove uma tarefa",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="task",
     *         in="path",
     *         description="ID da tarefa a ser removida",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Tarefa removida com sucesso"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Não autorizado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tarefa não encontrada"
     *     )
     * )
     */

    public function destroy($id)
    {
        $task = Auth::user()->tasks()->find($id);

      

        if (!$task) {
            return response()->json(['message' => 'Tarefa não encontrada ou não pertence ao usuário'], 404);
        }
    
        $task->delete();
    
        return response()->json(['message' => 'Tarefa apagada com sucesso!']);
    }


      /**
     * @OA\Patch(
     *     path="/api/tasks/{task}/status",
     *     tags={"Tasks"},
     *     summary="Altera o status da tarefa",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="task",
     *         in="path",
     *         description="ID da tarefa",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"status"},
     *             @OA\Property(property="status", type="string", enum={"pendente", "em andamento", "concluído"}, example="concluído")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Status alterado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Não autorizado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tarefa não encontrada"
     *     )
     * )
     */

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





