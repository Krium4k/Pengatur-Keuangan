<?php

session_start();

header('Content-Type: application/json');


// ======================================================
// CEK LOGIN
// ======================================================

if (!isset($_SESSION["user_id"])) {

    echo json_encode([
        "status" => false,
        "pesan" => "User belum login."
    ]);

    exit;

}


$user_id = (int) $_SESSION["user_id"];


// ======================================================
// KONEKSI DATABASE
// ======================================================

require_once "koneksi.php";


// ======================================================
// AMBIL TAHUN DAN BULAN
// ======================================================

$tahun = isset($_REQUEST["tahun"])
    ? (int) $_REQUEST["tahun"]
    : (int) date("Y");

$bulan = isset($_REQUEST["bulan"])
    ? (int) $_REQUEST["bulan"]
    : (int) date("n");


// Validasi bulan dan tahun

if ($bulan < 1 || $bulan > 12) {

    echo json_encode([
        "status" => false,
        "pesan" => "Bulan tidak valid."
    ]);

    exit;

}


// ======================================================
// AMBIL BUDGET
// ======================================================

if ($_SERVER["REQUEST_METHOD"] === "GET") {

    $stmt = mysqli_prepare(
        $koneksi,
        "SELECT id, bulan, tahun, nominal
         FROM budget
         WHERE user_id = ?
         AND bulan = ?
         AND tahun = ?
         LIMIT 1"
    );


    mysqli_stmt_bind_param(
        $stmt,
        "iii",
        $user_id,
        $bulan,
        $tahun
    );


    mysqli_stmt_execute($stmt);


    $result =
        mysqli_stmt_get_result($stmt);


    $data =
        mysqli_fetch_assoc($result);


    mysqli_stmt_close($stmt);


    if ($data) {

        echo json_encode([
            "status" => true,
            "data" => [
                "id" => (int) $data["id"],
                "bulan" => (int) $data["bulan"],
                "tahun" => (int) $data["tahun"],
                "nominal" => (float) $data["nominal"]
            ]
        ]);

    }

    else {

        echo json_encode([
            "status" => true,
            "data" => null
        ]);

    }

    exit;

}


// ======================================================
// SIMPAN / UPDATE BUDGET
// ======================================================

if (
    $_SERVER["REQUEST_METHOD"] === "POST" &&
    ($_POST["aksi"] ?? "") === "simpan"
) {

    $nominal =
        isset($_POST["nominal"])
            ? (float) $_POST["nominal"]
            : 0;


    if ($nominal <= 0) {

        echo json_encode([
            "status" => false,
            "pesan" => "Budget harus lebih dari Rp0."
        ]);

        exit;

    }


    // Cek apakah budget bulan tersebut sudah ada

    $stmt = mysqli_prepare(
        $koneksi,
        "SELECT id
         FROM budget
         WHERE user_id = ?
         AND bulan = ?
         AND tahun = ?
         LIMIT 1"
    );


    mysqli_stmt_bind_param(
        $stmt,
        "iii",
        $user_id,
        $bulan,
        $tahun
    );


    mysqli_stmt_execute($stmt);


    $result =
        mysqli_stmt_get_result($stmt);


    $existing =
        mysqli_fetch_assoc($result);


    mysqli_stmt_close($stmt);


    // ==================================================
    // UPDATE BUDGET YANG SUDAH ADA
    // ==================================================

    if ($existing) {

        $id =
            (int) $existing["id"];


        $stmt = mysqli_prepare(
            $koneksi,
            "UPDATE budget
             SET nominal = ?
             WHERE id = ?
             AND user_id = ?"
        );


        mysqli_stmt_bind_param(
            $stmt,
            "dii",
            $nominal,
            $id,
            $user_id
        );


        $berhasil =
            mysqli_stmt_execute($stmt);


        mysqli_stmt_close($stmt);


        if (!$berhasil) {

            echo json_encode([
                "status" => false,
                "pesan" => "Gagal memperbarui budget."
            ]);

            exit;

        }

    }

    // ==================================================
    // INSERT BUDGET BARU
    // ==================================================

    else {

        $stmt = mysqli_prepare(
            $koneksi,
            "INSERT INTO budget
                (user_id, bulan, tahun, nominal)
             VALUES (?, ?, ?, ?)"
        );


        mysqli_stmt_bind_param(
            $stmt,
            "iiid",
            $user_id,
            $bulan,
            $tahun,
            $nominal
        );


        $berhasil =
            mysqli_stmt_execute($stmt);


        mysqli_stmt_close($stmt);


        if (!$berhasil) {

            echo json_encode([
                "status" => false,
                "pesan" => "Gagal menyimpan budget."
            ]);

            exit;

        }

    }


    echo json_encode([
        "status" => true,
        "pesan" => "Budget bulan ini berhasil disimpan."
    ]);

    exit;

}


// ======================================================
// AKSI TIDAK DIKENAL
// ======================================================

echo json_encode([
    "status" => false,
    "pesan" => "Aksi tidak valid."
]);

?>