@extends('layouts.admin')

@section('title', 'Kullanıcılar')

@section('content')
    <div class="page-header">
        <div class="page-header-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#36607D">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
        </div>
        <div>
            <h2>Kullanıcılar</h2>
            <div class="subtitle">{{ $users->total() }} kullanıcı</div>
        </div>
    </div>

    <div class="toolbar">
        <div class="toolbar-left"></div>
        <div class="toolbar-right">
            <a href="{{ route('admin.users.create') }}" class="btn btn-success">+ Yeni Kullanıcı</a>
        </div>
    </div>

    <div class="panel">
        <table class="table">
            <thead>
                <tr>
                    <th width="40">ID</th>
                    <th>Kullanıcı Adı</th>
                    <th>E-posta</th>
                    <th>Rol</th>
                    <th width="120">Kayıt</th>
                    <th width="150">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id_user }}</td>
                        <td><a href="{{ route('admin.users.edit', $user->id_user) }}">{{ $user->username }}</a></td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role->role_name ?? '-' }}</td>
                        <td>{{ $user->join_date?->format('d.m.Y') }}</td>
                        <td class="actions">
                            <a href="{{ route('admin.users.edit', $user->id_user) }}" class="btn">Düzenle</a>
                            @if($user->id_user !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $user->id_user) }}" method="POST" style="display: inline;" onsubmit="return confirm('Bu kullanıcıyı silmek istediğinize emin misiniz?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Sil</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $users->links() }}
@endsection
