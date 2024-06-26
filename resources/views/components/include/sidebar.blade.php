<div id="sidebar">
    <div class="container">
        <a href="{{ url('/') }}" class="logo-box">
            <img src="{{ asset('logo.svg') }}" alt="" class="img-fluid">
        </a>
        <div class="sidebar-list">
            <div class="sidebar-list-title">
                Menu
            </div>
            <a href="{{ route('admin.dashboard') }}"
                class="sidebar-list-link @if(Route::currentRouteName() === 'admin.dashboard') active @endif">
                <i class='bx bxs-dashboard'></i>
                <span>Dashboard</span>
            </a>
            <div class="sidebar-list-dropdown">
                <div
                    class="sidebar-list-dropdown-title  {{ ((Route::current()->getName() === 'admin.sales') || (Route::current()->getName() === 'admin.sales.add') || (Route::current()->getName() === 'admin.generate-offer')) ? 'active' : '' }}">
                    <div>
                        <i class='bx bxs-offer'></i>
                        <span>Offers</span>
                    </div>
                    <i class='bx bx-chevron-down'></i>
                </div>
                <div class="sidebar-list-dropdown-content d-none">
                    @if (Auth::user()->user_type != 'manager')
                    <a href="{{ route('admin.sales.add') }}"
                        class="sidebar-list-link {{ (Route::current()->getName() === 'admin.sales.add') ? 'active' : '' }}">
                        Create Offer
                    </a>
                    @endif
                    <a href="{{ route('admin.sales') }}"
                        class="sidebar-list-link {{ (Route::current()->getName() === 'admin.sales') ? 'active' : '' }}">
                        All Offers
                    </a>
                    @if (Auth::user()->user_type != 'manager')
                    <a href="{{ route('admin.generate-offer') }}"
                        class="sidebar-list-link {{ (Route::current()->getName() === 'admin.generate-offer') ? 'active' : '' }}">
                        Generate Offer Letter
                    </a>
                    @endif
                </div>
            </div>
            <div class="sidebar-list-dropdown">
                <div
                    class="sidebar-list-dropdown-title {{ ((Route::current()->getName() === 'admin.paid') || (Route::current()->getName() === 'admin.sales.enrolled')|| (Route::current()->getName() === 'admin.dues')) ? 'active' : '' }}">
                    <div>
                        <i class='bx bxs-purchase-tag-alt'></i>
                        <span>Sales</span>
                    </div>
                    <i class='bx bx-chevron-down'></i>
                </div>
                <div class="sidebar-list-dropdown-content d-none">
                    <a href="{{ route('admin.sales.enrolled') }}"
                        class="sidebar-list-link  {{ (Route::current()->getName() === 'admin.sales.enrolled') ? 'active' : '' }}">
                        All Sales
                    </a>
                    <a href="{{ route('admin.paid') }}"
                        class="sidebar-list-link  {{ (Route::current()->getName() === 'admin.paid') ? 'active' : '' }}">
                        Payment History
                    </a>
                    <a href="{{ route('admin.online-payment') }}"
                        class="sidebar-list-link  {{ (Route::current()->getName() === 'admin.online-payment') ? 'active' : '' }}">
                        Direct Payment
                    </a>
                    <a href="{{ route('admin.dues') }}"
                        class="sidebar-list-link  {{ (Route::current()->getName() === 'admin.dues') ? 'active' : '' }}">
                        Dues
                    </a>
                </div>
            </div>
            <div class="sidebar-list-dropdown">
                <div
                    class="sidebar-list-dropdown-title  {{ ((Route::current()->getName() === 'admin.programs') || (Route::current()->getName() === 'admin.batches') || (Route::current()->getName() === 'admin.fee-structure') || (Route::current()->getName() === 'admin.complemantries')) ? 'active' : '' }}">
                    <div>
                        <i class='bx bx-task'></i>
                        <span>Product Plan</span>
                    </div>
                    <i class='bx bx-chevron-down'></i>
                </div>
                <div class="sidebar-list-dropdown-content d-none">
                    <a href="{{ route('admin.programs') }}"
                        class="sidebar-list-link  {{ (Route::current()->getName() === 'admin.programs') ? 'active' : '' }}">
                        Programs
                    </a>
                    <a href="{{ route('admin.batches') }}"
                        class="sidebar-list-link  {{ (Route::current()->getName() === 'admin.batches') ? 'active' : '' }}">
                        Batches
                    </a>
                    <a href="{{ route('admin.fee-structure') }}"
                        class="sidebar-list-link  {{ (Route::current()->getName() === 'admin.fee-structure') ? 'active' : '' }}">
                        Fee Structure
                    </a>
                    <a href="{{ route('admin.complemantries') }}"
                        class="sidebar-list-link  {{ (Route::current()->getName() === 'admin.complemantries') ? 'active' : '' }}">
                        Complemantry Programs
                    </a>
                </div>
            </div>
            @if(Auth::user()->user_type === 'admin')
            <div class="sidebar-list-dropdown">
                <div
                    class="sidebar-list-dropdown-title  {{ ((Route::current()->getName() === 'admin.tally.courses') || (Route::current()->getName() === 'admin.tally.batches') || (Route::current()->getName() === 'admin.tally.dues') || (Route::current()->getName() === 'admin.tally.student') || (Route::current()->getName()) === 'admin.tally.receipts') ? 'active' : '' }}">
                    <div>
                        <i class='bx bx-task'></i>
                        <span>Tally</span>
                    </div>
                    <i class='bx bx-chevron-down'></i>
                </div>
                <div class="sidebar-list-dropdown-content d-none">
                    <a href="{{ route('admin.tally.batches') }}"
                        class="sidebar-list-link  {{ (Route::current()->getName() === 'admin.tally.batches') ? 'active' : '' }}">
                        Tally Batches
                    </a>
                    <a href="{{ route('admin.tally.courses') }}"
                        class="sidebar-list-link  {{ (Route::current()->getName() === 'admin.tally.courses') ? 'active' : '' }}">
                        Tally Courses
                    </a>
                    <a href="{{ route('admin.tally.dues') }}"
                        class="sidebar-list-link  {{ (Route::current()->getName() === 'admin.tally.dues') ? 'active' : '' }}">
                        Tally Dues
                    </a>
                    <a href="{{ route('admin.tally.receipts') }}"
                        class="sidebar-list-link  {{ (Route::current()->getName() === 'admin.tally.receipts') ? 'active' : '' }}">
                        Tally Receipts
                    </a>
                    <a href="{{ route('admin.tally.student') }}"
                        class="sidebar-list-link  {{ (Route::current()->getName() === 'admin.tally.student') ? 'active' : '' }}">
                        Tally Student Master
                    </a>
                </div>
            </div>
            @endif
            <div class="sidebar-list-dropdown">
                <div
                    class="sidebar-list-dropdown-title {{ (Route::current()->getName() === 'admin.profile') ? 'active' : '' }}">
                    <div>
                        <i class='bx bx-cog'></i>
                        <span>Settings</span>
                    </div>
                    <i class='bx bx-chevron-down'></i>
                </div>
                <div class="sidebar-list-dropdown-content d-none">
                    <a href="{{ route('admin.profile') }}"
                        class="sidebar-list-link {{ (Route::current()->getName() === 'admin.profile') ? 'active' : '' }}">
                        Profile
                    </a>
                </div>
            </div>

            @if(Auth::user()->user_type === 'admin')
            <div class="sidebar-list-dropdown">
                <div
                    class="sidebar-list-dropdown-title {{ (Route::current()->getName() === 'admin.users') ? 'active' : '' }}">
                    <div>
                        <i class='bx bx-user'></i>
                        <span>Users</span>
                    </div>
                    <i class='bx bx-chevron-down'></i>
                </div>
                <div class="sidebar-list-dropdown-content d-none">
                    <a href="{{ route('admin.users') }}"
                        class="sidebar-list-link {{ (Route::current()->getName() === 'admin.users') ? 'active' : '' }}">
                        Users
                    </a>
                    <a href="{{ route('admin.datastudio') }}" class="sidebar-list-link">
                        Data Studio
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
