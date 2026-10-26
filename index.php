<?php
include 'koneksi.php';

if (isset($_POST['tambah'])) {
    $nim     = $_POST['nim'];
    $nama    = $_POST['nama'];
    $email   = $_POST['email'];
    $jurusan = $_POST['jurusan'];
    $status  = $_POST['status'];

    $query = "INSERT INTO mahasiswa (nim, nama, email, jurusan, status) VALUES ('$nim', '$nama', '$email', '$jurusan', '$status')";
    mysqli_query($koneksi, $query);
    header("Location: index.php");
    exit;
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM mahasiswa WHERE id = '$id'");
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Data Mahasiswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 1100px;
            margin: 0 auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h2 {
            color: #0056b3;
            margin-bottom: 20px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        .grid-layout {
            display: flex;
            gap: 20px;
        }
        .form-section {
            flex: 1;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #ddd;
        }
        .table-section {
            flex: 2;
        }
        .form-group {
            margin-bottom: 12px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            font-size: 14px;
        }
        input, select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-weight: bold;
        }
        button:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-size: 14px;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .btn-hapus {
            background-color: #dc3545;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 3px;
            font-size: 12px;
        }
        .btn-hapus:hover {
            background-color: #bd2130;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 11px;
            color: white;
        }
        .badge-aktif { background-color: #28a745; }
        .badge-cuti { background-color: #ffc107; color: #333; }
        .badge-lulus { background-color: #6c757d; }
    </style>
</head>
<body>

<div class="container">
    <h2>Sistem Manajemen Data Mahasiswa</h2>

    <div class="grid-layout">
        <div class="form-section">
            <h3>Tambah Mahasiswa</h3>
            <form action="" method="POST">
                <div class="form-group">
                    <label>NIM</label>
                    <input type="text" name="nim" required placeholder="Contoh: 152024059">
                </div>
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" required placeholder="Nama mahasiswa">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required placeholder="email@domain.com">
                </div>
                <div class="form-group">
                    <label>Jurusan</label>
                    <select name="jurusan" required>
                        <option value="">Pilih Jurusan</option>
                        <option value="Teknik Informatika">Teknik Informatika</option>
                        <option value="Rekayasa Perangkat Lunak">Rekayasa Perangkat Lunak</option>
                        <option value="Sistem Informasi">Sistem Informasi</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" required>
                        <option value="Aktif">Aktif</option>
                        <option value="Cuti">Cuti</option>
                        <option value="Lulus">Lulus</option>
                    </select>
                </div>
                <button type="submit" name="tambah">Simpan Data</button>
            </form>
        </div>

        <div class="table-section">
            <h3>Daftar Mahasiswa</h3>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Jurusan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $data = mysqli_query($koneksi, "SELECT * FROM mahasiswa ORDER BY id DESC");
                    if (mysqli_num_rows($data) > 0) {
                        while ($row = mysqli_fetch_assoc($data)) {
                            // Badge warna status
                            $badgeClass = "badge-aktif";
                            if ($row['status'] == 'Cuti') $badgeClass = "badge-cuti";
                            if ($row['status'] == 'Lulus') $badgeClass = "badge-lulus";
                            
                            echo "<tr>
                                    <td>{$no}</td>
                                    <td>{$row['nim']}</td>
                                    <td>{$row['nama']}</td>
                                    <td>{$row['jurusan']}</td>
                                    <td><span class='badge {$badgeClass}'>{$row['status']}</span></td>
                                    <td>
                                        <a href='index.php?hapus={$row['id']}' class='btn-hapus' onclick='return confirm(\"Yakin ingin menghapus data ini?\")'>Hapus</a>
                                    </td>
                                  </tr>";
                            $no++;
                        }
                    } else {
                        echo "<tr><td colspan='6' style='text-align: center; color: #777;'>Belum ada data mahasiswa.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>