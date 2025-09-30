<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;


class PostController extends Controller
{
    public function index(Category $category = null): Factory|View
    {
        $query = Post::where('status', 'published')->with('user', 'media', 'category');

        if ($category) {
            $query->where('post_category_id', $category->id);
        }

        $posts = $query->latest('published_at')->paginate(9);
        $categories = Category::withCount('posts')->get();

        return view('blog.index', compact('posts', 'category', 'categories'));
    }

    public function show(Post $post): Factory|View
    {
        // শুধুমাত্র প্রকাশিত পোস্টই দেখা যাবে
        if ($post->status !== 'published') {
            abort(404);
        }

        // একই ক্যাটাগরির অন্যান্য পোস্ট (Related Posts)
        $relatedPosts = Post::where('status', 'published')
            ->where('id', '!=', $post->id)
            // ->where('post_category_id', $post->post_category_id) // ক্যাটাগরি থাকলে
            ->with('user', 'media')
            ->latest('published_at')
            ->take(4)
            ->get();

        return view('blog.show', compact('post', 'relatedPosts'));
    }
}
