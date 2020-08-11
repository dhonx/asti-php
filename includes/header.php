<?php
$sidenav_active = isset($_SESSION["sidenav"]) ? $_SESSION["sidenav"] : 0;
$login_as = $_SESSION["login_as"];
$nama_awal = explode(" ", $_SESSION["nama"])[0];
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
        <span class="font-bold lg:hidden ml-2 mr-auto label self-center">
            <?= $nama_awal ?>
        </span>
    </div>
    <ul class="flex flex-col mt-2">
        <li>
            <a class="<?= check_active_url("/admin/") ?>" href="<?= build_url("/admin") ?>" role="menuitem" role="menuitem" title="Dashboard">
                <span class="mdi align-middle mdi-view-dashboard text-lg"></span>
                <span class="lg:hidden label">Dashboard</span>
            </a>
        </li>
        <?php if ($login_as == "super_admin") { ?>
            <li>
                <a class="<?= check_active_url("/admin/manajemen_admin/", TRUE) ?>" href="<?= build_url("/admin/manajemen_admin") ?>" role="menuitem" title="Manajemen Admin">
                    <span class="mdi align-middle mdi-account-cog text-lg"></span>
                    <span class="lg:hidden label">Manajemen Admin</span>
                </a>
            </li>
        <?php } ?>
        <li>
            <a class="<?= check_active_url("/admin/manajemen_instansi/", TRUE) ?>" href="<?= build_url("/admin/manajemen_instansi") ?>" role="menuitem" title="Manajemen Instansi">
                <span class="mdi align-middle mdi-office-building text-lg"></span>
                <span class="lg:hidden label">Manajemen Instansi</span>
            </a>
        </li>
        <li>
            <a class="<?= check_active_url("/admin/manajemen_pegawai/", TRUE) ?>" href="<?= build_url("/admin/manajemen_pegawai") ?>" role="menuitem" title="Manajemen Pegawai">
                <span class="mdi align-middle mdi-account-supervisor text-lg"></span>
                <span class="lg:hidden label">Manajemen Pegawai</span>
            </a>
        </li>
        <li>
            <a class="<?= check_active_url("/admin/manajemen_peminjam/", TRUE) ?>" href="<?= build_url("/admin/manajemen_peminjam") ?>" role="menuitem" title="Manajemen Peminjam">
                <span class="mdi align-middle mdi-account-group text-lg"></span>
                <span class="lg:hidden label">Manajemen Peminjam</span>
            </a>
        </li>
        <li>
            <a class="<?= check_active_url("/admin/manajemen_komponen/", TRUE) ?>" href="<?= build_url("/admin/manajemen_komponen") ?>" role="menuitem" title="Manajemen Komponen">
                <span class="mdi align-middle mdi-package text-lg"></span>
                <span class="lg:hidden label">Manajemen Komponen</span>
            </a>
        </li>
        <li>
            <a class="<?= check_active_url("/admin/manajemen_barang/", TRUE) ?>" href="<?= build_url("/admin/manajemen_barang") ?>" role="menuitem" title="Manajemen Barang">
                <span class="mdi align-middle mdi-ballot-outline text-lg"></span>
                <span class="lg:hidden label">Manajemen Barang</span>
            </a>
        </li>
        <li>
            <a class="<?= check_active_url("/admin/pemesanan/", TRUE) ?>" href="<?= build_url("/admin/pemesanan") ?>" role="menuitem" title="Pemesanan Barang">
                <span class="mdi align-middle mdi-cart-arrow-down text-lg"></span>
                <span class="lg:hidden label">Pemesanan</span>
            </a>
        </li>
        <li>
            <a class="<?= check_active_url("/admin/unit/", TRUE) ?>" href="<?= build_url("/admin/unit") ?>" role="menuitem" title="Manajemen Unit">
                <span class="mdi align-middle mdi-puzzle-outline text-lg"></span>
                <span class="lg:hidden label">Manajemen Unit</span>
            </a>
        </li>
        <li>
            <a class="<?= check_active_url("/admin/ruang/", TRUE) ?>" href="<?= build_url("/admin/ruang") ?>" role="menuitem" title="Pemesanan Barang">
                <span class="mdi align-middle mdi-door text-lg"></span>
                <span class="lg:hidden label">Ruang</span>
            </a>
        </li>
        <li>
            <a class="<?= check_active_url("/admin/pemasok/", TRUE) ?>" href="<?= build_url("/admin/pemasok") ?>" role="menuitem" title="Manajemen Pemasok">
                <span class="mdi align-middle mdi-truck text-lg"></span>
                <span class="lg:hidden label">Pemasok</span>
            </a>
        </li>
        <li>
            <a class="<?= check_active_url("/admin/perolehan/", TRUE) ?>" href="<?= build_url("/admin/perolehan") ?>" role="menuitem" title="Manajemen Perolehan">
                <span class="mdi align-middle mdi-package-down text-lg"></span>
                <span class="lg:hidden label">Perolehan</span>
            </a>
        </li>
        <li class="hidden lg:flex lg:mt-auto" id="expand-button" data-url="<?= build_url("/admin/set_config?sidenav") ?>" onclick="toggleSidenavExpand(this)">
            <a class="w-full" href="#" role="menuitem" title="Perluas">
                <?php if ($sidenav_active) { ?>
                    <span class="mdi align-middle mdi-chevron-double-left text-lg"></span>
                <?php } else { ?>
                    <span class="mdi align-middle mdi-chevron-double-right text-lg"></span>
                <?php } ?>

                <span class="lg:hidden label">
                    <?= $sidenav_active == 1 ? "Perkecil" : "Perbesar" ?>
                </span>
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
