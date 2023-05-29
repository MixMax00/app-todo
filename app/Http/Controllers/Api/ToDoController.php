<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ToDo;
use Illuminate\Http\Request;

class ToDoController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/todo",
     *     summary="Retrieve all todo items",
     *     tags={"Todo"},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="datas", type="array", @OA\Items(ref="#/components/schemas/todos"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Todo not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Todo not found.")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $datas = ToDo::latest()->get();
        if ($datas) {
            return response()->json([
                "code"    => 200,
                "status"  => true,
                "datas"   => $datas,
            ]);
        } else {
            return response()->json([
                "code"   => 404,
                "status"  => false,
                "message" => "Todo not found.",
            ]);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/todo/store",
     *     summary="Create a new todo item",
     *     tags={"Todo"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Todo Title"),
     *             @OA\Property(property="description", type="string", example="Todo Description")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Todo created successfully!",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=201),
     *             @OA\Property(property="message", type="string", example="Todo created successfully!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object", additionalProperties={"type": "array", "items": {"type": "string"}})
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "title" => "required|string",
        ]);

        ToDo::create([
            "title" => $request->title,
            "description" => $request->description,
        ]);

        return response()->json([
            "status" => 201,
            "message" => "Todo created successfully!"
        ]);
    }


    /**
     * @OA\Put(
     *     path="/api/todo/update",
     *     summary="Update a todo item",
     *     tags={"Todo"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="Updated Todo Title"),
     *             @OA\Property(property="description", type="string", example="Updated Todo Description")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Todo updated successfully!",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Todo updated successfully!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Todo not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Todo not found.")
     *         )
     *     )
     * )
     */
    public function update(Request $request)
    {
        if (auth()->user()) {
            ToDo::where('id', $request->id)->update([
                "title" => $request->title,
                "description" => $request->description,
            ]);
        }

        return response()->json([
            "status" => true,
            "message" => "Todo updated successfully!"
        ]);
    }


    /**
     * @OA\Delete(
     *     path="/api/todo/delete/{id}",
     *     summary="Delete a todo item",
     *     tags={"Todo"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the todo item to delete",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Todo deleted successfully!",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Todo deleted successfully!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Todo not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Todo not found.")
     *         )
     *     )
     * )
     */
    public function delete($id)
    {
        $todo = ToDo::find($id);
        if ($todo) {
            $todo->delete();
            return response()->json([
                "status" => true,
                "message" => "Todo deleted successfully!"
            ]);
        }
    }
}
