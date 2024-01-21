<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
<div class="app-brand demo">
    <span class="app-brand-logo demo">
      
        <!-- <img src="{{ asset('theme_login/images/ARty.png') }}" alt="Brand Logo" class="img-fluid" style="width: 150px; height: 80px;" /> -->
        @if(Auth::user()->is_admin == 1)
        <a href="{{route('home')}}"><h3 class="text-grey" style="font-size: 24px;"><i class="menu-icon tf-icons bx bx-user" style="font-size: 24px;"></i>Supervisor</h3></a>
        @else
        <a href="{{route('sales.dashboard')}}"><span class="app-brand-text demo text-primary font-weight-bold" style="font-size: 24px;"><i class="menu-icon tf-icons bx bx-user" style="font-size: 24px;"></i> Sales</span></a>
        @endif
    </span>
    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
        <i class="bx bx-chevron-left bx-sm align-middle"></i>
    </a>
</div>


    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
      @if(Auth::user()->is_admin == 1)
      <li class="menu-header small text-uppercase">
        <span class="menu-header-text">Account</span>
      </li>
      <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons bx bx-dock-top"></i>
          <div data-i18n="Account Settings">Account Settings</div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item">
            <a href="{{ route('profile') }}" class="menu-link">
              <div data-i18n="Account">Your Account</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="{{ route('account.user')}}" class="menu-link">
              <div data-i18n="Notifications">Sales Account</div>
            </a>
            </li>
            <li class="menu-item">
            <a href="{{ route('account.admin') }}" class="menu-link">
              <div data-i18n="Notifications">Supervisor Account</div>
            </a>
            </li>
        </ul>
      </li>
      <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-user"></i>
          <div data-i18n="Authentications">Account Manage</div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item">
            <a href="{{route('account.create')}}" class="menu-link">  
              <div data-i18n="Basic">Add Account</div>
            </a>
          </li>
        </ul>
      </li>
      <li class="menu-header small text-uppercase"><span class="menu-header-text">Product</span></li>
      <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-user"></i>
          <div data-i18n="Authentications">Product</div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item">
            <a href="{{route('product')}}" class="menu-link">  
              <div data-i18n="Basic">Add Product</div>
            </a>
          </li>
        </ul>
      </li>
     
      <li class="menu-header small text-uppercase"><span class="menu-header-text">Pembelian</span></li>
      <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-user"></i>
          <div data-i18n="Authentications">Pembelian</div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item">
            <a href="{{route('purchase_order')}}" class="menu-link">  
              <div data-i18n="Basic">Pembelian Produk</div>
            </a>
          </li>
    
        </ul>
        <ul class="menu-sub">
          <li class="menu-item">
            <a href="{{route('list_order')}}" class="menu-link">  
              <div data-i18n="Basic">List Pembelian</div>
            </a>
          </li>
         
        </ul>
      </li>
      <li class="menu-header small text-uppercase"><span class="menu-header-text">Sales</span></li>
    <!-- Forms -->
    <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-cart"></i>
        <div data-i18n="Form Elements">Tugas Sales</div>
        </a>
        <ul class="menu-sub">
        <li class="menu-item">
            <a href="{{ route('task') }}" class="menu-link">
            <div data-i18n="Basic Inputs">List Tugas</div>
            </a>
        </li>
        </ul>
        <!--<ul class="menu-sub">-->
        <!--<li class="menu-item">-->
        <!--    <a href="{{ route('sales.all') }}" class="menu-link">-->
        <!--    <div data-i18n="Basic Inputs">Sales</div>-->
        <!--    </a>-->
        <!--</li>-->
        <!--</ul>-->
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Customer</span></li>
    <!-- Forms -->
    <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-cart"></i>
        <div data-i18n="Form Elements">Customer</div>
        </a>
        <ul class="menu-sub">
        <li class="menu-item">
            <a href="{{ route('customer') }}" class="menu-link">
            <div data-i18n="Basic Inputs">Customer List</div>
            </a>
        </li>
        </ul>
    </li>
    @else
    <li class="menu-header small text-uppercase"><span class="menu-header-text">Tugas</span></li>
    <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-cart"></i>
        <div data-i18n="Form Elements">Tugas</div>
        </a>
        <ul class="menu-sub">
        <li class="menu-item">
            <a href="{{ route('sales.home') }}" class="menu-link">
            <div data-i18n="Basic Inputs">List Tugas</div>
            </a>
        </li>
  
        </ul>
    </li>
    @endif
    <li class="menu-header small text-uppercase"><span class="menu-header-text">Akun</span></li>
    <!-- Forms -->
    <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-user"></i>
        <div data-i18n="Form Elements">Akun</div>
        </a>
        <ul class="menu-sub">
        @if(Auth::user()->is_admin == 0)
        <li class="menu-item">
            <a href="{{ route('sales.profile') }}" class="menu-link">
            <div data-i18n="Basic Inputs">Profile</div>
            </a>
        </li>
        @else
        <li class="menu-item">
            <a href="{{ route('profile') }}" class="menu-link">
            <div data-i18n="Basic Inputs">Profile</div>
            </a>
        </li>
        @endif
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link" onclick="submitLogoutForm()">
                <div data-i18n="Basic Inputs">Logout</div>
            </a>
        </li>
       
        </ul>
    </li>
    <form id="logoutForm" method="POST" action="{{ route('logout') }}" style="display: none;">
    @csrf
</form>
<script>
    function submitLogoutForm() {
        // Submit the logout form when the "Logout" link is clicked
        document.getElementById('logoutForm').submit();
    }
</script>

    </ul>
</aside>