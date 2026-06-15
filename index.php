 <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Perhitungan Ojek Online</title>

    <style>
        body {
            background-color: #ffffff;
            color: #000000;
            font-family: sans-serif;
            text-align: center;
            padding: 20px;
        }
        form {
            display: inline-block;
            text-align: left;
        }
        button {
            background-color: green;
            color: white;
            border: none;
            padding: 6px 12px;
            cursor: pointer;
            width: 100%;
        }
        .hasil {
            display: inline-block;
            text-align: left;
            margin-top: 20px;
            border: 1px solid #000;
            padding: 15px;
        }
    </style>
</head>
<body>

<h1>Sistem Ojek Online</h1>

<form action="" method="POST">
    <p>
        <label>Nama Pelanggan:</label><br>
        <input type="text" id="nama" name="Nama_Pelanggan" placeholder="Masukin Nama Pelanggan" value="<?php echo isset($_POST['Nama_Pelanggan']) ? htmlspecialchars($_POST['Nama_Pelanggan']) : ''; ?>">
    </p>
    <p>
        <label>No HP:</label><br>
        <input type="number" id="no_hp" name="No_HP" placeholder="Masukkin No HP" value="<?php echo isset($_POST['No_HP']) ? htmlspecialchars($_POST['No_HP']) : ''; ?>">
    </p>
    <p>
        <label>Jarak Tempuh (km):</label><br>
        <input type="number" id="jarak" name="Jarak_Tempuh" step="0.1" placeholder="Masukkin Jarak Tempuh (km)" value="<?php echo isset($_POST['Jarak_Tempuh']) ? htmlspecialchars($_POST['Jarak_Tempuh']) : ''; ?>">
    </p>
    <p>
        <label>Kode Voucher:</label><br>
        <input type="text" id="voucher" name="Kode_Voucher" placeholder="Masukkin Kode Voucher" value="<?php echo isset($_POST['Kode_Voucher']) ? htmlspecialchars($_POST['Kode_Voucher']) : ''; ?>">
    </p>
    <p>
        <label>Jenis Layanan:</label><br>
        <select name="Jenis_Layanan">
            <option value="Goride Reguler" <?php echo (isset($_POST['Jenis_Layanan']) && $_POST['Jenis_Layanan'] == 'Goride Reguler') ? 'selected' : ''; ?>>Goride Reguler</option>
            <option value="Goride Prioritas" <?php echo (isset($_POST['Jenis_Layanan']) && $_POST['Jenis_Layanan'] == 'Goride Prioritas') ? 'selected' : ''; ?>>Goride Prioritas</option>
            <option value="Gocar" <?php echo (isset($_POST['Jenis_Layanan']) && $_POST['Jenis_Layanan'] == 'Gocar') ? 'selected' : ''; ?>>Gocar</option>
            <option value="Gocar XL" <?php echo (isset($_POST['Jenis_Layanan']) && $_POST['Jenis_Layanan'] == 'Gocar XL') ? 'selected' : ''; ?>>Gocar XL</option>
            <option value="Gofood" <?php echo (isset($_POST['Jenis_Layanan']) && $_POST['Jenis_Layanan'] == 'Gofood') ? 'selected' : ''; ?>>Gofood</option>
        </select>
    </p>
    <p>
        <label>Metode Pembayaran:</label><br>
        <select name="Metode_Pembayaran">
            <option value="cash" <?php echo (isset($_POST['Metode_Pembayaran']) && $_POST['Metode_Pembayaran'] == 'cash') ? 'selected' : ''; ?>>Cash</option>
            <option value="ewallet" <?php echo (isset($_POST['Metode_Pembayaran']) && $_POST['Metode_Pembayaran'] == 'ewallet') ? 'selected' : ''; ?>>E-Wallet</option>
            <option value="transfer_bank" <?php echo (isset($_POST['Metode_Pembayaran']) && $_POST['Metode_Pembayaran'] == 'transfer_bank') ? 'selected' : ''; ?>>Transfer Bank</option>
        </select>
    </p>

    <button type="submit">Hitung Biaya</button>
</form>

<?php
class User {
    public $nama;
    public $noHp;

    public function __construct($nama, $noHp) {
        $this->nama = $nama;
        $this->noHp = $noHp;
    }

    public function getNama() {
        return $this->nama;
    }
}

class Pelanggan extends User {
    public $poin;

    public function __construct($nama, $noHp, $poin) {
        parent::__construct($nama, $noHp);
        $this->poin = $poin;
    }

    public function tambahpoin($totalBayar) {
        $poinDiperoleh = floor($totalBayar / 10000);
        $this->poin = $poinDiperoleh;
        return $this->poin;
    }
}

class Layanan {
    public $jenisLayanan;
    public $tarifPerKm;

    public function __construct($jenisLayanan, $tarifPerKm) {
        $this->jenisLayanan = $jenisLayanan;
        $this->tarifPerKm = $tarifPerKm;
    }

    public function getTarif() {
        return $this->tarifPerKm;
    }

    public function getJenis() {
        return $this->jenisLayanan;
    }
}

class Voucher {
    public $kodeVoucher;
    public $diskonPersen;

    public function __construct($kodeVoucher) {
        $this->kodeVoucher = $kodeVoucher;

        if ($kodeVoucher == "HEMAT10") {
            $this->diskonPersen = 10;
        } else if ($kodeVoucher == "HEMAT20") {
            $this->diskonPersen = 20;
        } else if ($kodeVoucher == "HEMAT30") {
            $this->diskonPersen = 30;
        } else {
            $this->diskonPersen = 0;
        }
    }

    public function hitungDiskon($subtotal) {
        return $subtotal * ($this->diskonPersen / 100);
    }
}

class Pembayaran {
    public $pilihanUser;

    public function __construct($pilihanUser) {
        $this->pilihanUser = $pilihanUser;
    }

    public function getmetode() {
        if ($this->pilihanUser == "ewallet") { return "E-Wallet"; }
        else if ($this->pilihanUser == "transfer_bank") { return "Transfer Bank"; }
        else { return "Cash"; }
    }
}

class PembayaranKhusus extends Pembayaran {
    public $ewallet;
    public $transferBank;
    public $cash;

    public function __construct($ewallet, $transferBank, $cash,  $pilihanUser) {
        parent::__construct($pilihanUser);
        $this->ewallet = $ewallet;
        $this->transferBank = $transferBank;
        $this->cash = $cash;
    }


    public function getBiayaAdmin() {
        if ($this->pilihanUser == "ewallet") { return $this->ewallet; }
        else if ($this->pilihanUser == "transfer_bank") { return $this->transferBank; }
        else { return $this->cash; }
    }
}


class Transaksi {
    public $pelanggan;
    public $layanan;
    public $pembayaran;
    public $voucher;
    public $jarakTempuh;

    private static $totaltransaksi = 0;

    public function __construct($pelanggan, $layanan, $pembayaran, $voucher, $jarakTempuh) {
        $this->pelanggan = $pelanggan;
        $this->layanan = $layanan;
        $this->pembayaran = $pembayaran;
        $this->voucher = $voucher;
        $this->jarakTempuh = $jarakTempuh;
        self::$totaltransaksi++;
    }

    public static function gettotaltransaksi() {
        return self::$totaltransaksi;
    }

    public function hitungSubtotal() {
        return $this->jarakTempuh * $this->layanan->getTarif();
    }

    public function getStatusMember() {
        if ($this->hitungSubtotal() > 50000) {
            return "Member";
        }
        return "Bukan Member";
    }

    public function hitungDiskonMember() {
        $subtotal = $this->hitungSubtotal();
        if ($subtotal > 50000) { return $subtotal * 0.05; }
        return 0;
    }

    public function hitungDiskonVoucher() {
        if ($this->voucher != null) {
            return $this->voucher->hitungDiskon($this->hitungSubtotal());
        }
        return 0;
    }

    public function hitungTotal() {
        return $this->hitungSubtotal() - $this->hitungDiskonMember() - $this->hitungDiskonVoucher() + $this->pembayaran->getBiayaAdmin();
    }
}

if ($_POST) {
    $namaInput = $_POST['Nama_Pelanggan'];
    $noHpInput = $_POST['No_HP'];
    $jarakInput = $_POST['Jarak_Tempuh'];
    $error = "";

    if ($namaInput == "") {
        $error = "Nama tidak boleh kosong";
    } elseif (strlen($noHpInput) < 10) {
        $error = "Nomor HP minimal 10 digit";
    } elseif ($jarakInput == "" || floatval($jarakInput) <= 0) {
        $error = "Jarak harus lebih dari 0";
    }

    if ($error != "") {
        echo "<br><div class='error'>Error: $error</div>";
    } else {
        $tarifLayanan = 0;
        if ($_POST['Jenis_Layanan'] == "Goride Reguler") { $tarifLayanan = 2500; }
        if ($_POST['Jenis_Layanan'] == "Goride Prioritas") { $tarifLayanan = 3000; }
        if ($_POST['Jenis_Layanan'] == "Gocar") { $tarifLayanan = 4500; }
        if ($_POST['Jenis_Layanan'] == "Gocar XL") { $tarifLayanan = 6000; }
        if ($_POST['Jenis_Layanan'] == "Gofood") { $tarifLayanan = 2000; }

        $objPelanggan = new Pelanggan($namaInput, $noHpInput, 0);
        $objLayanan   = new Layanan($_POST['Jenis_Layanan'], $tarifLayanan);
        $objVoucher   = new Voucher($_POST['Kode_Voucher']);
        $objPembayaran = new PembayaranKhusus(1000, 2500, 0, $_POST['Metode_Pembayaran']);

        $hasil = new Transaksi($objPelanggan, $objLayanan, $objPembayaran, $objVoucher, floatval($jarakInput));

        ?>
        <br>
        <div class="hasil">
            <h3>Struk Pembayaran</h3>
            <p>Nama Pelanggan: <?php echo $hasil->pelanggan->getNama(); ?></p>
            <p>No HP Pelanggan: <?php echo $hasil->pelanggan->noHp; ?></p>
            <p>Status Pelanggan: <?php echo $hasil->getStatusMember(); ?></p>
            <p>Jenis Layanan yang Dipakai: <?php echo $hasil->layanan->getJenis(); ?></p>
            <p>Metode Pembayaran: <?php echo $hasil->pembayaran->getmetode(); ?></p>
            <p>Jarak Tempuh: <?php echo $hasil->jarakTempuh; ?> km</p>
            <hr>
            <p>Subtotal: Rp <?php echo number_format($hasil->hitungSubtotal(), 0, ',', '.'); ?></p>
            <p>Diskon Member: Rp <?php echo number_format($hasil->hitungDiskonMember(), 0, ',', '.'); ?></p>
            <p>Diskon Voucher: Rp <?php echo number_format($hasil->hitungDiskonVoucher(), 0, ',', '.'); ?></p>
            <p>Biaya Admin: Rp <?php echo number_format($hasil->pembayaran->getBiayaAdmin(), 0, ',', '.'); ?></p> <hr>
            <p>Total Bayar: Rp <?php echo number_format($hasil->hitungTotal(), 0, ',', '.'); ?></p>
            <p>Total Poin dapet : <?php echo $hasil->pelanggan->tambahpoin($hasil->hitungTotal()); ?> Poin</p>
            <hr>
            <p>Total Transaksi: <?php echo Transaksi::gettotaltransaksi(); ?></p>
        </div>
        <?php
    }
}
?>

</body>
</html>