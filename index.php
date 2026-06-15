<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Perhitungan Ojek Online</title>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px;
            color: #333;
        }

        h1 {
            color: #ffc107;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        form {
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 400px;
        }

        label {
            font-weight: 600;
            font-size: 14px;
            display: block;
            margin-bottom: 5px;
        }

        input, select {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            box-sizing: border-box;
            background: #fafafa;
        }

        button {
            background-color: #ffc107;
            color: #000;
            border: none;
            padding: 14px;
            width: 100%;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            border-radius: 6px;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: #e0a800;
        }

        .hasil {
            background: #ffffff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            margin-top: 30px;
            width: 100%;
            max-width: 400px;
            border-left: 6px solid #ffc107;
        }

        .error {
            background: #ffebee;
            color: #c62828;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #ef9a9a;
        }
    </style>
</head>
<body>

<h1>Sistem Ojek Online</h1>

<form action="" method="POST">
    <label>Nama Pelanggan:</label>
    <input type="text" name="Nama_Pelanggan" placeholder="Nama Lengkap" value="<?php echo isset($_POST['Nama_Pelanggan']) ? htmlspecialchars($_POST['Nama_Pelanggan']) : ''; ?>" required>

    <label>No HP:</label>
    <input type="number" name="No_HP" placeholder="08..." value="<?php echo isset($_POST['No_HP']) ? htmlspecialchars($_POST['No_HP']) : ''; ?>" required>

    <label>Jarak Tempuh (km):</label>
    <input type="number" name="Jarak_Tempuh" step="0.1" placeholder="Contoh: 5.5" value="<?php echo isset($_POST['Jarak_Tempuh']) ? htmlspecialchars($_POST['Jarak_Tempuh']) : ''; ?>" required>

    <label>Kode Voucher:</label>
    <input type="text" name="Kode_Voucher" placeholder="Opsional" value="<?php echo isset($_POST['Kode_Voucher']) ? htmlspecialchars($_POST['Kode_Voucher']) : ''; ?>">

    <label>Jenis Layanan:</label>
    <select name="Jenis_Layanan">
        <option value="Goride Reguler">Goride Reguler</option>
        <option value="Goride Prioritas">Goride Prioritas</option>
        <option value="Gocar">Gocar</option>
        <option value="Gocar XL">Gocar XL</option>
        <option value="Gofood">Gofood</option>
    </select>

    <label>Metode Pembayaran:</label>
    <select name="Metode_Pembayaran">
        <option value="cash">Cash</option>
        <option value="ewallet">E-Wallet</option>
        <option value="transfer_bank">Transfer Bank</option>
    </select>

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
