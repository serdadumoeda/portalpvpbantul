# Tahap 1 — Fondasi & Discovery (Skillhub / Pelatihan Gratis)

Dokumen ini merangkum hasil tahap 1: audit, rancangan peran/izin, model data konseptual, strategi survei (memakai modul survei existing), workflow publikasi, serta risiko dan mitigasi.

## 1. Audit Fitur & Proses Eksisting (Gap Analysis)
- **Inventaris saat ini**: profil user, katalog kelas, materi, tugas/submission, forum/chat, sertifikat, modul survei.
- **Gap utama untuk instruktur/peserta**:
  - Profil instruktur belum detail (sertifikasi, proyek riil, testimoni, jadwal mengajar, spesialisasi, ketersediaan mentoring).
  - Silabus per sesi & prasyarat belum konsisten; belum ada target kompetensi (SKKNI/KKNI) dan badge.
  - Kehadiran & rekaman sesi live belum terintegrasi; izin/reschedule peserta belum tercatat.
  - Tugas/praktik: rubrik penilaian, feedback per kriteria, auto-grading kuis, sandbox/VM/repo Git belum standar.
  - Komunitas: forum/tanya-jawab per kelas, pengumuman kelas, polling materi tambahan belum seragam.
  - Survei: belum otomatis ter-trigger saat kelas selesai; belum ada dashboard agregat per kelas/instruktur.
  - Karier: papan lowongan terkurasi, CV/portfolio builder, verifikasi badge oleh HR belum ada.
  - Kepatuhan: consent rekaman/chat, retensi rekaman, anonimitas survei, moderasi konten belum terdokumentasi.

## 2. Rancangan Peran, Akses, & Privasi
Peran inti: **Admin**, **Instruktur**, **Peserta**, opsional **Reviewer/QC**, **Moderator**.

| Modul / Aksi                         | Admin | Reviewer/QC | Instruktur | Peserta | Moderator |
|--------------------------------------|:-----:|:-----------:|:----------:|:-------:|:---------:|
| Buat/Edit kelas, silabus, materi     |  ✔    |     R       |    ✔\*     |   -     |    -      |
| Publish kelas/materi/rekaman         |  ✔    |     ✔       |    S       |   -     |    -      |
| Kelola peserta & kehadiran           |  ✔    |     -       |    ✔       |   -     |    -      |
| Tugas/praktik: buat/rubrik/nilai     |  ✔    |     R       |    ✔       |   -     |    -      |
| Forum/chat: posting                  |  ✔    |     ✔       |    ✔       |   ✔     |    ✔      |
| Forum/chat: moderasi/blokir          |  ✔    |     ✔       |    S       |   -     |    ✔      |
| Survei: template & distribusi        |  ✔    |     ✔       |    S       |   R     |    -      |
| Laporan survei/kelas                 |  ✔    |     ✔       |    ✔ (kelasnya) | R (terbatas) | - |
| Sertifikat/Badge                     |  ✔    |     ✔       |    S       |   R     |    -      |

Keterangan: ✔ penuh, R read-only, S submit/ajukan (butuh persetujuan).

Privasi & kepatuhan:
- Rekaman sesi live: unduh terbatas (Admin/Reviewer), peserta hanya stream; watermark; retensi ditetapkan (mis. 90 hari).
- Chat/forum: arsip, tombol lapor penyalahgunaan, masking PII bila perlu.
- Consent: tampilkan consent rekaman & penggunaan data pembelajaran; kebijakan anonimitas survei (hasil ditampilkan agregat).

## 3. Model Data Konseptual (ringkas)
- **User** (peran/role), **InstructorProfile** (sertifikasi, proyek, testimoni, jadwal, spesialisasi, mentoring).
- **Class/Course** (format sinkron/asinkron, prasyarat, silabus, target kompetensi, badge, status draft/pending/published).
- **Session** (jadwal, link live, rekaman, retensi, kehadiran).
- **Enrollment** (status peserta, progres, izin/reschedule).
- **Material** (tipe: teks/video/file/link; akses).
- **Assignment/Task** (rubrik, tenggat, auto-grading opsional).
- **Submission** (nilai per kriteria, feedback, revisi).
- **Attendance** (per sesi).
- **Announcement** (kelas).
- **Forum/Thread/Post** (kelas), **Report** (pelaporan konten).
- **SurveyTemplate** (CSAT/NPS Kelas, Kualitas Instruktur), **SurveyInstance** (terikat ke Class/Instructor), **SurveyResponse**.
- **Certificate/Badge** (verifikasi QR), **CareerItem** (lowongan terkurasi), **Portfolio/CV** (opsional).
- State machine: draft → pending → published (kelas, materi, tugas, rekaman, survei template).

## 4. Strategi Integrasi Survei (pakai modul survei existing)
- Template baru:
  - **Survei Kepuasan Kelas (CSAT/NPS)**: NPS, kepuasan materi, fasilitas, dukungan instruktur, rekomendasi perbaikan (open text).
  - **Survei Kualitas Instruktur**: kejelasan penyampaian, responsif tanya-jawab, pemberian feedback, penguasaan materi.
- Binding: `survey_instance` terikat ke `class_id` dan/atau `instructor_id`, generasi per kelas yang selesai.
- Distribusi: trigger otomatis saat kelas selesai / tugas akhir submit; reminder otomatis (mis. H+2, H+5); batasi 1 respon/peserta.
- Reporting: dashboard agregat per kelas & per instruktur, tren waktu, highlight komplain (open text), filter periode.
- Anonimitas: tampilkan hasil hanya jika respon ≥ threshold (mis. 5) untuk menjaga kerahasiaan.

## 5. Workflow Draft → Review → Publish
- **Siapa**: Instruktur membuat draft; Reviewer/Admin meninjau dan publish. Instruktur hanya dapat submit (status pending) bila tidak punya izin approve.
- **Checklist review**:
  - Silabus lengkap, prasyarat jelas, target kompetensi terisi.
  - Materi video punya subtitle/transkrip; file sesuai batas ukuran/mime.
  - Tugas punya rubrik dan tenggat; auto-grading dikonfigurasi bila kuis.
  - Rekaman: cek kualitas, retensi, watermark.
- **Retensi & arsip**: rekaman kadaluarsa otomatis; materi/tugas dapat di-archive tanpa menghapus riwayat nilai.

## 6. Risiko & Mitigasi
- Kebocoran rekaman/materi → akses RBAC, watermark, blokir unduh peserta.
- PII di forum/chat → fitur lapor, moderasi, batasan lampiran/link.
- Survei tidak anonim → threshold tampilan hasil, agregasi.
- Kualitas materi rendah → wajib review, checklist QA, rubrik.
- Spam/abuse → rate limit posting, moderasi, ban sementara.

## 7. Deliverable Tahap 1
- Dokumen ini (gap, role/izin, ERD konseptual, strategi survei, workflow).
- Backlog prioritas (High/Med/Low) untuk tahapan berikutnya.
