<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\BeritaController;
use App\Http\Controllers\Admin\GaleriController;
use App\Http\Controllers\Admin\OrgStructureController;
use App\Http\Controllers\Admin\PengumumanController;
use App\Http\Controllers\Admin\PesanController;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\InstructorController;
use App\Http\Controllers\Admin\BenefitController;
use App\Http\Controllers\Admin\FlowStepController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\TrainingServiceController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\TrainingScheduleController;
use App\Http\Controllers\Admin\EmpowermentController;
use App\Http\Controllers\Admin\ProductivityController;
use App\Http\Controllers\Admin\JobVacancyController;
use App\Http\Controllers\Admin\CertificationContentController;
use App\Http\Controllers\Admin\CertificationSchemeController;
use App\Http\Controllers\Admin\PublicationSettingController;
use App\Http\Controllers\Admin\PublicationCategoryController;
use App\Http\Controllers\Admin\PublicationItemController;
use App\Http\Controllers\Admin\InfographicYearController;
use App\Http\Controllers\Admin\InfographicMetricController;
use App\Http\Controllers\Admin\InfographicCardController;
use App\Http\Controllers\Admin\InfographicEmbedController;
use App\Http\Controllers\Admin\PublicServiceSettingController;
use App\Http\Controllers\Admin\PublicServiceFlowController;
use App\Http\Controllers\Admin\FaqSettingController;
use App\Http\Controllers\Admin\FaqCategoryController;
use App\Http\Controllers\Admin\FaqItemController;
use App\Http\Controllers\Admin\ContactSettingController;
use App\Http\Controllers\Admin\ContactChannelController;
use App\Http\Controllers\Admin\PpidSettingController;
use App\Http\Controllers\Admin\PpidHighlightController;
use App\Http\Controllers\Admin\PpidRequestController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BrandingKpiController;
use App\Http\Controllers\Admin\AlumniTracerController;

Route::get('/', [HomeController::class, 'index'])->name('home');


Route::get('/profil', function () { return view('profil'); })->name('profil');
Route::get('/program', [HomeController::class, 'katalogPelatihan'])->name('program');
Route::get('/kontak', [HomeController::class, 'kontak'])->name('kontak');
Route::get('/alumni/tracer', [HomeController::class, 'alumniTracerForm'])->name('alumni.tracer');
Route::post('/alumni/tracer', [HomeController::class, 'storeAlumniTracer'])->name('alumni.tracer.store');

Route::get('/pengumuman', [HomeController::class, 'pengumumanIndex'])->name('pengumuman.index');
Route::get('/pengumuman/{slug}', [HomeController::class, 'pengumumanShow'])->name('pengumuman.show');

Route::redirect('/berita', '/berita/terkini');
Route::get('/berita/terkini', [HomeController::class, 'beritaTerkini'])->name('berita.terkini');
Route::get('/berita/lowongan', [HomeController::class, 'lowonganKerja'])->name('berita.lowongan');
Route::get('/berita/lowongan/{lowongan}', [HomeController::class, 'lowonganDetail'])->name('berita.lowongan.detail');
Route::get('/berita/{slug}', [HomeController::class, 'showBerita'])->name('berita.show');
Route::prefix('resource')->name('resource.')->group(function () {
    Route::get('/infografis-alumni', [HomeController::class, 'resourceInfografis'])->name('infografis');
    Route::get('/publikasi', [HomeController::class, 'resourcePublikasi'])->name('publikasi');
    Route::get('/pelayanan-publik', [HomeController::class, 'resourcePelayanan'])->name('pelayanan');
    Route::get('/faq', [HomeController::class, 'resourceFaq'])->name('faq');
    Route::get('/hubungi-kami', [HomeController::class, 'resourceHubungi'])->name('hubungi');
});


Route::get('/program/{id}', [HomeController::class, 'showProgram'])->name('program.show');


Route::get('/profil/sejarah', [HomeController::class, 'sejarah'])->name('profil.sejarah');
Route::get('/profil/visi-misi', [HomeController::class, 'visiMisi'])->name('profil.visimisi');
Route::get('/profil/instansi', [HomeController::class, 'profilInstansi'])->name('profil.instansi');
Route::get('/profil/instruktur', [HomeController::class, 'profilInstruktur'])->name('profil.instruktur');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



Route::prefix('admin')->name('admin.')->middleware(['auth', 'permission:access-admin'])->group(function () {
    
    Route::get('/', App\Http\Controllers\Admin\DashboardController::class)->name('dashboard');

    Route::resource('berita', BeritaController::class);
    Route::patch('berita/{berita}/submit', [BeritaController::class, 'submit'])->name('berita.submit');
    Route::patch('berita/{berita}/approve', [BeritaController::class, 'approve'])->name('berita.approve')->middleware('permission:approve-content');
    Route::resource('program', ProgramController::class); 
    Route::resource('galeri', GaleriController::class);   
    Route::resource('pengumuman', PengumumanController::class);
    Route::resource('struktur', OrgStructureController::class)->except(['show']);
    Route::get('pesan', [PesanController::class, 'index'])->name('pesan.index');
    Route::resource('partner', PartnerController::class)->except(['show']);
    Route::resource('instructor', InstructorController::class)->except(['show']);
    Route::resource('benefit', BenefitController::class)->except(['show']);
    Route::resource('flow', FlowStepController::class)->except(['show']);
    Route::resource('alumni-tracer', AlumniTracerController::class)->only(['index','show','destroy']);
    Route::patch('alumni-tracer/{alumni_tracer}/verify', [AlumniTracerController::class, 'verify'])->name('alumni-tracer.verify');
    Route::resource('testimonial', TestimonialController::class)->except(['show']);
    Route::resource('training-service', TrainingServiceController::class)->except(['show']);
    Route::resource('training-schedule', TrainingScheduleController::class)->except(['show']);
    Route::resource('empowerment', EmpowermentController::class)->except(['show']);
    Route::resource('productivity', ProductivityController::class)->except(['show']);
    Route::resource('lowongan', JobVacancyController::class)->except(['show']);
    Route::resource('certification-content', CertificationContentController::class)->except(['show']);
    Route::resource('certification-scheme', CertificationSchemeController::class)->except(['show']);
    Route::get('publication/settings', [PublicationSettingController::class, 'edit'])->name('publication.settings');
    Route::put('publication/settings', [PublicationSettingController::class, 'update'])->name('publication.settings.update');
    Route::resource('publication-category', PublicationCategoryController::class)->except(['show']);
    Route::resource('publication-item', PublicationItemController::class)->except(['show']);
    Route::get('public-service/settings', [PublicServiceSettingController::class, 'edit'])->name('public-service.settings');
    Route::put('public-service/settings', [PublicServiceSettingController::class, 'update'])->name('public-service.settings.update');
    Route::resource('public-service-flow', PublicServiceFlowController::class)->except(['show']);
    Route::get('faq/settings', [FaqSettingController::class, 'edit'])->name('faq.settings');
    Route::put('faq/settings', [FaqSettingController::class, 'update'])->name('faq.settings.update');
    Route::resource('faq-category', FaqCategoryController::class)->except(['show']);
    Route::resource('faq-item', FaqItemController::class)->except(['show']);
    Route::get('contact/settings', [ContactSettingController::class, 'edit'])->name('contact.settings');
    Route::put('contact/settings', [ContactSettingController::class, 'update'])->name('contact.settings.update');
    Route::resource('contact-channel', ContactChannelController::class)->except(['show']);
    Route::get('ppid/settings', [PpidSettingController::class, 'edit'])->name('ppid.settings');
    Route::put('ppid/settings', [PpidSettingController::class, 'update'])->name('ppid.settings.update');
    Route::resource('ppid-highlight', PpidHighlightController::class)->except(['show']);
    Route::resource('ppid-request', PpidRequestController::class)->only(['index', 'show', 'destroy']);
    Route::resource('infographic-year', InfographicYearController::class)->except(['show']);
    Route::resource('infographic-metric', InfographicMetricController::class)->except(['show']);
    Route::resource('infographic-card', InfographicCardController::class)->except(['show']);
    Route::resource('infographic-embed', InfographicEmbedController::class)->except(['show']);
    Route::resource('profile', ProfileController::class)->only(['index', 'edit', 'update']);
    Route::get('settings/site', [SiteSettingController::class, 'edit'])->name('settings.site');
    Route::put('settings/site', [SiteSettingController::class, 'update'])->name('settings.site.update');
    Route::resource('users', UserController::class)->except(['show'])->middleware('permission:manage-users');
    Route::resource('roles', RoleController::class)->except(['show'])->middleware('permission:manage-access');
    Route::resource('permissions', PermissionController::class)->except(['show'])->middleware('permission:manage-access');
    Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index')->middleware('permission:manage-audit');
    Route::delete('activity-logs/{activity_log}', [ActivityLogController::class, 'destroy'])->name('activity-logs.destroy')->middleware('permission:manage-audit');
    Route::delete('activity-logs', [ActivityLogController::class, 'clear'])->name('activity-logs.clear')->middleware('permission:manage-audit');
    Route::get('branding-kpi/{branding_kpi}/download', [BrandingKpiController::class, 'download'])->name('branding-kpi.download');
    Route::resource('branding-kpi', BrandingKpiController::class);
});

Route::get('/profil/sejarah', [HomeController::class, 'sejarah'])->name('profil.sejarah');
Route::get('/profil/struktur-organisasi', [HomeController::class, 'struktur'])->name('profil.struktur');
Route::get('/kontak', [App\Http\Controllers\HomeController::class, 'kontak'])->name('kontak');
Route::post('/kontak', [App\Http\Controllers\HomeController::class, 'storeKontak'])->name('kontak.store');

Route::get('/pencarian', [HomeController::class, 'search'])->name('search');
Route::get('/pelatihan/katalog', [HomeController::class, 'katalogPelatihan'])->name('pelatihan.katalog');
Route::get('/pelatihan/jadwal', [HomeController::class, 'jadwalPelatihan'])->name('pelatihan.jadwal');
Route::get('/pelatihan/pemberdayaan', [HomeController::class, 'pemberdayaan'])->name('pelatihan.pemberdayaan');
Route::get('/pelatihan/produktivitas', [HomeController::class, 'produktivitas'])->name('pelatihan.produktivitas');
Route::get('/sertifikasi', [HomeController::class, 'sertifikasi'])->name('sertifikasi');
Route::get('/ppid', [HomeController::class, 'ppid'])->name('ppid');
Route::post('/ppid/permohonan', [HomeController::class, 'storePpidRequest'])->name('ppid.store');
// Profile admin routes now under admin prefix
