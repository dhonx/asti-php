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

<body class="font-sans min-h-screen bg-gray-200">
    <?php require_once "../../header.php"; ?>

    <main class="main">
        <h3 class="text-2xl font-bold p-2 page-header">Manajemen Admin</h3>

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
                        $updated_at = $row[7];
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
                    </tr>
                    <?php } ?>
                    <!-- <tr>
                        <td class="border px-4 py-2">2</td>
                        <td class="border px-4 py-2">Adam</td>
                        <td class="border px-4 py-2">Adam</td>
                        <td class="border px-4 py-2">112</td>
                    </tr>
                    <tr>
                        <td class="border px-4 py-2">3</td>
                        <td class="border px-4 py-2">Chris</td>
                        <td class="border px-4 py-2">Chris</td>
                        <td class="border px-4 py-2">1,280</td>
                    </tr> -->
                </tbody>
            </table>
        </div>
    </main>
</body>

</html>
