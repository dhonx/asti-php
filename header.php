<?php require_once "config.php" ?>
<?php require_once "utils.php" ?>

<header class="bg-blue-500 flex pl-2 pr-5 md:items-center shadow text-white">
    <h3 class="brand font-bold ml-auto lg:ml-0 text-3xl"><a href="<?= BASE_PATH ?>">ASTI</a></h3>
    <div class="cursor-pointer lg:hidden ml-auto md:flex self-center sm:flex xs:flex" id="mobile-menu" role="menu">
        <img src="<?= BASE_PATH ?>/img/open-menu.svg" alt="">
    </div>
</header>
<nav class="sidenav bg-blue-600 lg:w-64 absolute w-screen lg:block md:hidden py-3 sm:hidden text-white xs:hidden" id="nav-menu">
    <ul>
        <li>
            <a href="<?= BASE_PATH ?>/admin" role="menuitem" role="menuitem">
                <span class="mdi align-middle mdi-view-dashboard text-lg"></span>
                Dashboard
            </a>
        </li>
        <li>
            <a href="<?= BASE_PATH ?>/admin/manajemen_admin" role="menuitem">
                <span class="mdi align-middle mdi-account-cog text-lg"></span>
                Manajemen Admin
            </a>
        </li>
        <li>
            <a href="#" role="menuitem">
                <span class="mdi align-middle mdi-office-building text-lg"></span>
                Manajemen Instansi
            </a>
        </li>
        <li>
            <a href="#" role="menuitem">
                <span class="mdi align-middle mdi-package text-lg"></span>
                Manajemen Komponen
            </a>
        </li>
        <li>
            <a href="#" role="menuitem">
                <span class="mdi align-middle mdi-account-supervisor text-lg"></span>
                Manajemen Pegawai
            </a>
        </li>
        <li>
            <a href="#" role="menuitem">
                <span class="mdi align-middle mdi-account-group text-lg"></span>
                Manajemen Peminjam
            </a>
        </li>
        <li>
            <a href="#" role="menuitem">
                <span class="mdi align-middle mdi-cog text-lg"></span>
                Pengaturan
            </a>
        </li>
        <li>
            <a href="<?= BASE_PATH ?>/logout" role="menuitem">
                <span class="mdi align-middle mdi-exit-to-app text-lg"></span>
                Logout
            </a>
        </li>
    </ul>
</nav>
