<div class="card shadow-sm mb-4">
    <div class="card-body">
        <ul class="nav nav-pills nav-fill">
            @php
                $user = Auth::user();
                $hasActiveSubscription = $user->activeSubscription() !== null;
            @endphp

            @if($hasActiveSubscription)
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('company.dashboard') ? 'active' : '' }}"
                        href="{{ route('company.dashboard') }}">
                        <i class="fas fa-tachometer-alt me-2"></i> 控制面板
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('company.products.*') ? 'active' : '' }}"
                        href="{{ route('company.products.index') }}">
                        <i class="fas fa-box me-2"></i> 产品
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('company.orders.*') ? 'active' : '' }}"
                        href="{{ route('company.orders.index') }}">
                        <i class="fas fa-shopping-cart me-2"></i> 订单
                    </a>
                </li>
            @endif

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('company.subscription.*') ? 'active' : '' }}"
                    href="{{ route('company.subscription.index') }}">
                    <i class="fas fa-credit-card me-2"></i> 订阅
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('company.profile') ? 'active' : '' }}"
                    href="{{ route('company.profile') }}">
                    <i class="fas fa-user me-2"></i> 资料
                </a>
            </li>
            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link text-danger border-0 bg-transparent w-100">
                        <i class="fas fa-sign-out-alt me-2"></i> 退出
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>