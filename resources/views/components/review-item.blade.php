@props(['item', 'property', 'level' => 0, 'editingReviewId'])

@php
    // ভ্যারিয়েবলটি এখানে ডিফাইন করা হয়েছে পঠনযোগ্যতার জন্য
    $isEditing = ($editingReviewId ?? null) === $item->id;
@endphp

{{--
    এই র‍্যাপার div-টি Livewire-কে DOM ডিফ্র্যাগমেন্টেশন সঠিকভাবে পরিচালনা করতে সাহায্য করে।
    wire:key এখানে থাকা অপরিহার্য।
--}}
<div wire:key="item-{{ $item->id }}" id="review-{{ $item->id }}">
    <div x-data="{ openReplyBox: false }"
         @open-reply-box.window="if (event.detail.reviewId === {{ $item->id }}) openReplyBox = !openReplyBox"
         x-on:reply-submitted-successfully.window="openReplyBox = false"
         class="card shadow-none review-items @if($level > 0) bg-light border-0 mb-0 ms-lg-5 ms-md-5 ms-3 mt-3 @else mb-4 @endif"
         style="padding: 1.25rem;">

        <div class="card-body p-0">
            {{-- === START: Quote Reply Section === --}}
            @if($item->replyTo)
                <div class="mb-3 p-3 rounded" style="background-color: #dcf5f1; border-left: 3px solid #03bd9d;">
                    <a href="#review-{{ $item->replyTo->id }}" class="d-flex align-items-center text-decoration-none text-muted mb-2">
                        <i class="material-icons-outlined fs-16 me-1">subdirectory_arrow_right</i>
                        <img src="{{ \Storage::url($item->replyTo->user->avatar_url) ?? 'https://placehold.co/100' }}" alt="{{ $item->replyTo->user->name }}" class="avatar avatar-xs rounded-circle me-2">
                        <span class="fs-14"><strong>{{ $item->replyTo->user->name }}</strong> এর উত্তরে</span>
                    </a>
                    <p class="mb-0 fst-italic text-dark" style="font-size: 0.9em;">
                        "{{ Str::limit($item->replyTo->body, 120) }}"
                    </p>
                </div>
            @endif
            {{-- === END === --}}

            <div class="d-flex align-items-start justify-content-between gap-1 mb-2">
                {{-- Author Info --}}
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <div class="avatar avatar-lg">
                        <img src="{{ \Storage::url($item->user->avatar_url) ?? 'https://placehold.co/100' }}" alt="{{ $item->user->name }}" class="img-fluid rounded-circle">
                    </div>
                    <div>
                        <h6 class="fs-16 fw-medium mb-1 d-flex align-items-center gap-2">
                            {{ $item->user->name }}
                            @if ($item->user_id === $property->user_id)
                                <span class="badge bg-primary">Owner</span>
                            @elseif ($item->user->hasRole('super_admin'))
                                <span class="badge bg-danger">Admin</span>
                            @endif
                        </h6>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <p class="fs-14 mb-0 text-body">{{ $item->created_at->diffForHumans() }}</p>
                            @if($item->rating)
                                <i class="fa-solid fa-circle text-body"></i>
                                <div class="d-flex align-items-center justify-content-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="material-icons-outlined text-warning fs-14">{{ $i <= $item->rating ? 'star' : 'star_border' }}</i>
                                    @endfor
                                </div>
                            @endif
                            @if($item->title)
                                <i class="fa-solid fa-circle text-body"></i>
                                <p class="fs-14 mb-0 text-body">{{ $item->title }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                @auth
                    <div class="d-flex gap-2">
                        {{-- শুধুমাত্র लेखक (author) এই বাটনগুলো দেখতে পাবে --}}
                        @if(auth()->id() === $item->user_id)
                            <a href="javascript:void(0);" wire:click="edit({{ $item->id }})"
                               class="btn btn-sm d-inline-flex align-items-center fs-13 fw-semibold reply-btn"
                               title="Edit">
                                <i class="material-icons-outlined fs-16">edit</i>
                            </a>

                            <a href="javascript:void(0);" wire:click="delete({{ $item->id }})"
                               wire:confirm="আপনি কি নিশ্চিতভাবে এটি মুছে ফেলতে চান?"
                               class="btn btn-sm d-inline-flex align-items-center fs-13 fw-semibold reply-btn bg-danger text-white"
                               title="Delete">
                                <i class="material-icons-outlined fs-16">delete</i>
                            </a>
                        @endif
                        <a href="javascript:void(0);" @click="$dispatch('open-reply-box', { reviewId: {{ $item->id }} })"
                           class="btn btn-sm d-inline-flex align-items-center fs-13 fw-semibold reply-btn">
                            <i class="material-icons-outlined text-dark me-1">repeat</i> Reply
                        </a>
                    </div>
                @endauth
            </div>

            {{-- Body / Edit Form --}}
            @if($isEditing)
                <div wire:key="editing-{{ $item->id }}">
                    <textarea wire:model.defer="editingReviewBody" class="form-control mb-2" rows="3"></textarea>
                    @error('editingReviewBody') <span class="text-danger fs-14">{{ $message }}</span> @enderror
                    <div class="d-flex justify-content-end gap-2">
                        <button wire:click="cancelEdit" class="btn btn-sm btn-secondary">বাতিল</button>
                        <button wire:click="update" class="btn btn-sm btn-primary">সেভ করুন</button>
                    </div>
                </div>
            @else
                <p class="mb-2 text-body">{{ $item->body }}</p>
            @endif

            {{-- Reactions (যদি মূল রিভিউ হয়) --}}
            <div class="d-flex align-items-center gap-3 mt-3">
                <a href="#" wire:click.prevent="toggleReaction({{ $item->id }}, 'like')" class="mb-0 d-flex align-items-center fs-14 text-decoration-none @if($item->authUserReaction?->type === 'like') text-primary @else text-body @endif">
                    <i class="material-icons-outlined me-1 fs-14">thumb_up</i> {{ $item->likes_count }}
                </a>
                <a href="#" wire:click.prevent="toggleReaction({{ $item->id }}, 'dislike')" class="mb-0 d-flex align-items-center fs-14 text-decoration-none @if($item->authUserReaction?->type === 'dislike') text-primary @else text-body @endif">
                    <i class="material-icons-outlined me-1 fs-14">thumb_down</i> {{ $item->dislikes_count }}
                </a>
                <a href="#" wire:click.prevent="toggleReaction({{ $item->id }}, 'favorite')" class="mb-0 d-flex align-items-center fs-14 text-decoration-none @if($item->authUserReaction?->type === 'favorite') text-danger @else text-body @endif">
                    <i class="material-icons-outlined me-1 fs-14">favorite_border</i> {{ $item->favorites_count }}
                </a>
            </div>

            {{-- Reply Form --}}
            <div x-show="openReplyBox" x-transition class="border-top pt-3 mt-3">
{{--                <form wire:submit.prevent="submitReply({{ $item->id }})">--}}
                <form wire:submit.prevent="submitReply({{ $item->parent_id ?? $item->id }}, {{ $item->id }})">
                    <div class="mb-2">
                        <textarea wire:model.defer="replyBody" class="form-control" rows="3" placeholder="আপনার উত্তর লিখুন..."></textarea>
                        @error('replyBody') <span class="text-danger fs-14">{{ $message }}</span> @enderror
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" @click="openReplyBox = false" class="btn btn-sm btn-secondary">বাতিল</button>
                        <button type="submit" class="btn btn-sm btn-primary">উত্তর দিন</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


{{-- Recursive call for children --}}
@if ($item->replies->isNotEmpty())
    @foreach ($item->replies as $reply)
        {{--
            প্রতিটি চাইল্ড আইটেমের জন্য কম্পোনেন্টটি নিজেকেই আবার কল করছে।
            :key অ্যাট্রিবিউট Livewire-কে প্রতিটি চাইল্ডকে আলাদাভাবে ট্র্যাক করতে সাহায্য করে।
        --}}
        <x-review-item :item="$reply" :property="$property" :level="$level + 1" :editingReviewId="$editingReviewId" :key="'item-child-' . $reply->id" />
    @endforeach
@endif
