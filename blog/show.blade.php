@extends('theme-db-cyber::layouts.app')

@php
    $trans = public_translation($post);
    $marketCode = $currentMarket?->code ?? 'es';
    $markdownRenderer = app(\DbCms\Core\Services\Media\MarkdownRendererService::class);
    $renderedBody = $trans ? $markdownRenderer->render($trans->content) : '';
@endphp

@section('title', $trans->meta_title ?? $trans->title ?? __('Artículo'))
@section('meta_description', $trans->meta_description ?? $trans->summary ?? '')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12" @if(!empty($post)) data-cms-edit-url="{{ route('admin.blog.posts.edit', $post->id) }}" data-cms-edit-label="Editar Artículo" @endif>

    <!-- Breadcrumb Component -->
    @include('components.breadcrumbs', ['trail' => $breadcrumbs ?? []])

    <!-- Article Header -->
    <header class="space-y-4 mb-8" data-scroll-magic>
        <div class="flex items-center gap-3 text-xs font-mono text-gray-400">
            <span class="px-2.5 py-1 rounded bg-[#00FF99]/10 text-[#00FF99] border border-[#00FF99]/30 font-bold">
                {{ public_translation($post->category)?->name ?? 'Tecnología' }}
            </span>
            <span>&bull;</span>
            <span>{{ ($post->published_at ?? now())->copy()->locale(app()->getLocale())->isoFormat('LL') }}</span>
            <span>&bull;</span>
            <span><i class="fa-solid fa-clock mr-1"></i> {{ $post->reading_time_minutes }} min de lectura</span>
        </div>

        <h1 class="text-3xl sm:text-5xl font-bold text-white tracking-tight leading-tight">
            {{ $trans->title ?? '' }}
        </h1>

        @if($trans->summary)
            <p class="text-base sm:text-lg text-gray-300 font-mono leading-relaxed border-l-2 border-[#00FF99] pl-4 py-1">
                {{ $trans->summary }}
            </p>
        @endif

        <div class="flex items-center space-x-3 pt-2 font-mono text-xs text-gray-400">
            <div class="w-8 h-8 rounded-full bg-[#1A1A1A] border border-[#00FF99] flex items-center justify-center text-[#00FF99] font-bold">
                {{ substr($post->author->name ?? 'A', 0, 1) }}
            </div>
            <div>
                <span class="text-white font-bold">{{ $post->author->name ?? 'Equipo dbCMS' }}</span>
                <span class="text-gray-500 text-[11px] block">{{ __('Investigación & Desarrollo') }}</span>
            </div>
        </div>
    </header>

    <!-- Featured Image -->
    @if($post->featured_image)
        <div class="mb-10 rounded-2xl overflow-hidden border border-gray-800 shadow-2xl" data-scroll-magic>
            <img src="{{ $post->featured_image }}" alt="{{ $trans->title ?? '' }}" class="w-full max-h-[480px] object-cover" loading="eager" fetchpriority="high" decoding="async">
        </div>
    @endif

    <!-- Article Body & Modular Sections -->
    @if(isset($renderedSections) && count($renderedSections) > 0)
        <div class="space-y-10">
            @foreach($renderedSections as $sectionHtml)
                <div data-scroll-magic>
                    {!! $sectionHtml !!}
                </div>
            @endforeach
        </div>
    @else
        <article class="cyber-card p-6 sm:p-10 font-mono text-sm text-gray-200 leading-relaxed space-y-6 prose prose-invert max-w-none" data-scroll-magic>
            {!! $renderedBody !!}
        </article>
    @endif

    @include('blog::partials.comments')

</div>

@endsection
