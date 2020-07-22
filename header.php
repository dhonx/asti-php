<?php require_once "config.php" ?>
<?php require_once "utils.php" ?>

<header class="bg-blue-500 flex pl-2 pr-5 md:items-center text-white">
    <h3 class="brand font-bold ml-auto lg:ml-0 text-3xl"><a href="<?= BASE_PATH ?>">ASTI</a></h3>
    <div class="cursor-pointer lg:hidden ml-auto md:flex self-center sm:flex xs:flex" id="mobile-menu" role="menu">
        <img src="<?= BASE_PATH ?>/img/open-menu.svg" alt="">
    </div>
</header>
<nav class="sidenav bg-blue-600 lg:w-64 absolute w-screen lg:block md:hidden py-3 sm:hidden text-white xs:hidden" id="nav-menu">
    <ul>
        <li><a href="<?= BASE_PATH ?>/admin">Dashboard</a></li>
        <li><a href="<?= BASE_PATH ?>/admin/manajemen_admin">Manajemen Admin</a></li>
        <li><a href="#">Manajemen Instansi</a></li>
        <li><a href="#">Manajemen Komponen</a></li>
        <li><a href="#">Manajemen Pegawai</a></li>
        <li><a href="#">Manajemen Peminjam</a></li>
        <li><a href="<?= BASE_PATH ?>/logout.php">Logout</a></li>
    </ul>
</nav>
