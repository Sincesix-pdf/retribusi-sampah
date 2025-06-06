<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelurahanSeeder extends Seeder
{
    public function run(): void
    {
        $kelurahan = [
                // 1. Ampelgading
            ['nama' => 'Argoyuwono', 'kecamatan_id' => 1],
            ['nama' => 'Lebakharjo', 'kecamatan_id' => 1],
            ['nama' => 'Mulyoasri', 'kecamatan_id' => 1],
            ['nama' => 'Purwoharjo', 'kecamatan_id' => 1],
            ['nama' => 'Sidorenggo', 'kecamatan_id' => 1],
            ['nama' => 'Simojayan', 'kecamatan_id' => 1],
            ['nama' => 'Sonowangi', 'kecamatan_id' => 1],
            ['nama' => 'Tamanasri', 'kecamatan_id' => 1],
            ['nama' => 'Tamansari', 'kecamatan_id' => 1],
            ['nama' => 'Tawangagung', 'kecamatan_id' => 1],
            ['nama' => 'Tirtomarto', 'kecamatan_id' => 1],
            ['nama' => 'Tirtomoyo', 'kecamatan_id' => 1],
            ['nama' => 'Wirotaman', 'kecamatan_id' => 1],

                // 2. Bantur
            ['nama' => 'Bandungrejo', 'kecamatan_id' => 2],
            ['nama' => 'Bantur', 'kecamatan_id' => 2],
            ['nama' => 'Karangsari', 'kecamatan_id' => 2],
            ['nama' => 'Pringgodani', 'kecamatan_id' => 2],
            ['nama' => 'Rejosari', 'kecamatan_id' => 2],
            ['nama' => 'Rejoyoso', 'kecamatan_id' => 2],
            ['nama' => 'Srigonco', 'kecamatan_id' => 2],
            ['nama' => 'Sumberbening', 'kecamatan_id' => 2],
            ['nama' => 'Wonokerto', 'kecamatan_id' => 2],
            ['nama' => 'Wonorejo', 'kecamatan_id' => 2],

                // 3. Bululawang
            ['nama' => 'Bakalan', 'kecamatan_id' => 3],
            ['nama' => 'Bululawang', 'kecamatan_id' => 3],
            ['nama' => 'Gading', 'kecamatan_id' => 3],
            ['nama' => 'Kasembon', 'kecamatan_id' => 3],
            ['nama' => 'Kasri', 'kecamatan_id' => 3],
            ['nama' => 'Krebet', 'kecamatan_id' => 3],
            ['nama' => 'Krebet Senggrong', 'kecamatan_id' => 3],
            ['nama' => 'Kuwolu', 'kecamatan_id' => 3],
            ['nama' => 'Lumbangsari', 'kecamatan_id' => 3],
            ['nama' => 'Pringu', 'kecamatan_id' => 3],
            ['nama' => 'Sempalwadak', 'kecamatan_id' => 3],
            ['nama' => 'Sudimoro', 'kecamatan_id' => 3],
            ['nama' => 'Sukonolo', 'kecamatan_id' => 3],
            ['nama' => 'Wandanpuro', 'kecamatan_id' => 3],

                // 4. Dampit
            ['nama' => 'Amadanom', 'kecamatan_id' => 4],
            ['nama' => 'Baturetno', 'kecamatan_id' => 4],
            ['nama' => 'Bumirejo', 'kecamatan_id' => 4],
            ['nama' => 'Jambangan', 'kecamatan_id' => 4],
            ['nama' => 'Majangtengah', 'kecamatan_id' => 4],
            ['nama' => 'Pamotan', 'kecamatan_id' => 4],
            ['nama' => 'Pojok', 'kecamatan_id' => 4],
            ['nama' => 'Rembun', 'kecamatan_id' => 4],
            ['nama' => 'Srimulyo', 'kecamatan_id' => 4],
            ['nama' => 'Sukodono', 'kecamatan_id' => 4],
            ['nama' => 'Sumbersuko', 'kecamatan_id' => 4],

                // 5. Dau
            ['nama' => 'Gadingkulon', 'kecamatan_id' => 5],
            ['nama' => 'Kalisongo', 'kecamatan_id' => 5],
            ['nama' => 'Karangwidoro', 'kecamatan_id' => 5],
            ['nama' => 'Kucur', 'kecamatan_id' => 5],
            ['nama' => 'Landungsari', 'kecamatan_id' => 5],
            ['nama' => 'Mulyoagung', 'kecamatan_id' => 5],
            ['nama' => 'Petungsewu', 'kecamatan_id' => 5],
            ['nama' => 'Selorejo', 'kecamatan_id' => 5],
            ['nama' => 'Sumbersekar', 'kecamatan_id' => 5],
            ['nama' => 'Tegalweru', 'kecamatan_id' => 5],
                // 6. Donomulyo
            ['nama' => 'Banjarejo', 'kecamatan_id' => 6],
            ['nama' => 'Donomulyo', 'kecamatan_id' => 6],
            ['nama' => 'Kedungsalam', 'kecamatan_id' => 6],
            ['nama' => 'Mentaraman', 'kecamatan_id' => 6],
            ['nama' => 'Purwodadi', 'kecamatan_id' => 6],
            ['nama' => 'Purworejo', 'kecamatan_id' => 6],
            ['nama' => 'Sumberoto', 'kecamatan_id' => 6],
            ['nama' => 'Tempursari', 'kecamatan_id' => 6],
            ['nama' => 'Tlogosari', 'kecamatan_id' => 6],
            ['nama' => 'Tulungrejo', 'kecamatan_id' => 6],

                // 7. Gedangan
            ['nama' => 'Gajahrejo', 'kecamatan_id' => 7],
            ['nama' => 'Gedangan', 'kecamatan_id' => 7],
            ['nama' => 'Girimulyo', 'kecamatan_id' => 7],
            ['nama' => 'Segaran', 'kecamatan_id' => 7],
            ['nama' => 'Sidodadi', 'kecamatan_id' => 7],
            ['nama' => 'Sindurejo', 'kecamatan_id' => 7],
            ['nama' => 'Sumberejo', 'kecamatan_id' => 7],
            ['nama' => 'Tumpakrejo', 'kecamatan_id' => 7],

                // 8. Gondanglegi
            ['nama' => 'Bulupitu', 'kecamatan_id' => 8],
            ['nama' => 'Ganjaran', 'kecamatan_id' => 8],
            ['nama' => 'Gondanglegi Kulon', 'kecamatan_id' => 8],
            ['nama' => 'Gondanglegi Wetan', 'kecamatan_id' => 8],
            ['nama' => 'Ketawang', 'kecamatan_id' => 8],
            ['nama' => 'Panggungrejo', 'kecamatan_id' => 8],
            ['nama' => 'Putat Kidul', 'kecamatan_id' => 8],
            ['nama' => 'Putat Lor', 'kecamatan_id' => 8],
            ['nama' => 'Putukrejo', 'kecamatan_id' => 8],
            ['nama' => 'Sepanjang', 'kecamatan_id' => 8],
            ['nama' => 'Sukorejo', 'kecamatan_id' => 8],
            ['nama' => 'Sukosari', 'kecamatan_id' => 8],
            ['nama' => 'Sumberjaya', 'kecamatan_id' => 8],
            ['nama' => 'Urek-Urek', 'kecamatan_id' => 8],

                // 9. Jabung
            ['nama' => 'Argosari', 'kecamatan_id' => 9],
            ['nama' => 'Gadingkembar', 'kecamatan_id' => 9],
            ['nama' => 'Gunung Jati', 'kecamatan_id' => 9],
            ['nama' => 'Jabung', 'kecamatan_id' => 9],
            ['nama' => 'Kemantren', 'kecamatan_id' => 9],
            ['nama' => 'Kemiri', 'kecamatan_id' => 9],
            ['nama' => 'Kenongo', 'kecamatan_id' => 9],
            ['nama' => 'Ngadirejo', 'kecamatan_id' => 9],
            ['nama' => 'Pandansari Lor', 'kecamatan_id' => 9],
            ['nama' => 'Sidomulyo', 'kecamatan_id' => 9],
            ['nama' => 'Sidorejo', 'kecamatan_id' => 9],
            ['nama' => 'Slamparejo', 'kecamatan_id' => 9],
            ['nama' => 'Sukolilo', 'kecamatan_id' => 9],
            ['nama' => 'Sukopuro', 'kecamatan_id' => 9],
            ['nama' => 'Taji', 'kecamatan_id' => 9],

                // 10. Kalipare
            ['nama' => 'Arjosari', 'kecamatan_id' => 10],
            ['nama' => 'Arjowilangun', 'kecamatan_id' => 10],
            ['nama' => 'Kaliasri', 'kecamatan_id' => 10],
            ['nama' => 'Kalipare', 'kecamatan_id' => 10],
            ['nama' => 'Kalirejo', 'kecamatan_id' => 10],
            ['nama' => 'Putukrejo', 'kecamatan_id' => 10],
            ['nama' => 'Sukowilangun', 'kecamatan_id' => 10],
            ['nama' => 'Sumberpetung', 'kecamatan_id' => 10],
            ['nama' => 'Tumpakrejo', 'kecamatan_id' => 10],
                // 11. Karangploso
            ['nama' => 'Ampeldento', 'kecamatan_id' => 11],
            ['nama' => 'Bocek', 'kecamatan_id' => 11],
            ['nama' => 'Donowarih', 'kecamatan_id' => 11],
            ['nama' => 'Girimoyo', 'kecamatan_id' => 11],
            ['nama' => 'Kepuharjo', 'kecamatan_id' => 11],
            ['nama' => 'Ngenep', 'kecamatan_id' => 11],
            ['nama' => 'Ngijo', 'kecamatan_id' => 11],
            ['nama' => 'Tawangargo', 'kecamatan_id' => 11],
            ['nama' => 'Tegalgondo', 'kecamatan_id' => 11],

                // 12. Kasembon
            ['nama' => 'Bayem', 'kecamatan_id' => 12],
            ['nama' => 'Kasembon', 'kecamatan_id' => 12],
            ['nama' => 'Pait', 'kecamatan_id' => 12],
            ['nama' => 'Pondokagung', 'kecamatan_id' => 12],
            ['nama' => 'Sukosari', 'kecamatan_id' => 12],
            ['nama' => 'Wonoagung', 'kecamatan_id' => 12],

                // 13. Kepanjen
            ['nama' => 'Curungrejo', 'kecamatan_id' => 13],
            ['nama' => 'Dilem', 'kecamatan_id' => 13],
            ['nama' => 'Jatirejoyoso', 'kecamatan_id' => 13],
            ['nama' => 'Jenggolo', 'kecamatan_id' => 13],
            ['nama' => 'Kedungpedaringan', 'kecamatan_id' => 13],
            ['nama' => 'Kemiri', 'kecamatan_id' => 13],
            ['nama' => 'Mangunrejo', 'kecamatan_id' => 13],
            ['nama' => 'Mojosari', 'kecamatan_id' => 13],
            ['nama' => 'Ngadilangkung', 'kecamatan_id' => 13],
            ['nama' => 'Panggungrejo', 'kecamatan_id' => 13],
            ['nama' => 'Sengguruh', 'kecamatan_id' => 13],
            ['nama' => 'Sukoraharjo', 'kecamatan_id' => 13],
            ['nama' => 'Talangagung', 'kecamatan_id' => 13],
            ['nama' => 'Tegalsari', 'kecamatan_id' => 13],

                // 14. Kromengan
            ['nama' => 'Jambuwer', 'kecamatan_id' => 14],
            ['nama' => 'Jatikerto', 'kecamatan_id' => 14],
            ['nama' => 'Karangrejo', 'kecamatan_id' => 14],
            ['nama' => 'Kromengan', 'kecamatan_id' => 14],
            ['nama' => 'Ngadirejo', 'kecamatan_id' => 14],
            ['nama' => 'Peniwen', 'kecamatan_id' => 14],
            ['nama' => 'Slorok', 'kecamatan_id' => 14],

                // 15. Lawang
            ['nama' => 'Bedali', 'kecamatan_id' => 15],
            ['nama' => 'Ketindan', 'kecamatan_id' => 15],
            ['nama' => 'Mulyoarjo', 'kecamatan_id' => 15],
            ['nama' => 'Sidodadi', 'kecamatan_id' => 15],
            ['nama' => 'Sidoluhur', 'kecamatan_id' => 15],
            ['nama' => 'Srigading', 'kecamatan_id' => 15],
            ['nama' => 'Sumberngepoh', 'kecamatan_id' => 15],
            ['nama' => 'Sumberporong', 'kecamatan_id' => 15],
            ['nama' => 'Turirejo', 'kecamatan_id' => 15],
            ['nama' => 'Wonorejo', 'kecamatan_id' => 15],
            ['nama' => 'Kalirejo', 'kecamatan_id' => 15],
            ['nama' => 'Lawang', 'kecamatan_id' => 15],
                // 16. Ngajum
            ['nama' => 'Babadan', 'kecamatan_id' => 16],
            ['nama' => 'Balesari', 'kecamatan_id' => 16],
            ['nama' => 'Banjarsari', 'kecamatan_id' => 16],
            ['nama' => 'Kesamben', 'kecamatan_id' => 16],
            ['nama' => 'Kranggan', 'kecamatan_id' => 16],
            ['nama' => 'Maguan', 'kecamatan_id' => 16],
            ['nama' => 'Ngajum', 'kecamatan_id' => 16],
            ['nama' => 'Ngasem', 'kecamatan_id' => 16],
            ['nama' => 'Palaan', 'kecamatan_id' => 16],

                // 17. Ngantang
            ['nama' => 'Banjarejo', 'kecamatan_id' => 17],
            ['nama' => 'Banturejo', 'kecamatan_id' => 17],
            ['nama' => 'Jombok', 'kecamatan_id' => 17],
            ['nama' => 'Kaumrejo', 'kecamatan_id' => 17],
            ['nama' => 'Mulyorejo', 'kecamatan_id' => 17],
            ['nama' => 'Ngantru', 'kecamatan_id' => 17],
            ['nama' => 'Pagersari', 'kecamatan_id' => 17],
            ['nama' => 'Pandansari', 'kecamatan_id' => 17],
            ['nama' => 'Purworejo', 'kecamatan_id' => 17],
            ['nama' => 'Sidodadi', 'kecamatan_id' => 17],
            ['nama' => 'Sumberagung', 'kecamatan_id' => 17],
            ['nama' => 'Tulungrejo', 'kecamatan_id' => 17],
            ['nama' => 'Waturejo', 'kecamatan_id' => 17],

                // 18. Pagak
            ['nama' => 'Gampingan', 'kecamatan_id' => 18],
            ['nama' => 'Pagak', 'kecamatan_id' => 18],
            ['nama' => 'Pandanrejo', 'kecamatan_id' => 18],
            ['nama' => 'Sempol', 'kecamatan_id' => 18],
            ['nama' => 'Sumberejo', 'kecamatan_id' => 18],
            ['nama' => 'Sumberkerto', 'kecamatan_id' => 18],
            ['nama' => 'Sumbermanjing Kulon', 'kecamatan_id' => 18],
            ['nama' => 'Tlogorejo', 'kecamatan_id' => 18],

                // 19. Pagelaran
            ['nama' => 'Balearjo', 'kecamatan_id' => 19],
            ['nama' => 'Banjarejo', 'kecamatan_id' => 19],
            ['nama' => 'Brongkal', 'kecamatan_id' => 19],
            ['nama' => 'Clumprit', 'kecamatan_id' => 19],
            ['nama' => 'Kademangan', 'kecamatan_id' => 19],
            ['nama' => 'Kanigoro', 'kecamatan_id' => 19],
            ['nama' => 'Karangsuko', 'kecamatan_id' => 19],
            ['nama' => 'Pagelaran', 'kecamatan_id' => 19],
            ['nama' => 'Sidorejo', 'kecamatan_id' => 19],
            ['nama' => 'Suwaru', 'kecamatan_id' => 19],

                // 20. Pakis
            ['nama' => 'Ampeldento', 'kecamatan_id' => 20],
            ['nama' => 'Asrikaton', 'kecamatan_id' => 20],
            ['nama' => 'Banjarejo', 'kecamatan_id' => 20],
            ['nama' => 'Bunutwetan', 'kecamatan_id' => 20],
            ['nama' => 'Kedungrejo', 'kecamatan_id' => 20],
            ['nama' => 'Mangliawan', 'kecamatan_id' => 20],
            ['nama' => 'Pakisjajar', 'kecamatan_id' => 20],
            ['nama' => 'Pakiskembar', 'kecamatan_id' => 20],
            ['nama' => 'Pucangsongo', 'kecamatan_id' => 20],
            ['nama' => 'Saptorenggo', 'kecamatan_id' => 20],
            ['nama' => 'Sekarpuro', 'kecamatan_id' => 20],
            ['nama' => 'Sukoanyar', 'kecamatan_id' => 20],
            ['nama' => 'Sumberkradenan', 'kecamatan_id' => 20],
            ['nama' => 'Sumberpasir', 'kecamatan_id' => 20],
            ['nama' => 'Tirtomoyo', 'kecamatan_id' => 20],
                // 21. Pakisaji
            ['nama' => 'Genengan', 'kecamatan_id' => 21],
            ['nama' => 'Glanggang', 'kecamatan_id' => 21],
            ['nama' => 'Jatisari', 'kecamatan_id' => 21],
            ['nama' => 'Karangduren', 'kecamatan_id' => 21],
            ['nama' => 'Karangpandan', 'kecamatan_id' => 21],
            ['nama' => 'Kebonagung', 'kecamatan_id' => 21],
            ['nama' => 'Kendalpayak', 'kecamatan_id' => 21],
            ['nama' => 'Pakisaji', 'kecamatan_id' => 21],
            ['nama' => 'Permanu', 'kecamatan_id' => 21],
            ['nama' => 'Sutojayan', 'kecamatan_id' => 21],
            ['nama' => 'Wadung', 'kecamatan_id' => 21],
            ['nama' => 'Wonokerso', 'kecamatan_id' => 21],

                // 22. Poncokusumo
            ['nama' => 'Argosuko', 'kecamatan_id' => 22],
            ['nama' => 'Belung', 'kecamatan_id' => 22],
            ['nama' => 'Dawuhan', 'kecamatan_id' => 22],
            ['nama' => 'Gubukklakah', 'kecamatan_id' => 22],
            ['nama' => 'Jambesari', 'kecamatan_id' => 22],
            ['nama' => 'Karanganyar', 'kecamatan_id' => 22],
            ['nama' => 'Karangnongko', 'kecamatan_id' => 22],
            ['nama' => 'Ngadas', 'kecamatan_id' => 22],
            ['nama' => 'Ngadireso', 'kecamatan_id' => 22],
            ['nama' => 'Ngebruk', 'kecamatan_id' => 22],
            ['nama' => 'Pajaran', 'kecamatan_id' => 22],
            ['nama' => 'Pandansari', 'kecamatan_id' => 22],
            ['nama' => 'Poncokusumo', 'kecamatan_id' => 22],
            ['nama' => 'Sumberejo', 'kecamatan_id' => 22],
            ['nama' => 'Wonomulyo', 'kecamatan_id' => 22],
            ['nama' => 'Wonorejo', 'kecamatan_id' => 22],
            ['nama' => 'Wringinanom', 'kecamatan_id' => 22],

                // 23. Pujon
            ['nama' => 'Bendosari', 'kecamatan_id' => 23],
            ['nama' => 'Madiredo', 'kecamatan_id' => 23],
            ['nama' => 'Ngabab', 'kecamatan_id' => 23],
            ['nama' => 'Ngroto', 'kecamatan_id' => 23],
            ['nama' => 'Pandesari', 'kecamatan_id' => 23],
            ['nama' => 'Pujon Kidul', 'kecamatan_id' => 23],
            ['nama' => 'Pujon Lor', 'kecamatan_id' => 23],
            ['nama' => 'Sukomulyo', 'kecamatan_id' => 23],
            ['nama' => 'Tawangsari', 'kecamatan_id' => 23],
            ['nama' => 'Wiyurejo', 'kecamatan_id' => 23],

                // 24. Singosari
            ['nama' => 'Ardimulyo', 'kecamatan_id' => 24],
            ['nama' => 'Banjararum', 'kecamatan_id' => 24],
            ['nama' => 'Baturetno', 'kecamatan_id' => 24],
            ['nama' => 'Dengkol', 'kecamatan_id' => 24],
            ['nama' => 'Gunungrejo', 'kecamatan_id' => 24],
            ['nama' => 'Klampok', 'kecamatan_id' => 24],
            ['nama' => 'Lang-Lang', 'kecamatan_id' => 24],
            ['nama' => 'Purwoasri', 'kecamatan_id' => 24],
            ['nama' => 'Randuagung', 'kecamatan_id' => 24],
            ['nama' => 'Tamanharjo', 'kecamatan_id' => 24],
            ['nama' => 'Toyomarto', 'kecamatan_id' => 24],
            ['nama' => 'Tunjungtirto', 'kecamatan_id' => 24],
            ['nama' => 'Watugede', 'kecamatan_id' => 24],
            ['nama' => 'Wonorejo', 'kecamatan_id' => 24],
            ['nama' => 'Candirenggo', 'kecamatan_id' => 24],
            ['nama' => 'Losari', 'kecamatan_id' => 24],
            ['nama' => 'Pagentan', 'kecamatan_id' => 24],

                // 25. Sumbermanjing Wetan
            ['nama' => 'Argotirto', 'kecamatan_id' => 25],
            ['nama' => 'Druju', 'kecamatan_id' => 25],
            ['nama' => 'Harjokuncaran', 'kecamatan_id' => 25],
            ['nama' => 'Kedungbanteng', 'kecamatan_id' => 25],
            ['nama' => 'Klepu', 'kecamatan_id' => 25],
            ['nama' => 'Ringinkembar', 'kecamatan_id' => 25],
            ['nama' => 'Ringinsari', 'kecamatan_id' => 25],
            ['nama' => 'Sekarbanyu', 'kecamatan_id' => 25],
            ['nama' => 'Sidoasri', 'kecamatan_id' => 25],
            ['nama' => 'Sitiarjo', 'kecamatan_id' => 25],
            ['nama' => 'Sumberagung', 'kecamatan_id' => 25],
            ['nama' => 'Sumbermanjing Wetan', 'kecamatan_id' => 25],
            ['nama' => 'Tambakasri', 'kecamatan_id' => 25],
            ['nama' => 'Tambakrejo', 'kecamatan_id' => 25],
            ['nama' => 'Tegalrejo', 'kecamatan_id' => 25],
                // 26. Sumberpucung
            ['nama' => 'Jatiguwi', 'kecamatan_id' => 26],
            ['nama' => 'Karangkates', 'kecamatan_id' => 26],
            ['nama' => 'Ngebruk', 'kecamatan_id' => 26],
            ['nama' => 'Sambigede', 'kecamatan_id' => 26],
            ['nama' => 'Senggreng', 'kecamatan_id' => 26],
            ['nama' => 'Sumberpucung', 'kecamatan_id' => 26],
            ['nama' => 'Ternyang', 'kecamatan_id' => 26],

                // 27. Tajinan
            ['nama' => 'Gunungronggo', 'kecamatan_id' => 27],
            ['nama' => 'Gunungsari', 'kecamatan_id' => 27],
            ['nama' => 'Jambearjo', 'kecamatan_id' => 27],
            ['nama' => 'Jatisari', 'kecamatan_id' => 27],
            ['nama' => 'Ngawonggo', 'kecamatan_id' => 27],
            ['nama' => 'Pandanmulyo', 'kecamatan_id' => 27],
            ['nama' => 'Purwosekar', 'kecamatan_id' => 27],
            ['nama' => 'Randugading', 'kecamatan_id' => 27],
            ['nama' => 'Sumbersuko', 'kecamatan_id' => 27],
            ['nama' => 'Tajinan', 'kecamatan_id' => 27],
            ['nama' => 'Tambakasri', 'kecamatan_id' => 27],
            ['nama' => 'Tangkilsari', 'kecamatan_id' => 27],

                // 28. Tirtoyudo
            ['nama' => 'Ampelgading', 'kecamatan_id' => 28],
            ['nama' => 'Gadungsari', 'kecamatan_id' => 28],
            ['nama' => 'Jogomulyan', 'kecamatan_id' => 28],
            ['nama' => 'Kepatihan', 'kecamatan_id' => 28],
            ['nama' => 'Pujiharjo', 'kecamatan_id' => 28],
            ['nama' => 'Purwodadi', 'kecamatan_id' => 28],
            ['nama' => 'Sukorejo', 'kecamatan_id' => 28],
            ['nama' => 'Sumbertangkil', 'kecamatan_id' => 28],
            ['nama' => 'Tamankuncaran', 'kecamatan_id' => 28],
            ['nama' => 'Tamansatriyan', 'kecamatan_id' => 28],
            ['nama' => 'Tirtoyudo', 'kecamatan_id' => 28],
            ['nama' => 'Tlogosari', 'kecamatan_id' => 28],
            ['nama' => 'Wonoagung', 'kecamatan_id' => 28],

                // 29. Tumpang
            ['nama' => 'Benjor', 'kecamatan_id' => 29],
            ['nama' => 'Bokor', 'kecamatan_id' => 29],
            ['nama' => 'Duwet', 'kecamatan_id' => 29],
            ['nama' => 'Duwet Krajan', 'kecamatan_id' => 29],
            ['nama' => 'Jeru', 'kecamatan_id' => 29],
            ['nama' => 'Kambingan', 'kecamatan_id' => 29],
            ['nama' => 'Kidal', 'kecamatan_id' => 29],
            ['nama' => 'Malangsuko', 'kecamatan_id' => 29],
            ['nama' => 'Ngingit', 'kecamatan_id' => 29],
            ['nama' => 'Pandanajeng', 'kecamatan_id' => 29],
            ['nama' => 'Pulungdowo', 'kecamatan_id' => 29],
            ['nama' => 'Slamet', 'kecamatan_id' => 29],
            ['nama' => 'Tulusbesar', 'kecamatan_id' => 29],
            ['nama' => 'Tumpang', 'kecamatan_id' => 29],
            ['nama' => 'Wringinsongo', 'kecamatan_id' => 29],

                // 30. Turen
            ['nama' => 'Gedog Kulon', 'kecamatan_id' => 30],
            ['nama' => 'Gedog Wetan', 'kecamatan_id' => 30],
            ['nama' => 'Jeru', 'kecamatan_id' => 30],
            ['nama' => 'Kedok', 'kecamatan_id' => 30],
            ['nama' => 'Kemulan', 'kecamatan_id' => 30],
            ['nama' => 'Pagedangan', 'kecamatan_id' => 30],
            ['nama' => 'Sanankerto', 'kecamatan_id' => 30],
            ['nama' => 'Sananrejo', 'kecamatan_id' => 30],
            ['nama' => 'Sawahan', 'kecamatan_id' => 30],
            ['nama' => 'Talangsuko', 'kecamatan_id' => 30],
            ['nama' => 'Talok', 'kecamatan_id' => 30],
            ['nama' => 'Tanggung', 'kecamatan_id' => 30],
            ['nama' => 'Tawangrejeni', 'kecamatan_id' => 30],
            ['nama' => 'Tumpukrenteng', 'kecamatan_id' => 30],
            ['nama' => 'Undaan', 'kecamatan_id' => 30],
            ['nama' => 'Sedayu', 'kecamatan_id' => 30],
            ['nama' => 'Turen', 'kecamatan_id' => 30],

                // 31. Wagir
            ['nama' => 'Dalisodo', 'kecamatan_id' => 31],
            ['nama' => 'Gondowangi', 'kecamatan_id' => 31],
            ['nama' => 'Jedong', 'kecamatan_id' => 31],
            ['nama' => 'Mendalanwangi', 'kecamatan_id' => 31],
            ['nama' => 'Pandanlandung', 'kecamatan_id' => 31],
            ['nama' => 'Pandanrejo', 'kecamatan_id' => 31],
            ['nama' => 'Parangargo', 'kecamatan_id' => 31],
            ['nama' => 'Petungsewu', 'kecamatan_id' => 31],
            ['nama' => 'Sidorahayu', 'kecamatan_id' => 31],
            ['nama' => 'Sitirejo', 'kecamatan_id' => 31],
            ['nama' => 'Sukodadi', 'kecamatan_id' => 31],
            ['nama' => 'Sumbersuko', 'kecamatan_id' => 31],

                // 32. Wajak
            ['nama' => 'Bambang', 'kecamatan_id' => 32],
            ['nama' => 'Blayu', 'kecamatan_id' => 32],
            ['nama' => 'Bringin', 'kecamatan_id' => 32],
            ['nama' => 'Codo', 'kecamatan_id' => 32],
            ['nama' => 'Dadapan', 'kecamatan_id' => 32],
            ['nama' => 'Kidangbang', 'kecamatan_id' => 32],
            ['nama' => 'Ngembal', 'kecamatan_id' => 32],
            ['nama' => 'Patokpicis', 'kecamatan_id' => 32],
            ['nama' => 'Sukoanyar', 'kecamatan_id' => 32],
            ['nama' => 'Sukolilo', 'kecamatan_id' => 32],
            ['nama' => 'Sumberputih', 'kecamatan_id' => 32],
            ['nama' => 'Wajak', 'kecamatan_id' => 32],
            ['nama' => 'Wonoayu', 'kecamatan_id' => 32],

                // 33. Wonosari
            ['nama' => 'Bangelan', 'kecamatan_id' => 33],
            ['nama' => 'Kebobang', 'kecamatan_id' => 33],
            ['nama' => 'Kluwut', 'kecamatan_id' => 33],
            ['nama' => 'Plandi', 'kecamatan_id' => 33],
            ['nama' => 'Plaosan', 'kecamatan_id' => 33],
            ['nama' => 'Sumberdem', 'kecamatan_id' => 33],
            ['nama' => 'Sumbertempur', 'kecamatan_id' => 33],
            ['nama' => 'Wonosari', 'kecamatan_id' => 33],
        ];

        DB::table('kelurahan')->insert($kelurahan);
    }
}
