<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\HomepageCategoriesResource;
use App\Http\Resources\API\V1\HomepagePostsResource;
use App\Http\Resources\API\V1\HomepageRecentPostsResource;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function getHomepagePosts(Request $request): JsonResponse
    {
        $returnData = ["isSuccess" => false, "message" => "Could not fetch homepage data"];

        try {
            $limit = $request->limit ?? CONFIG["noOfPostsPerPage"] ?? 10;
            $page = $request->page ?? 1;
            $searchQuery = $request->query('searchQuery', '');

            $postsQuery = Post::query();
            if (!empty($searchQuery)) {
                $postsQuery->where(function ($postsQuery) use ($searchQuery) {
                    $postsQuery->where('title', 'like', "%{$searchQuery}%")
                        ->orWhere('author', 'like', "%{$searchQuery}%")
                        ->orWhere('content', 'like', "%{$searchQuery}%")
                        ->orWhere('tags', 'like', "%{$searchQuery}%");
                });
            }
            $posts = $postsQuery->orderBy('updated_at', 'desc')
                ->with('category')
                ->paginate($limit, ['*'], 'page', $page)
                ->onEachSide(1);
            $posts->withPath(request()->url());
            foreach ($posts as $post) {
                $post->content = Str::limit($post->content, 300);
            }

            $returnData = [
                "isSuccess" => true,
                "message" => "Homepage posts retrieved successfully",
                "posts" => HomepagePostsResource::collection($posts),
                "queryParams" => request()->query() ?: null,
                'meta' => [
                    'currentPage' => $posts->currentPage(),
                    'lastPage' => $posts->lastPage(),
                    'perPage' => $posts->perPage(),
                    'total' => $posts->total(),
                    'from' => $posts->firstItem(),
                    'to' => $posts->lastItem(),
                ],
                'links' => [
                    'first' => $posts->url(1),
                    'last' => $posts->url($posts->lastPage()),
                    'prev' => $posts->previousPageUrl(),
                    'next' => $posts->nextPageUrl(),
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

    public function getNavLinks(): JsonResponse
    {
        $returnData = ["isSuccess" => false, "message" => "Could not fetch navigation links"];

        try {
            $navLinks = Category::select(
                'id',
                'title',
                'slug',
            )
                ->orderBy('updated_at', 'desc')
                ->limit(CONFIG['noOfNavLinks'])
                ->get();

            $returnData = [
                "isSuccess" => true,
                "message" => "Homepage nav links retrieved successfully",
                "navLinks" => HomepageCategoriesResource::collection($navLinks),
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

    public function getCategories(): JsonResponse
    {
        $returnData = ["isSuccess" => false, "message" => "Could not fetch categories"];

        try {
            $categories = Category::select(
                'id',
                'title',
                'slug',
            )->get();

            $returnData = [
                "isSuccess" => true,
                "message" => "Homepage categories retrieved successfully",
                "categories" => HomepageCategoriesResource::collection($categories),
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

    public function getRecentPosts(): JsonResponse
    {
        $returnData = ["isSuccess" => false, "message" => "Could not fetch recent posts"];

        try {
            $recentPosts = Post::select(
                'id',
                'title',
                'slug',
                'author',
                'image'
            )
                ->orderBy('created_at', 'desc')
                ->limit(CONFIG['noOfRecentPosts'])
                ->get();

            $returnData = [
                "isSuccess" => true,
                "message" => "Homepage recent posts retrieved successfully",
                "recentPosts" => HomepageRecentPostsResource::collection($recentPosts),
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
