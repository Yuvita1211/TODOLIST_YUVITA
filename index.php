<?php
include("koneksi.php"); //memanggil file koneksi.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, 
    initial-scale=1.0">
    <!-- Bootstrap Online -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
    rel="stylesheet" 
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
    crossorigin="anonymous">   
    
    <title>My To Do List</title>
</head>
<body>
    <div class="container mt-3">
        <h3> To Do List
            <small class="text-muted">
                Catat semua hal yang akan kamu kerjakan disini.
            </small>
        </h3>
        <hr>  
<!--Form Input Data-->

<form class="form row" method="POST" action="" name="myForm" onsubmit="return(validate());">
    <!-- Kode php untuk menghubungkan form dengan database -->
    <?php
    $id = '';
    $isi = '';
    $tgl_awal = '';
    $tgl_akhir =  '';
    if (isset($_GET['Id'])) {
        $id = $_GET['Id'];
        $ambil = mysqli_query($mysqli, 
        "SELECT * FROM kegiatan 
        WHERE id=$id" . $_GET['Id'] . "'");
        while ($row = mysqli_fetch_array($ambil)) {
            $id = $row['Id'];
            $isi = $row['isi'];
            $tgl_awal = $row['tgl_awal'];
            $tgl_akhir = $row['tgl_akhir'];
        }
    ?>
        <input type="hidden" name="id" value="<?php echo
        $_GET['Id'] ?>">
    <?php
    }
    ?>
    <div class="col mb-2">
        <label for="inputIsi" class="form-label fw-bold">
            Kegiatan
        </label>
        <input type="text" class="form-control" name="isi" id="inputIsi" placeholder="Kegiatan" value="<?php echo $isi ?>">
    </div>
    <div class="col mb-2">
        <label for="inputTanggalAwal" class="form-label fw-bold">
            Tanggal Awal
        </label>
        <input type="date" class="form-control" name="tgl_awal" id="inputTanggalAwal" placeholder="Tanggal Awal" value="<?php echo $tgl_awal ?>">
    </div>
    <div class="col mb-2">
        <label for="inputTanggalAkhir" class="form-label fw-bold">
        Tanggal Akhir
        </label>
        <input type="date" class="form-control" name="tgl_akhir" id="inputTanggalAkhir" placeholder="Tanggal Akhir" value="<?php echo $tgl_akhir ?>">
    </div>
    <div class="col mb-2 d-flex">
        <button type="submit" class="btn btn-primary rounded-pill px-3 mt-auto" name="simpan">Simpan</button>
    </div>
</form>
<!-- Table-->
<table class="table table-hover">
    <!--thead atau baris judul-->
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Kegiatan</th>
            <th scope="col">Awal</th>
            <th scope="col">Akhir</th>
            <th scope="col">Status</th>
            <th scope="col">Aksi</th>
        </tr>
    </thead>
    <!--tbody berisi isi tabel sesuai dengan judul atau head-->
    <tbody>
        <!-- Kode PHP untuk menampilkan semua isi dari tabel urut
        berdasarkan status dan tanggal awal-->
        <?php
        $result = mysqli_query(
            $mysqli,"SELECT * FROM kegiatan ORDER BY status,tgl_awal"
            );
        $no = 1;
        while ($data = mysqli_fetch_array($result)) {
        ?>
            <tr>
                <th scope="row"><?php echo $no++ ?></th>
                <td><?php echo $data['isi'] ?></td>
                <td><?php echo $data['tgl_awal'] ?></td>
                <td><?php echo $data['tgl_akhir'] ?></td>
                <td>
                    <?php
                    if ($data['status'] == '1') {
    
                    ?>
                        <a class="btn btn-success rounded-pill px-3" type="button" 
                        href="index.php?id=<?php echo $data['Id'] ?>&aksi=ubah_status&status=0">
                        Sudah
                        </a>
                    <?php
                    } else {
                    ?>
                        <a class="btn btn-warning rounded-pill px-3" type="button" 
                        href="index.php?id=<?php echo $data['Id'] ?>&aksi=ubah_status&status=1">
                        Belum</a>
                    <?php
                    }
                    ?>
                </td>
                <td>
                    <a class="btn btn-info rounded-pill px-3" name = ubah
                    href="index.php?id=<?php echo $data['Id'] ?>">Ubah
                    </a>
                    <a class="btn btn-danger rounded-pill px-3" 
                    href="index.php?id=<?php echo $data['Id'] ?>&aksi=hapus">Hapus
                    </a>
                </td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>
<!--Kode PHP untuk fungsi menyimpan dan update data kedalam database-->
 <?php
if (isset($_POST['simpan'])) {
    $id = $_POST['Id'];
    $isi = $_POST['isi'];
    $tgl_awal = $_POST['tgl_awal'];
    $tgl_akhir = $_POST['tgl_akhir'];
    if (isset($_POST['Id'])) {
        $ubah = mysqli_query($mysqli, "UPDATE kegiatan SET 
                                        isi = '$isi" . $_POST['isi'] . "',
                                        tgl_awal = '$tgl_awal" . $_POST['tgl_awal'] . "',
                                        tgl_akhir = '$tgl_akhir" . $_POST['tgl_akhir'] . "'
                                        WHERE
                                        id = $id" . $_POST['Id'] . "'");
    } else {
        $tambah = mysqli_query($mysqli, "INSERT INTO kegiatan (isi,tgl_awal,tgl_akhir,status) 
                                        VALUES ( 
                                            '" . $_POST['isi'] . "',
                                            '" . $_POST['tgl_awal'] . "',
                                            '" . $_POST['tgl_akhir'] . "',
                                            '0'
                                            )");
    }

    echo "<script> 
            document.location='index.php';
            </script>";
}
//fungsi php untuk menghapus dan mengubah status data
if (isset($_GET['aksi'])) {
    $id = $_GET['id'];
    if ($_GET['aksi'] == 'hapus') {
        $hapus = mysqli_query($mysqli, "DELETE FROM kegiatan WHERE id = $id");
    } else if ($_GET['aksi'] == 'ubah_status') {
        $ubah_status = mysqli_query($mysqli, "UPDATE kegiatan SET 
                                        status = 1 WHERE
                                        id = $id");
    } else if ($_GET['aksi'] == 'ubah_data') {
        
        
    }

    echo "<script> 
            document.location='index.php';
            </script>";
}
?>
    </div>
<script>
// Fungsi untuk menampilkan data yang akan diubah dalam form saat tombol "Ubah" diklik
    $(document).ready(function(){
    $('button[name="ubah"]').click(function(){
        var id = $(this).data('Id');
        $.ajax({
            url: $ambil, //URL untuk mengambil data dari server
            type: 'GET',
            data: { id : id },
            success: function(response){
                var data = JSON.parse(response);
                $('#inputIsi').val(data.isi);
                $('#inputTanggalAwal').val(data.tgl_awal);
                $('#inputTanggalAkhir').val(data.tgl_akhir);
            }
        });
    });
});
</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>   
    <!-- cukup gunakan salah satu saja -->
</body>
</html>