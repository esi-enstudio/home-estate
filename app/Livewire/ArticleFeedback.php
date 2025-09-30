<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;

class ArticleFeedback extends Component
{
    public Post $post;
    public int $helpfulCount;
    public int $unhelpfulCount;
    public bool $hasVoted = false;

    public function mount(Post $post): void
    {
        $this->post = $post;
        $this->helpfulCount = $post->helpful_votes;
        $this->unhelpfulCount = $post->unhelpful_votes;

        // সেশন থেকে চেক করা হচ্ছে যে ইউজার এই পোস্টের জন্য আগে ভোট দিয়েছে কিনা
        if (session()->has('voted_post_' . $this->post->id)) {
            $this->hasVoted = true;
        }
    }

    public function vote(string $type): void
    {
        if ($this->hasVoted) {
            session()->flash('vote_message', 'আপনি ইতোমধ্যে আপনার মতামত দিয়েছেন।');
            return;
        }

        if ($type === 'yes') {
            $this->post->increment('helpful_votes');
            $this->helpfulCount++;
        } else {
            $this->post->increment('unhelpful_votes');
            $this->unhelpfulCount++;
        }

        // সেশনে একটি ফ্ল্যাগ সেট করা হচ্ছে যাতে একই ইউজার বারবার ভোট দিতে না পারে
        session()->put('voted_post_' . $this->post->id, true);
        $this->hasVoted = true;
        session()->flash('vote_message', 'আপনার মূল্যবান মতামতের জন্য ধন্যবাদ!');
    }
    public function render()
    {
        return view('livewire.article-feedback');
    }
}
