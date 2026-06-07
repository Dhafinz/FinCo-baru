<aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-brand">
        <div class="sidebar-logo">FC</div>
        <div class="sidebar-brand-text">
            <div class="sidebar-brand-name">FIN<span>CO</span></div>
            <div class="sidebar-brand-sub">Admin Panel</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="sidebar-section-label">Menu Utama</div>
        <ul class="sidebar-menu">
            @php
                $items = [
                    ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'dashboard', 'badge' => null],
                    ['label' => 'Users', 'route' => 'admin.users.index', 'icon' => 'users', 'badge' => null],
                    ['label' => 'Transactions', 'route' => 'admin.transactions.index', 'icon' => 'transactions', 'badge' => null],
                    ['label' => 'Categories', 'route' => 'admin.categories.index', 'icon' => 'categories', 'badge' => null],
                    ['label' => 'Budgets', 'route' => 'admin.budgets.index', 'icon' => 'budgets', 'badge' => null],
                    ['label' => 'Goals', 'route' => 'admin.goals.index', 'icon' => 'goals', 'badge' => null],
                ];
            @endphp
            @foreach ($items as $item)
                <li>
                    <a class="sidebar-menu-item {{ request()->routeIs($item['route']) ? 'active' : '' }}" href="{{ route($item['route']) }}">
                        <span class="sidebar-menu-icon">
                            @switch($item['icon'])
                                @case('dashboard')
                                    <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                                    @break
                                @case('users')
                                    <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                    @break
                                @case('transactions')
                                    <svg viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                                    @break
                                @case('categories')
                                    <svg viewBox="0 0 24 24"><path d="M4 4h5l2 2h9v2H4V4z"/><path d="M4 10h16v10H4V10z"/></svg>
                                    @break
                                @case('budgets')
                                    <svg viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                                    @break
                                @case('goals')
                                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
                                    @break
                            @endswitch
                        </span>
                        <span>{{ $item['label'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="sidebar-section-label">Gamifikasi</div>
        <ul class="sidebar-menu">
            @php
                $gamificationItems = [
                    ['label' => 'Quests', 'route' => 'admin.quests.index', 'icon' => 'quests'],
                    ['label' => 'Badges', 'route' => 'admin.badges.index', 'icon' => 'badges'],
                    ['label' => 'Gamification', 'route' => 'admin.gamification.index', 'icon' => 'gamification'],
                ];
            @endphp
            @foreach ($gamificationItems as $item)
                <li>
                    <a class="sidebar-menu-item {{ request()->routeIs($item['route']) ? 'active' : '' }}" href="{{ route($item['route']) }}">
                        <span class="sidebar-menu-icon">
                            @switch($item['icon'])
                                @case('quests')
                                    <svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                    @break
                                @case('badges')
                                    <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="6"/><path d="M15.33 22H18a2 2 0 0 0 2-2v-3.33"/><path d="M8.67 22H6a2 2 0 0 1-2-2v-3.33"/><path d="M12 14v8"/></svg>
                                    @break
                                @case('gamification')
                                    <svg viewBox="0 0 24 24"><line x1="6" y1="3" x2="6" y2="15"/><circle cx="18" cy="6" r="3"/><circle cx="6" cy="18" r="3"/><path d="M18 9a9 9 0 0 1-9 9"/><path d="M9 6a9 9 0 0 0-9 9"/></svg>
                                    @break
                            @endswitch
                        </span>
                        <span>{{ $item['label'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="sidebar-section-label">Laporan</div>
        <ul class="sidebar-menu">
            <li>
                <a class="sidebar-menu-item {{ request()->routeIs('admin.reports.index') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">
                    <span class="sidebar-menu-icon">
                        <svg viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/><line x1="3" y1="20" x2="21" y2="20"/></svg>
                    </span>
                    <span>Reports</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-section-label">Lainnya</div>
        <ul class="sidebar-menu">
            <li>
                <a class="sidebar-menu-item" href="{{ route('dashboard') }}">
                    <span class="sidebar-menu-icon">
                        <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    </span>
                    <span>DashboardUser</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>
