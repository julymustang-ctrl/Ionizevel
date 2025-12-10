@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <!-- Welcome Card -->
    <div class="welcome-card">
        <h2>Hoş geldin, {{ auth()->user()->firstname ?? auth()->user()->username }}! 👋</h2>
        <p>Ionizevel CMS yönetici paneline hoş geldiniz. İçeriklerinizi buradan yönetebilirsiniz.</p>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-header">
                <div>
                    <div class="stat-title">Sayfalar</div>
                    <div class="stat-value">{{ $stats['pages'] }}</div>
                </div>
                <div class="stat-icon pages">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div>
                    <div class="stat-title">Makaleler</div>
                    <div class="stat-value">{{ $stats['articles'] }}</div>
                </div>
                <div class="stat-icon articles">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div>
                    <div class="stat-title">Kullanıcılar</div>
                    <div class="stat-value">{{ $stats['users'] }}</div>
                </div>
                <div class="stat-icon users">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div>
                    <div class="stat-title">Medya</div>
                    <div class="stat-value">{{ $stats['media'] }}</div>
                </div>
                <div class="stat-icon media">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
@endsection
