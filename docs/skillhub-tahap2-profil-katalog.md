# Tahap 2 — Profil Instruktur & Katalog Kelas (Skillhub / Pelatihan Gratis)

Fokus: melengkapi profil instruktur, katalog kelas + silabus, serta fondasi aksesibilitas dasar.

## 1) Profil Instruktur (public + admin edit)
- **Data inti**: nama, jabatan, bio singkat (sanitasi HTML), foto, kontak profesional (LinkedIn, email opsional), lokasi/kantor.
- **Sertifikasi & keahlian**: daftar sertifikasi (nama, penerbit, tahun), spesialisasi/keahlian (tag), level (pemula/menengah/lanjut).
- **Proyek riil**: judul, deskripsi singkat, peran instruktur, link demo/repo (opsional).
- **Testimoni alumni**: kutipan pendek + nama/program (opsional), admin dapat kurasi.
- **Jadwal mengajar & ketersediaan mentoring**: slot waktu, preferensi format (online/offline), kapasitas mentoring 1:1 (jika berlaku).
- **Statistik publik** (opsional): jumlah kelas diajar, nilai rata-rata survei instruktur (agregat, anonim), daftar kelas aktif.
- **Kontrol akses**: instruktur boleh edit draft profilnya, publish oleh admin/reviewer jika perlu kurasi.

## 2) Katalog Kelas & Silabus
- **Data kelas**: judul, deskripsi ringkas, format (sinkron/asinkron), durasi/estimasi jam, jadwal (jika sinkron), kapasitas (jika relevan), status (draft/pending/published).
- **Prasyarat**: skill/alat yang diperlukan; checklist agar peserta siap.
- **Target kompetensi**: referensi SKKNI/KKNI atau unit kompetensi; lencana/badge yang dikaitkan.
- **Silabus per sesi**: topik per sesi, tujuan belajar, bahan bacaan, tugas/quiz yang terkait.
- **Materi pendukung**: tipe (teks/video/file/link), ukuran/mime, subtitle/transkrip untuk video (aksesibilitas dasar).
- **Pengumuman kelas**: kanal khusus pengumuman dari instruktur/admin ke peserta.
- **Tag & pencarian**: kategori/kejuruan, tingkat kesulitan, teknologi.
- **Workflow**: kelas & silabus bisa di-draft oleh instruktur, direview/publish oleh admin/reviewer (mengikuti state machine tahap 1).

## 3) Aksesibilitas Dasar
- Video: wajib menyediakan subtitle/transkrip; metadata bahasa.
- Teks/file: kontras warna sesuai WCAG, heading terstruktur, deskripsi alternatif untuk gambar penting.
- Kapasitas file: batas ukuran & jenis file yang diterima, validasi mime.

## 4) Integrasi dengan Tahap 1
- Menggunakan peran/izin dari tahap 1: instruktur submit, admin/reviewer publish.
- Status konten: gunakan draft/pending/published pada profil instruktur (opsional) dan kelas/silabus.
- Survei: tetap memakai modul survei existing; class ID diperlukan untuk binding survei setelah kelas selesai.

## 5) Risiko & Mitigasi
- Profil instruktur tidak akurat → review/publish oleh admin, wajib lampiran bukti sertifikasi jika kritikal.
- Materi tanpa aksesibilitas → checklist QA (subtitle/transkrip) sebelum publish.
- Prasyarat tidak jelas → field wajib, validasi minimal (panjang/format) dan contoh tool/skill.
- Kebocoran data kontak → tampilkan hanya kontak profesional; PII disaring (no telepon pribadi jika tidak perlu).

## 6) Output Tahap 2
- Spesifikasi field & alur form untuk Profil Instruktur dan Kelas/Silabus.
- Checklist QA aksesibilitas dasar.
- Rencana implementasi: model/DB (profil instruktur, kelas, sesi, silabus), form admin/instruktur, halaman publik katalog, binding status workflow.
