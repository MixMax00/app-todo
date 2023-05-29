<?php

namespace App\Http\Controllers\Api;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

class ArticleController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/articles",
     *     summary="Get all articles",
     *     tags={"Articles"},
     *     @OA\Response(
     *         response=200,
     *         description="Articles found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="datas", type="array", @OA\Items(ref="#/components/schemas/articles"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No articles found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Article not found.")
     *         )
     *     )
     * )
     */
    public function allArticle()
    {
        $datas = Article::where('status', 1)->get();
        if ($datas) {
            return response()->json([
                "status" => true,
                "datas" => $datas,
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "Article not found.",
            ]);
        }
    }


    /**
     * @OA\Get(
     *     path="/api/my-articles",
     *     summary="Get user's articles",
     *     tags={"Articles"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="User's articles found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="datas", type="array", @OA\Items(ref="#/components/schemas/articles"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No articles found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Article not found.")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $datas = Article::where('user_id', auth()->user()->id)->get();
        if ($datas) {
            return response()->json([
                "status" => true,
                "datas" => $datas,
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "Article not found.",
            ]);
        }
    }


    /**
     * @OA\Post(
     *     path="/api/articles",
     *     summary="Create a new article",
     *     tags={"Articles"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="New Article"),
     *             @OA\Property(property="description", type="string", example="This is a new article.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Article created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=201),
     *             @OA\Property(property="message", type="string", example="Article created successfully!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid.")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "title" => "required|string",
            "description" => "required",
        ]);

        Article::create([
            "user_id" => auth()->user()->id,
            "title" => $request->title,
            "description" => $request->description,
            "created_at" => Carbon::now(),
        ]);

        return response()->json([
            "status" => 201,
            "message" => "Article created successfully!"
        ]);
    }


    /**
     * @OA\Put(
     *     path="/api/articles/{id}",
     *     summary="Update an existing article",
     *     tags={"Articles"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Article ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             example=1
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Updated Article"),
     *             @OA\Property(property="description", type="string", example="This article has been updated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Article updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Article updated successfully!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Article not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Article not found.")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $article = Article::where('user_id', auth()->user()->id)->find($id);
        if (!$article) {
            return response()->json([
                "status" => false,
                "message" => "Article not found.",
            ], 404);
        }

        $this->validate($request, [
            "title" => "required|string",
            "description" => "required",
        ]);

        $article->title = $request->title;
        $article->description = $request->description;
        $article->updated_at = Carbon::now();
        $article->save();

        return response()->json([
            "status" => true,
            "message" => "Article updated successfully!"
        ]);
    }


    /**
     * @OA\Delete(
     *     path="/api/articles/{id}",
     *     summary="Delete an article",
     *     tags={"Articles"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Article ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             example=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Article deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Article deleted successfully!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Article not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Article not found.")
     *         )
     *     )
     * )
     */
    public function delete($id)
    {
        $article = Article::where('user_id', auth()->user()->id)->find($id);
        if (!$article) {
            return response()->json([
                "status" => false,
                "message" => "Article not found.",
            ], 404);
        }

        $article->delete();
        return response()->json([
            "status" => true,
            "message" => "Article deleted successfully!"
        ]);
    }
}
