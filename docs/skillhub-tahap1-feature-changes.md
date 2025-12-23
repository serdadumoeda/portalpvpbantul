# Perubahan Fitur — Tahap 1 (Fondasi & Discovery)

Dokumen ini merinci perubahan fitur yang perlu dikerjakan di tahap 1 (berdasarkan `skillhub-tahap1-foundation.md`) agar siap lanjut ke tahap berikutnya.

## 1) RBAC & Peran
- Tambah/konfirmasi peran: **Admin**, **Instruktur**, **Peserta**, opsional **Reviewer/QC**, **Moderator**.
- Matriks izin:
  - Admin: CRUD kelas/materi/tugas/forum/survei, publish, laporan, moderasi.
  - Reviewer: review/publish konten (kelas/materi/rekaman), read laporan.
  - Instruktur: kelola kelasnya (silabus, materi, tugas, pengumuman, kehadiran, penilaian), submit publish; akses survei kelasnya.
  - Peserta: akses kelas terdaftar, materi, tugas, forum, survei; tidak bisa unduh rekaman jika dibatasi.
  - Moderator: moderasi forum/chat (hapus/lapor/ban).

## 2) Workflow Draft → Pending → Published
- Terapkan state machine untuk: Kelas, Materi, Rekaman, Tugas/Quiz, Silabus, Pengumuman (opsional).
- Logika peran:
  - Instruktur dapat `submit` → status `pending`.
  - Reviewer/Admin dapat `approve/publish`.
  - Revisi mengembalikan ke `draft/pending`.
- UI: tombol “Ajukan” (instruktur), “Setujui/Terbitkan” (reviewer/admin), badge status di listing.

## 3) Model Data Konseptual (baseline DB change list)
- **User/Role**: pivot role_user jika belum ada.
- **InstructorProfile**: sertifikasi, keahlian (tags), proyek, jadwal/ketersediaan mentoring, testimoni (kurasi admin), statistik agregat (kelas diajar, skor survei).
- **Class/Course**: format (sinkron/asinkron), prasyarat, target kompetensi (SKKNI/KKNI/unit), badge, status.
- **Session**: jadwal, link live, rekaman (URL), retensi, allow_download (bool), status.
- **Material**: tipe (teks/video/file/link), status, metadata aksesibilitas (subtitle/transkrip).
- **Assignment/Task**: deskripsi, due, type (essay/file/quiz), rubrik_id, weight, max_score, status.
- **Submission**: content/file/link, version, late flag, status (submitted/graded).
- **Attendance**: hadir/telat/izin/absen, alasan/bukti.
- **Announcement**: pinned, window tampil.
- **Survey**: reuse modul survei; tambahkan binding `class_id`, `instructor_id` di `survey_instance`.

## 4) Survei (gunakan modul existing)
- Template baru:
  - Survei Kepuasan Kelas (CSAT/NPS, materi, fasilitas, dukungan instruktur, saran).
  - Survei Kualitas Instruktur (kejelasan, responsif, feedback, penguasaan).
- Binding & distribusi:
  - Survei instance otomatis dibuat saat kelas selesai (atau tugas akhir terkumpul).
  - 1 respon per peserta; reminder H+2/H+5; threshold tampilan hasil (>=5 respon).
- Reporting: dashboard agregat per kelas & per instruktur, tren periode, open text highlight.

## 5) Privasi, Akses Rekaman, & Konsent
- Rekaman: stream-only untuk peserta, download hanya Admin/Reviewer; watermark; retensi default 90 hari (konfigurable).
- Consent: tampilkan persetujuan rekaman & penggunaan data pembelajaran; anonimitas survei ditegaskan.
- Log akses rekaman (opsional) untuk audit.

## 6) Moderasi Forum/Chat
- Tombol lapor (report), mute/ban oleh Moderator/Admin.
- Rate limit posting; filtering link/lampiran.
- Arsip setelah kelas selesai (read-only).

## 7) Checklist QA Sebelum Publish
- Silabus lengkap & prasyarat jelas; target kompetensi terisi.
- Materi video punya subtitle/transkrip; mime/ukuran file divalidasi.
- Tugas memiliki rubrik + due date; weight/rubric valid.
- Rekaman punya retensi & allow_download=false secara default.
- RBAC diuji: peserta tidak bisa akses konten privat/nilai peserta lain.

## 8) Notifikasi & Automasi (scope dasar)
- Reminder jadwal session (H-1/H), pengumuman baru, rekaman tersedia (dengan tanggal kadaluarsa), tugas mendekati due, hasil penilaian, survei pasca kelas.
- Job/cron: update status session (scheduled→ongoing→done), expire rekaman, trigger survei pasca kelas.

## 9) Deliverables Tahap 1 (yang harus selesai)
- Implementasi RBAC per modul sesuai matriks izin.
- State machine draft→pending→published pada kelas/materi/tugas/rekaman/pengumuman (opsional).
- Schema updates: tabel profil instruktur, binding survei ke class/instructor, atribut status/retensi/allow_download di session/rekaman, status & rubrik/weight di tugas/submission.
- UI/UX dasar: tombol ajukan/approve/publish, badge status di listing, form peran/izin.
- Konfigurasi retensi rekaman & consent tampilan.
- Modul survei dihubungkan ke kelas/instruktur dengan template baru dan trigger otomatis.

---

## 10) Breakdown Implementasi (detail teknis)

### A. RBAC & Workflow
- Tambah seeder/role: `admin`, `instructor`, `participant`, `reviewer`, `moderator`.
- Middleware/policy: pastikan `approve-content` dimiliki admin/reviewer (instruktur bisa publish hanya jika punya permission).
- Button & flow:
  - Instruktur: tombol `Ajukan` → set status `pending`.
  - Reviewer/Admin: tombol `Setujui/Publikasikan` → set `published`, isi `approved_by/approved_at`.
  - Revisi: tombol `Kembalikan ke Draft`.
- Badge status di semua listing: draft/pending/published dengan warna konsisten.

### B. Perubahan Skema Utama
1. **InstructorProfile**: user_id, bio, certifications (JSON), skills (tags), projects (JSON), testimonials (kurasi), availability, mentoring_available (bool), stats (classes_taught, rating_avg).
2. **Class/Course**: format, prerequisites, competencies (JSON), badge, status, approver fields, published_at.
3. **Session**: class_id, start_at, end_at, meeting_link, recording_url, recording_expired_at, allow_download (bool, default false), status.
4. **Material**: class_id, type (text/video/file/link), status, accessibility_meta (subtitle_url, transcript_text), file_path/link, size/mime.
5. **Assignment/Task**: class_id, title, description, type, due_at, weight, max_score, rubric_id, late_policy, penalty_percent, status.
6. **Submission**: assignment_id, user_id, content/link/file, version, late flag, submitted_at, status (submitted/graded/reopened).
7. **Attendance**: session_id, user_id, status (hadir/telat/izin/absen), reason, proof_url, checked_at.
8. **Announcement**: class_id, title, body, pinned, visible_from/until, status.
9. **Survey binding**: `survey_instances` tambah `class_id`, `instructor_id`, `triggered_at`, `status` (open/closed), `min_responses_threshold`.

### C. Survei (reusing modul existing)
- Template seed:
  - CSAT/NPS Kelas: NPS, kepuasan materi, fasilitas, dukungan instruktur, saran.
  - Kualitas Instruktur: kejelasan, responsif, feedback, penguasaan.
- Trigger job:
  - Buat `survey_instance` saat kelas berubah ke `done` atau tugas akhir dinilai.
  - Kirim notifikasi survei; reminder H+2/H+5 jika belum isi.
- Reporting:
  - Endpoint/dashboard agregat: filter by class/instructor/date range; sembunyikan jika `responses < threshold`.

### D. Rekaman & Privasi
- Default `allow_download=false`; expiry 90 hari (config).
- Watermark di player (opsional label nama user).
- Consent banner saat akses kelas; link ke kebijakan data & rekaman.
- Log akses rekaman (opsional tabel: recording_access_logs).

### E. Moderasi Forum/Chat
- Field `reports`, `muted_until`, `banned` per user per class.
- Endpoint moderator: mute/ban, resolve report, delete post.
- Rate limit posting (middleware).

### F. QA & Validasi
- Validator: mime/size file materi/tugas; subtitle/transkrip wajib untuk video sebelum publish.
- Rubrik total weight = 100, weight tugas total ≤ 100 (atau gunakan penimbang yang disepakati).
- Tests (opsional): policy tests untuk role, status transition tests, validator tests.

### G. Notifikasi & Cron
- Jobs: update status session (scheduled→ongoing→done), expire recordings, survey reminders, due-date reminders, grading SLA reminders.
- Channels: email/in-app; templating sederhana.

### H. UI/UX Minimal
- Form profil instruktur: input sertifikasi (dinamis), project, skills tags.
- Form kelas: status control, prasyarat, target kompetensi, badge.
- Form session: jadwal, link, rekaman, retensi toggle.
- Form assignment: due, weight, late policy, rubrik attach, status control.
- Listing dengan filter status + badge konsisten.
