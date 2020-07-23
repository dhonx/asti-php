<?php require_once "config.php" ?>
<?php require_once "utils.php" ?>

<header class="bg-blue-500 flex lg:hidden w-screen lg:mb-10 pl-2 pr-5 items-center shadow text-white">
    <h3 class="brand font-bold ml-auto lg:ml-0 text-3xl"><a href="<?= BASE_PATH ?>">ASTI</a></h3>
    <div class="cursor-pointer lg:hidden ml-auto md:flex self-center sm:flex xs:flex" id="mobile-menu" role="menu">
        <span class="mdi mdi-big mdi-menu"></span>
    </div>
</header>
<nav class="bg-gray-200 hidden lg:flex p-2 sidenav" id="nav-menu" role="menu">
    <ul>
        <li>
            <a href="<?= BASE_PATH ?>/admin" role="menuitem" role="menuitem">
                <span class="mdi align-middle mdi-view-dashboard text-lg"></span>
                <span class="label">Dashboard</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_PATH ?>/admin/manajemen_admin" role="menuitem">
                <span class="mdi align-middle mdi-account-cog text-lg"></span>
                <span class="label">Manajemen Admin</span>
            </a>
        </li>
        <li>
            <a href="#" role="menuitem">
                <span class="mdi align-middle mdi-office-building text-lg"></span>
                <span class="label">Manajemen Instansi</span>
            </a>
        </li>
        <li>
            <a href="#" role="menuitem">
                <span class="mdi align-middle mdi-package text-lg"></span>
                <span class="label">Manajemen Komponen</span>
            </a>
        </li>
        <li>
            <a href="#" role="menuitem">
                <span class="mdi align-middle mdi-account-supervisor text-lg"></span>
                <span class="label">Manajemen Pegawai</span>
            </a>
        </li>
        <li>
            <a href="#" role="menuitem">
                <span class="mdi align-middle mdi-account-group text-lg"></span>
                <span class="label">Manajemen Peminjam</span>
            </a>
        </li>
        <li>
            <a href="#" role="menuitem">
                <span class="mdi align-middle mdi-cog text-lg"></span>
                <span class="label">Pengaturan</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_PATH ?>/logout" role="menuitem">
                <span class="mdi align-middle mdi-exit-to-app text-lg"></span>
                <span class="label">Logout</span>
            </a>
        </li>
    </ul>
</nav>
