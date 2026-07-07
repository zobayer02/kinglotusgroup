@extends('admin.layouts.app')

@section('title', 'Admin Dashboard | King Lotus International')

@section('content')
    <header class="admin-header">
        <div class="admin-header-copy">
            <p class="admin-kicker">King Lotus International Admin Portal</p>
            <h1 class="admin-title">Dashboard</h1>
            <p class="admin-subtitle">A simplified admin workspace aligned with your website theme.</p>
        </div>

        <div class="admin-profile" id="profile-panel">
            <div class="admin-avatar">{{ $admin->displayInitials() }}</div>
            <div class="admin-profile-copy">
                <p class="admin-profile-name">{{ $admin->displayName() }}</p>
                <p class="admin-profile-email" data-autofit-text data-max-size="16" data-min-size="10">{{ $admin->email }}</p>
                <p class="admin-profile-email">{{ $admin->name }}</p>
            </div>
        </div>
    </header>
@endsection
