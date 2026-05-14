@props(['items' => []])
@if(count($items) > 1)
<nav aria-label="Breadcrumb" class="py-3">
    <ol class="flex items-center gap-2 text-sm text-neutral-500">
        @foreach($items as $i => $item)
            @if($i > 0)<li><span class="text-neutral-300">/</span></li>@endif
            <li>
                @if(isset($item['url']) && $item['url'] !== '#' && $i < count($items)-1)
                    <a href="{{ $item['url'] }}" class="hover:text-brand-primary transition-colors">{{ $item['label'] }}</a>
                @else
                    <span class="text-neutral-800 font-medium">{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
@endif