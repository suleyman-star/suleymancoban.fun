# 💼 Portfolyo İletişim Formu

Bu proje, PHP ile hazırlanmış modern bir portfolyo sitesinde yer alan gelişmiş bir iletişim formu sistemidir. Kullanıcıdan alınan bilgiler hem e-posta yoluyla hem de Discord Webhook üzerinden iletilir. Aynı zamanda kullanıcının IP adresi ve konumu da otomatik olarak tespit edilerek kaydedilir.

## 🚀 Özellikler

- PHP tabanlı iletişim formu
- HTML formatlı e-posta gönderimi (PHPMailer kullanılarak)
- Discord Webhook ile anlık bildirim
- Kullanıcı IP ve konum tespiti (ip-api.com üzerinden)
- Gelişmiş hata yönetimi ve kullanıcı dostu uyarılar
- Güvenlik önlemleri (XSS koruması, e-posta doğrulama vs.)

## 🧰 Kullanılan Teknolojiler

- PHP 7+
- [PHPMailer](https://github.com/PHPMailer/PHPMailer)
- Discord Webhook API
- [ip-api.com](http://ip-api.com/) (IP konum verisi için)
- HTML/CSS (Responsive e-posta görünümü için)

## 📝 Kurulum

1. Projeyi klonlayın:

   ```bash
   git clone https://github.com/suleyman-star/suleymancoban.fun.git
   ```

2. Gerekli dosyaları sunucunuza yükleyin.

3. `phpmailer/` klasörünün içerisinde PHPMailer dosyalarının bulunduğuna emin olun.

4. `index.html` dosyasında form alanlarını kullanıma açın ve `send_mail.php` dosyasına yönlendirdiğinizden emin olun.

5. `send_mail.php` içindeki şu alanları kendinize göre düzenleyin:

   ```php
   $mail->Username   = 'gmailadresiniz@gmail.com';
   $mail->Password   = 'gmail-uygulama-şifresi';
   $mail->setFrom('gmailadresiniz@gmail.com', 'Gönderici Adı');
   $mail->addAddress('nereye-gidecek@gmail.com');
   ```

6. Discord Webhook için:

   ```php
   $discordWebhookURL = 'https://discord.com/api/webhooks/...';
   ```

## 📩 Örnek E-posta Görünümü

E-posta HTML formatında aşağıdaki bilgileri içerir:

- Ad Soyad
- E-posta adresi
- Mesaj
- Gönderim tarihi
- IP adresi
- İl/İlçe bilgisi

## 📷 Ekran Görüntüsü

>  <img src="https://cdn.discordapp.com/attachments/1087817754041667595/1397217663393992704/suleymancoban.fun_.png?ex=6880ebda&is=687f9a5a&hm=1450a429cc6311940f656b58a58747bddbce91ec9f681aee3f7a8f239dfb241b&" alt="1"/>

## 🛡️ Güvenlik

- Giriş verileri temizleniyor (XSS koruması)
- E-posta formatı kontrol ediliyor
- IP bilgisi alınmadan önce hata kontrolü yapılıyor
- Discord mesajı 1000 karakterden fazlaysa otomatik kısaltılıyor

## ⚠️ Uyarı

`ip-api.com` ücretsiz bir servistir ve dakikada 45 sorgu limiti vardır. Daha profesyonel kullanım için ücretli bir IP lokasyon servisi tercih edebilirsiniz.

## 👨‍💻 Geliştirici

**Süleyman ÇOBAN**  
📧 suleymancoban647@gmail.com  
🌐 [suleymancoban.fun](https://suleymancoban.fun)

## 📄 Lisans

Bu proje MIT lisansı ile lisanslanmıştır.
