<?php require_once "config.php" ?>
<?php require_once "utils.php" ?>
<?php
$sidenav_active = isset($_SESSION["sidenav"]) ? $_SESSION["sidenav"] : 0
?>

<header class="bg-blue-900 flex header lg:hidden w-screen lg:mb-10 pl-2 pr-5 items-center text-white">
    <h3 class="brand font-bold ml-auto lg:ml-0 text-3xl"><a href="<?= BASE_PATH ?>">ASTI</a></h3>
    <div class="cursor-pointer lg:hidden ml-auto md:flex self-center sm:flex xs:flex" id="mobile-menu" role="menu">
        <span class="mdi mdi-big mdi-menu"></span>
    </div>
</header>
<nav class="bg-gray-200 hidden lg:flex flex-col p-2 sidenav <?= $sidenav_active == 1 ? "expand" : "" ?>" id="nav-menu" role="menu">
    <div class="flex border-bottom cursor-pointer" style="padding: 20px 4px; border-bottom: 1px solid #dde4e8;">
        <img src="https://pbs.twimg.com/profile_images/1272910160609107974/VLBJhHRb_bigger.jpg" width="30px" style="border-radius: 20px;" alt="">
        <span class="font-bold lg:hidden ml-2 mr-auto label self-center"><?= explode(" ",$_SESSION["nama"])[0] ?></span>
    </div>
    <ul class="flex flex-col">
        <li>
            <a href="<?= BASE_PATH ?>/admin" role="menuitem" role="menuitem" title="Dashboard">
                <span class="mdi align-middle mdi-view-dashboard text-lg"></span>
                <span class="lg:hidden label">Dashboard</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_PATH ?>/admin/manajemen_admin" role="menuitem" title="Manajemen Admin">
                <span class="mdi align-middle mdi-account-cog text-lg"></span>
                <span class="lg:hidden label">Manajemen Admin</span>
            </a>
        </li>
        <li>
            <a href="#" role="menuitem" title="Manajemen Instansi">
                <span class="mdi align-middle mdi-office-building text-lg"></span>
                <span class="lg:hidden label">Manajemen Instansi</span>
            </a>
        </li>
        <li>
            <a href="#" role="menuitem" title="Manajemen Komponen">
                <span class="mdi align-middle mdi-package text-lg"></span>
                <span class="lg:hidden label">Manajemen Komponen</span>
            </a>
        </li>
        <li>
            <a href="#" role="menuitem" title="Manajemen Pegawai">
                <span class="mdi align-middle mdi-account-supervisor text-lg"></span>
                <span class="lg:hidden label">Manajemen Pegawai</span>
            </a>
        </li>
        <li>
            <a href="#" role="menuitem" title="Manajemen Peminjam">
                <span class="mdi align-middle mdi-account-group text-lg"></span>
                <span class="lg:hidden label">Manajemen Peminjam</span>
            </a>
        </li>
        <li class="hidden lg:flex lg:mt-auto" id="expand-button" data-url="<?= BASE_PATH ?>/admin/set_config?sidenav" onclick="toggleSidenavExpand(this)">
            <a class="w-full" href="#"  role="menuitem" title="Perluas">
                <span class="mdi align-middle mdi-chevron-double-right text-lg"></span>
                <span class="lg:hidden label">Perluas</span>
            </a>
        </li>
        <li>
            <a href="#" role="menuitem" title="Pengaturan">
                <span class="mdi align-middle mdi-cog text-lg"></span>
                <span class="lg:hidden label">Pengaturan</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_PATH ?>/logout" role="menuitem" title="Logout">
                <span class="mdi align-middle mdi-exit-to-app text-lg"></span>
                <span class="lg:hidden label">Logout</span>
            </a>
        </li>
    </ul>
</nav>
