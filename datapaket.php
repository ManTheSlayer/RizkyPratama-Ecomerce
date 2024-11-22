<?php
$ekspedisi = $_POST["ekspedisi"];
$distrik = $_POST["distrik"];
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.rajaongkir.com/starter/cost",
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "origin=54&destination=" . $distrik . "&weight=1200&courier=" . $ekspedisi,
    CURLOPT_HTTPHEADER => array(
        "content-type: application/x-www-form-urlencoded",
        "key: 358f0f3fba423456c308d638aa74550e"
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    $array_response = json_decode($response, TRUE);
    $results = $array_response["rajaongkir"]["results"];

    echo "<option value=''>--Pilih Paket--</option>";

    foreach ($results as $result) {
        $courier_name = strtoupper($result["name"]);

        // Singkatan ekspedisi
        $courier = getCourierShortName($courier_name);

        foreach ($result["costs"] as $paket) {
            $service = $paket["service"]; // Nama layanan
            $cost = $paket["cost"][0]["value"]; // Ongkir
            $etd = $paket["cost"][0]["etd"]; // Estimasi waktu
            $description = getServiceDescription($service); // Keterangan layanan

            echo "<option 
                courier='{$courier}' 
                paket='{$service}' 
                ongkir='{$cost}' 
                etd='{$etd}' 
                description='{$description}'>";
            echo "{$courier} - {$service} (Rp " . number_format($cost) . ", Estimasi: {$etd} hari, {$description})";
            echo "</option>";
        }
    }
}

/**
 * Fungsi untuk mendapatkan singkatan nama ekspedisi.
 * @param string $courier_name Nama lengkap ekspedisi.
 * @return string Singkatan ekspedisi.
 */
function getCourierShortName($courier_name)
{
    $couriers = [
        "JNE EXPRESS" => "JNE",
        "POS INDONESIA" => "POS",
        "TIKI" => "TIKI"
    ];
    return $couriers[$courier_name] ?? substr($courier_name, 0, 3);
}

/**
 * Fungsi untuk menambahkan keterangan layanan.
 * @param string $service Nama layanan.
 * @return string Keterangan detail layanan.
 */
function getServiceDescription($service)
{
    // Daftar layanan standar
    $descriptions = [
        "REG" => "Regular service, ongkir standar dengan waktu pengiriman normal.",
        "OKE" => "Ongkos Kirim Ekonomis, biaya lebih murah dengan waktu lebih lama.",
        "YES" => "Yakin Esok Sampai, prioritas dengan estimasi pengiriman 1 hari.",
        "EXPRESS" => "Layanan cepat untuk barang penting dan darurat.",
        "CARGO" => "Layanan untuk pengiriman barang berat atau besar.",
        "CTC" => "City to City, pengiriman antar kota tanpa layanan door-to-door.",
        "JTR" => "JNE Trucking, pengiriman barang besar atau banyak dengan truk.",
        "CTCYES" => "City to City Yes, pengiriman antar kota dengan layanan ekspres.",
        "SDS" => "Same Day Service, pengiriman pada hari yang sama di dalam kota.",
        "ONS" => "Over Night Service, pengiriman dengan estimasi sampai keesokan hari.",
        "ECO" => "Economy, layanan pengiriman ekonomis dengan estimasi waktu lebih lama.",
        "EMS" => "Express Mail Service, pengiriman internasional dengan prioritas.",
        "HDS" => "Holiday Delivery Service, pengiriman pada hari libur atau akhir pekan."
    ];

    // Keterangan default
    return $descriptions[$service] ?? "Layanan khusus dengan ketentuan berbeda.";
}
?>