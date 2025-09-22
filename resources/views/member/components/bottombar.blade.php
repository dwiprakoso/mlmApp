<!-- Bottom Navbar -->
<div class="bottom-nav">
    <a href="{{ route('member.dashboard.index') }}"
        class="nav-item {{ request()->routeIs('member.dashboard.*') ? 'active' : '' }}">
        <i class="bi bi-house-door-fill d-block fs-5"></i>
        <span>Home</span>
    </a>
    <a href="{{ route('member.invest.index') }}"
        class="nav-item {{ request()->routeIs('member.invest.*') ? 'active' : '' }}">
        <i class="bi bi-graph-up d-block fs-5"></i>
        <span>Investasi</span>
    </a>
    <a href="{{ route('member.bonus.index') }}"
        class="nav-item {{ request()->routeIs('member.bonus.*') ? 'active' : '' }}">
        <i class="bi bi-gift-fill d-block fs-5"></i>
        <span>Bonus</span>
    </a>
    <a href="{{ route('member.dompet.index') }}"
        class="nav-item {{ request()->routeIs('member.dompet.*') ? 'active' : '' }}">
        <i class="bi bi-wallet-fill d-block fs-5"></i>
        <span>Dompetku</span>
    </a>
</div>
