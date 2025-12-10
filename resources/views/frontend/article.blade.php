@extends('frontend.layouts.main')

@section('meta_title', $translation->meta_title ?? $translation->title ?? $siteName)
@section('meta_description', $translation->meta_description ?? '')
@section('meta_keywords', $translation->meta_keywords ?? '')

@section('content')
    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <div class="container">
            <a href="{{ url($currentLang) }}">Ana Sayfa</a>
            <span>›</span>
            <a href="{{ url($currentLang . '/' . $pageTranslation->url) }}">{{ $pageTranslation->title ?? $page->name }}</a>
            <span>›</span>
            <span>{{ $translation->title ?? $article->name }}</span>
        </div>
    </div>

    <main class="main">
        <div class="container">
            <div class="two-col">
                <article class="content">
                    <h1>{{ $translation->title ?? $article->name }}</h1>

                    @if($translation->subtitle ?? false)
                        <p style="font-size: 18px; color: var(--text-muted); margin-bottom: 30px;">
                            {{ $translation->subtitle }}
                        </p>
                    @endif

                    <div style="font-size: 14px; color: var(--text-muted); margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid var(--border);">
                        <span>{{ $article->created_at?->format('d.m.Y') }}</span>
                        @if($article->author)
                            <span> • {{ $article->author }}</span>
                        @endif
                    </div>

                    @if($translation->content ?? false)
                        {!! $translation->content !!}
                    @endif

                    <!-- Categories -->
                    @if($article->categories->count() > 0)
                        <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid var(--border);">
                            <strong>Kategoriler:</strong>
                            @foreach($article->categories as $category)
                                @php $catTranslation = $category->translate($currentLang); @endphp
                                <span style="background: var(--bg-alt); padding: 4px 12px; border-radius: 20px; font-size: 14px; margin-left: 5px;">
                                    {{ $catTranslation->title ?? $category->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </article>

                <aside class="sidebar">
                    <div class="sidebar-widget">
                        <h3>Diğer Makaleler</h3>
                        <ul>
                            @foreach($page->articles->where('id_article', '!=', $article->id_article)->take(5) as $otherArticle)
                                @php $otherTranslation = $otherArticle->translate($currentLang); @endphp
                                <li>
                                    <a href="{{ url($currentLang . '/' . $pageTranslation->url . '/' . ($otherTranslation->url ?? $otherArticle->name)) }}">
                                        {{ $otherTranslation->title ?? $otherArticle->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="sidebar-widget">
                        <h3>Kategoriler</h3>
                        <ul>
                            @foreach($article->categories as $category)
                                @php $catTranslation = $category->translate($currentLang); @endphp
                                <li>
                                    <a href="#">{{ $catTranslation->title ?? $category->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </main>
@endsection
