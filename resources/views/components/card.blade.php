@props(['title', 'content', 'footer'])

<div {{ $attributes->merge(['class' => 'bg-white shadow-md rounded-lg p-6 border border-gray-200']) }}>
    @if($title)
    <h3 class="text-lg font-semibold mb-2">{{ $title }}</h3>
    @endif

    <div class="mb-4">
        {{ $content }}
    </div>

    @if($footer)
    <div class="text-sm text-gray-500 border-t pt-2">
        {{ $footer }}
    </div>
    @endif
</div>