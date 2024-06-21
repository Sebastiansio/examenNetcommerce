<?php
namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Validation\ValidationException;
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
        try {
            // Validar los datos de entrada
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'status' => 'nullable|string|in:pending,completed',
                'user_id' => 'required|exists:users,id',
                'company_id' => 'required|exists:companies,id',
                'expired_at' => 'nullable|date|after_or_equal:start_at',
            ]);

    
            if (!isset($validatedData['status'])) {
                $validatedData['status'] = 'pending';
            }
            if (!isset($validatedData['is_completed'])) {
                $validatedData['is_completed'] = false;
            }
            if (!isset($validatedData['start_at'])) {
                $validatedData['start_at'] = now();
            }
            if (!isset($validatedData['expired_at'])) {
                $validatedData['expired_at'] = now();
            }

            // Obtener el usuario
            $user = User::find($validatedData['user_id']);

            // Verificar si el usuario ya tiene 5 tareas pendientes
            $pendingTasksCount = $user->tasks()->where('status', 'pending')->count();
            if ($pendingTasksCount >= 5) {
                return response()->json(['message' => 'El usuario ya tiene 5 tareas pendientes.'], 400);
            }

            // Crear la nueva tarea
            $task = Task::create($validatedData);
            
            $task->load('user', 'company');

            
            $response = [
                'id' => $task->id,
                'name' => $task->name,
                'description' => $task->description,
                'status' => $task->status,
                'is_completed' => $task->is_completed,
                'start_at' => $task->start_at,
                'expired_at' => $task->expired_at,
                'user' => $task->user->name,
                'company' => $task->company->name,
            ];

            return response()->json($response, 201);
        } catch (ValidationException $e) {
            // Retornar los errores de validación
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            // Retornar otros errores
            return response()->json(['message' => $e->getMessage()], 500);
        }
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
                'name' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'status' => 'nullable|string|in:pending,completed',
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

