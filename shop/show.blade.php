@extends('theme-db-cyber::layouts.app')

@section('title', ($translation->meta_title ?? $translation->name) . ' - dbCMS')
@section('meta_description', $translation->meta_description ?? $translation->summary ?? '')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-16" @if(!empty($product)) data-cms-edit-url="{{ route('admin.shop.products.edit', $product->id) }}" data-cms-edit-label="Editar Producto" @endif x-data="{ activeImage: @js($product->featured_image ?? '') }">

    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-2 text-xs font-mono text-gray-500" data-scroll-magic>
        <a href="{{ route('shop.index', ['market' => $currentMarket->code, 'locale' => $currentLocale]) }}" class="hover:text-[#00FF99]">{{ __(__('Catálogo')) }}</a>
        <span>/</span>
        @if($product->category)
            <span>{{ $product->category->localizedName() }}</span>
            <span>/</span>
        @endif
        <span class="text-gray-300 font-bold">{{ $translation->name }}</span>
    </nav>

    <!-- Product Overview Grid (Photo Gallery + Purchase / Quote Box) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start" data-scroll-magic>

        <!-- Left: Image Gallery -->
        <div class="space-y-4">
            <div class="cyber-card h-96 sm:h-[480px] bg-black rounded-2xl overflow-hidden border border-gray-800 flex items-center justify-center p-2">
                <template x-if="activeImage">
                    <img :src="activeImage" alt="{{ $translation->name }}" class="max-h-full max-w-full object-contain" loading="lazy" decoding="async">
                </template>
                <template x-if="!activeImage">
                    <div class="text-gray-600 text-6xl"><i class="fa-solid fa-box"></i></div>
                </template>
            </div>

            <!-- Thumbnail Switcher -->
            @if(!empty($product->gallery_images) && count($product->gallery_images) > 0)
                <div class="flex items-center gap-3 overflow-x-auto pb-2">
                    @if($product->featured_image)
                        <button type="button" @click="activeImage = @js($product->featured_image)" class="w-16 h-16 rounded-lg overflow-hidden border-2 bg-black shrink-0 transition-all" :class="activeImage === @js($product->featured_image) ? 'border-[#00FF99]' : 'border-gray-800 opacity-60 hover:opacity-100'">
                            <img src="{{ $product->featured_image }}" class="w-full h-full object-cover" loading="lazy" decoding="async">
                        </button>
                    @endif
                    @foreach($product->gallery_images as $gImg)
                        <button type="button" @click="activeImage = @js($gImg)" class="w-16 h-16 rounded-lg overflow-hidden border-2 bg-black shrink-0 transition-all" :class="activeImage === @js($gImg) ? 'border-[#00FF99]' : 'border-gray-800 opacity-60 hover:opacity-100'">
                            <img src="{{ $gImg }}" class="w-full h-full object-cover" loading="lazy" decoding="async">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Right: Specs, Pricing & Purchase / Quote Action Box -->
        <div class="cyber-card p-6 sm:p-8 space-y-6 font-mono">
            <div class="space-y-2 border-b border-gray-800 pb-5">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-[#00FF99] uppercase font-bold tracking-wider">
                        {{ $product->category?->localizedName() ?? 'General' }}
                    </span>
                    <span class="text-xs text-gray-500 font-mono">
                        SKU: {{ $product->sku }}
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-white leading-tight">
                    {{ $translation->name }}
                </h1>
            </div>

            <!-- Short Summary -->
            @if($translation->summary)
            <p class="text-gray-300 text-sm leading-relaxed">
                {{ $translation->summary ?? '' }}
            </p>
            @endif

            <!-- Actions: Cart vs Quote -->
            @include('shop::partials.product-offer')

            <!-- Technical Specifications Quick Preview -->
            @include('shop::partials.product-specifications')

        </div>

    </div>

    <!-- Modular Sections & Detailed Description (Content Block Manager) -->
    @if(isset($renderedSections) && count($renderedSections) > 0)
        <section class="space-y-12">
            <h2 class="text-xl font-bold text-white font-mono flex items-center gap-2 border-b border-gray-800 pb-3" data-scroll-magic>
                <span class="text-[#00FF99]">&gt;</span>
                <span>{{ __('Detalles de Ingeniería & Características') }}</span>
            </h2>
            @foreach($renderedSections as $sectionHtml)
                <div data-scroll-magic>
                    {!! $sectionHtml !!}
                </div>
            @endforeach
        </section>
    @elseif(!empty($translation->description))
        <section class="cyber-card p-8 font-mono text-sm leading-relaxed prose prose-invert max-w-none" data-scroll-magic>
            {!! app(\DbCms\Core\Services\Media\MarkdownRendererService::class)->render((string) $translation->description) !!}
        </section>
    @endif

    <!-- Related Products -->
    @if($relatedProducts->isNotEmpty())
        <section class="space-y-6" data-scroll-magic>
            <h2 class="text-xl font-bold text-white font-mono flex items-center gap-2 border-b border-gray-800 pb-3">
                <span class="text-[#00FF99]">&gt;</span>
                <span>Soluciones Relacionadas</span>
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                @foreach($relatedProducts as $rel)
                    @php
                        $relTrans = public_translation($rel, $currentLocale, $currentMarket ?? null);
                        $shopPrefix = app(\DbCms\Core\Contracts\Services\LocalizationServiceInterface::class)->getModulePrefix('shop', $currentLocale) ?: 'shop';
                        $relUrl = app(\DbCms\Core\Contracts\Services\LocalizationServiceInterface::class)->url("/{$shopPrefix}/{$relTrans?->slug}", $currentMarket);
                    @endphp
                    <a href="{{ $relUrl }}" class="cyber-card p-4 hover:border-[#00FF99] transition-all block space-y-2 group font-mono text-xs">
                        <div class="h-32 bg-black rounded-lg overflow-hidden flex items-center justify-center">
                            @if($rel->featured_image)
                                <img src="{{ $rel->featured_image }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform" loading="lazy" decoding="async">
                            @else
                                <span class="text-3xl text-gray-600"><i class="fa-solid fa-box"></i></span>
                            @endif
                        </div>
                        <h4 class="font-bold text-white group-hover:text-[#00FF99] transition-colors truncate">{{ $relTrans?->name }}</h4>
                        @include('shop::partials.product-price', ['product' => $rel])
                    </a>
                @endforeach
            </div>
        </section>
    @endif

</div>

@endsection
