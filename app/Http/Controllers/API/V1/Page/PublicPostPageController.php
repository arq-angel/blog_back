<?php

namespace App\Http\Controllers\API\V1\Page;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\PublicStoreCommentRequest;
use App\Http\Resources\API\V1\PublicCommentResource;
use App\Http\Resources\API\V1\PublicPostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicPostPageController extends Controller
{
    public function getPost($slug): JsonResponse
    {
        $returnData = [
            "isSuccess" => false,
            "message" => "Could not fetch post",
        ];
        try {
            $post = Post::where('slug', $slug)->firstOrFail();
            $returnData = [
                "isSuccess" => true,
                "message" => "Post found",
                "post" => new PublicPostResource($post),
            ];
        } catch (\Exception $e) {
            if (isDebug()) {
                $returnData["Debug"] = $e->getMessage();
            }
        } catch (\Throwable $th) {
            if (isDebug()) {
                $returnData["Debug"] = $th->getMessage();
            }
        }
        return response()->json($returnData);
    }

    public function getComments(post $post, Request $request): JsonResponse
    {
        $returnData = [
            "isSuccess" => false,
            "message" => "Could not fetch comments",
        ];
        $limit = $request->limit ?? CONFIG["noOfCommentsPerPage"] ?? 10;
        $page = $request->page ?? 1;
        try {
            $comments = $post->comments()->paginate($limit, ['*'], 'page', $page);
            $comments->withPath(request()->url());
            $returnData = [
                "isSuccess" => true,
                "message" => "Comments found",
                "comments" => PublicCommentResource::collection($comments),
                "meta" => [
                    "current_page" => $comments->currentPage(),
                    "from" => $comments->firstItem(),
                    "last_page" => $comments->lastPage(),
                    "links" => [
                        "first" => $comments->url(1),
                        "last" => $comments->url($comments->lastPage()),
                        "prev" => $comments->previousPageUrl(),
                        "next" => $comments->nextPageUrl(),
                    ],
                    "path" => $comments->path(),
                    "per_page" => $comments->perPage(),
                    "to" => $comments->lastItem(),
                    "total" => $comments->total(),
                ],
            ];
        } catch (\Exception $e) {
            if (isDebug()) {
                $returnData["Debug"] = $e->getMessage();
            }
        } catch (\Throwable $th) {
            if (isDebug()) {
                $returnData["Debug"] = $th->getMessage();
            }
        }
        return response()->json($returnData);
    }

    public function storeComments(Post $post, PublicStoreCommentRequest $request): JsonResponse
    {
        $returnData = [
            "isSuccess" => false,
            "message" => "Could not add comment",
        ];

        try {
            $validated = $request->validated();

            $comment = $post->comments()->create([
                "author" => $validated["author"],
                "email" => $validated["email"],
                "content" => $validated["content"],
                "status" => getDefaultCommentStatus() ?? 1, // 1: approved
            ]);

            $returnData = [
                "isSuccess" => true,
                "message" => "Comment added",
                "comment" => new PublicCommentResource($comment),
            ];

        } catch (\Exception $e) {
            if (isDebug()) {
                $returnData["Debug"] = $e->getMessage();
            }
        } catch (\Throwable $th) {
            if (isDebug()) {
                $returnData["Debug"] = $th->getMessage();
            }
        }
        return response()->json($returnData);
    }

}
