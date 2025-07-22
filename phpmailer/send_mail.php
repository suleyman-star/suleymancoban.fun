<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

function getIpLocation($ip) {
    $url = "http://ip-api.com/json/" . $ip . "?fields=status,country,regionName,city";
    $response = @file_get_contents($url);
    if ($response === FALSE) {
        return null;
    }
    $data = json_decode($response, true);
    if ($data['status'] === 'success') {
        return $data;
    }
    return null;
}

function sendDiscordWebhook($webhookUrl, $embed) {
    $data = json_encode([
        "embeds" => [$embed]
    ], JSON_UNESCAPED_UNICODE);

    $ch = curl_init($webhookUrl);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        http_response_code(400);
        echo "Lütfen tüm alanları doldurun.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Geçerli bir e-posta adresi girin.";
        exit;
    }

    $ip = $_SERVER['REMOTE_ADDR'] ?? 'Bilinmiyor';
    $location = getIpLocation($ip);

    $date = date('d.m.Y H:i:s');

    $city = $location['city'] ?? 'Bilinmiyor';
    $region = $location['regionName'] ?? 'Bilinmiyor';
    $country = $location['country'] ?? 'Bilinmiyor';

    $htmlBody = "
    <html>
    <head>
      <style>
        body { font-family: Arial, sans-serif; background:#f4f4f4; color:#333; padding: 20px; }
        .container { background: #fff; padding: 20px; border-radius: 8px; }
        h2 { color: #0066cc; }
        .info { margin-bottom: 15px; }
        .label { font-weight: bold; color: #444; }
        .message { white-space: pre-wrap; background: #eee; padding: 10px; border-radius: 5px; }
        .footer { font-size: 0.8em; color: #888; margin-top: 20px; }
      </style>
    </head>
    <body>
      <div class='container'>
        <h2>Yeni İletişim Formu Mesajı</h2>
        <div class='info'><span class='label'>İsim:</span> " . htmlspecialchars($name) . "</div>
        <div class='info'><span class='label'>E-posta:</span> " . htmlspecialchars($email) . "</div>
        <div class='info'><span class='label'>Gönderim Tarihi:</span> $date</div>
        <div class='info'><span class='label'>Gönderen IP:</span> $ip</div>
        <div class='info'><span class='label'>Konum:</span> $city, $region, $country</div>
        <div class='info'><span class='label'>Mesaj:</span></div>
        <div class='message'>" . nl2br(htmlspecialchars($message)) . "</div>
        <div class='footer'>Bu mesaj otomatik olarak gönderilmiştir.</div>
      </div>
    </body>
    </html>
    ";

    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'Gmailiniz';
        $mail->Password   = 'Aplikasyon Şifreniz';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('Gmaliliniz', 'Portfolio Contact');
        $mail->addAddress('Gmaliliniz');

        $mail->isHTML(true);
        $mail->Subject = "suleymancoban.fun - Portfolyo Iletisim Formu - $name";
        $mail->Body    = $htmlBody;
        $mail->AltBody = "İsim: $name\nE-posta: $email\nGönderim Tarihi: $date\nGönderen IP: $ip\nKonum: $city, $region, $country\n\nMesaj:\n$message";

        $mail->addReplyTo($email);

        $mail->send();

        // Discord webhook URL'nizi buraya yazın
        $discordWebhookURL = 'DiscordWebhook_Kimliginiz';

        // Embed formatında mesaj yapısı
        $embed = [
            "title" => "Yeni Portfolyo İletişim Formu Mesajı",
            "color" => hexdec("00ffaa"),
            "fields" => [
                ["name" => "İsim", "value" => $name, "inline" => true],
                ["name" => "E-posta", "value" => $email, "inline" => true],
                ["name" => "Gönderim Tarihi", "value" => $date, "inline" => false],
                ["name" => "IP Adresi", "value" => $ip, "inline" => true],
                ["name" => "Konum", "value" => "$city, $region, $country", "inline" => true],
                ["name" => "Mesaj", "value" => strlen($message) > 1000 ? substr($message, 0, 997) . "..." : $message, "inline" => false],
            ],
            "footer" => [
                "text" => "suleymancoban.fun - Portfolyo İletişim Formu"
            ],
            "timestamp" => date(DATE_ISO8601)
        ];

        sendDiscordWebhook($discordWebhookURL, $embed);

        echo "Mesajınız başarıyla gönderildi. Teşekkürler!";
    } catch (Exception $e) {
        http_response_code(500);
        echo "Mesaj gönderilirken hata oluştu.";
    }
} else {
    http_response_code(405);
    echo "Yalnızca POST metodu desteklenmektedir.";
}
