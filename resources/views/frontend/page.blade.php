@extends('frontend.layouts.main')

@section('meta_title', $translation->meta_title ?? $translation->title ?? $siteName)
@section('meta_description', $translation->meta_description ?? '')
@section('meta_keywords', $translation->meta_keywords ?? '')

@section('content')
    @if($page->home)
        <!-- Hero for homepage -->
        <section class="hero">
            <div class="container">
                <h1>{{ $translation->title ?? $page->name }}</h1>
                @if($translation->subtitle)
                    <p class="subtitle">{{ $translation->subtitle }}</p>
                @endif
            </div>
        </section>
    @else
        <!-- Breadcrumb for inner pages -->
        <div class="breadcrumb">
            <div class="container">
                <a href="{{ url($currentLang) }}">Ana Sayfa</a>
                <span>›</span>
                <span>{{ $translation->title ?? $page->name }}</span>
            </div>
        </div>
    @endif

    <main class="main">
        <div class="container">
            <div class="two-col">
                <div class="content">
                    @if(!$page->home)
                        <h1>{{ $translation->title ?? $page->name }}</h1>
                    @endif

                    @if($translation->content ?? false)
                        {!! $translation->content !!}
                    @endif

                    @if($articles->count() > 0)
                        <div class="articles-grid">
                            @foreach($articles as $article)
                                @php $articleTranslation = $article->translate($currentLang); @endphp
                                <div class="article-card">
                                    <div class="article-card-image"></div>
                                    <div class="article-card-content">
                                        <h3>
                                            <a href="{{ url($currentLang . '/' . $translation->url . '/' . ($articleTranslation->url ?? $article->name)) }}">
                                                {{ $articleTranslation->title ?? $article->name }}
                                            </a>
                                        </h3>
                                        @if($articleTranslation->subtitle ?? false)
                                            <p>{{ $articleTranslation->subtitle }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <aside class="sidebar">
                    <div class="sidebar-widget">
                        <h3>Sayfalar</h3>
                        <ul>
                            @foreach($menus->first()?->pages ?? [] as $sidebarPage)
                                @php $sidebarTranslation = $sidebarPage->translate($currentLang); @endphp
                                <li>
                                    <a href="{{ url($currentLang . '/' . ($sidebarTranslation->url ?? $sidebarPage->name)) }}">
                                        {{ $sidebarTranslation->title ?? $sidebarPage->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </main>
@endsection
