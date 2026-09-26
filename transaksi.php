<?php

session_start();

include "koneksi.php";

header("Content-Type: application/json; charset=UTF-8");


// ======================================================
// CEK LOGIN
// ======================================================

if (!isset($_SESSION["user_id"])) {
    echo json_encode([
        "status" => false,
        "pesan" => "Anda harus login terlebih dahulu."
    ]);
    exit;
}

$user_id = (int)$_SESSION["user_id"];


// ======================================================
// CEK KONEKSI
// ======================================================

if (!$koneksi) {
    echo json_encode([
        "status" => false,
        "pesan" => "Koneksi database gagal."
    ]);
    exit;
}


// ======================================================
// POST
// ======================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $aksi = $_POST['aksi'] ?? '';


    // ==================================================
    // TAMBAH
    // ==================================================

    if ($aksi === 'tambah') {

        $tanggal = trim($_POST['tanggal'] ?? '');
        $keterangan = trim($_POST['keterangan'] ?? '');
        $jenis = trim($_POST['jenis'] ?? '');
        $nominal = $_POST['nominal'] ?? '';
        $nilai_investasi = $_POST['nilai_investasi'] ?? '';
        $kategori = trim($_POST['kategori'] ?? '');


        if (
            $tanggal === '' ||
            $keterangan === '' ||
            $jenis === '' ||
            $nominal === '' ||
            $kategori === ''
        ) {
            echo json_encode([
                "status" => false,
                "pesan" => "Data transaksi belum lengkap."
            ]);
            exit;
        }


        if (
            $jenis !== 'Pemasukan' &&
            $jenis !== 'Pengeluaran' &&
            $jenis !== 'Investasi'
        ) {
            echo json_encode([
                "status" => false,
                "pesan" => "Jenis transaksi tidak valid."
            ]);
            exit;
        }


        if (!is_numeric($nominal)) {
            echo json_encode([
                "status" => false,
                "pesan" => "Nominal tidak valid."
            ]);
            exit;
        }


        $nominal = (float)$nominal;


        // ==============================================
        // INVESTASI
        // ==============================================

        if ($jenis === 'Investasi') {

            if (
                $nilai_investasi === '' ||
                !is_numeric($nilai_investasi)
            ) {
                echo json_encode([
                    "status" => false,
                    "pesan" => "Nilai investasi saat ini wajib diisi."
                ]);
                exit;
            }


            $nilai_investasi = (float)$nilai_investasi;


            if ($nominal < 0) {
                echo json_encode([
                    "status" => false,
                    "pesan" => "Nominal investasi tidak boleh negatif."
                ]);
                exit;
            }


            $sql = "
                INSERT INTO transaksi
                (
                    user_id,
                    tanggal,
                    keterangan,
                    jenis,
                    nominal,
                    nilai_investasi,
                    kategori
                )
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ";


            $stmt = mysqli_prepare($koneksi, $sql);


            if (!$stmt) {
                echo json_encode([
                    "status" => false,
                    "pesan" => mysqli_error($koneksi)
                ]);
                exit;
            }


            mysqli_stmt_bind_param(
                $stmt,
                "isssdds",
                $user_id,
                $tanggal,
                $keterangan,
                $jenis,
                $nominal,
                $nilai_investasi,
                $kategori
            );


            if (mysqli_stmt_execute($stmt)) {

                echo json_encode([
                    "status" => true,
                    "pesan" => "Investasi berhasil disimpan."
                ]);

            } else {

                echo json_encode([
                    "status" => false,
                    "pesan" => mysqli_stmt_error($stmt)
                ]);

            }


            mysqli_stmt_close($stmt);

            exit;
        }


        // ==============================================
        // PEMASUKAN / PENGELUARAN
        // ==============================================

        if ($nominal <= 0) {
            echo json_encode([
                "status" => false,
                "pesan" => "Nominal harus lebih dari 0."
            ]);
            exit;
        }


        $sql = "
            INSERT INTO transaksi
            (
                user_id,
                tanggal,
                keterangan,
                jenis,
                nominal,
                nilai_investasi,
                kategori
            )
            VALUES (?, ?, ?, ?, ?, NULL, ?)
        ";


        $stmt = mysqli_prepare($koneksi, $sql);


        if (!$stmt) {
            echo json_encode([
                "status" => false,
                "pesan" => mysqli_error($koneksi)
            ]);
            exit;
        }


        mysqli_stmt_bind_param(
            $stmt,
            "isssds",
            $user_id,
            $tanggal,
            $keterangan,
            $jenis,
            $nominal,
            $kategori
        );


        if (mysqli_stmt_execute($stmt)) {

            echo json_encode([
                "status" => true,
                "pesan" => "Transaksi berhasil ditambahkan."
            ]);

        } else {

            echo json_encode([
                "status" => false,
                "pesan" => mysqli_stmt_error($stmt)
            ]);

        }


        mysqli_stmt_close($stmt);

        exit;
    }


    // ==================================================
    // UPDATE
    // ==================================================

    if ($aksi === 'update') {

        $id = (int)($_POST['id'] ?? 0);
        $tanggal = trim($_POST['tanggal'] ?? '');
        $keterangan = trim($_POST['keterangan'] ?? '');
        $jenis = trim($_POST['jenis'] ?? '');
        $nominal = $_POST['nominal'] ?? '';
        $nilai_investasi = $_POST['nilai_investasi'] ?? '';
        $kategori = trim($_POST['kategori'] ?? '');


        if (
            $id <= 0 ||
            $tanggal === '' ||
            $keterangan === '' ||
            $jenis === '' ||
            $nominal === '' ||
            $kategori === ''
        ) {
            echo json_encode([
                "status" => false,
                "pesan" => "Data update belum lengkap."
            ]);
            exit;
        }


        if (
            $jenis !== 'Pemasukan' &&
            $jenis !== 'Pengeluaran' &&
            $jenis !== 'Investasi'
        ) {
            echo json_encode([
                "status" => false,
                "pesan" => "Jenis transaksi tidak valid."
            ]);
            exit;
        }


        if (!is_numeric($nominal)) {
            echo json_encode([
                "status" => false,
                "pesan" => "Nominal tidak valid."
            ]);
            exit;
        }


        $nominal = (float)$nominal;


        // ==============================================
        // UPDATE INVESTASI
        // ==============================================

        if ($jenis === 'Investasi') {

            if (
                $nilai_investasi === '' ||
                !is_numeric($nilai_investasi)
            ) {
                echo json_encode([
                    "status" => false,
                    "pesan" => "Nilai investasi wajib diisi."
                ]);
                exit;
            }


            $nilai_investasi = (float)$nilai_investasi;


            if ($nominal < 0) {
                echo json_encode([
                    "status" => false,
                    "pesan" => "Nominal investasi tidak boleh negatif."
                ]);
                exit;
            }


            $sql = "
                UPDATE transaksi
                SET
                    tanggal = ?,
                    keterangan = ?,
                    jenis = ?,
                    nominal = ?,
                    nilai_investasi = ?,
                    kategori = ?
                WHERE id = ?
                AND user_id = ?
            ";


            $stmt = mysqli_prepare($koneksi, $sql);


            if (!$stmt) {
                echo json_encode([
                    "status" => false,
                    "pesan" => mysqli_error($koneksi)
                ]);
                exit;
            }


            mysqli_stmt_bind_param(
                $stmt,
                "sssddsii",
                $tanggal,
                $keterangan,
                $jenis,
                $nominal,
                $nilai_investasi,
                $kategori,
                $id,
                $user_id
            );

        }


        // ==============================================
        // UPDATE PEMASUKAN / PENGELUARAN
        // ==============================================

        else {

            if ($nominal <= 0) {
                echo json_encode([
                    "status" => false,
                    "pesan" => "Nominal harus lebih dari 0."
                ]);
                exit;
            }


            $sql = "
                UPDATE transaksi
                SET
                    tanggal = ?,
                    keterangan = ?,
                    jenis = ?,
                    nominal = ?,
                    nilai_investasi = NULL,
                    kategori = ?
                WHERE id = ?
                AND user_id = ?
            ";


            $stmt = mysqli_prepare($koneksi, $sql);


            if (!$stmt) {
                echo json_encode([
                    "status" => false,
                    "pesan" => mysqli_error($koneksi)
                ]);
                exit;
            }


            mysqli_stmt_bind_param(
                $stmt,
                "sssdsii",
                $tanggal,
                $keterangan,
                $jenis,
                $nominal,
                $kategori,
                $id,
                $user_id
            );

        }


        // Jalankan update

        if (mysqli_stmt_execute($stmt)) {

            echo json_encode([
                "status" => true,
                "pesan" => "Transaksi berhasil diperbarui."
            ]);

        } else {

            echo json_encode([
                "status" => false,
                "pesan" => mysqli_stmt_error($stmt)
            ]);

        }


        mysqli_stmt_close($stmt);

        exit;
    }


    // ==================================================
    // HAPUS
    // ==================================================

    if ($aksi === 'hapus') {

        $id = (int)($_POST['id'] ?? 0);


        if ($id <= 0) {

            echo json_encode([
                "status" => false,
                "pesan" => "ID transaksi tidak valid."
            ]);

            exit;
        }


        $sql = "
            DELETE FROM transaksi
            WHERE id = ?
            AND user_id = ?
        ";


        $stmt = mysqli_prepare($koneksi, $sql);


        if (!$stmt) {

            echo json_encode([
                "status" => false,
                "pesan" => mysqli_error($koneksi)
            ]);

            exit;
        }


        mysqli_stmt_bind_param(
            $stmt,
            "ii",
            $id,
            $user_id
        );


        if (mysqli_stmt_execute($stmt)) {

            echo json_encode([
                "status" => true,
                "pesan" => "Transaksi berhasil dihapus."
            ]);

        } else {

            echo json_encode([
                "status" => false,
                "pesan" => mysqli_stmt_error($stmt)
            ]);

        }


        mysqli_stmt_close($stmt);

        exit;
    }


    echo json_encode([
        "status" => false,
        "pesan" => "Aksi tidak dikenal."
    ]);

    exit;
}


// ======================================================
// GET : AMBIL DATA USER YANG LOGIN
// ======================================================

$sql = "
    SELECT
        id,
        tanggal,
        keterangan,
        jenis,
        nominal,
        nilai_investasi,
        kategori
    FROM transaksi
    WHERE user_id = ?
    ORDER BY tanggal DESC, id DESC
";


$stmt = mysqli_prepare($koneksi, $sql);


if (!$stmt) {

    echo json_encode([
        "status" => false,
        "pesan" => "Gagal mengambil data: " . mysqli_error($koneksi)
    ]);

    exit;
}


mysqli_stmt_bind_param(
    $stmt,
    "i",
    $user_id
);


mysqli_stmt_execute($stmt);


$result = mysqli_stmt_get_result($stmt);


$data = [];


while ($row = mysqli_fetch_assoc($result)) {

    $data[] = $row;

}


mysqli_stmt_close($stmt);


echo json_encode($data);

?>