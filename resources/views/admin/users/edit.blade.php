@extends('layouts.admin')
@section('title', 'Kullanıcı Düzenle')
@section('content')
    <div class="page-header"><h2>{{ $user->username }}</h2></div>
    <form action="{{ route('admin.users.update', $user->id_user) }}" method="POST">
        @csrf @method('PUT')
        <div class="panel">
            <div class="panel-header">Kullanıcı Bilgileri</div>
            <div class="panel-body">
                <div class="form-group"><label class="required">Kullanıcı Adı</label><input type="text" name="username" class="form-control" value="{{ $user->username }}" required></div>
                <div class="form-group"><label class="required">E-posta</label><input type="email" name="email" class="form-control" value="{{ $user->email }}" required></div>
                <div class="form-group"><label>Ad</label><input type="text" name="firstname" class="form-control" value="{{ $user->firstname }}"></div>
                <div class="form-group"><label>Soyad</label><input type="text" name="lastname" class="form-control" value="{{ $user->lastname }}"></div>
                <div class="form-group"><label>Yeni Şifre (boş bırakılabilir)</label><input type="password" name="password" class="form-control"></div>
                <div class="form-group"><label>Şifre Tekrar</label><input type="password" name="password_confirmation" class="form-control"></div>
                <div class="form-group"><label class="required">Rol</label><select name="id_role" class="form-control" required>@foreach($roles as $role)<option value="{{ $role->id_role }}" {{ $user->id_role == $role->id_role ? 'selected' : '' }}>{{ $role->role_name }}</option>@endforeach</select></div>
            </div>
            <div class="panel-footer"><a href="{{ route('admin.users.index') }}" class="btn">İptal</a> <button type="submit" class="btn btn-success">Kaydet</button></div>
        </div>
    </form>
@endsection
