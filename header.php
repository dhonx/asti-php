<?php require_once "config.php" ?>

<header class="flex bg-blue-500 text-white pl-2 pr-5 md:items-center">
    <h3 class="brand text-3xl font-bold">ASTI</h3>
</header>

<nav class="py-3 sm:h-full sidenav text-white absolute sm:hidden xs:hidden md:hidden lg:block">
    <ul>
        <li><a href="<?php echo BASE_PATH; ?>/admin">Dashboard</a></li>
        <li><a href="<?php echo BASE_PATH; ?>/admin/manajemen_admin">Manajemen Admin</a></li>
        <li><a href="#">Manajemen Instansi</a></li>
        <li><a href="#">Manajemen Komponen</a></li>
        <li><a href="#">Manajemen Pegawai</a></li>
        <li><a href="#">Manajemen Peminjam</a></li>
        <li><a href="<?php echo BASE_PATH; ?>/logout.php">Logout</a></li>
    </ul>
</nav>
