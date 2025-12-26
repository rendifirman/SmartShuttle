<!-- Navbar dengan background mobil.jpeg dan panel putih -->
<!-- Navbar dengan background mobil.jpeg dan panel putih -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<nav class="navbar" id="navbar">
    <!-- Brand -->
    <div class="nav-brand">
        <img src="/images/smartshuttlelogo.png" alt="Smart Shuttle" style="height: 35px; width: auto;">
    </div>

    <!-- Menu -->
    <div class="nav-menu">
       <ul class="nav-links">
    <li>
        <a href="{{ route('customer.beranda') }}"
           class="{{ request()->routeIs('customer.beranda') ? 'active' : '' }}">
            Beranda
        </a>
    </li>

    <li>
        <a href="{{ route('customer.search') }}"
           class="{{ request()->routeIs('customer.search') ? 'active' : '' }}">
            Tiket
        </a>
    </li>

    <li>
        <a href="{{ route('customer.outlet') }}"
           class="{{ request()->routeIs('customer.outlet') ? 'active' : '' }}">
            Outlet
        </a>
    </li>

    <li>
        <a href="{{ route('customer.contact') }}"
           class="{{ request()->routeIs('customer.contact') ? 'active' : '' }}">
            Kontak
        </a>
    </li>
</ul>

    </div>

    <!-- Auth -->
   <div class="nav-auth">
    @auth
        <div class="profile-wrapper">
            <button id="profile-dropdown" class="profile-btn" type="button" aria-expanded="false">
                @if(!empty(Auth::user()->avatar_url))
                    <span class="profile-avatar">
                        <img src="{{ Auth::user()->avatar_url }}" alt="avatar">
                    </span>
                @else
                    <span class="profile-avatar">
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                    </span>
                @endif

                <span class="profile-name">
                    {{ strlen(Auth::user()->name ?? '') > 12
                        ? substr(Auth::user()->name, 0, 12).'...'
                        : (Auth::user()->name ?? 'User')
                    }}
                </span>
            </button>

            <div id="dropdown-menu" class="dropdown-menu">


                <a href="{{ route('customer.dashboardprofile') }}"
                   style="display:block; padding:8px 12px; color:#00215E; text-decoration:none; border-radius:5px; margin-bottom:5px;">
                    Profil
                </a>

                <form action="{{ route('customer.logout') }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit"
                        style="display:block; width:100%; text-align:left; padding:8px 12px;
                        background:none; border:none; color:#00215E; cursor:pointer; border-radius:5px;">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    @else
        <a href="{{ route('customer.login') }}" class="btn-login">Login</a>
    @endauth
</div>

</nav>

<style>
.navbar {
    background-image: url('/images/bgHeader.jpeg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    padding: 15px 5%;
    min-height: 70px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);

    display: flex;
    justify-content: space-between;
    align-items: center;

    position: sticky;
    top: 0;
    z-index: 1000;

    transition: all 0.3s ease;
}

/* Panel putih memanjang */
.navbar::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 5%;
    right: 5%;
    height: 50px;
    background: white;
    border-radius: 25px;
    transform: translateY(-50%);
    z-index: 0;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.nav-brand,
.nav-menu,
.nav-auth {
    position: relative;
    z-index: 1;
}

.nav-brand {
    flex: 1;
    font-size: 1.5rem;
    font-weight: bold;
}

.nav-menu {
    flex: 2;
    display: flex;
    justify-content: center;
}

.nav-links {
    display: flex;
    gap: 40px;
    list-style: none;
    margin: 0;
    padding: 0;
}

.nav-links a {
    text-decoration: none;
    color: var(--primary-color);
    font-weight: 500;
    font-size: 1.1rem;
    position: relative;
    transition: color 0.3s;
}

.nav-links a:hover,
.nav-links a.active {
    color: var(--secondary-color);
}

.nav-links a::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: -5px;
    width: 0;
    height: 2px;
    background-color: var(--secondary-color);
    transition: width 0.3s;
}

.nav-links a:hover::after,
.nav-links a.active::after {
    width: 100%;
}

.nav-auth {
    flex: 1;
    display: flex;
    justify-content: flex-end;
}

.btn-login {
    background-color: var(--primary-color);
    color: white;
    border: none;
    padding: 8px 20px;
    border-radius: 20px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-login:hover {
    background-color: var(--secondary-color);
    transform: translateY(-2px);
}

.navbar.scrolled {
    padding: 10px 5%;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

/* Fallback jika gambar tidak ditemukan */
.navbar.fallback-bg {
    background: linear-gradient(135deg, #00215E 0%, #FF581E 100%);
}

/* Responsive */
@media (max-width: 768px) {
    .navbar {
        flex-direction: column;
        gap: 15px;
        padding: 15px;
        position: relative;
    }

    .navbar::before {
        display: none;
    }

    .nav-brand,
    .nav-menu,
    .nav-auth {
        width: 100%;
        justify-content: center;
    }

    .nav-links {
        flex-direction: column;
        gap: 10px;
        text-align: center;
    }
}
/* Profile icon + small name */
.profile-wrapper {
    position: relative;
    display: inline-block;
}

.profile-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    background: transparent;
    border: none;
    padding: 6px 8px;
    border-radius: 999px;
    cursor: pointer;
}

.profile-btn:focus {
    outline: none;
    box-shadow: 0 6px 18px rgba(0,0,0,0.12);
}

.profile-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    color: #fff;
    background: linear-gradient(135deg, var(--secondary-color), #ff7b4d);
    box-shadow: 0 6px 18px rgba(0,0,0,0.12);
    flex-shrink: 0;
    font-size: 16px;
    text-transform: uppercase;
}

.profile-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}

.profile-name {
    font-size: 12px;
    color: var(--primary-color);
    font-weight: 600;
    max-width: 110px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Posisi dropdown */
.profile-wrapper #dropdown-menu {
    top: calc(100% + 8px);
    right: 0;
}
/* ---------- DROPDOWN MENU ---------- */
.dropdown-menu {
    display: none;
    position: absolute;
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    padding: 10px;
    z-index: 1000;
    min-width: 170px;
    top: calc(100% + 8px);
    right: 0;
}

.dropdown-menu.show {
    display: block;
}

</style>

  <script>
document.addEventListener('DOMContentLoaded', () => {
    const navbar = document.getElementById('navbar');

    // ✅ Fallback Background
    const bgImage = new Image();
    bgImage.src = '/images/bgHeader.jpeg';
    bgImage.onerror = () => navbar.classList.add('fallback-bg');

    // ✅ Sticky Effect
    window.addEventListener('scroll', () => {
        navbar.classList.toggle('scrolled', window.scrollY > 50);
    });

    /* ✅ DROPDOWN PROFILE */
    const dropdownButton = document.getElementById('profile-dropdown');
    const dropdownMenu = document.getElementById('dropdown-menu');

    if (dropdownButton && dropdownMenu) {
        dropdownButton.addEventListener('click', function (e) {
            e.stopPropagation();
            dropdownMenu.classList.toggle('show');
        });

        document.addEventListener('click', function () {
            dropdownMenu.classList.remove('show');
        });
    }

    /* ✅ ALERT SESSION */
    const successMsg = @json(session('success'));
    const errorMsg = @json(session('error'));

    if (successMsg) alert(successMsg);
    if (errorMsg) alert(errorMsg);
});
</script>
