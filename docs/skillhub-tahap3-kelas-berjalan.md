# Tahap 3 — Kelas Berjalan: Kalender, Kehadiran, Tugas/Praktik, Penilaian (Skillhub / Pelatihan Gratis)

Fokus: eksekusi kelas harian—jadwal, kehadiran, rekaman, submission, rubrik, feedback, progress.

## 1) Kalender & Kehadiran
- **Session**: jadwal mulai/selesai, link live (meeting), rekaman (URL/file) + retensi, status (scheduled/ongoing/done).
- **Attendance**: kehadiran per sesi (hadir/telat/izin/absen), alasan izin, bukti bila diperlukan; batas keterlambatan (opsional).
- **Reschedule/izin**: peserta mengajukan izin/reschedule; instruktur/admin menyetujui atau menolak; dicatat di log.
- **Notifikasi**: reminder H-1/H, perubahan jadwal, link rekaman tersedia.
- **Akses rekaman**: peserta hanya stream; download dibatasi (admin/reviewer). Rekaman otomatis kadaluarsa sesuai retensi.
- **Moderasi**: watermark rekaman, batas akses; log akses rekaman (opsional).

## 2) Submission & Praktik
- **Assignment/Task**: tipe (teks, file, quiz auto-grading opsional), tenggat, rubrik, bobot nilai, status (draft/pending/published).
- **Submission**: unggah file/link/teks; boleh revisi hingga tenggat (atau penalti). Catat waktu kirim, versi revisi, komentar peserta.
- **Auto-grading (ringan)**: untuk kuis/objektif; skor awal otomatis; instruktur bisa override/beri feedback.
- **Sandbox/VM/Repo (opsional)**: tautan eksternal (Git/VM) disimpan; progress di luar sistem bisa dicatat manual.
- **Checklist kelulusan**: prasyarat/komponen yang harus lulus untuk mendapatkan sertifikat.

## 3) Rubrik, Penilaian, & Feedback
- **Rubrik per tugas**: kriteria, bobot, deskripsi level (mis. 1–4). Disimpan di Assignment, ditarik saat penilaian.
- **Penilaian**: nilai per kriteria + catatan; total skor terhitung otomatis dari bobot.
- **Feedback**: komentar instruktur per kriteria (opsional) + komentar umum; lampiran feedback (opsional).
- **Progress kompetensi**: progress bar per kelas/kompetensi berdasarkan tugas/quiz/kehadiran (aturan penimbang perlu disepakati).

## 4) Pengumuman & Diskusi Kelas
- **Pengumuman**: broadcast instruktur/admin ke peserta; bisa pin; dapat mencantumkan rekaman atau tugas baru.
- **Forum/Chat**: ruang tanya-jawab per kelas; moderasi (lapor/ban); rate limit posting; arsip setelah kelas selesai.

## 5) Integrasi Survei (dari Tahap 1)
- Trigger survei otomatis saat kelas selesai atau tugas akhir terkumpul.
- Binding survei ke `class_id` (dan `instructor_id` bila instruktur tetap).
- Reminder otomatis dan agregasi hasil (hanya tampil jika respon ≥ threshold).

## 6) Workflow & Akses
- **Workflow**: Assignment/Session/Rekaman mengikuti draft→pending→published; rekaman butuh review jika perlu penyuntingan.
- **Peran/izin**: Instruktur kelola kelasnya (jadwal, materi, tugas, penilaian, pengumuman); Admin/Reviewer dapat publish/approve; Peserta hanya akses kelas terdaftar, kirim tugas, lihat feedback.
- **Retensi rekaman**: rekaman kadaluarsa otomatis (mis. 90 hari) kecuali diperpanjang Admin.

## 7) Risiko & Mitigasi
- Penyalahgunaan rekaman/materi → stream-only untuk peserta, watermark, log akses.
- Keterlambatan input nilai → reminder ke instruktur, SLA penilaian (mis. ≤7 hari).
- Beban server unggahan → batas ukuran/mime, gunakan storage publik (S3-compatible/CDN), validasi virus/malware opsional.
- Plagiarisme tugas → izin/minta tautan repo asli, spot-check manual, opsi integrasi deteksi plagiarisme (opsional).
- Spam di forum/chat → rate limit, moderasi, ban sementara, fitur lapor.

## 8) Output Tahap 3
- Spesifikasi model/field untuk Session, Attendance, Assignment, Submission, Rubric, Score/Feedback.
- Alur UI/UX: kalender kelas, form izin/reschedule, form tugas/submission, layar penilaian (rubrik), progress bar.
- Kebijakan retensi rekaman & SLA penilaian.

---

## 9) Spesifikasi Data Model (usulan field)

### Session
- `id`, `class_id`, `judul`, `deskripsi` (sanitize), `start_at`, `end_at`, `status` (scheduled/ongoing/done), `meeting_link`, `recording_url`, `recording_expired_at`, `allow_download` (bool, default false), `created_by`, timestamps.

### Attendance
- `id`, `session_id`, `user_id`, `status` (hadir/telat/izin/absen), `reason` (izin), `proof_url` (opsional), `checked_at`, timestamps.

### Assignment / Task
- `id`, `class_id`, `title`, `description` (rich text terbatas), `type` (essay/file/quiz), `due_at`, `late_policy` (no-accept/penalty/allow), `penalty_percent` (jika ada), `rubric_id` (nullable), `weight` (0-100), `max_score`, `status` (draft/pending/published), `created_by`, timestamps.

### Rubric
- `id`, `title`, `class_id`, `criteria` (JSON array: `{name, weight, levels: [{label, score, description}]}`), `total_weight` (100), `created_by`, timestamps.

### Submission
- `id`, `assignment_id`, `user_id`, `content_text` (nullable), `file_url` (nullable), `link_url` (nullable), `submitted_at`, `version` (int, increment on resubmit), `late` (bool), `late_minutes` (int), `status` (submitted/graded/reopened), timestamps.

### Score / Feedback
- `id`, `submission_id`, `grader_id`, `scores` (JSON per kriteria `{criterion, score, comment}`), `total_score`, `feedback` (text), `attachments` (opsional), `graded_at`, timestamps.

### Announcement
- `id`, `class_id`, `title`, `body`, `pinned` (bool), `visible_from`, `visible_until`, `created_by`, timestamps.

### Permission Hooks
- RBAC: Admin/Reviewer boleh publish; Instruktur hanya publish jika punya izin approve; Peserta hanya read + create submission/izin/attendance self.

## 10) Alur UX & Notifikasi (ringkas)
- **Kalender/Session**: Admin/Instruktur buat session (judul, waktu, link). Reminder otomatis ke peserta H-1/H-0. Status pindah otomatis scheduled→ongoing→done (cron/queue).
- **Kehadiran**: Instruktur/Admin tandai hadir/telat/izin; peserta dapat ajukan izin/reschedule (form singkat + alasan + bukti). Notifikasi ke instruktur untuk approve/deny.
- **Rekaman**: Setelah sesi selesai, instruktur upload/taruh link; Admin/Reviewer dapat set allow_download=false dan set `recording_expired_at`. Notifikasi ke peserta bahwa rekaman tersedia sampai tanggal tertentu.
- **Tugas**: Instruktur buat tugas (due date, rubrik). Peserta submit (teks/file/link). Notifikasi saat mendekati tenggat, saat feedback keluar, saat tugas direvisi/ditolak.
- **Penilaian**: Layar rubrik (kriteria, bobot, level); grader isi skor per kriteria, komentar; sistem hitung total otomatis. Notifikasi ke peserta setelah grading.
- **Progress**: Progress bar dihitung dari bobot assignment/quiz + kehadiran (kebijakan penimbang disepakati). Tampilkan di dashboard peserta.
- **Pengumuman**: Instruktur/Admin broadcast; dapat pin; notifikasi ke peserta; bisa menyertakan tautan rekaman/tugas baru.
- **Forum/Chat**: Ruang per kelas; tombol lapor; moderator bisa mute/ban; rate limit posting.

## 11) SLA & Kebijakan Operasional
- **Retensi rekaman**: default 90 hari (konfigurable); otomatis expire, download dibatasi peserta.
- **Penilaian**: SLA grader maksimal 7 hari setelah due; reminder otomatis H+3/H+6 jika belum dinilai.
- **Late policy**: opsi no-accept; atau penalty (mis. -10% per hari hingga max X%); atau allow tanpa penalti (disetel per tugas).
- **Batas unggah**: file maks 25 MB (atau sesuai storage); mime whitelist; scanning dasar (opsional).
- **Anonimitas survei**: hasil baru tampil jika respon ≥5; data agregat untuk instruktur/peserta.

## 12) Workflow & State Machine (aksi per peran)
- **Session**: Admin/Instruktur buat (draft) → publish (scheduled). Transition to ongoing otomatis saat start_at, done saat end_at atau manual. Rekaman: draft → pending review (opsional) → published (visible).
- **Assignment**: draft (instruktur) → pending (submit for review) → published (Admin/Reviewer atau instruktur dengan izin). Revisi: kembali ke draft/pending.
- **Submission**: created/submitted oleh peserta → graded oleh instruktur/admin; dapat reopened untuk revisi jika kebijakan mengizinkan.
- **Announcement**: draft → published; auto-expire via visible_until.

## 13) Checklist QA & Acceptance
- Session punya start/end, link valid, timezone jelas; rekaman punya retensi & allow_download=false secara default.
- Tugas punya due date, deskripsi, rubrik, weight; mime/ukuran file tervalidasi.
- Rubrik total bobot 100; scoring otomatis kalkulasi total benar.
- Notifikasi berjalan: reminder jadwal, due date, hasil penilaian, rekaman tersedia, survei pasca kelas.
- Aksesibilitas: subtitle/transkrip tersedia untuk video; kontras teks; form validasi ramah pengguna.
- Security: RBAC diuji; peserta tak bisa akses rekaman privat atau nilai orang lain; forum rate limit.

## 14) Risiko & Mitigasi (lanjutan)
- **Server beban unggahan**: gunakan storage eksternal/CDN; batasi concurrency; queue untuk proses video (jika ada).
- **Data hilang (rekaman/unggah)**: backup berkala; checksum untuk file; retry upload.
- **Penilaian bias/inkonsisten**: pakai rubrik baku; training grader; audit sampling.
- **Komplain peserta**: sediakan kanal ticket; log keputusan (izin/reschedule/penilaian).

## 15) Integrasi Teknis (arah implementasi)
- **Cron/Queue**: job perubahan status session, reminder notifikasi, expire rekaman, SLA grading reminder, survei trigger.
- **Endpoints/UI** (arah):
  - Session CRUD (admin/instruktur), attendance marking, izin/reschedule approval.
  - Assignment CRUD + rubrik; submission CRUD (peserta); grading endpoint (instruktur).
  - Announcement CRUD; forum endpoints (thread/post/report).
  - Progress API: hitung dari attendance + assignment weights.
- **Logging**: audit trail untuk publish/unpublish, grading, izin/reschedule, akses rekaman (opsional).
