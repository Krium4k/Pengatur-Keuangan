<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FinTrack - Dashboard Keuangan</title>

    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

<nav class="navbar">
    <div class="navbar-container">
        <h2>FinTrack</h2>

        <div class="nav-menu">
            <a href="#dashboard">Dashboard</a>
            <a href="#transaksi">Transaksi</a>
            <a href="#laporan">Laporan</a>

            <a href="logout.php" class="btn-logout">Logout</a>
        </div>
    </div>
</nav>


<main class="container" id="dashboard">

    <!-- =========================
         WELCOME
    ========================== -->
    <section class="welcome">
        <div>
            <p class="welcome-label">FINTRACK</p>

            <h1>Dashboard Keuangan</h1>

            <p>
                Pantau pemasukan, pengeluaran, investasi,
                dan kondisi keuangan kamu dengan lebih mudah.
            </p>
        </div>
    </section>


    <!-- =========================
         SUMMARY
    ========================== -->
    <section class="summary">

        <div class="card saldo-card">
            <div class="card-icon">💰</div>

            <div class="card-content">
                <span>Saldo Cash</span>
                <h2 id="totalSaldo">Rp 0</h2>
            </div>
        </div>


        <div class="card pemasukan-card">
            <div class="card-icon">↗</div>

            <div class="card-content">
                <span>Total Pemasukan</span>
                <h2 id="totalPemasukan">Rp 0</h2>
            </div>
        </div>


        <div class="card pengeluaran-card">
            <div class="card-icon">↘</div>

            <div class="card-content">
                <span>Total Pengeluaran</span>
                <h2 id="totalPengeluaran">Rp 0</h2>
            </div>
        </div>


        <div class="card">
            <div class="card-icon">📈</div>

            <div class="card-content">
                <span>Nilai Investasi</span>
                <h2 id="totalInvestasi">Rp 0</h2>
            </div>
        </div>


        <div class="card">
            <div class="card-icon">📊</div>

            <div class="card-content">
                <span>Keuntungan Investasi</span>
                <h2 id="keuntunganInvestasi">Rp 0</h2>
            </div>
        </div>


        <div class="card">
            <div class="card-icon">%</div>

            <div class="card-content">
                <span>Return Investasi</span>
                <h2 id="returnInvestasi">0%</h2>
            </div>
        </div>


        <div class="card">
            <div class="card-icon">💎</div>

            <div class="card-content">
                <span>Total Kekayaan</span>
                <h2 id="totalKekayaan">Rp 0</h2>
            </div>
        </div>

    </section>

    <section class="transactions" style="margin-top:20px;">
    <div class="transaction-header">
        <div>
            <span class="section-label">PROGRESS</span>
            <h2>Progress Budget</h2>
            <p id="teksProgressBudget">Belum ada budget yang diatur.</p>
        </div>
    </div>

    <div style="
        width:100%;
        height:24px;
        background:#e5e7eb;
        border-radius:999px;
        overflow:hidden;
        margin-top:15px;
    ">
        <div
            id="progressBudget"
            style="
                width:0%;
                height:100%;
                border-radius:999px;
                transition:width 0.4s ease;
            "
        ></div>
    </div>
</section>

    <!-- =========================
         BUDGET BULANAN
    ========================== -->
    <section class="summary" style="margin-top:30px;">

        <div class="card">
            <div class="card-icon">🎯</div>

            <div class="card-content">
                <span>Budget Bulan Ini</span>
                <h2 id="budgetBulanan">Belum diatur</h2>
            </div>
        </div>


        <div class="card">
            <div class="card-icon">💸</div>

            <div class="card-content">
                <span>Pengeluaran Bulan Ini</span>
                <h2 id="pengeluaranBulanIni">Rp 0</h2>
            </div>
        </div>


        <div class="card">
            <div class="card-icon">💵</div>

            <div class="card-content">
                <span>Sisa Budget</span>
                <h2 id="sisaBudget">Belum diatur</h2>
            </div>
        </div>
 
        <div class="card">
            <div class="card-icon">🚦</div>
            <div class="card-content">
                <span>Status Budget</span>
                <h2 id="statusBudget">Belum diatur</h2>
            </div>
        </div>

        <div class="card">
            <div class="card-icon">📊</div>
            <div class="card-content">
                <span>Budget Terpakai</span>
                <h2 id="persentaseBudget">0%</h2>
            </div>
        </div>

    </section>


    <section class="transactions" style="margin-top:30px;">

        <div class="transaction-header">

            <div>
                <span class="section-label">
                    BUDGET
                </span>

                <h2>
                    Budget Bulanan
                </h2>

                <p>
                    Atur batas pengeluaran untuk bulan berjalan.
                </p>
            </div>

        </div>


        <div class="form-grid">

            <div class="form-group">

                <label for="inputBudget">
                    Budget Bulan Ini
                </label>

                <input
                    type="number"
                    id="inputBudget"
                    placeholder="Contoh: 3000000"
                    min="0"
                    step="1000"
                >

            </div>

        </div>


        <div class="form-actions">

            <button
                type="button"
                class="btn-primary"
                onclick="simpanBudget()"
            >
                Simpan Budget
            </button>

        </div>

    </section>

    <!-- =========================
         GRAFIK KEUANGAN
    ========================== -->
    <section class="chart-section">

        <div class="chart-header">
            <div>

                <span class="section-label">
                    ANALISIS
                </span>

                <h2>
                    Ringkasan Keuangan
                </h2>

                <p>
                    Perbandingan pemasukan,
                    pengeluaran, dan investasi.
                </p>

            </div>
        </div>


        <div class="chart-container">
            <canvas id="grafikKeuangan"></canvas>
        </div>

    </section>



    <!-- =========================
         TRANSAKSI
    ========================== -->
    <section class="transactions" id="transaksi">

        <div class="transaction-header">

            <div>

                <span class="section-label">
                    KEUANGAN
                </span>

                <h2>
                    Daftar Transaksi
                </h2>

            </div>


            <button
                type="button"
                class="btn-primary"
                onclick="tampilkanForm()"
            >
                + Tambah Transaksi
            </button>

        </div>



        <!-- FORM -->
        <div
            class="form-transaksi"
            id="formTransaksi"
            style="display:none;"
        >

            <div class="form-header">

                <div>

                    <h3 id="judulForm">
                        Tambah Transaksi
                    </h3>

                    <p>
                        Masukkan data pemasukan,
                        pengeluaran, atau investasi.
                    </p>

                </div>

            </div>



            <div class="form-grid">

                <!-- TANGGAL -->
                <div class="form-group">

                    <label for="tanggal">
                        Tanggal
                    </label>

                    <input
                        type="date"
                        id="tanggal"
                    >

                </div>



                <!-- KETERANGAN -->
                <div class="form-group">

                    <label for="keterangan">
                        Keterangan
                    </label>

                    <input
                        type="text"
                        id="keterangan"
                        placeholder="Contoh: Gaji bulanan"
                    >

                </div>



                <!-- JENIS -->
                <div class="form-group">

                    <label for="jenis">
                        Jenis Transaksi
                    </label>

                    <select
                        id="jenis"
                        onchange="ubahJenisTransaksi()"
                    >

                        <option value="Pemasukan">
                            Pemasukan
                        </option>

                        <option value="Pengeluaran">
                            Pengeluaran
                        </option>

                        <option value="Investasi">
                            Investasi
                        </option>

                    </select>

                </div>



                <!-- NOMINAL -->
                <div class="form-group">

                    <label for="nominal">
                        Nominal
                    </label>

                    <input
                        type="number"
                        id="nominal"
                        placeholder="Contoh: 1000000"
                        min="0"
                        step="0.01"
                    >

                    <small
                        id="keteranganNominalInvestasi"
                        style="display:none;"
                    >
                        Isi Rp0 jika hanya memperbarui
                        nilai investasi tanpa setor uang baru.
                    </small>

                </div>



                <!-- NILAI INVESTASI -->
                <div
                    class="form-group"
                    id="inputNilaiInvestasi"
                    style="display:none;"
                >

                    <label for="nilai_investasi">
                        Nilai Investasi Saat Ini
                    </label>

                    <input
                        type="number"
                        id="nilai_investasi"
                        placeholder="Contoh: 520000"
                        min="0"
                        step="0.01"
                    >

                </div>



                <!-- KATEGORI -->
                <div class="form-group">

                    <label for="kategori">
                        Kategori
                    </label>

                    <select id="kategori">

                        <option value="Gaji">
                            Gaji
                        </option>

                        <option value="Makanan">
                            Makanan
                        </option>

                        <option value="Transportasi">
                            Transportasi
                        </option>

                        <option value="Belanja">
                            Belanja
                        </option>

                        <option value="Tagihan">
                            Tagihan
                        </option>

                        <option value="Hiburan">
                            Hiburan
                        </option>

                        <option value="Investasi">
                            Investasi
                        </option>

                        <option value="Lainnya">
                            Lainnya
                        </option>

                    </select>

                </div>

            </div>



            <div class="form-actions">

                <button
                    type="button"
                    class="btn-secondary"
                    onclick="batalForm()"
                >
                    Batal
                </button>


                <button
                    type="button"
                    class="btn-primary"
                    onclick="simpanTransaksi()"
                >
                    Simpan Transaksi
                </button>

            </div>

        </div>



        <!-- FILTER -->
        <div class="filter-container">

            <div class="search-box">

                <input
                    type="text"
                    id="searchTransaksi"
                    placeholder="Cari transaksi..."
                    oninput="filterTransaksi()"
                >

            </div>


            <select
                id="filterJenis"
                onchange="filterTransaksi()"
            >

                <option value="">
                    Semua Jenis
                </option>

                <option value="Pemasukan">
                    Pemasukan
                </option>

                <option value="Pengeluaran">
                    Pengeluaran
                </option>

                <option value="Investasi">
                    Investasi
                </option>

            </select>


            <select
                id="filterKategori"
                onchange="filterTransaksi()"
            >

                <option value="">
                    Semua Kategori
                </option>

                <option value="Gaji">
                    Gaji
                </option>

                <option value="Makanan">
                    Makanan
                </option>

                <option value="Transportasi">
                    Transportasi
                </option>

                <option value="Belanja">
                    Belanja
                </option>

                <option value="Tagihan">
                    Tagihan
                </option>

                <option value="Hiburan">
                    Hiburan
                </option>

                <option value="Investasi">
                    Investasi
                </option>

                <option value="Lainnya">
                    Lainnya
                </option>

            </select>


            <button
                type="button"
                class="btn-reset"
                onclick="resetFilter()"
            >
                Reset
            </button>

        </div>



        <!-- TABEL TRANSAKSI -->
        <div class="table-wrapper">

            <table>

                <thead>

                    <tr>

                        <th>
                            Tanggal
                        </th>

                        <th>
                            Keterangan
                        </th>

                        <th>
                            Jenis
                        </th>

                        <th>
                            Nominal
                        </th>

                        <th>
                            Nilai Investasi
                        </th>

                        <th>
                            Kategori
                        </th>

                        <th>
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody id="tabelTransaksi">

                    <tr>
                        <td
                            colspan="7"
                            class="empty-data"
                        >
                            Memuat transaksi...
                        </td>
                    </tr>

                </tbody>

            </table>

        </div>

    </section>



    <!-- =========================
         LAPORAN
    ========================== -->
    <section
        class="transactions"
        id="laporan"
        style="margin-top:30px;"
    >

        <div class="transaction-header">

            <div>

                <span class="section-label">
                    LAPORAN
                </span>

                <h2>
                    Laporan Keuangan Bulanan
                </h2>

                <p>
                    Lihat perkembangan uang dan
                    alokasi keuangan setiap bulan.
                </p>

            </div>


            <select
                id="filterBulanLaporan"
                onchange="renderLaporan()"
            >

                <option value="">
                    Semua Bulan
                </option>

            </select>

        </div>



        <!-- TABEL LAPORAN -->
        <div class="table-wrapper">

            <table>

                <thead>

                    <tr>

                        <th>
                            Bulan
                        </th>

                        <th>
                            Pemasukan
                        </th>

                        <th>
                            Pengeluaran
                        </th>

                        <th>
                            Investasi Baru
                        </th>

                        <th>
                            Saldo Cash
                        </th>

                        <th>
                            Nilai Investasi
                        </th>

                        <th>
                            Total Kekayaan
                        </th>

                    </tr>

                </thead>


                <tbody id="tabelLaporan">

                    <tr>

                        <td
                            colspan="7"
                            class="empty-data"
                        >
                            Belum ada laporan.
                        </td>

                    </tr>

                </tbody>

            </table>

        </div>



        <!-- =========================
             GRAFIK INVESTASI
        ========================== -->
        <div style="margin-top:30px;">

            <span class="section-label">
                PERKEMBANGAN INVESTASI
            </span>

            <h3>
                Nilai Investasi per Bulan
            </h3>

            <p>
                Nilai investasi diambil dari
                pembaruan manual terakhir pada setiap bulan.
            </p>


            <div
                class="chart-container"
                style="margin-top:20px;"
            >

                <canvas
                    id="grafikInvestasiBulanan"
                ></canvas>

            </div>

        </div>



        <!-- =========================
             GRAFIK PENGELUARAN
        ========================== -->
        <div style="margin-top:30px;">

            <span class="section-label">
                GRAFIK PENGELUARAN
            </span>

            <h3>
                Pengeluaran Berdasarkan Kategori
            </h3>

            <p>
                Grafik mengikuti bulan yang
                dipilih pada laporan.
            </p>


            <div
                class="chart-container"
                style="max-width:600px;margin:20px auto 0;"
            >

                <canvas
                    id="grafikPengeluaran"
                ></canvas>

            </div>

        </div>



        <!-- =========================
             RINCIAN PENGELUARAN
        ========================== -->
        <div style="margin-top:30px;">

            <span class="section-label">
                RINCIAN PENGELUARAN
            </span>

            <h3>
                Pengeluaran Berdasarkan Kategori
            </h3>

            <p>
                Lihat ke mana uang kamu digunakan.
            </p>


            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>

                            <th>
                                Kategori
                            </th>

                            <th>
                                Jumlah
                            </th>

                            <th>
                                Persentase
                            </th>

                        </tr>

                    </thead>


                    <tbody id="tabelKategori">

                        <tr>

                            <td
                                colspan="3"
                                class="empty-data"
                            >
                                Belum ada data.
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </section>

</main>



<script>

// ======================================================
// VARIABEL GLOBAL
// ======================================================

let transaksi = [];

let editId = null;

let grafikKeuangan = null;

let grafikPengeluaran = null;

let grafikInvestasiBulanan = null;

let budgetBulanIni = null;


// ======================================================
// FORMAT RUPIAH
// ======================================================

function formatRupiah(angka) {

    return new Intl.NumberFormat(
        'id-ID',
        {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }
    ).format(
        Number(angka) || 0
    );

}


// ======================================================
// TANGGAL HARI INI
// ======================================================

function tanggalHariIni() {

    const d = new Date();

    return `${d.getFullYear()}-${String(
        d.getMonth() + 1
    ).padStart(2, '0')}-${String(
        d.getDate()
    ).padStart(2, '0')}`;

}


// ======================================================
// FORMAT BULAN
// ======================================================

function formatBulan(bulan) {

    const bagian = String(bulan).split('-');

    const tahun = bagian[0];

    const nomor = bagian[1];

    const nama = [
        'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    ];

    return `${nama[
        Number(nomor) - 1
    ] || ''} ${tahun}`;

}


// ======================================================
// ESCAPE HTML
// ======================================================

function escapeHtml(value) {

    return String(value ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');

}


// ======================================================
// AMBIL DATA TRANSAKSI
// ======================================================

async function ambilTransaksi() {

    try {

        const response = await fetch(
            'transaksi.php',
            {
                cache: 'no-store'
            }
        );


        if (!response.ok) {

            throw new Error(
                `HTTP ${response.status}`
            );

        }


        const data =
            await response.json();


        if (!Array.isArray(data)) {

            throw new Error(
                'Format data tidak valid.'
            );

        }


        transaksi = data;


        updateDashboard();

        renderTransaksi();

        updateGrafik();

        isiFilterLaporan();

        renderLaporan();

        await ambilBudgetBulanIni();


    } catch (error) {

        console.error(error);

        document.getElementById(
            'tabelTransaksi'
        ).innerHTML = `
            <tr>
                <td
                    colspan="7"
                    style="text-align:center;"
                >
                    Gagal mengambil data transaksi.
                </td>
            </tr>
        `;

    }

}


// ======================================================
// HITUNG KEUANGAN
// ======================================================

function hitungKeuangan() {

    let totalPemasukan = 0;

    let totalPengeluaran = 0;

    let totalInvestasi = 0;


    transaksi.forEach(item => {

        const nominal =
            Number(item.nominal) || 0;


        if (
            item.jenis === 'Pemasukan'
        ) {

            totalPemasukan += nominal;

        }


        else if (
            item.jenis === 'Pengeluaran'
        ) {

            totalPengeluaran += nominal;

        }


        else if (
            item.jenis === 'Investasi'
        ) {

            totalInvestasi += nominal;

        }

    });



    // Ambil nilai investasi
    // terbaru berdasarkan tanggal + id
    const dataInvestasi =
        transaksi
            .filter(item =>
                item.jenis === 'Investasi' &&
                item.nilai_investasi !== null &&
                item.nilai_investasi !== ''
            )
            .sort((a, b) => {

                if (
                    a.tanggal !== b.tanggal
                ) {

                    return b.tanggal.localeCompare(
                        a.tanggal
                    );

                }

                return Number(b.id) -
                    Number(a.id);

            });



    const nilaiInvestasiSekarang =
        dataInvestasi.length > 0
            ? Number(
                dataInvestasi[0].nilai_investasi
              ) || 0
            : 0;



    // Total uang yang sudah dialokasikan
    // ke investasi
    const modalInvestasi =
        totalInvestasi;



    // Keuntungan investasi
    const keuntunganInvestasi =
        nilaiInvestasiSekarang -
        modalInvestasi;



    // Return investasi
    const returnInvestasi =
        modalInvestasi > 0
            ? (
                keuntunganInvestasi /
                modalInvestasi *
                100
              )
            : 0;



    // Saldo cash
    const saldoCash =
        totalPemasukan -
        totalPengeluaran -
        totalInvestasi;



    // Total kekayaan
    const totalKekayaan =
        saldoCash +
        nilaiInvestasiSekarang;



    return {

        totalPemasukan,

        totalPengeluaran,

        totalInvestasi,

        modalInvestasi,

        nilaiInvestasiSekarang,

        keuntunganInvestasi,

        returnInvestasi,

        saldoCash,

        totalKekayaan

    };

}

// ======================================================
// BUDGET BULANAN
// ======================================================

async function ambilBudgetBulanIni() {

    const sekarang = new Date();

    const tahun =
        sekarang.getFullYear();

    const bulan =
        sekarang.getMonth() + 1;


    try {

        const response =
            await fetch(
                `budget.php?tahun=${tahun}&bulan=${bulan}`,
                {
                    cache: 'no-store'
                }
            );


        if (!response.ok) {

            throw new Error(
                `HTTP ${response.status}`
            );

        }


        const hasil =
            await response.json();


        if (!hasil.status) {

            console.error(
                hasil.pesan
            );

            return;

        }


        budgetBulanIni =
            hasil.data;


        updateBudgetDashboard();


    }

    catch (error) {

        console.error(
            'Gagal mengambil budget:',
            error
        );

    }

}


// ======================================================
// HITUNG PENGELUARAN BULAN INI
// ======================================================

function hitungPengeluaranBulanIni() {

    const sekarang =
        new Date();


    const tahun =
        sekarang.getFullYear();


    const bulan =
        String(
            sekarang.getMonth() + 1
        ).padStart(2, '0');


    const bulanIni =
        `${tahun}-${bulan}`;


    let total = 0;


    transaksi.forEach(item => {

        const tanggal =
            String(
                item.tanggal || ''
            );


        if (
            item.jenis === 'Pengeluaran' &&
            tanggal.substring(0, 7) === bulanIni
        ) {

            total +=
                Number(item.nominal) || 0;

        }

    });


    return total;

}


// ======================================================
// UPDATE BUDGET DI DASHBOARD
// ======================================================

function updateBudgetDashboard() {

    const budgetElement =
        document.getElementById('budgetBulanan');

    const pengeluaranElement =
        document.getElementById('pengeluaranBulanIni');

    const sisaElement =
        document.getElementById('sisaBudget');

    const statusElement =
        document.getElementById('statusBudget');

    const persentaseElement =
        document.getElementById('persentaseBudget');

    const progressElement =
        document.getElementById('progressBudget');

    const teksProgressElement =
        document.getElementById('teksProgressBudget');

    const inputBudget =
        document.getElementById('inputBudget');

    const pengeluaran =
        hitungPengeluaranBulanIni();

    // Tampilkan pengeluaran bulan ini
    if (pengeluaranElement) {
        pengeluaranElement.textContent =
            formatRupiah(pengeluaran);
    }

    // Jika budget belum diatur
    if (!budgetBulanIni) {

        if (budgetElement) {
            budgetElement.textContent =
                'Belum diatur';
        }

        if (sisaElement) {
            sisaElement.textContent =
                'Belum diatur';
        }

        if (statusElement) {
            statusElement.textContent =
                'Belum diatur';
        }

        if (persentaseElement) {
            persentaseElement.textContent =
                '0%';
        }

        if (progressElement) {
            progressElement.style.width =
                '0%';
        }

        if (teksProgressElement) {
            teksProgressElement.textContent =
                'Belum ada budget yang diatur.';
        }

        if (inputBudget) {
            inputBudget.value = '';
        }

        return;
    }

    const budget =
        Number(budgetBulanIni.nominal) || 0;

    const sisa =
        budget - pengeluaran;

    // Hitung persentase budget terpakai
    let persentase =
        budget > 0
            ? (pengeluaran / budget) * 100
            : 0;

    // Untuk progress bar, maksimal 100%
    const progress =
        Math.min(Math.max(persentase, 0), 100);

    // Tentukan status
    let status = '';

    if (pengeluaran > budget) {

        status = '🔴 Terlampaui';

    } else if (pengeluaran > budget * 0.8) {

        status = '🟡 Mendekati Batas';

    } else {

        status = '🟢 Aman';
    }

    // Tampilkan budget
    if (budgetElement) {
        budgetElement.textContent =
            formatRupiah(budget);
    }

    // Tampilkan sisa budget
    if (sisaElement) {
        sisaElement.textContent =
            formatRupiah(sisa);
    }

    // Tampilkan status
    if (statusElement) {
        statusElement.textContent =
            status;
    }

    // Tampilkan persentase
    if (persentaseElement) {
        persentaseElement.textContent =
            persentase.toFixed(1) + '%';
    }

    // Tampilkan progress bar
    if (progressElement) {
        progressElement.style.width =
            progress + '%';
    }

    // Tampilkan teks progress
    if (teksProgressElement) {

        if (pengeluaran > budget) {

            teksProgressElement.textContent =
                `Budget terlampaui sebesar ${formatRupiah(
                    pengeluaran - budget
                )}.`;

        } else {

            teksProgressElement.textContent =
                `${formatRupiah(pengeluaran)} dari ${formatRupiah(
                    budget
                )} sudah digunakan.`;
        }
    }

    // Isi input dengan budget yang tersimpan
    if (inputBudget) {
        inputBudget.value = budget;
    }
}

// ======================================================
// SIMPAN BUDGET
// ======================================================

async function simpanBudget() {

    const input =
        document.getElementById(
            'inputBudget'
        );


    const nominal =
        input.value;


    if (
        nominal === '' ||
        Number(nominal) <= 0
    ) {

        alert(
            'Masukkan budget lebih dari Rp0.'
        );

        return;

    }


    const sekarang =
        new Date();


    const tahun =
        sekarang.getFullYear();


    const bulan =
        sekarang.getMonth() + 1;


    const formData =
        new FormData();


    formData.append(
        'aksi',
        'simpan'
    );


    formData.append(
        'tahun',
        tahun
    );


    formData.append(
        'bulan',
        bulan
    );


    formData.append(
        'nominal',
        nominal
    );


    try {

        const response =
            await fetch(
                'budget.php',
                {
                    method: 'POST',
                    body: formData
                }
            );


        const text =
            await response.text();


        let hasil;


        try {

            hasil =
                JSON.parse(text);

        }

        catch (error) {

            alert(
                'Respons dari budget.php tidak valid:\n\n' +
                text
            );

            return;

        }


        if (!hasil.status) {

            alert(
                hasil.pesan ||
                'Budget gagal disimpan.'
            );

            return;

        }


        alert(
            hasil.pesan ||
            'Budget berhasil disimpan.'
        );


        // Ambil ulang budget

        await ambilBudgetBulanIni();

    }

    catch (error) {

        console.error(error);


        alert(
            'Tidak bisa menyimpan budget.\n\n' +
            error.message
        );

    }

}

// ======================================================
// UPDATE DASHBOARD
// ======================================================

function updateDashboard() {

    const data =
        hitungKeuangan();


    document.getElementById(
        'totalSaldo'
    ).textContent =
        formatRupiah(
            data.saldoCash
        );


    document.getElementById(
        'totalPemasukan'
    ).textContent =
        formatRupiah(
            data.totalPemasukan
        );


    document.getElementById(
        'totalPengeluaran'
    ).textContent =
        formatRupiah(
            data.totalPengeluaran
        );


    document.getElementById(
        'totalInvestasi'
    ).textContent =
        formatRupiah(
            data.nilaiInvestasiSekarang
        );


    document.getElementById(
        'keuntunganInvestasi'
    ).textContent =
        formatRupiah(
            data.keuntunganInvestasi
        );


    document.getElementById(
        'returnInvestasi'
    ).textContent =
        data.returnInvestasi.toFixed(2) +
        '%';


    document.getElementById(
        'totalKekayaan'
    ).textContent =
        formatRupiah(
            data.totalKekayaan
        );
     updateBudgetDashboard();
}


// ======================================================
// GRAFIK RINGKASAN KEUANGAN
// ======================================================

function updateGrafik() {

    const canvas =
        document.getElementById(
            'grafikKeuangan'
        );


    if (!canvas) return;


    const data =
        hitungKeuangan();


    if (grafikKeuangan) {

        grafikKeuangan.destroy();

    }



    grafikKeuangan =
        new Chart(
            canvas,
            {

                type: 'bar',

                data: {

                    labels: [
                        'Pemasukan',
                        'Pengeluaran',
                        'Investasi'
                    ],

                    datasets: [

                        {
                            label: 'Total',

                            data: [

                                data.totalPemasukan,

                                data.totalPengeluaran,

                                data.totalInvestasi

                            ]

                        }

                    ]

                },


                options: {

                    responsive: true,


                    plugins: {

                        tooltip: {

                            callbacks: {

                                label:
                                    context =>
                                        formatRupiah(
                                            context.raw
                                        )

                            }

                        }

                    },


                    scales: {

                        y: {

                            ticks: {

                                callback:
                                    value =>
                                        formatRupiah(
                                            value
                                        )

                            }

                        }

                    }

                }

            }
        );

}


// ======================================================
// RENDER TRANSAKSI
// ======================================================

function renderTransaksi(
    data = transaksi
) {

    const tbody =
        document.getElementById(
            'tabelTransaksi'
        );


    tbody.innerHTML = '';


    if (!data.length) {

        tbody.innerHTML = `
            <tr>
                <td
                    colspan="7"
                    class="empty-data"
                >
                    Belum ada transaksi.
                </td>
            </tr>
        `;

        return;

    }



    data.forEach(item => {

        const nilai =
            item.nilai_investasi !== null &&
            item.nilai_investasi !== ''
                ? formatRupiah(
                    item.nilai_investasi
                  )
                : '-';


        tbody.innerHTML += `

            <tr>

                <td>
                    ${escapeHtml(item.tanggal)}
                </td>


                <td>
                    ${escapeHtml(item.keterangan)}
                </td>


                <td>
                    ${escapeHtml(item.jenis)}
                </td>


                <td>
                    ${formatRupiah(item.nominal)}
                </td>


                <td>
                    ${nilai}
                </td>


                <td>
                    ${escapeHtml(item.kategori)}
                </td>


                <td>

                    <button
                        type="button"
                        onclick="editTransaksi(${Number(item.id)})"
                    >
                        Edit
                    </button>


                    <button
                        type="button"
                        onclick="hapusTransaksi(${Number(item.id)})"
                    >
                        Hapus
                    </button>

                </td>

            </tr>

        `;

    });

}


// ======================================================
// TAMPILKAN FORM
// ======================================================

function tampilkanForm() {

    editId = null;


    document.getElementById(
        'judulForm'
    ).textContent =
        'Tambah Transaksi';


    document.getElementById(
        'tanggal'
    ).value =
        tanggalHariIni();


    document.getElementById(
        'keterangan'
    ).value = '';


    document.getElementById(
        'jenis'
    ).value =
        'Pemasukan';


    document.getElementById(
        'nominal'
    ).value = '';


    document.getElementById(
        'kategori'
    ).value =
        'Gaji';


    document.getElementById(
        'nilai_investasi'
    ).value = '';


    document.getElementById(
        'nilai_investasi'
    ).required =
        false;


    document.getElementById(
        'inputNilaiInvestasi'
    ).style.display =
        'none';


    document.getElementById(
        'keteranganNominalInvestasi'
    ).style.display =
        'none';


    document.getElementById(
        'formTransaksi'
    ).style.display =
        'block';


    document.getElementById(
        'formTransaksi'
    ).scrollIntoView({
        behavior: 'smooth'
    });

}


// ======================================================
// RESET FORM
// ======================================================

function resetForm() {

    editId = null;


    document.getElementById(
        'tanggal'
    ).value =
        tanggalHariIni();


    document.getElementById(
        'keterangan'
    ).value = '';


    document.getElementById(
        'jenis'
    ).value =
        'Pemasukan';


    document.getElementById(
        'nominal'
    ).value = '';


    document.getElementById(
        'kategori'
    ).value =
        'Gaji';


    document.getElementById(
        'nilai_investasi'
    ).value = '';


    document.getElementById(
        'nilai_investasi'
    ).required =
        false;


    document.getElementById(
        'inputNilaiInvestasi'
    ).style.display =
        'none';


    document.getElementById(
        'keteranganNominalInvestasi'
    ).style.display =
        'none';


    document.getElementById(
        'judulForm'
    ).textContent =
        'Tambah Transaksi';

}


// ======================================================
// BATAL FORM
// ======================================================

function batalForm() {

    resetForm();


    document.getElementById(
        'formTransaksi'
    ).style.display =
        'none';

}


// ======================================================
// PERUBAHAN JENIS TRANSAKSI
// ======================================================

function ubahJenisTransaksi() {

    const jenis =
        document.getElementById(
            'jenis'
        ).value;


    const container =
        document.getElementById(
            'inputNilaiInvestasi'
        );


    const input =
        document.getElementById(
            'nilai_investasi'
        );


    const kategori =
        document.getElementById(
            'kategori'
        );


    const bantuan =
        document.getElementById(
            'keteranganNominalInvestasi'
        );



    if (jenis === 'Investasi') {

        container.style.display =
            'block';


        input.required =
            true;


        bantuan.style.display =
            'block';


        kategori.value =
            'Investasi';

    }

    else {

        container.style.display =
            'none';


        input.required =
            false;


        input.value =
            '';


        bantuan.style.display =
            'none';


        if (
            kategori.value ===
            'Investasi'
        ) {

            kategori.value =
                jenis === 'Pemasukan'
                    ? 'Gaji'
                    : 'Makanan';

        }

    }

}


// ======================================================
// SIMPAN TRANSAKSI
// ======================================================

async function simpanTransaksi() {

    const tanggal =
        document.getElementById(
            'tanggal'
        ).value;


    const keterangan =
        document.getElementById(
            'keterangan'
        ).value.trim();


    const jenis =
        document.getElementById(
            'jenis'
        ).value;


    const nominal =
        document.getElementById(
            'nominal'
        ).value;


    const kategori =
        document.getElementById(
            'kategori'
        ).value;


    const inputInvestasi =
        document.getElementById(
            'nilai_investasi'
        );



    // Validasi dasar
    if (
        !tanggal ||
        !keterangan ||
        nominal === '' ||
        Number(nominal) < 0
    ) {

        alert(
            'Mohon lengkapi tanggal, keterangan, dan nominal.'
        );

        return;

    }



    // Pemasukan dan pengeluaran
    // harus lebih dari 0
    if (
        jenis !== 'Investasi' &&
        Number(nominal) <= 0
    ) {

        alert(
            'Nominal pemasukan/pengeluaran harus lebih dari 0.'
        );

        return;

    }



    let nilaiInvestasi = '';



    // Investasi wajib punya
    // nilai investasi saat ini
    if (jenis === 'Investasi') {

        nilaiInvestasi =
            inputInvestasi.value;


        if (
            nilaiInvestasi === '' ||
            Number(nilaiInvestasi) < 0
        ) {

            alert(
                'Masukkan nilai investasi saat ini.'
            );

            return;

        }

    }



    const formData =
        new FormData();


    formData.append(
        'aksi',
        editId
            ? 'update'
            : 'tambah'
    );


    if (editId) {

        formData.append(
            'id',
            editId
        );

    }


    formData.append(
        'tanggal',
        tanggal
    );


    formData.append(
        'keterangan',
        keterangan
    );


    formData.append(
        'jenis',
        jenis
    );


    formData.append(
        'nominal',
        nominal
    );


    formData.append(
        'nilai_investasi',
        nilaiInvestasi
    );


    formData.append(
        'kategori',
        kategori
    );



    try {

        const response =
            await fetch(
                'transaksi.php',
                {
                    method: 'POST',
                    body: formData
                }
            );


        const text =
            await response.text();


        let hasil;


        try {

            hasil =
                JSON.parse(text);

        }

        catch (e) {

            alert(
                'Respons dari transaksi.php tidak valid:\n\n' +
                text
            );

            return;

        }



        if (!hasil.status) {

            alert(
                hasil.pesan ||
                'Transaksi gagal disimpan.'
            );

            return;

        }



        alert(
            hasil.pesan ||
            'Transaksi berhasil disimpan.'
        );


        batalForm();


        await ambilTransaksi();


    }

    catch (error) {

        console.error(error);

        alert(
            'Tidak bisa menyimpan transaksi.\n\n' +
            error.message
        );

    }

}


// ======================================================
// EDIT TRANSAKSI
// ======================================================

function editTransaksi(id) {

    const item =
        transaksi.find(
            data =>
                Number(data.id) ===
                Number(id)
        );


    if (!item) {

        alert(
            'Data transaksi tidak ditemukan.'
        );

        return;

    }



    editId =
        Number(item.id);



    document.getElementById(
        'formTransaksi'
    ).style.display =
        'block';


    document.getElementById(
        'judulForm'
    ).textContent =
        'Edit Transaksi';


    document.getElementById(
        'tanggal'
    ).value =
        item.tanggal;


    document.getElementById(
        'keterangan'
    ).value =
        item.keterangan;


    document.getElementById(
        'jenis'
    ).value =
        item.jenis;


    document.getElementById(
        'nominal'
    ).value =
        item.nominal;


    document.getElementById(
        'kategori'
    ).value =
        item.kategori;



    if (
        item.jenis ===
        'Investasi'
    ) {

        document.getElementById(
            'inputNilaiInvestasi'
        ).style.display =
            'block';


        document.getElementById(
            'keteranganNominalInvestasi'
        ).style.display =
            'block';


        document.getElementById(
            'nilai_investasi'
        ).value =
            item.nilai_investasi || '';


        document.getElementById(
            'nilai_investasi'
        ).required =
            true;

    }

    else {

        document.getElementById(
            'inputNilaiInvestasi'
        ).style.display =
            'none';


        document.getElementById(
            'keteranganNominalInvestasi'
        ).style.display =
            'none';


        document.getElementById(
            'nilai_investasi'
        ).value =
            '';


        document.getElementById(
            'nilai_investasi'
        ).required =
            false;

    }



    document.getElementById(
        'formTransaksi'
    ).scrollIntoView({
        behavior: 'smooth'
    });

}


// ======================================================
// HAPUS TRANSAKSI
// ======================================================

async function hapusTransaksi(id) {

    if (
        !confirm(
            'Yakin ingin menghapus transaksi ini?'
        )
    ) {

        return;

    }



    const formData =
        new FormData();


    formData.append(
        'aksi',
        'hapus'
    );


    formData.append(
        'id',
        id
    );



    try {

        const response =
            await fetch(
                'transaksi.php',
                {
                    method: 'POST',
                    body: formData
                }
            );


        const text =
            await response.text();


        let hasil;


        try {

            hasil =
                JSON.parse(text);

        }

        catch (e) {

            alert(
                'Respons server tidak valid:\n\n' +
                text
            );

            return;

        }



        if (!hasil.status) {

            alert(
                hasil.pesan ||
                'Gagal menghapus transaksi.'
            );

            return;

        }



        alert(
            hasil.pesan ||
            'Transaksi berhasil dihapus.'
        );


        await ambilTransaksi();


    }

    catch (error) {

        console.error(error);

        alert(
            'Terjadi kesalahan saat menghapus transaksi.'
        );

    }

}


// ======================================================
// FILTER TRANSAKSI
// ======================================================

function filterTransaksi() {

    const keyword =
        document.getElementById(
            'searchTransaksi'
        ).value
        .toLowerCase()
        .trim();


    const filterJenis =
        document.getElementById(
            'filterJenis'
        ).value;


    const filterKategori =
        document.getElementById(
            'filterKategori'
        ).value;



    const hasil =
        transaksi.filter(item => {

            const keterangan =
                String(
                    item.keterangan || ''
                ).toLowerCase();


            return (

                keterangan.includes(
                    keyword
                )

                &&

                (
                    filterJenis === '' ||
                    item.jenis === filterJenis
                )

                &&

                (
                    filterKategori === '' ||
                    item.kategori === filterKategori
                )

            );

        });



    renderTransaksi(hasil);

}


// ======================================================
// RESET FILTER
// ======================================================

function resetFilter() {

    document.getElementById(
        'searchTransaksi'
    ).value = '';


    document.getElementById(
        'filterJenis'
    ).value = '';


    document.getElementById(
        'filterKategori'
    ).value = '';


    renderTransaksi();

}


// ======================================================
// ISI FILTER BULAN
// ======================================================

function isiFilterLaporan() {

    const select =
        document.getElementById(
            'filterBulanLaporan'
        );


    const nilaiLama =
        select.value;


    const bulanSet =
        new Set();


    transaksi.forEach(item => {

        const bulan =
            String(
                item.tanggal
            ).substring(0, 7);


        if (bulan) {

            bulanSet.add(bulan);

        }

    });



    const daftarBulan =
        Array.from(
            bulanSet
        ).sort(
            (a, b) =>
                b.localeCompare(a)
        );



    select.innerHTML =
        '<option value="">Semua Bulan</option>';



    daftarBulan.forEach(
        bulan => {

            const option =
                document.createElement(
                    'option'
                );


            option.value =
                bulan;


            option.textContent =
                formatBulan(bulan);


            select.appendChild(
                option
            );

        }
    );



    if (
        bulanSet.has(nilaiLama)
    ) {

        select.value =
            nilaiLama;

    }

}


// ======================================================
// NILAI INVESTASI SAMPAI BULAN
// ======================================================

function nilaiInvestasiSampaiBulan(
    bulanTarget
) {

    const data =
        transaksi
            .filter(item => {

                const bulan =
                    String(
                        item.tanggal
                    ).substring(0, 7);


                return (

                    item.jenis ===
                    'Investasi'

                    &&

                    bulan <=
                    bulanTarget

                    &&

                    item.nilai_investasi !==
                    null

                    &&

                    item.nilai_investasi !==
                    ''

                );

            })


            .sort(
                (a, b) => {

                    if (
                        a.tanggal !==
                        b.tanggal
                    ) {

                        return b.tanggal.localeCompare(
                            a.tanggal
                        );

                    }


                    return Number(b.id) -
                        Number(a.id);

                }
            );



    return data.length
        ? Number(
            data[0].nilai_investasi
          ) || 0
        : 0;

}


// ======================================================
// HITUNG LAPORAN BULAN
// ======================================================

function hitungLaporanBulan(
    bulan
) {

    let pemasukan = 0;

    let pengeluaran = 0;

    let investasi = 0;



    transaksi.forEach(item => {

        const bulanItem =
            String(
                item.tanggal
            ).substring(0, 7);


        if (
            bulanItem > bulan
        ) {

            return;

        }



        const nominal =
            Number(item.nominal) || 0;



        if (
            item.jenis ===
            'Pemasukan'
        ) {

            pemasukan +=
                nominal;

        }



        if (
            item.jenis ===
            'Pengeluaran'
        ) {

            pengeluaran +=
                nominal;

        }



        if (
            item.jenis ===
            'Investasi'
        ) {

            investasi +=
                nominal;

        }

    });



    const saldoCash =
        pemasukan -
        pengeluaran -
        investasi;



    const nilaiInvestasi =
        nilaiInvestasiSampaiBulan(
            bulan
        );



    const totalKekayaan =
        saldoCash +
        nilaiInvestasi;



    return {

        pemasukan,

        pengeluaran,

        investasi,

        saldoCash,

        nilaiInvestasi,

        totalKekayaan

    };

}


// ======================================================
// RENDER LAPORAN
// ======================================================

function renderLaporan() {

    const tbody =
        document.getElementById(
            'tabelLaporan'
        );


    const filterBulan =
        document.getElementById(
            'filterBulanLaporan'
        ).value;


    const kelompok = {};



    transaksi.forEach(item => {

        const bulan =
            String(
                item.tanggal
            ).substring(0, 7);


        if (!bulan) {

            return;

        }



        if (!kelompok[bulan]) {

            kelompok[bulan] = {

                pemasukan: 0,

                pengeluaran: 0,

                investasi: 0

            };

        }



        const nominal =
            Number(item.nominal) || 0;



        if (
            item.jenis ===
            'Pemasukan'
        ) {

            kelompok[bulan].pemasukan +=
                nominal;

        }



        if (
            item.jenis ===
            'Pengeluaran'
        ) {

            kelompok[bulan].pengeluaran +=
                nominal;

        }



        if (
            item.jenis ===
            'Investasi'
        ) {

            kelompok[bulan].investasi +=
                nominal;

        }

    });



    let daftarBulan =
        Object.keys(
            kelompok
        ).sort(
            (a, b) =>
                b.localeCompare(a)
        );



    if (filterBulan) {

        daftarBulan =
            daftarBulan.filter(
                bulan =>
                    bulan ===
                    filterBulan
            );

    }



    tbody.innerHTML = '';



    if (!daftarBulan.length) {

        tbody.innerHTML = `

            <tr>

                <td
                    colspan="7"
                    class="empty-data"
                >
                    Belum ada data laporan.
                </td>

            </tr>

        `;

    }

    else {

        daftarBulan.forEach(
            bulan => {

                const dataBulan =
                    kelompok[bulan];


                const kumulatif =
                    hitungLaporanBulan(
                        bulan
                    );



                tbody.innerHTML += `

                    <tr>

                        <td>
                            ${formatBulan(bulan)}
                        </td>


                        <td>
                            ${formatRupiah(
                                dataBulan.pemasukan
                            )}
                        </td>


                        <td>
                            ${formatRupiah(
                                dataBulan.pengeluaran
                            )}
                        </td>


                        <td>
                            ${formatRupiah(
                                dataBulan.investasi
                            )}
                        </td>


                        <td>
                            ${formatRupiah(
                                kumulatif.saldoCash
                            )}
                        </td>


                        <td>
                            ${formatRupiah(
                                kumulatif.nilaiInvestasi
                            )}
                        </td>


                        <td>
                            ${formatRupiah(
                                kumulatif.totalKekayaan
                            )}
                        </td>

                    </tr>

                `;

            }
        );

    }



    renderRincianKategori();

    updateGrafikPengeluaran();

    updateGrafikInvestasiBulanan();

}


// ======================================================
// RINCIAN KATEGORI PENGELUARAN
// ======================================================

function renderRincianKategori() {

    const tbody =
        document.getElementById(
            'tabelKategori'
        );


    const filterBulan =
        document.getElementById(
            'filterBulanLaporan'
        ).value;


    const kategoriData = {};



    transaksi.forEach(item => {

        if (
            item.jenis !==
            'Pengeluaran'
        ) {

            return;

        }



        const bulan =
            String(
                item.tanggal
            ).substring(0, 7);


        if (
            filterBulan &&
            bulan !== filterBulan
        ) {

            return;

        }



        const kategori =
            item.kategori ||
            'Lainnya';


        const nominal =
            Number(item.nominal) || 0;



        kategoriData[kategori] =
            (
                kategoriData[kategori] ||
                0
            ) +
            nominal;

    });



    const daftarKategori =
        Object.entries(
            kategoriData
        ).sort(
            (a, b) =>
                b[1] - a[1]
        );



    tbody.innerHTML = '';



    if (
        !daftarKategori.length
    ) {

        tbody.innerHTML = `

            <tr>

                <td
                    colspan="3"
                    class="empty-data"
                >
                    Belum ada pengeluaran.
                </td>

            </tr>

        `;

        return;

    }



    const total =
        daftarKategori.reduce(
            (sum, item) =>
                sum + item[1],
            0
        );



    daftarKategori.forEach(
        ([kategori, nominal]) => {

            const persen =
                total > 0
                    ? nominal / total * 100
                    : 0;



            tbody.innerHTML += `

                <tr>

                    <td>
                        ${escapeHtml(kategori)}
                    </td>


                    <td>
                        ${formatRupiah(
                            nominal
                        )}
                    </td>


                    <td>
                        ${persen.toFixed(1)}%
                    </td>

                </tr>

            `;

        }
    );

}


// ======================================================
// GRAFIK PENGELUARAN
// ======================================================

function updateGrafikPengeluaran() {

    const canvas =
        document.getElementById(
            'grafikPengeluaran'
        );


    if (!canvas) {

        return;

    }



    const filterBulan =
        document.getElementById(
            'filterBulanLaporan'
        ).value;


    const dataKategori = {};



    transaksi.forEach(item => {

        if (
            item.jenis !==
            'Pengeluaran'
        ) {

            return;

        }



        const bulan =
            String(
                item.tanggal
            ).substring(0, 7);


        if (
            filterBulan &&
            bulan !== filterBulan
        ) {

            return;

        }



        const kategori =
            item.kategori ||
            'Lainnya';


        const nominal =
            Number(item.nominal) || 0;



        dataKategori[kategori] =
            (
                dataKategori[kategori] ||
                0
            ) +
            nominal;

    });



    const labels =
        Object.keys(
            dataKategori
        );


    const values =
        Object.values(
            dataKategori
        );



    if (
        grafikPengeluaran
    ) {

        grafikPengeluaran.destroy();

    }



    if (
        !labels.length
    ) {

        grafikPengeluaran =
            new Chart(
                canvas,
                {

                    type: 'doughnut',

                    data: {

                        labels: [
                            'Belum ada pengeluaran'
                        ],

                        datasets: [

                            {
                                data: [1]
                            }

                        ]

                    },


                    options: {

                        responsive: true,

                        plugins: {

                            legend: {
                                position: 'bottom'
                            },

                            tooltip: {
                                enabled: false
                            }

                        }

                    }

                }
            );

        return;

    }



    grafikPengeluaran =
        new Chart(
            canvas,
            {

                type: 'doughnut',


                data: {

                    labels: labels,

                    datasets: [

                        {

                            label:
                                'Pengeluaran',

                            data:
                                values

                        }

                    ]

                },


                options: {

                    responsive: true,

                    plugins: {

                        legend: {

                            position:
                                'bottom'

                        },


                        tooltip: {

                            callbacks: {

                                label:
                                    function(context) {

                                        const total =
                                            values.reduce(
                                                (a, b) =>
                                                    a + b,
                                                0
                                            );


                                        const nominal =
                                            Number(
                                                context.raw
                                            ) || 0;


                                        const persen =
                                            total > 0
                                                ? (
                                                    nominal /
                                                    total *
                                                    100
                                                  ).toFixed(1)
                                                : 0;


                                        return `${context.label}: ${formatRupiah(nominal)} (${persen}%)`;

                                    }

                            }

                        }

                    }

                }

            }
        );

}


// ======================================================
// GRAFIK INVESTASI BULANAN
// ======================================================

function updateGrafikInvestasiBulanan() {

    const canvas =
        document.getElementById(
            'grafikInvestasiBulanan'
        );


    if (!canvas) {

        return;

    }



    const filterBulan =
        document.getElementById(
            'filterBulanLaporan'
        ).value;


    const bulanSet =
        new Set();



    transaksi.forEach(item => {

        const bulan =
            String(
                item.tanggal
            ).substring(0, 7);


        if (bulan) {

            bulanSet.add(
                bulan
            );

        }

    });



    let labels =
        Array.from(
            bulanSet
        ).sort(
            (a, b) =>
                a.localeCompare(b)
        );



    if (filterBulan) {

        labels =
            labels.filter(
                bulan =>
                    bulan ===
                    filterBulan
            );

    }



    const values =
        labels.map(
            bulan =>
                nilaiInvestasiSampaiBulan(
                    bulan
                )
        );



    if (
        grafikInvestasiBulanan
    ) {

        grafikInvestasiBulanan.destroy();

    }



    if (!labels.length) {

        grafikInvestasiBulanan =
            new Chart(
                canvas,
                {

                    type: 'line',

                    data: {

                        labels: [
                            'Belum ada data'
                        ],

                        datasets: [

                            {

                                label:
                                    'Nilai Investasi',

                                data: [0],

                                tension:
                                    0.3

                            }

                        ]

                    },


                    options: {

                        responsive: true,


                        scales: {

                            y: {

                                ticks: {

                                    callback:
                                        value =>
                                            formatRupiah(
                                                value
                                            )

                                }

                            }

                        }

                    }

                }
            );

        return;

    }



    grafikInvestasiBulanan =
        new Chart(
            canvas,
            {

                type: 'line',


                data: {

                    labels:
                        labels.map(
                            formatBulan
                        ),


                    datasets: [

                        {

                            label:
                                'Nilai Investasi',

                            data:
                                values,

                            tension:
                                0.3,

                            fill:
                                false

                        }

                    ]

                },


                options: {

                    responsive: true,


                    plugins: {

                        tooltip: {

                            callbacks: {

                                label:
                                    context =>
                                        formatRupiah(
                                            context.raw
                                        )

                            }

                        }

                    },


                    scales: {

                        y: {

                            ticks: {

                                callback:
                                    value =>
                                        formatRupiah(
                                            value
                                        )

                            }

                        }

                    }

                }

            }
        );

}


// ======================================================
// INIT
// ======================================================

document.addEventListener(
    'DOMContentLoaded',
    function() {

        document.getElementById(
            'tanggal'
        ).value =
            tanggalHariIni();


        ubahJenisTransaksi();


        ambilTransaksi();

    }
);

</script>

</body>
</html>