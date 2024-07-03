<?php

use Illuminate\Support\Facades\Auth;

const CONFIG = [
    'noOfNavLinks' => 8,
    'noOfRecentPosts' => 10,
    'noOfPostsPerPage' => 5,
    'noOfCommentsPerPage' => 5,
];

if (!function_exists('getPublicToken')) {
    function getPublicToken(): string
    {
        return "2|LTBqZlXejYRxYHgpC6rHTSrnKSlIzimx9Jnxb73973c10e72";
    }
}

if (!function_exists('isDebug')) {
    function isDebug(): bool
    {
        return true;
    }
}

if (!function_exists('getAuthenticatedUser')) {
    function getAuthenticatedUser()
    {
        return Auth::guard('sanctum')->user();
    }
}

if (!function_exists('getDefaultCommentStatus')) {
    function getDefaultCommentStatus(): int
    {
        return 1; // 1: approved
    }
}

