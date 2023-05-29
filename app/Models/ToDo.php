<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 * @OA\Schema(
 * schema="todos",
 * description="todos data",
 * @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="title", type="string"),
 * @OA\Property(property="description", type="string" ),
 * @OA\Property(property="status", type="boolean", example="true"),
 * @OA\Property(property="created_at", type="timestamp"),
 * @OA\Property(property="updated_at", type="timestamp"),
 * ),
 */

class ToDo extends Model
{
    use HasFactory;
}
