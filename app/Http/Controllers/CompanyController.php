<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    
    public function index()
    {
        // Obtener todas las compañías con sus tareas y los usuarios asociados
        $companies = Company::with(['tasks.user'])->get();
      

        // Formatear la respuesta
        $formattedCompanies = $companies->map(function ($company) {
            return [
                'id' => $company->id,
                'name' => $company->name,
                'tasks' => $company->tasks->map(function ($task) {
                    return [
                        'id' => $task->id,
                        'name' => $task->name,
                        'description' => $task->description,
                        'user' => $task->user->name,
                        'is_completed' => $task->is_completed,
                        'start_at' => $task->start_at,
                        'expired_at' => $task->expired_at,
                    ];
                }),
            ];
        });

        return response()->json($formattedCompanies);
    }

   /**
    * Store a newly created resource in storage.
    */
   public function store(Request $request)
   {
       //Validacion de los datos que llegan
       $validatedData = $request->validate([
           'name' => 'required|string|max:255',
       ]);

       
       // Crear la company
       $company = Company::create([
           'name' => $validatedData['name']
       ]);


       return response()->json($company,201);
   }

   /**
    * Display the specified resource.
    */
   public function show(string $id)
   {
       //devuelve un usuario especifico
       $company = Company::find($id);

       if($company){
           return response()->json($company);
       }else{
           return response()->json(['message'=> ''],404);
       }
   }

   /**
    * Update the specified resource in storage.
    */
   public function update(Request $request, string $id)
   {
       $company = Company::find($id);
       if ($company) {
           // Validar los datos de entrada
           $validatedData = $request->validate([
               'name' => 'sometimes|required|string|max:255',
           ]);

           // Actualizar el usuario
           $company->update([
               'name' => $validatedData['name'] ?? $company->name,
           ]);

           return response()->json($company);
       } else {
           return response()->json(['message' => 'company not found'], 404);
       }
   }

   /**
    * Remove the specified resource from storage.
    */
   public function destroy(string $id)
   {
       $company = Company::find($id);
       if ($company) {
           $company->delete();
           return response()->json(['message' => 'company deleted']);
       } else {
           return response()->json(['message' => 'company not found'], 404);
       }
   }
}
