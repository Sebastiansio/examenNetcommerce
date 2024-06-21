<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;

class TaskController extends Controller
{
    public function index()
    {
        // Obtener todas las tareas con su usuario y compañía asociados
        $tasks = Task::with(['user', 'company'])->get();
        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        // Validar los datos de entrada
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string|in:pending,completed',
            'user_id' => 'required|exists:users,id',
            'company_id' => 'required|exists:companies,id',
            'start_at' => 'nullable|date',
            'expired_at' => 'nullable|date|after_or_equal:start_at',
        ]);

        // Obtener el usuario
        $user = User::find($validatedData['user_id']);

        // Verificar si el usuario ya tiene 5 tareas pendientes
        $pendingTasksCount = $user->tasks()->where('status', 'pending')->count();
        if ($pendingTasksCount >= 5) {
            return response()->json(['message' => 'El usuario ya tiene 5 tareas pendientes.'], 400);
        }

        // Crear la nueva tarea
        $task = Task::create($validatedData);

        return response()->json($task, 201);
    }

    public function show($id)
    {
        // Obtener la tarea con su usuario y compañía asociados
        $task = Task::with(['user', 'company'])->find($id);
        if ($task) {
            return response()->json($task);
        } else {
            return response()->json(['message' => 'Task not found'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $task = Task::find($id);
        if ($task) {
            // Validar los datos de entrada
            $validatedData = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'status' => 'required|string|in:pending,completed',
                'user_id' => 'required|exists:users,id',
                'company_id' => 'required|exists:companies,id',
                'start_at' => 'nullable|date',
                'expired_at' => 'nullable|date|after_or_equal:start_at',
            ]);

            $task->update($validatedData);

            return response()->json($task);
        } else {
            return response()->json(['message' => 'Task not found'], 404);
        }
    }

    public function destroy($id)
    {
        $task = Task::find($id);
        if ($task) {
            $task->delete();
            return response()->json(['message' => 'Task deleted']);
        } else {
            return response()->json(['message' => 'Task not found'], 404);
        }
    }
}

