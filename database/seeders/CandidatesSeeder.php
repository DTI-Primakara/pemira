<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CandidatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('candidates')->insert([
            // DPM
            [
                'event_id' => 1,
                'name_candidates' => 'Tian',
                'number' => 1,
                'vision' => 'Mewujudkan Dewan Perwakilan Mahasiswa yang transparan, aspiratif, dan progresif, serta mampu menjadi organisasi yang kuat, inklusif, dan berintegritas dalam mendorong kemajuan mahasiswa.',
                'mission' => '1. Menampung, menyuarakan, serta memperjuangkan setiap aspirasi mahasiswa melalui mekanisme yang terbuka, cepat, dan mudah diakses demi terwujudnya DPM yang aspiratif dan transparan.
                              2. Membangun sistem kerja yang terstruktur, transparan, dan akuntabel dalam setiap proses pengambilan keputusan dan pelaksanaan program untuk memperkuat kinerja organisasi.
                              3. Meningkatkan kompetensi, integritas, dan kolaborasi anggota serta menciptakan program yang progresif, inovatif, dan inklusif guna mendorong kemajuan mahasiswa dan dinamika organisasi.',
                'image' => '/candidates/candidate_dpm_1_1x.webp',
                'position' => 'Calon Ketua Dewan Perwakilan Mahasiswa',
            ],
            [
                'event_id' => 1,
                'name_candidates' => 'Iqbal',
                'number' => 2,
                'vision' => 'Mewujudkan Dewan Perwakilan Mahasiswa yang transparan, inklusif, dan berintegritas sebagai pengawal aspirasi serta penguat ekosistem mahasiswa di Primakara University.',
                'mission' => '1. Menghadirkan ruang aspirasi yang terbuka dan mudah diakses oleh seluruh mahasiswa melalui forum rutin, kanal digital, serta mekanisme pelaporan yang jelas dan bertanggung jawab.
                              2. Membangun budaya dialog yang sehat antara mahasiswa, ormawa dan pihak kampus untuk mengurangi kesenjangan dan menguatkan kolaborasi lintas bidang.
                              3. Memperkuat fungsi pengawasan DPM terhadap kebijakan ormawa dan pelaksanaan program agar lebih akuntabel, efisien, serta berpihak pada kebutuhan mahasiswa.
                              4. Mendukung pengembangan ekosistem kemahasiswaan yang seimbang, baik pada aspek akademik, non-akademik, maupun kegiatan kepemimpinan, tanpa adanya polarisasi atau kelompok yang merasa terpinggirkan.
                              5. Mendorong penerapan prinsip tata kelola yang transparan melalui publikasi laporan kerja, keterbukaan anggaran, dan evaluasi kegiatan secara berkala.
                              6. Menciptakan lingkungan yang aman, nyaman, dan beretika dengan mengedepankan nilai profesionalitas, keberagaman, dan rasa saling menghargai.',
                'image' => '/candidates/candidate_dpm_2_1x.webp',
                'position' => 'Calon Ketua Dewan Perwakilan Mahasiswa',
            ],

            // BEM
            [
                'event_id' => 2,
                'name_candidates' => 'Fajrin & Neralino',
                'number' => 1,
                'vision' => 'Mewujudkan BEM yang dekat dengan mahasiswa, responsif terhadap isu kampus yang berdampak pada kehidupan mahasiswa, serta terbuka pada aspirasi dan kritik demi terciptanya lingkungan yang inklusif, transparan, dan progresif.',
                'mission' =>    '1. Menghadirkan hubungan yang erat antara BEM dan mahasiswa hingga civitas kampus
                                2. Memperjuangkan isu-isu mahasiswa secara nyata dan terstruktur
                                3. Menjadi BEM yang terbuka terhadap aspirasi dan kritik membangun
                                4.Menjalankan BEM yang jujur, terbuka, dan bisa  dipertanggungjawabkan.
                                5. Mendorong kolaborasi lintas organisasi mahasiswa
                                6. Menciptakan lingkungan kampus yang inklusif dan suportif',
                'image' => '/candidates/candidate_bem_1_1x.webp',
                'position' => 'Calon Presma & Wapresma',
            ],

            // HIMA SI
            [
                'event_id' => 3,
                'name_candidates' => 'Dwitya',
                'number' => 1,
                'vision' => 'Mewujudkan HIMA Sistem Informasi yang berkembang, bersinergi, dan penuh kolaborasi.',
                'mission' => '1. Meningkatkan kebersamaan melalui kegiatan yang mempererat hubungan antar anggota HIMA.
                              2. Menyelenggarakan program akademik dan non - akademik yang relevan, inovatif, dan bermanfaat bagi mahasiswa Sistem Informasi.
                              3. Membangun sinergi dengan berbagai pihak, baik internal maupun eksternal, demi menghadirkan kolaborasi yang berdampak positif.
                              4. Mengoptimalkan kinerja organisasi dengan kepengurusan yang profesional, kreatif, dan bertanggung jawab.',
                'image' => '/candidates/candidate_himasi_1_1x.webp',
                'position' => 'Calon Ketua HIMA Sistem Informasi',
            ],
            [
                'event_id' => 3,
                'name_candidates' => 'Manis',
                'number' => 2,
                'vision' => 'Menjadikan HIMA Sistem Informasi sebagai organisasi yang progresif, inklusif, dan penuh kolaborasi untuk menciptakan ekosistem mahasiswa S istem Informasi yang unggul, berdaya saing, dan berkarakter.”',
                'mission' => '1. Membangun budaya kebersamaan agar tercipta lingkungan HIMA yang hangat, solid, dan suportif.
                              2. Mengembangkan potensi mahasiswa melalui program peningkatan kapasitas akademik, non -akademik, dan soft -skill.
                              3. Memperluas kolaborasi internal maupun eksternal yang memberi dampak nyata bagi mahasiswa SI.
                              4. Mewujudkan kepengurusan yang profesional, kreatif, adaptif, dan berintegritas.
                              5. Meningkatkan kualitas layanan dan program HIMA dengan pendekatan yang
                              responsif terhadap kebutuhan mahasiswa.',
                'image' => '/candidates/candidate_himasi_2_1x.webp',
                'position' => 'Calon Ketua HIMA Sistem Informasi',
            ],


            // HIMA IF
            [
                'event_id' => 4,
                'name_candidates' => 'Ananta',
                'number' => 1,
                'vision' => 'Menjadikan Himpunan Mahasiswa Program Studi Informatika sebagai organisasi yang inklusif, harmonis, dan kompetitif, dengan menegakkan nilai keseimbangan antara manusia dengan sesama, Tuhan, dan alam, serta tetap berpedoman pada nilai-nilai Primakara.',
                'mission' => '1. Membangun lingkungan organisasi yang menghargai keberagaman dan memperkuat solidaritas antar mahasiswa.
                              2. Menyediakan ruang aspirasi yang efektif bagi seluruh mahasiswa, khususnya Program Studi Informatika.
                              3. Mendorong pengembangan keterampilan serta daya saing mahasiswa di bidang informatika yang memberikan kontribusi bagi masyarakat.
                              4. Mengimplementasikan nilai-nilai Primakara: BERSIH, BERSINAR, KOMPETITIF, dan PEDULI dalam setiap program dan aktivitas himpunan.',
                'image' => '/candidates/candidate_himaif_1_1x.webp',
                'position' => 'Calon Ketua HIMA Informatika',
            ],
            [
                'event_id' => 4,
                'name_candidates' => 'Wira',
                'number' => 2,
                'vision' => 'Mendukung HIMA IF yang aktif, nyaman, dan bermanfaat bagi seluruh mahasiswa.',
                'mission' => '1. Melanjutkan dan menjaga kegiatan yang sudah berjalan baik,
                              2. Menghadirkan komunikasi yang lebih rapi dan jelas, agar informasi program dan kegiatan bisa tersampaikan dengan baik.
                              3. Membuka ruang untuk ide dari mahasiswa, sehingga HIMA bisa berkembang dengan masukan dari berbagai sudut pandang.
                              4. Membangun suasana organisasi yang ringan dan inklusif, supaya siapa pun bisa terlibat tanpa merasa terbebani.',
                'image' => '/candidates/candidate_himaif_2_1x.webp',
                'position' => 'Calon Ketua HIMA Informatika',
            ],

            // HIMA SIA
            [
                'event_id' => 5,
                'name_candidates' => 'Putri',
                'number' => 1,
                'vision' => 'Mewujudkan HIMA Sistem Informasi Akuntansi yang kolaboratif dan harmonis sebagai wadah pengembangan mahasiswa berlandaskan nilai kekeluargaan.',
                'mission' => '1. Mengembangkan kompetensi dan potensi mahasiswa melalui program edukatif dan inovatif yang berkelanjutan.
                              2. Memperkuat kolaborasi harmonis di lingkungan internal dan eksternal.
                              3. Menumbuhkan nilai kekeluargaan dan solidaritas sebagai dasar pengembangan karakter
                              diri mahasiswa.',
                'image' => '/candidates/candidate_himasia_1_1x.webp',
                'position' => 'Calon Ketua HIMA Sistem Informasi Akuntansi',
            ],

            [
                'event_id' => 5,
                'name_candidates' => 'Suci',
                'number' => 2,
                'vision' => 'Mewujudkan HIMA Sistem Informasi Akuntansi yang kolaboratif, inovatif, dan berintegritas sebagai wadah pengembangan akademik, organisasi, serta kompetensi digital mahasiswa.',
                'mission' => '1. Mengembangkan lingkungan organisasi yang inklusif dan kolaboratif
                              2. Mendorong peningkatan kompetensi akademik dan digital
                              3. Memperkuat profesionalisme dan integritas dalam organisasi
                              4. Membangun kemitraan strategis
                              5. Menciptakan program kerja kreatif dan berdampak.',
                'image' => '/candidates/candidate_himasia_2_1x.webp',
                'position' => 'Calon Ketua HIMA Sistem Informasi Akuntansi',
            ],

            // HIMA DKV
            [
                'event_id' => 6,
                'name_candidates' => 'Made',
                'number' => 1,
                'vision' => 'Mewujudkan HIMA DKV sebagai organisasi yang inklusif dan open-minded dengan menyediakan ruang kreatif yang progresif untuk mengembangkan keterampilan, mendorong inovasi, memperkuat kolaborasi, serta memastikan setiap mahasiswa dapat belajar dan berkembang secara maksimal dalam lingkungan yang suportif.',
                'mission' => '1. Membangun lingkungan HIMA DKV yang inklusif dan ramah bagi seluruh mahasiswa.
                              2. Menyelenggarakan program pengembangan keterampilan yang berkelanjutan, seperti workshop, mentoring, dan pelatihan.
                              3. Mewujudkan sistem komunikasi yang transparan, responsif, dan mudah diakses oleh seluruh anggota.
                              4. Menumbuhkan budaya open-minded dan kolaboratif dengan membuka ruang diskusi serta menerima ide-ide baru tanpa memandang senioritas.
                              5. Menjalin kerja sama strategis dengan industri dan komunitas serta menyediakan platform untuk apresiasi dan publikasi karya mahasiswa.',
                'image' => '/candidates/candidate_himadkv_1_1x.webp',
                'position' => 'Calon Ketua HIMA Desain Komunikasi Visual',
            ],

            [
                'event_id' => 6,
                'name_candidates' => 'Made',
                'number' => 2,
                'vision' => 'Menjadi Himpunan Mahasiswa DKV yang kreatif dan kolaboratif , di mana setiap anggota dapat berkembang dan bersama -sama mencapai tujuan bersama.',
                'mission' => '1. Menyediakan wadah pengembangan diri melalui program dan kegiatan pendukung di bidang DKV.
                              2. Menciptakan lingkungan yang positif, inklusif, dan saling mendukung bagi perkembangan anggota.
                              3. Mendorong kolaborasi internal maupun eksternal untuk memperluas wawasan dan pengalaman anggota.
                              4. Mengembangkan budaya organisasi yang profesional, responsif, dan berorientasi pada tujuan bersama.',
                'image' => '/candidates/candidate_himadkv_2_1x.webp',
                'position' => 'Calon Ketua HIMA Desain Komunikasi Visual',
            ],

            // HIMA MANAJEMEN
            [
                'event_id' => 7,
                'name_candidates' => 'Fikri',
                'number' => 1,
                'vision' => 'Menjadi organisasi mahasiswa yang unggul dalam pengembangan kompetensi technopreneurship, serta memajukan ekonomi kreatif digital di Universitas Primakara.',
                'mission' => '1. Membangun jaringan kerja sama antar industri start up dan lembaga untuk memperluas peluang pengembangan diri mahasiswa
                              2. Menjadi wadah bagi mahasiswa untuk berbagai pengalaman dan pengetahuan melalui kegiatan - kegiatan yang mampu menginspirasi dan memotivasi mahasiswa untuk berinovasi dan berkarya dibidang teknologi informasi.
                              3. Menumbuhkan jiwa kewirausahaan untuk mahasiswa dibidang bisnis teknologi informasi melalui inkubasi bisnis dan pendampingan usaha rintisan dengan menyediakan berbagai program pelatihan, workshop, dan kompetisi.',
                'image' => '/candidates/candidate_himamana_1_1x.webp',
                'position' => 'Calon Ketua HIMA Manajemen',
            ],

            [
                'event_id' => 7,
                'name_candidates' => 'Yudi',
                'number' => 2,
                'vision' => 'Menjadikan Himpunan Mahasiswa Manajemen Primakara University sebagai organisasi yang progresif, kolaboratif, dan berintegritas dalam mencetak kandidat unggul, dan adaptif terhadap perkembangan teknologi dan bisnis',
                'mission' => '1. Mengembangkan Kompetensi Mahasiswa Prodi Manajemen
                              2. Menciptakan Solidaritas dan Profesionalisme Internal Organisasi
                              3. Berinovasi dan Mengabdi Kepada Hima Prodi Manajemen
                              4. Menjunjung Tinggi Nilai -nilai Primakara, Bersih, Bersinar, Kompetitif, dan Peduli',
                'image' => '/candidates/candidate_himamana_2_1x.webp',
                'position' => 'Calon Ketua HIMA Manajemen',
            ],

            // HIMA AKUNTANSI
            [
                'event_id' => 8,
                'name_candidates' => 'Dwitya',
                'number' => 1,
                'vision' => 'Mewujudkan HIMA Akuntansi yang aktif, adaptif, dan berintegritas sebagai wadah pengembangan akademik, kreativitas, dan profesionalisme mahasiswa akuntansi.',
                'mission' => '1. Membangun organisasi yang transparan dan inklusif
                              2. Menghadirkan program kreatif dan bermanfaat
                              3. Meningkatkan rasa kebersamaan dan solidaritas mahasiswa akuntansi
                              4. Memperkuat soft skill dan profesionalisme',
                'image' => '/candidates/candidate_himaakun_1_1x.webp',
                'position' => 'Calon Ketua HIMA Akuntansi',
            ],

            [
                'event_id' => 8,
                'name_candidates' => 'Japa',
                'number' => 2,
                'vision' => 'Mewujudkan HIMA Akuntansi yang profesional, berintegritas, dan berlandaskan nilai-nilai Primakara, serta menjadi ruang kolaborasi dan dukungan yang aman, inklusif, dan berkembang bagi seluruh mahasiswa Prodi Akuntansi.',
                'mission' => '1. Memperkuat pondasi internal HIMA melalui tata kelola organisasi yang jelas, akuntabel, dan berorientasi kinerja.
                              2. Membangun budaya kolaborasi yang sehat antara pengurus, mahasiswa, dan dosen guna menciptakan ekosistem organisasi yang berkelanjutan.
                              3. Menciptakan organisasi yang profesional, aktif, kreatif, dan adaptif melalui program kerja yang relevan, inovatif, serta responsif terhadap kebutuhan mahasiswa/i.
                              4. Mengoptimalkan layanan aspirasi mahasiswa dengan mekanisme yang terbuka, cepat, dan berorientasi solusi.
                              5. Mendorong peningkatan kompetensi akademik dan profesional mahasiswa akuntansi melalui pelatihan, seminar, dan aktivitas berbasis pengembangan diri.',
                'image' => '/candidates/candidate_himaakun_2_1x.webp',
                'position' => 'Calon Ketua HIMA Akuntansi',
            ],

            // HIMA BISNIS DIGITAL
            [
                'event_id' => 9,
                'name_candidates' => 'Agus',
                'number' => 1,
                'vision' => 'Menjadikan HIMA sebagai wadah untuk berekspresi dan mengembangkan diri serta menjadi pribadi yang berakhlak dan sesuai dengan nilai -nilai Primakara.',
                'mission' => '1. Meningkatkan kreativitas dan inovasi dengan menyelenggarakan berbagai workshop dan pelatihan di bidang seni, teknologi, dan kewirausahaan.
                              2. Mengadakan lomba bisnis tahunan dalam meningkatkan jiwa kompetitif mahasiswa prodi Bisnis Digital.
                              3. Mengembangkan jaringan kolaborasi dengan berbagai pihak, termasuk perusahaan, komunitas, dan lembaga pendidikan, untuk memperluas kesempatan bagi mahasiswa dalam mengembangkan potensi diri.
                              4. Menyediakan wadah bagi anggota untuk mengembangkan kemampuan komunikasi, kepemimpinan, dan kerja sama tim melalui kegiatan rutin dan event yang melibatkan berbagai pihak.',
                'image' => '/candidates/candidate_himabd_1_1x.webp',
                'position' => 'Calon Ketua HIMA Bisnis Digital',
            ],

            [
                'event_id' => 9,
                'name_candidates' => 'Suwardi',
                'number' => 2,
                'vision' => 'Menciptakan Hima bisnis digital yang aktif, solid, inspiratif, dan juga menjadikan hima wadah berekspresi, sekaligus tempat pengembangan potensi dari para mahasiswa program studi bisnis digital.',
                'mission' => '1. Membangun budaya organisasi yang solid dan komunikatif antar mahasiswa Bisnis Digital melalui kegiatan internal untuk memperkuat rasa kebersamaan.
                              2. Mengembangkan jaringan kolaborasi dengan pihak kampus, instansi sejenis, UKM, dan komunitas, untuk membuka akses pengalaman dan wawasan baru bagi mahasiswa.
                              3. Mewadahi pengembangan potensi mahasiswa melalui kesempatan untuk bereksplorasi, berkreasi, dan membangun kepercayaan diri di ranah Bisnis Digital.
                              4. Menyelenggarakan berbagai workshop, webinar dan pelatihan dalam bidang teknologi dan kewirausahaan untuk meningkatkan kemampuan mahasiswa dalam hal Bisnis dan Teknologi',
                'image' => '/candidates/candidate_himabd_2_1x.webp',
                'position' => 'Calon Ketua HIMA Bisnis Digital',
            ],








        ]);
    }
}
