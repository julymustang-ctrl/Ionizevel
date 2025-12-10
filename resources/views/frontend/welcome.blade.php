@extends('frontend.layouts.main')

@section('content')
    <section class="hero">
        <div class="container">
            <h1>Ionize<span style="color: var(--primary);">vel</span> CMS'e Hoş Geldiniz</h1>
            <p class="subtitle">Laravel tabanlı, çok dilli, modern içerik yönetim sistemi.</p>
        </div>
    </section>

    <main class="main">
        <div class="container">
            <div class="content" style="max-width: 800px; margin: 0 auto; text-align: center;">
                <h2>Henüz içerik eklenmemiş</h2>
                <p>Bu site Ionizevel CMS ile oluşturulmuştur. İçerik eklemek için admin paneline giriş yapın.</p>
                <p style="margin-top: 30px;">
                    <a href="{{ route('admin.dashboard') }}" style="display: inline-block; padding: 12px 30px; background: var(--primary); color: white; border-radius: 6px; font-weight: 600;">
                        Admin Paneli
                    </a>
                </p>
            </div>
        </div>
    </main>
@endsection
