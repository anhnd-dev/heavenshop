<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Blog;
use App\Http\Controllers\Controller;

class BlogController extends Controller
{
    public function index() // :GET
    {
        $blogs = Blog::latest()->paginate(12);
        return view('frontend.pages.blog', compact('blogs'));
    }

    public function show($slug) // :GET
    {
        $blog = Blog::where('slug', $slug)->first();

        $currentBlogTags = explode(',', $blog->tags);

        // Lấy các bài viết có chứa tất cả các tags giống với bài viết hiện tại và loại bỏ bài viết hiện tại
        $relatedBlogs = Blog::where(function ($query) use ($currentBlogTags) {
            foreach ($currentBlogTags as $tag) {
                $query->where('tags', 'LIKE', '%' . $tag . '%');
            }
        })
            ->whereNotIn('id', [$blog->id]) // Loại bỏ bài viết hiện tại
            ->take(3) // Giới hạn số lượng bài viết liên quan
            ->get();

        return view('frontend.pages.blog_detail', compact('blog', 'relatedBlogs'));
    }
}
