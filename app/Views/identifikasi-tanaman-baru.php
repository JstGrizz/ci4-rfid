<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Identifikasi Tanaman - Baru</title>

    <link rel="shortcut icon" href="<?= base_url('/assets/compiled/svg/favicon.svg'); ?>" type="image/x-icon" />
    <link rel="shortcut icon"
        href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACEAAAAiCAYAAADRcLDBAAAEs2lUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4KPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iWE1QIENvcmUgNS41LjAiPgogPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4KICA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIgogICAgeG1sbnM6ZXhpZj0iaHR0cDovL25zLmFkb2JlLmNvbS9leGlmLzEuMC8iCiAgICB4bWxuczp0aWZmPSJodHRwOi8vbnMuYWRvYmUuY29tL3RpZmYvMS4wLyIKICAgIHhtbG5zOnBob3Rvc2hvcD0iaHR0cDovL25zLmFkb2JlLmNvbS9waG90b3Nob3AvMS4wLyIKICAgIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIKICAgIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIgogICAgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIKICAgZXhpZjpQaXhlbFhEaW1lbnNpb249IjMzIgogICBleGlmOlBpeGVsWURpbWVuc2lvbj0iMzQiCiAgIGV4aWY6Q29sb3JTcGFjZT0iMSIKICAgdGlmZjpJbWFnZVdpZHRoPSIzMyIKICAgdGlmZjpJbWFnZUxlbmd0aD0iMzQiCiAgIHRpZmY6UmVzb2x1dGlvblVuaXQ9IjIiCiAgIHRpZmY6WFJlc29sdXRpb249Ijk2LjAiCiAgIHRpZmY6WVJlc29sdXRpb249Ijk2LjAiCiAgIHBob3Rvc2hvcDpDb2xvck1vZGU9IjMiCiAgIHBob3Rvc2hvcDpJQ0NQcm9maWxlPSJzUkdCIElFQzYxOTY2LTIuMSIKICAgeG1wOk1vZGlmeURhdGU9IjIwMjItMDMtMzFUMTA6NTA6MjMrMDI6MDAiCiAgIHhtcDpNZXRhZGF0YURhdGU9IjIwMjItMDMtMzFUMTA6NTA6MjMrMDI6MDAiPgogICA8eG1wTU06SGlzdG9yeT4KICAgIDxyZGY6U2VxPgogICAgIDxyZGY6bGkKICAgICAgc3RFdnQ6YWN0aW9uPSJwcm9kdWNlZCIKICAgICAgc3RFdnQ6c29mdHdhcmVBZ2VudD0iQWZmaW5pdHkgRGVzaWduZXIgMS4xMC4xIgogICAgICBzdEV2dDp3aGVuPSIyMDIyLTAzLTMxVDEwOjUwOjIzKzAyOjAwIi8+CiAgICA8L3JkZjpTZXE+CiAgIDwveG1wTU06SGlzdG9yeT4KICA8L3JkZjpEZXNjcmlwdGlvbj4KIDwvcmRmOlJERj4KPC94OnhtcG1ldGE+Cjw/eHBhY2tldCBlbmQ9InIiPz5V57uAAAABgmlDQ1BzUkdCIElFQzYxOTY2LTIuMQAAKJF1kc8rRFEUxz9maORHo1hYKC9hISNGTWwsRn4VFmOUX5uZZ36oeTOv954kW2WrKLHxa8FfwFZZK0WkZClrYoOe87ypmWTO7dzzud97z+nec8ETzaiaWd4NWtYyIiNhZWZ2TvE946WZSjqoj6mmPjE1HKWkfdxR5sSbgFOr9Ll/rXoxYapQVik8oOqGJTwqPL5i6Q5vCzeo6dii8KlwpyEXFL519LjLLw6nXP5y2IhGBsFTJ6ykijhexGra0ITl5bRqmWU1fx/nJTWJ7PSUxBbxJkwijBBGYYwhBgnRQ7/MIQIE6ZIVJfK7f/MnyUmuKrPOKgZLpEhj0SnqslRPSEyKnpCRYdXp/9++msneoFu9JgwVT7b91ga+LfjetO3PQ9v+PgLvI1xkC/m5A+h7F32zoLXug38dzi4LWnwHzjeg8UGPGbFfySvuSSbh9QRqZ6H+Gqrm3Z7l9zm+h+iafNUV7O5Bu5z3L/wAdthn7QIme0YAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAJTSURBVFiF7Zi9axRBGIefEw2IdxFBRQsLWUTBaywSK4ubdSGVIY1Y6HZql8ZKCGIqwX/AYLmCgVQKfiDn7jZeEQMWfsSAHAiKqPiB5mIgELWYOW5vzc3O7niHhT/YZvY37/swM/vOzJbIqVq9uQ04CYwCI8AhYAlYAB4Dc7HnrOSJWcoJcBS4ARzQ2F4BZ2LPmTeNuykHwEWgkQGAet9QfiMZjUSt3hwD7psGTWgs9pwH1hC1enMYeA7sKwDxBqjGnvNdZzKZjqmCAKh+U1kmEwi3IEBbIsugnY5avTkEtIAtFhBrQCX2nLVehqyRqFoCAAwBh3WGLAhbgCRIYYinwLolwLqKUwwi9pxV4KUlxKKKUwxC6ZElRCPLYAJxGfhSEOCz6m8HEXvOB2CyIMSk6m8HoXQTmMkJcA2YNTHm3congOvATo3tE3A29pxbpnFzQSiQPcB55IFmFNgFfEQeahaAGZMpsIJIAZWAHcDX2HN+2cT6r39GxmvC9aPNwH5gO1BOPFuBVWAZue0vA9+A12EgjPadnhCuH1WAE8ivYAQ4ohKaagV4gvxi5oG7YSA2vApsCOH60WngKrA3R9IsvQUuhIGY00K4flQG7gHH/mLytB4C42EgfrQb0mV7us8AAMeBS8mGNMR4nwHamtBB7B4QRNdaS0M8GxDEog7iyoAguvJ0QYSBuAOcAt71Kfl7wA8DcTvZ2KtOlJEr+ByyQtqqhTyHTIeB+ONeqi3brh+VgIN0fohUgWGggizZFTplu12yW8iy/YLOGWMpDMTPXnl+Az9vj2HERYqPAAAAAElFTkSuQmCC"
        type="image/png" />

    <link rel="stylesheet" href="<?= base_url('/assets/extensions/simple-datatables/style.css'); ?>" />

    <link rel="stylesheet" href="<?= base_url('/assets/compiled/css/table-datatable.css'); ?>" />
    <link rel="stylesheet" href="<?= base_url('/assets/compiled/css/app.css'); ?>" />
    <link rel="stylesheet" href="<?= base_url('/assets/compiled/css/app-dark.css'); ?>" />
</head>

<body>
    <style>
        .table-centered th,
        .table-centered td {
            text-align: center;
            justify-content: center;
        }
    </style>
    <script src="<?= base_url('/assets/static/js/initTheme.js'); ?>"></script>
    <div id="app">
        <?= view('sidebar'); ?>
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            <div class="page-heading">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Identifikasi Tanaman Baru</h3>
                            <p class="text-subtitle text-muted">
                                Form untuk mengidentifikasi tanaman Baru
                            </p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="<?= base_url('admin/dashboard'); ?>">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Identifikasi Tanaman
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <form class="form" id="form-identifikasi-tanaman-baru">
                    <section class="section">
                        <div class="card">
                            <div class="card-header">
                                <h4>Data Karyawan</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="npk">NPK</label>
                                    <input type="text" id="npk" class="form-control" name="npk"
                                        value="<?= isset($npk) ? $npk : ''; ?>" readonly />
                                </div>
                                <div class="form-group">
                                    <label for="nama">Nama Karyawan</label>
                                    <input type="text" id="nama" class="form-control" name="nama"
                                        value="<?= isset($nama) ? $nama : ''; ?>" readonly />
                                </div>
                            </div>
                        </div>
                    </section>
                    <section class="section">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Tipe Aktivitas</h4>
                                <p>Masukkan Tipe Aktivitas</p>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="tipe_aktivitas">Tipe Aktivitas</label>
                                    <select class="form-control" id="tipe_aktivitas" name="tipe_aktivitas"
                                        onchange="toggleCards()">
                                        <option value="">- Pilih Tipe Aktivitas -</option>
                                        <?php foreach ($tipeAktivitas as $aktivitas): ?>
                                            <?php
                                            // Convert to lowercase to make comparison case-insensitive
                                            $aktivitas_name = strtolower($aktivitas['nama_aktivitas']);
                                            if ($aktivitas_name === 'seleksi' || $aktivitas_name === 'shooting'): ?>
                                                <option value="<?= $aktivitas['aktivitas_id']; ?>">
                                                    <?= $aktivitas['nama_aktivitas']; ?>
                                                </option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </section>
                    <section id="multiple-column-form">

                        <div class="row match-height">
                            <!-- Card for Hectar Statement Data -->
                            <div class="col-12" id="hectare-statement-card" style="display:none;">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Data Hectar Statement</h4>
                                        <p>Masukkan Data Hectar Statement</p>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <!-- PT & Estate Dropdown -->
                                                <div class="form-group">
                                                    <label for="pt_estate">PT & Estate</label>
                                                    <select class="form-control" id="pt_estate" name="pt_estate"
                                                        onchange="updateBloks()">
                                                        <option value="">Select PT and Estate</option>
                                                        <?php foreach ($ptEstates as $ptEstate): ?>
                                                            <option value="<?= $ptEstate['pt_estate_id']; ?>">
                                                                <?= $ptEstate['pt']; ?> - <?= $ptEstate['estate']; ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <!-- Blok Selection -->
                                                <div class="form-group">
                                                    <label for="blok_id">Nama Blok</label>
                                                    <select class="form-control" id="blok_id" name="blok_id">
                                                        <option value="">Select Blok</option>
                                                    </select>
                                                </div>

                                                <!-- Umur Tanam (Minggu) -->
                                                <div class="form-group">
                                                    <label for="week">Umur Tanam (Minggu)</label>
                                                    <input type="text" id="week" class="form-control" name="week"
                                                        readonly />
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-12">
                                                <!-- Tahun Tanam -->
                                                <div class="form-group">
                                                    <label for="tahun_tanam">Tahun Tanam</label>
                                                    <input type="text" id="tahun_tanam" class="form-control"
                                                        name="tahun_tanam" readonly />
                                                </div>

                                                <!-- Bulan Tanam -->
                                                <div class="form-group">
                                                    <label for="bulan_tanam">Bulan Tanam</label>
                                                    <input type="text" id="bulan_tanam" class="form-control"
                                                        name="bulan_tanam" readonly />
                                                </div>

                                                <!-- Luas Tanah (Ha) -->
                                                <div class="form-group">
                                                    <label for="luas_tanah">Luas Tanah (Ha)</label>
                                                    <input type="text" id="luas_tanah" class="form-control"
                                                        name="luas_tanah" readonly />
                                                </div>

                                                <!-- Varian Bibit -->
                                                <div class="form-group">
                                                    <label for="varian_bibit">Varian Bibit</label>
                                                    <input type="text" id="varian_bibit" class="form-control"
                                                        name="varian_bibit" readonly />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card for Identifikasi Tanaman Data -->
                            <div class="col-12" id="identifikasi-tanaman-card" style="display:none;">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Data Identifikasi Tanaman</h4>
                                        <p>Masukkan Data Identifikasi Tanaman</p>
                                    </div>

                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="no_titik_tanam">No Titik Tanam</label>
                                                    <input type="text" id="no_titik_tanam" class="form-control"
                                                        name="no_titik_tanam" />
                                                </div>
                                                <div class="form-group">
                                                    <label for="longitude">Titik Tanaman Longitude</label>
                                                    <input type="text" id="longitude" class="form-control"
                                                        name="longitude" />
                                                </div>
                                                <div class="form-group">
                                                    <label for="latitude">Titik Tanaman Latitude</label>
                                                    <input type="text" id="latitude" class="form-control"
                                                        name="latitude" />
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="status">Status Tanaman</label>
                                                    <select class="form-select" id="status" name="status">
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="sister_ke">Sister ke-</label>
                                                    <input type="text" id="sister_ke" class="form-control"
                                                        name="sister_ke" readonly />
                                                </div>
                                                <div class="form-group">
                                                    <label for="rfid">RFID Tanaman</label>
                                                    <input type="text" id="rfid" class="form-control" name="rfid" />
                                                </div>
                                            </div>

                                            <div class="col-12 d-flex justify-content-end">
                                                <button type="button" id="submit-seleksi"
                                                    class="btn btn-primary me-1 mb-1">Submit Seleksi</button>
                                                <button type="reset"
                                                    class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- New Card for Shooting Activity -->
                            <div class="col-12" id="shooting-card" style="display:none;">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Mencari Data Identifikasi Tanaman</h4>
                                        <p>Masukkan No Titik Tanam untuk mencari data tanaman</p>
                                    </div>

                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="no_titik_tanam_shooting">No Titik Tanam</label>
                                            <input type="text" id="no_titik_tanam_shooting" class="form-control"
                                                name="no_titik_tanam_shooting" />
                                        </div>
                                        <div class="form-group">
                                            <label for="longitude_shooting">Titik Tanaman Longitude</label>
                                            <input type="text" id="longitude_shooting" class="form-control"
                                                name="longitude_shooting" />
                                        </div>
                                        <div class="form-group">
                                            <label for="latitude_shooting">Titik Tanaman Latitude</label>
                                            <input type="text" id="latitude_shooting" class="form-control"
                                                name="latitude_shooting" />
                                        </div>
                                    </div>
                                </div>

                                <!-- Container to display fetched tanaman data for shooting -->
                                <div id="tanaman-container-shooting"></div>

                                <div class="col-12 d-flex justify-content-end">
                                    <button type="button" id="submit-shooting" class="btn btn-primary me-1 mb-1">Submit
                                        Shooting</button>
                                </div>
                            </div>
                        </div>
                    </section>
                </form>
            </div>
            <?= view('footer'); ?>
        </div>
    </div>
    <script src="<?= base_url('/assets/static/js/components/dark.js'); ?>"></script>
    <script src="<?= base_url('/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js'); ?>"></script>

    <script src="<?= base_url('/assets/compiled/js/app.js'); ?>"></script>

    <script src="<?= base_url('/assets/extensions/simple-datatables/umd/simple-datatables.js'); ?>"></script>
    <script src="<?= base_url('/assets/static/js/pages/simple-datatables.js'); ?>"></script>
    <script src=" <?= base_url('/assets/static/js/pages/simple-datatables.js'); ?>"></script>
    <script>
        // Function to fetch blocks when a PT & Estate is selected
        function updateBloks() {
            const ptEstateId = document.getElementById('pt_estate').value;
            const blokSelect = document.getElementById('blok_id');

            if (ptEstateId) {
                fetch(`<?= base_url('identifikasi-tanaman/getBloksByPtEstateId'); ?>/${ptEstateId}`)
                    .then(response => response.json())
                    .then(data => {
                        blokSelect.innerHTML = '<option value="">Select Blok</option>'; // Reset blok options
                        data.bloks.forEach(blok => {
                            const option = document.createElement('option');
                            option.value = blok.blok_id;
                            option.textContent = blok.nama_blok;
                            blokSelect.appendChild(option);
                        });

                        // Auto-fill fields after fetching blocks
                        autoFillFields();
                    })
                    .catch(error => console.error('Error fetching blocks:', error));
            } else {
                blokSelect.innerHTML = '<option value="">Select Blok</option>'; // Reset blok if no PT selected
            }
        }

        // Function to auto-fill the fields when a blok is selected
        function autoFillFields() {
            const ptEstateId = document.getElementById('pt_estate').value;
            const blokId = document.getElementById('blok_id').value;

            if (ptEstateId && blokId) {
                fetch(
                        `<?= base_url('identifikasi-tanaman/getHectareStatementByPtEstateIdAndBlockId'); ?>/${ptEstateId}/${blokId}`
                    )
                    .then(response => response.json())
                    .then(data => {
                        if (data) {
                            document.getElementById('tahun_tanam').value = data.tahun_tanam;
                            document.getElementById('bulan_tanam').value = data.bulan_tanam;
                            document.getElementById('luas_tanah').value = data.luas_tanah;
                            document.getElementById('varian_bibit').value = data.varian_bibit;
                            document.getElementById('week').value = data.week; // Auto-fill week
                        }
                    })
                    .catch(error => console.error('Error fetching hectare statement data:', error));
            }
        }

        function autoFillTitikTanam() {
            const noTitikTanam = document.getElementById('no_titik_tanam').value; // For seleksi
            const noTitikTanamShooting = document.getElementById('no_titik_tanam_shooting').value; // For shooting
            const ptEstateId = document.getElementById('pt_estate').value;
            const blokId = document.getElementById('blok_id').value;

            const longitudeField = document.getElementById('longitude');
            const latitudeField = document.getElementById('latitude');
            const longitudeFieldShooting = document.getElementById('longitude_shooting');
            const latitudeFieldShooting = document.getElementById('latitude_shooting');

            // Determine which value to use for 'no_titik_tanam'
            const noTitikTanamToUse = noTitikTanamShooting || noTitikTanam; // If shooting, use 'no_titik_tanam_shooting'

            // If shooting activity, use the shooting fields
            if (noTitikTanamShooting) {
                longitudeFieldShooting.readOnly = false; // Make longitude and latitude editable for shooting
                latitudeFieldShooting.readOnly = false;
            }

            // If seleksi activity, use the seleksi fields
            if (noTitikTanamToUse && ptEstateId && blokId) {
                fetch(
                        `<?= base_url('identifikasi-tanaman/getNoTitikTanamData'); ?>/${noTitikTanamToUse}/${ptEstateId}/${blokId}`
                    )
                    .then(response => response.json())
                    .then(data => {
                        if (data.found) {
                            // For seleksi activity, auto-fill longitude and latitude
                            if (!noTitikTanamShooting) {
                                longitudeField.value = data.longitude;
                                latitudeField.value = data.latitude;
                                longitudeField.readOnly = true;
                                latitudeField.readOnly = true;
                            } else {
                                // For shooting activity, auto-fill longitude and latitude
                                longitudeFieldShooting.value = data.longitude;
                                latitudeFieldShooting.value = data.latitude;
                                longitudeFieldShooting.readOnly = true;
                                latitudeFieldShooting.readOnly = true;
                            }

                            autoFillStatus();
                        } else {
                            if (!noTitikTanamShooting) {
                                longitudeField.value = ''; // Reset longitude if not found
                                latitudeField.value = ''; // Reset latitude if not found
                                longitudeField.readOnly = false;
                                latitudeField.readOnly = false;
                            }
                            console.log('No data found for No Titik Tanam:', noTitikTanamToUse);
                            autoFillStatus();
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching No Titik Tanam data:', error);
                        autoFillStatus();
                    });
            } else {
                if (!noTitikTanamShooting) {
                    longitudeField.readOnly = false;
                    latitudeField.readOnly = false;
                    longitudeField.value = '';
                    latitudeField.value = '';
                    autoFillStatus();
                }
            }
        }

        // Function to auto-fill the status dropdown when No Titik Tanam is provided
        function autoFillStatus() {
            const noTitikTanam = document.getElementById('no_titik_tanam').value;
            const statusSelect = document.getElementById('status');
            const ptEstateId = document.getElementById('pt_estate').value;
            const blokId = document.getElementById('blok_id').value;
            const longitudeTanam = document.getElementById('longitude').value;
            const latitudeTanam = document.getElementById('latitude').value;

            if (noTitikTanam && ptEstateId && blokId && longitudeTanam && latitudeTanam) {
                fetch(
                        `<?= base_url('identifikasi-tanaman/getTanamanStatus'); ?>/${noTitikTanam}/${ptEstateId}/${blokId}/${longitudeTanam}/${latitudeTanam}`
                    )
                    .then(response => response.json())
                    .then(data => {
                        // Clear existing options before adding new ones
                        statusSelect.innerHTML = '<option value="">Pilih Status</option>';

                        if (data.success) {
                            data.statusOptions.forEach(status => {
                                const option = document.createElement('option');
                                option.value = status.value;
                                option.textContent = status.label;
                                statusSelect.appendChild(option);
                            });
                        } else {
                            alert(data.error); // Show error if no status is found
                        }
                    })
                    .catch(error => console.error('Error fetching status options:', error));
            } else {
                // Fetch all statuses from the Master Status table
                fetch('<?= base_url('master-status/data'); ?>')
                    .then(response => response.json())
                    .then(data => {
                        // Clear existing options before adding new ones
                        statusSelect.innerHTML = '<option value="">Pilih Status</option>';

                        if (data.status) { // Check if 'status' is available in the response
                            // Loop through all statuses and add them as options
                            data.status.forEach(
                                status => { // Change to data.status because the response key is 'status'
                                    const option = document.createElement('option');
                                    // Check if the status is 'pc' regardless of case
                                    if (status.nama_status.toLowerCase() === 'pc') {
                                        option.value = status.status_id; // Set the value as status_id
                                        option.textContent = status
                                            .nama_status; // Display the nama_status as the label
                                        statusSelect.appendChild(option);
                                    }
                                });
                        } else {
                            alert('No statuses found'); // Show error if no statuses are found
                        }
                    })
                    .catch(error => console.error('Error fetching master statuses:', error));
            }
        }

        function fetchSisterData() {
            const noTitikTanam = document.getElementById('no_titik_tanam').value;
            const ptEstateId = document.getElementById('pt_estate').value;
            const blokId = document.getElementById('blok_id').value;

            // Ensure all necessary parameters are provided
            if (noTitikTanam && ptEstateId && blokId) {
                const params = new URLSearchParams({
                    noTitikTanam: noTitikTanam,
                    ptEstateId: ptEstateId,
                    blokId: blokId
                });

                fetch(`<?= base_url('identifikasi-tanaman/fetchSister'); ?>?${params}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('sister_ke').value = data.sister;
                        } else {
                            console.warn('fetchSister error:', data.error);
                        }
                    })
                    .catch(err => console.error('Error fetching sister data:', err));
            } else {
                console.warn('Missing parameters for fetching sister data.');
            }
        }

        // 1. Fetch data when block is selected (basic hectare statement data)
        document.getElementById('blok_id').addEventListener('change', autoFillFields);

        // 2. Populate longitude and latitude when No Titik Tanam changes
        document.getElementById('no_titik_tanam').addEventListener('change', autoFillTitikTanam);
        // Add event listener to No Titik Tanam for Shooting activity
        document.getElementById('no_titik_tanam_shooting').addEventListener('change', function() {
            autoFillTitikTanam(); // Fetch the data when shooting input changes
        });

        // 3. (Inside autoFillTitikTanam) Auto-fill status after longitude and latitude are populated
        //    (autoFillStatus is called from inside autoFillTitikTanam now)

        // 4. Fetch sister data when latitude, longitude, or No Titik Tanam change (after basic data is ready)
        document.getElementById('no_titik_tanam').addEventListener('blur', fetchSisterData);

        // Separate function for Seleksi activity submission
        function insertTanamanDataSeleksi() {
            const formData = new FormData(document.querySelector('form'));

            // Get the selected 'aktivitas_id' and 'status_id' from dropdowns
            const aktivitasSelect = document.getElementById('tipe_aktivitas');
            const statusSelect = document.getElementById('status');

            // Get the IDs (aktivitas_id and status_id)
            const aktivitasId = aktivitasSelect.value;
            const statusId = statusSelect.value;

            // Append 'aktivitas_id' and 'status_id' to the formData
            formData.append('tipe_aktivitas', aktivitasId); // Send the ID instead of the name
            formData.append('status', statusId); // Send the ID instead of the name

            // Send the form data to the backend for Seleksi
            fetch('<?= base_url('identifikasi-tanaman/insertTanamanDataSeleksi'); ?>', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        // Save selected values before refreshing
                        const ptEstateSelect = document.getElementById('pt_estate');
                        const blokSelect = document.getElementById('blok_id');

                        const ptEstateValue = ptEstateSelect.value;
                        const blokValue = blokSelect.value;

                        // Refresh the page while preserving the selected values
                        window.location.reload();

                        // After page reload, we need to restore the selected values
                        ptEstateSelect.value = ptEstateValue;
                        blokSelect.value = blokValue;
                    } else {
                        alert(data.error || 'An error occurred while inserting Seleksi data');
                    }
                })
                .catch(error => console.error('Error inserting Seleksi data:', error));
        }

        // Separate function for Shooting activity submission
        function insertTanamanDataShooting() {
            const formData = new FormData(document.querySelector('form'));

            // Ensure that required fields are not empty before proceeding
            const noTitikTanam = document.getElementById('no_titik_tanam_shooting').value;
            const ptEstateId = document.getElementById('pt_estate').value;
            const blokId = document.getElementById('blok_id').value;

            if (!noTitikTanam || !ptEstateId || !blokId) {
                alert("Please ensure all required fields are filled.");
                return;
            }

            // Append the necessary data to formData
            formData.append('no_titik_tanam', noTitikTanam);
            formData.append('pt_estate', ptEstateId);
            formData.append('blok_id', blokId);

            // Send the form data to the backend for Shooting
            fetch('<?= base_url('identifikasi-tanaman/insertTanamanDataShooting'); ?>', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        // Refresh or reset page as needed
                        window.location.reload();
                    } else {
                        alert(data.error || 'An error occurred while inserting Shooting data');
                    }
                })
                .catch(error => {
                    console.error('Error inserting Shooting data:', error);
                    alert('An error occurred while submitting the form. Please try again.');
                });
        }

        // Add event listener to the Seleksi submit button
        document.getElementById('submit-seleksi').addEventListener('click', function() {
            insertTanamanDataSeleksi(); // Call the Seleksi insert function
        });

        // Add event listener to the Shooting submit button
        document.getElementById('submit-shooting').addEventListener('click', function() {
            insertTanamanDataShooting(); // Call the Shooting insert function
        });

        // Function to toggle visibility of cards based on activity type
        function toggleCards() {
            const tipeAktivitasSelect = document.getElementById('tipe_aktivitas');
            const tipeAktivitasText = tipeAktivitasSelect.options[tipeAktivitasSelect.selectedIndex].text.toLowerCase();
            const seleksiCard = document.getElementById('identifikasi-tanaman-card');
            const shootingCard = document.getElementById('shooting-card');
            const rfidField = document.getElementById('rfid');
            const hectareStatementCard = document.getElementById('hectare-statement-card');

            // Show/Hide cards based on activity type
            if (tipeAktivitasText === 'seleksi') {
                rfidField.disabled = true; // Disable the RFID input field
                rfidField.value = ''; // Clear RFID field
                hectareStatementCard.style.display = 'block';
                seleksiCard.style.display = 'block';
                shootingCard.style.display = 'none';
            } else if (tipeAktivitasText === 'shooting') {
                hectareStatementCard.style.display = 'block';
                seleksiCard.style.display = 'none';
                shootingCard.style.display = 'block';
                autoFillTitikTanam();
                autoFillTanamanData();
            } else {
                seleksiCard.style.display = 'none';
                shootingCard.style.display = 'none';
            }
        }

        function autoFillTanamanData() {
            const noTitikTanam = document.getElementById('no_titik_tanam_shooting').value;
            const ptEstateId = document.getElementById('pt_estate').value; // Get PT Estate ID
            const blokId = document.getElementById('blok_id').value; // Get Blok ID
            const tipeAktivitas = document.getElementById('tipe_aktivitas').value; // Get Tipe Aktivitas
            const tanamanContainer = document.getElementById('tanaman-container-shooting');

            // Log to ensure that the correct values are being passed
            console.log('no_titik_tanam:', noTitikTanam);
            console.log('pt_estate_id:', ptEstateId);
            console.log('blok_id:', blokId);
            console.log('tipe_aktivitas:', tipeAktivitas);

            if (noTitikTanam && ptEstateId && blokId && tipeAktivitas) {
                // Show loading indicator
                tanamanContainer.innerHTML = 'Loading...';

                // Create the URL with the additional parameters
                const url =
                    `<?= base_url('identifikasi-tanaman/getActiveTanamanDataSeleksi'); ?>/${noTitikTanam}?pt_estate_id=${ptEstateId}&blok_id=${blokId}&tipe_aktivitas=${tipeAktivitas}`;

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            tanamanContainer.innerHTML = ''; // Clear loading text
                            data.tanaman.forEach((tanaman, index) => {
                                const formHtml = `
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tanaman ${index + 1}</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>RFID</label>
                        <input type="text" class="form-control" name="rfid_tanaman[${index}]"
                            id="rfid_tanaman_${index}" value="${tanaman.rfid_tanaman}" readonly />
                        <input type="hidden" name="tanaman_id[${index}]" value="${tanaman.tanaman_id}" />
                    </div>
                    <div class="form-group">
                        <label>Perbarui RFID?</label>
                        <input type="checkbox" class="form-check-input" id="update_rfid_${index}" name="update_rfid[${index}]" onchange="toggleNewRfid(${index})" />
                        <div id="updateRfidFields_${index}" style="display: none;">
                            <input type="text" class="form-control" name="new_rfid[${index}]"
                                id="update_rfid_tanaman_${index}" placeholder="Masukkan RFID baru" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Sister</label>
                        <input type="text" class="form-control" name="sister[${index}]"
                            id="sister_${index}" value="${tanaman.sister}" readonly />
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <input type="text" class="form-control" name="status[${index}]"
                            id="status_${index}" value="${tanaman.nama_status}" readonly />
                    </div>
                </div>
            </div>`;
                                tanamanContainer.innerHTML += formHtml;
                            });
                        } else {
                            tanamanContainer.innerHTML = 'Tidak ada tanaman aktif ditemukan.';
                        }
                    })
                    .catch(error => {
                        console.error('Gagal mengambil data tanaman aktif:', error);
                        tanamanContainer.innerHTML = 'Terjadi kesalahan saat mengambil data tanaman.';
                    });
            }
        }

        // Toggle RFID input field visibility for Shooting Activity
        function toggleNewRfid(index) {
            const checkbox = document.getElementById(`update_rfid_${index}`);
            const newRfidFields = document.getElementById(`updateRfidFields_${index}`);
            newRfidFields.style.display = checkbox.checked ? 'block' : 'none';
        }

        // Add event listener to No Titik Tanam for Shooting activity
        document.getElementById('no_titik_tanam_shooting').addEventListener('change', function() {
            if (!this.value) {
                document.getElementById('tanaman-container-shooting').innerHTML = ''; // Clear the container
            }
            autoFillTanamanData(); // Fetch tanaman data
        });

        // Call toggleCards on page load to check the initial state
        document.addEventListener('DOMContentLoaded', function() {
            toggleCards(); // Ensure the correct card is visible based on selected activity
        });
    </script>

    <script>
        // once the DOM is ready...
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('form-identifikasi-tanaman-baru');
            form.addEventListener('keydown', function(e) {
                // if the key is Enter, prevent its default behavior
                if (e.key === 'Enter' || e.keyCode === 13) {
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>
</body>

</html>