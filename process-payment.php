<?php
// process-payment.php - Inahifadhi kwenye SQL na kuunganisha na PesaPal Sandbox API

$host = "localhost";
$dbname = "dalalifasta_db";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $fullName = trim($_POST['full_name'] ?? '');
        $accountNumber = trim($_POST['account_number'] ?? '');
        $provider = trim($_POST['provider'] ?? '');
        $expiry = trim($_POST['expiry'] ?? '');
        $cvv = trim($_POST['cvv'] ?? '');

        if (empty($fullName) || empty($accountNumber) || empty($provider)) {
            die("<div style='font-family:Poppins; text-align:center; margin-top:50px;'><h3 style='color:red;'>Tafadhali jaza taarifa zote zinazohitajika.</h3><br><a href='payment.html'>Rudi Nyuma</a></div>");
        }

        // 1. Hifadhi kwanza kwenye SQL Database ya ndani
        $sql = "INSERT INTO payments (full_name, account_number, provider, expiry, cvv, created_at) 
                VALUES (:full_name, :account_number, :provider, :expiry, :cvv, NOW())";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':full_name' => $fullName,
            ':account_number' => $accountNumber,
            ':provider' => $provider,
            ':expiry' => $expiry,
            ':cvv' => $cvv
        ]);

        // 2. Taarifa za PesaPal Sandbox (Tanzania Merchant uliyozipata)
        $consumerKey = "ngW+UEcndHltUc5fxPfrCD987xMh3Lx8";
        $consumerSecret = "q27RChyS5UkypdcNYKzuUw460Dg=";
        $pesapalUrl = "https://cybqa.pesapal.com/pesapalv3/api/Auth/RequestToken"; // API ya kupata Token ya PesaPal

        $credentials = array(
            "consumer_key" => $consumerKey,
            "consumer_secret" => $consumerSecret
        );

        // Kuomba Token kupitia cURL kwenda PesaPal
        $ch = curl_init($pesapalUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($credentials));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json'
        ));

        $response = curl_exec($ch);
        curl_close($ch);
        
        $result = json_decode($response, true);

        // Ujumbe wa mafanikio kwa mteja
        ?>
        <!DOCTYPE html>
        <html lang="sw">
        <head>
            <meta charset="UTF-8">
            <title>Malipo Yamewasilishwa - DalaliFasta</title>
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
            <style>
                body { font-family: 'Poppins', sans-serif; background: #f4f7f6; display: grid; place-items: center; min-height: 100vh; margin: 0; }
                .success-card { background: #fff; padding: 35px; border-radius: 24px; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.08); max-width: 420px; width: 100%; }
                .icon { width: 60px; height: 60px; background: #e3f4f1; color: #0e8f80; border-radius: 50%; display: grid; place-items: center; font-size: 28px; margin: 0 auto 16px; font-weight: bold; }
                h2 { font-size: 20px; color: #14201e; margin-bottom: 8px; }
                p { font-size: 13.5px; color: #6b7a77; line-height: 1.5; margin-bottom: 24px; }
                .btn { display: inline-block; background: #0e8f80; color: #fff; padding: 12px 24px; border-radius: 999px; text-decoration: none; font-weight: 600; font-size: 14px; box-shadow: 0 6px 18px rgba(14,143,128,0.25); }
                .btn:hover { background: #0a6b60; }
            </style>
        </head>
        <body>
            <div class="success-card">
                <div class="icon">✓</div>
                <h2>Taarifa Zimerekodiwa!</h2>
                <p>Ombi lako limewasilishwa kikamilifu na limerekodiwa kwenye mfumo kupitia <b>PesaPal Sandbox</b> kwa ajili ya <b><?php echo strtoupper(htmlspecialchars($provider)); ?></b>.</p>
                <a href="index.html" class="btn">Rudi Nyumbani</a>
            </div>
        </body>
        </html>
        <?php
    }
} catch (PDOException $e) {
    echo "<div style='font-family:Poppins; text-align:center; margin-top:50px;'><h3 style='color:red;'>Hitilafu ya Mfumo wa SQL: " . htmlspecialchars($e->getMessage()) . "</h3><br><a href='payment.html'>Jaribu Tena</a></div>";
}
?>