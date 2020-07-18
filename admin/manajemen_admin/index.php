<?php
include "../../utils.php";
include_once "../../config.php";
include_once "../../connection/connection.php";

authenticate();

$result = $connection->query("SELECT * FROM admin WHERE tipe_admin != 'super admin'");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
    <link href="<?php echo BASE_PATH; ?>/css/main.css" rel="stylesheet">
    <title>Manajemen Admin - ASTI</title>
</head>

<body class="font-sans min-h-screen bg-gray-200 overflow-hidden">
    <?php require_once "../../header.php"; ?>

    <main class="main">
        <h3 class="text-2xl font-bold py-2 page-header">Manajemen Admin</h3>

        <div class="flex my-5 justify">
            <button class="py-2 px-3 bg-blue-500 text-white rounded-sm">Tambah Admin</button>
        </div>

        <div class="my-5">
            <table class="table-auto bg-white">
                <thead>
                    <tr>
                        <th class="border px-4 py-2">ID</th>
                        <th class="border px-4 py-2">Nama</th>
                        <th class="border px-4 py-2">Email</th>
                        <th class="border px-4 py-2">Telp</th>
                        <th class="border px-4 py-2">Aktif</th>
                        <th class="border px-4 py-2">Tipe</th>
                        <th class="border px-4 py-2">Tgl Tambah</th>
                        <th class="border px-4 py-2">Tgl Update</th>
                        <th class="border px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_row()) {
                        $id_admin   = $row[0];
                        $nama       = $row[1];
                        $email      = $row[2];
                        $no_telp    = $row[3];
                        $aktif      = $row[5];
                        $tipe_admin = $row[6];
                        $created_at = $row[7];
                        $updated_at = $row[8];
                    ?>
                        <tr>
                            <td class="border px-4 py-2"><?php echo $id_admin; ?></td>
                            <td class="border px-4 py-2"><?php echo $nama; ?></td>
                            <td class="border px-4 py-2"><?php echo $email; ?></td>
                            <td class="border px-4 py-2">
                                <a class="text-blue-600 underline" href="telp:<?php echo $no_telp; ?>"><?php echo $no_telp; ?></a>
                            </td>
                            <td class="border px-4 py-2"><?php echo $aktif == 1 ? 'aktif' : 'tidak aktif'; ?></td>
                            <td class="border px-4 py-2"><?php echo $tipe_admin; ?></td>
                            <td class="border px-4 py-2"><?php echo $created_at; ?></td>
                            <td class="border px-4 py-2"><?php echo $updated_at; ?></td>
                            <td class="border px-4 py-2">
                                <button class="p-2 m-1 bg-blue-500 text-white rounded-sm">Edit</button>
                                <button class="p-2 m-1 bg-red-500 text-white rounded-sm">Hapus</button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </main>
</body>

</html>
