![php](https://img.shields.io/github/languages/top/par274/depremler-api)
![license](https://img.shields.io/github/license/par274/depremler-api)

# Depremler API

Son depremleri anlık olarak çeker ve tablo, Json veya Xml formatlarında okunabilir. Projelerinizde API olarak kullanılabilir.

# Kurulum

### Gerekenler
- Composer
- PHP 7.4+

Dosyaları indirin veya Git ile kurulum yapın.

```
git clone https://github.com/par274/depremler-api
```

```
cd src
composer update
```

# Kullanım

Kodlarınızı eğer bu kütüphane içinde yazacaksanız, app.php dosyasındaki RenderPlatformDirect() fonksiyonu içine yazmalısınız.

```php
<?php
/**
 * Gelen veri Json veya XML olabilir. 
 * Api.php dosyasını inceleyin $output verisi Array olarak gelmekte.
 * index.php?json, index.php?xml şeklinde GET ile de çekebilirsiniz.
 * Native API olarakta kullanabilirsiniz.
 */
foreach ($data['data'] as $item)
{
    echo $item['date']['humanreadable']; //Okunabilen tarih damgası. 11 Ocak Çarşamba 2023, 00:51 gibi.
    echo $item['date']['date']; //Tarih.
    echo $item['date']['time']; //Saat.

    echo $item['geo']['full']; // Enlem, boylam.
    echo $item['geo']['latitude']; //Enlem.
    echo $item['geo']['longitude']; //Boylam.
    echo $item['geo']['google_maps'] //Google Haritalar linki.

    echo $item['depth']; //Derinlik (km).

    echo $item['ml']; //Şiddeti.

    echo $item['location']['full']; //Tam bölge adı.
    echo $item['location']['city']; //Şehir.
    echo $item['location']['state']; //Bölge/Eyalet.

    echo $item['accuracy']; //Doğruluk. İlksel veya revize belirtir.
}
?>
```

## Örnekler

Bunun için direkt Index çalıştırabilirsiniz veya [src/View/view.table.php](https://github.com/par274/depremler-api/blob/master/src/View/view.table.php) dosyasına göz atabilirsiniz. Bütün örnekler ve tablo tasarımı bu dosyadadır.

### Json çıktısı:

> http://siteadresiniz.com/?json

```json
{
  "status": "ok",
  "data": [
    {
      "date": {
        "humanreadable": "16 Ocak Pazartesi 2023, 04:16",
        "date": "16-01-2023",
        "time": "04:16"
      },
      "geo": {
        "full": "38.3487,27.2238",
        "latitude": "38.3487",
        "longitude": "27.2238",
        "google_maps": "https://www.google.com/maps?q=38.3487,27.2238&ll=38.3487,27.2238&z=12"
      },
      "depth": "6.7",
      "ml": "2.2",
      "location": {
        "full": "Gokdere-bornova (izmir)",
        "city": "Gokdere-bornova",
        "state": "Izmir"
      },
      "accuracy": "İlksel"
    }
  ]
}
```

### XML çıktısı

> http://siteadresiniz.com/?xml

```xml
<?xml version="1.0" encoding="utf-8"?>
<response>
	<status>ok</status>
	<data>
		<date>
			<humanreadable>16 Ocak Pazartesi 2023, 04:16</humanreadable>
			<date>16-01-2023</date>
			<time>04:16</time>
		</date>
		<geo>
			<full>38.3487,27.2238</full>
			<latitude>38.3487</latitude>
			<longitude>27.2238</longitude>
			<google_maps>https://www.google.com/maps?q=38.3487,27.2238&amp;ll=38.3487,27.2238&amp;z=12</google_maps>
		</geo>
		<depth>6.7</depth>
		<ml>2.2</ml>
		<location>
			<full>Gokdere-bornova (izmir)</full>
			<city>Gokdere-bornova</city>
			<state>Izmir</state>
		</location>
		<accuracy>İlksel</accuracy>
	</data>
</response>
```

# Hata çıktıları

Hata çıktıları cevaplardaki status dizisinde belirtilir ve cevabın tipi URL sorgusuna göre döner. URL'de xml varsa xml, değilse json dönecektir.

```json
{
  "status": "fail-response-error",
  "message": "The connection to the site could not be established."
}
```

```xml
<response>
    <status>fail-response-error</status>
    <message>
        The connection to the site could not be established.
    </message>
</response>
```

```
fail-response-error
The connection to the site could not be established.
```

# Altyapı

Projede Par2 adını verdiğim bir uygulama çatısının basit bir hali kullanılmıştır. Proje içinde özel template sınıfları var. Genel olarak Symfony komponentleri kullanılmıştır.

## Kullanılan komponentler

- [Guzzle/Guzzle](https://github.com/guzzle/guzzle)
- [Symfony/Serializer](https://symfony.com/doc/5.4/components/serializer.html)
- [Symfony/DomCrawler](https://symfony.com/doc/5.4/components/dom_crawler.html)
- [Nesbot/Carbon](https://carbon.nesbot.com/)
- Bootstrap 4 & DataTables & jQuery

# Lisans
Bu kütüphanenin lisansı MIT(MIT License)'dir. Satışı yapılamaz ancak kullanabilirsiniz.

Daha fazla bilgi için [bu dosyaya](https://github.com/par274/depremler-api/blob/master/license.md) göz atabilirsiniz.