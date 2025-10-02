{{-- resources/views/components/layouts/menu-item.blade.php (চূড়ান্ত সংস্করণ) --}}
@props(['item', 'level' => 1])

{{-- 'has-submenu' ক্লাসটি শুধুমাত্র তখনই যোগ হবে যখন children থাকবে --}}
<li class="{{ $item->children->isNotEmpty() ? 'has-submenu' : '' }} {{-- Active logic --}}">
    <a href="{{ url($item->url) }}">
        {{ $item->label }}

        {{-- === START: চূড়ান্ত এবং সঠিক আইকন লজিক === --}}
        {{--
            শুধুমাত্র লেভেল ১ এবং children থাকলেই expand_more আইকনটি রেন্ডার হবে।
            অন্য কোনো লেভেলে কোনো <i...> ট্যাগ রেন্ডার হবে না, কারণ CSS স্বয়ংক্রিয়ভাবে '>' আইকনটি যোগ করবে।
        --}}
        @if($item->children->isNotEmpty() && $level === 1)
            <i class="material-icons-outlined">expand_more</i>
        @endif
        {{-- === END: চূড়ান্ত এবং সঠিক আইকন লজিক === --}}
    </a>

    @if($item->children->isNotEmpty())
        <ul class="submenu">
            @foreach($item->children as $child)
                <x-layouts.menu-item :item="$child" :level="$level + 1" />
            @endforeach
        </ul>
    @endif
</li>
