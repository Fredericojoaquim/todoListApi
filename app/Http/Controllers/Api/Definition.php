<?php

namespace App\Http\Controllers\Api;

/**
 * @OA\Schema(
 *     schema="Task",
 *     type="object",
 *     title="Tarefa",
 *     required={"id", "title", "status", "user_id"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Comprar pão"),
 *     @OA\Property(property="description", type="string", example="Ir à padaria amanhã"),
 *     @OA\Property(property="status", type="string", example="pendente"),
 *     @OA\Property(property="user_id", type="integer", example=2),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Definitions
{
    // Essa classe é só para armazenar o schema
}
