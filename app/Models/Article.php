<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 * @OA\Schema(
 * schema="articles",
 * description="artical data",
 * @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="title", type="string"),
 * @OA\Property(property="description", type="string" ),
 * @OA\Property(property="created_at", type="timestamp"),
 * @OA\Property(property="updated_at", type="timestamp"),
 * ),
 */
class Article extends Model
{
    use HasFactory;

    protected $guarded = ["id"];
}
