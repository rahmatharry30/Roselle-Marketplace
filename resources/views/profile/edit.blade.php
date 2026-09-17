@extends('layout')

@section('title', 'Profil Saya')

@section('content')
    <h1 class="rose-page-title">Profil Saya</h1>

    <div class="rose-panel" style="max-width:550px;">
        <h2 class="rose-panel-title">Informasi Akun</h2>
        <p style="color:#a78b7a; font-size:13px; margin-top:-10px; margin-bottom:20px;">
            Perbarui nama dan email akun Anda.
        </p>

        @if (session('status') === 'profile-updated')
            <p style="color:#15803d; font-size:13px; margin-bottom:15px;">Profil berhasil diperbarui.</p>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="rose-form">
            @csrf
            @method('PATCH')

            @if ($user->avatar)
                <img src="{{ $user->avatar_url }}" alt="Avatar" style="width:90px; height:90px; border-radius:50%; object-fit:cover; margin-bottom:15px; display:block;">
            @endif

            <label>Foto Profil</label>
            <input type="file" name="avatar" accept="image/*">


            <label>Nama</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
            @error('name')
                <div class="rose-form-error">{{ $message }}</div>
            @enderror

            <label>Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
            @error('email')
                <div class="rose-form-error">{{ $message }}</div>
            @enderror

            <button type="submit" class="rose-btn-pink">Simpan Perubahan</button>
        </form>
    </div>

    <div class="rose-panel" style="max-width:550px;">
        <h2 class="rose-panel-title">Ubah Password</h2>
        <p style="color:#a78b7a; font-size:13px; margin-top:-10px; margin-bottom:20px;">
            Gunakan password yang panjang dan unik untuk keamanan akun.
        </p>

        @if (session('status') === 'password-updated')
            <p style="color:#15803d; font-size:13px; margin-bottom:15px;">Password berhasil diperbarui.</p>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="rose-form">
            @csrf
            @method('PUT')

            <label>Password Saat Ini</label>
            <input type="password" name="current_password">
            @error('current_password', 'updatePassword')
                <div class="rose-form-error">{{ $message }}</div>
            @enderror

            <label>Password Baru</label>
            <input type="password" name="password">
            @error('password', 'updatePassword')
                <div class="rose-form-error">{{ $message }}</div>
            @enderror

            <label>Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation">

            <button type="submit" class="rose-btn-pink">Ubah Password</button>
        </form>
    </div>

    <div class="rose-panel" style="max-width:550px; border:1px solid #fecaca;">
        <h2 class="rose-panel-title" style="color:#dc2626;">Hapus Akun</h2>
        <p style="color:#a78b7a; font-size:13px; margin-top:-10px; margin-bottom:20px;">
            Setelah akun dihapus, semua data terkait akan hilang permanen.
        </p>

        <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Yakin ingin menghapus akun? Tindakan ini tidak bisa dibatalkan.');" class="rose-form">
            @csrf
            @method('DELETE')

            <label>Masukkan password untuk konfirmasi</label>
            <input type="password" name="password">
            @error('password', 'userDeletion')
                <div class="rose-form-error">{{ $message }}</div>
            @enderror

            <button type="submit" class="rose-btn-outline" style="border-color:#dc2626; color:#dc2626;">Hapus Akun Saya</button>
        </form>
    </div>
@endsection
