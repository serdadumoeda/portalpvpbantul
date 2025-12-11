@php
    $metaTitle = old('meta_title', optional($model)->meta_title);
    $metaDescription = old('meta_description', optional($model)->meta_description);
    $focusKeyword = old('focus_keyword', optional($model)->focus_keyword);
    $slugSource = $slugField ?? null;
    $titleSource = $titleField ?? '[name=judul]';
    $excerptSource = $excerptField ?? '[name=excerpt]';
    $baseUrl = $baseUrl ?? config('app.url');
@endphp

<div class="card mt-4 seo-tool" data-seo-root data-title-source="{{ $titleSource }}" data-excerpt-source="{{ $excerptSource }}" data-base-url="{{ $baseUrl }}">
    <div class="card-header bg-white">
        <h5 class="mb-0">SEO Tools</h5>
        <small class="text-muted">Optimalkan judul & deskripsi agar mudah ditemukan.</small>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Meta Title</label>
            <input type="text" name="meta_title" class="form-control" value="{{ $metaTitle }}" data-seo-meta-title>
            <small class="text-muted">Saran 50-60 karakter. <span class="fw-semibold" data-seo-title-count>0</span> karakter.</small>
        </div>
        <div class="mb-3">
            <label class="form-label">Meta Description</label>
            <textarea name="meta_description" rows="3" class="form-control" data-seo-meta-description>{{ $metaDescription }}</textarea>
            <small class="text-muted">Saran 120-160 karakter. <span class="fw-semibold" data-seo-description-count>0</span> karakter.</small>
        </div>
        <div class="mb-3">
            <label class="form-label">Focus Keyword</label>
            <input type="text" name="focus_keyword" class="form-control" value="{{ $focusKeyword }}" data-seo-keyword>
            <small class="text-muted">Gunakan kata kunci utama (misal: "pelatihan welding").</small>
        </div>

        <div class="seo-preview border rounded p-3 mb-3 bg-body-secondary">
            <div class="small text-success" data-seo-url>{{ $baseUrl }}</div>
            <div class="fw-semibold text-primary" data-seo-preview-title>Judul Konten</div>
            <div class="text-muted" data-seo-preview-description>Deskripsi akan tampil di sini.</div>
        </div>

        <div class="d-flex justify-content-between small text-muted">
            <div>Skor SEO: <span class="fw-semibold" data-seo-score>0</span>/100</div>
            <div data-seo-hints></div>
        </div>
    </div>
</div>

@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('[data-seo-root]').forEach(root => {
                    const metaTitle = root.querySelector('[data-seo-meta-title]');
                    const metaDesc = root.querySelector('[data-seo-meta-description]');
                    const keyword = root.querySelector('[data-seo-keyword]');
                    const titleFallback = document.querySelector(root.dataset.titleSource);
                    const excerptFallback = document.querySelector(root.dataset.excerptSource);
                    const titlePreview = root.querySelector('[data-seo-preview-title]');
                    const descPreview = root.querySelector('[data-seo-preview-description]');
                    const urlPreview = root.querySelector('[data-seo-url]');
                    const titleCount = root.querySelector('[data-seo-title-count]');
                    const descCount = root.querySelector('[data-seo-description-count]');
                    const scoreEl = root.querySelector('[data-seo-score]');
                    const hints = root.querySelector('[data-seo-hints]');

                    const update = () => {
                        const title = (metaTitle?.value.trim()) || (titleFallback?.value ?? 'Judul Konten');
                        const description = (metaDesc?.value.trim()) || (excerptFallback?.value ?? 'Deskripsi konten portal PVP Bantul.');
                        const keywordValue = keyword?.value.trim().toLowerCase() || '';
                        const slug = slugify(titleFallback?.value ?? title);
                        urlPreview.textContent = `${root.dataset.baseUrl}/${slug}`;
                        titlePreview.textContent = title;
                        descPreview.textContent = description;
                        titleCount.textContent = title.length;
                        descCount.textContent = description.length;
                        toggleHighlight(titleCount, title.length >= 50 && title.length <= 60);
                        toggleHighlight(descCount, description.length >= 120 && description.length <= 160);
                        const titleHasKeyword = keywordValue && title.toLowerCase().includes(keywordValue);
                        const descHasKeyword = keywordValue && description.toLowerCase().includes(keywordValue);
                        let score = 40;
                        if (title.length >= 50 && title.length <= 60) score += 20;
                        if (description.length >= 120 && description.length <= 160) score += 20;
                        if (titleHasKeyword) score += 10;
                        if (descHasKeyword) score += 10;
                        scoreEl.textContent = score;
                        hints.textContent = keywordValue ? (titleHasKeyword && descHasKeyword ? 'Keyword terdeteksi' : 'Tambahkan keyword di judul/deskripsi') : 'Isi focus keyword';
                    };

                    const slugify = (str) => {
                        return str.toString().toLowerCase()
                            .replace(/\s+/g, '-')
                            .replace(/[^\w\-]+/g, '')
                            .replace(/\-\-+/g, '-')
                            .replace(/^-+/, '')
                            .replace(/-+$/, '');
                    };

                    const toggleHighlight = (el, condition) => {
                        el.classList.toggle('text-success', condition);
                        el.classList.toggle('text-danger', !condition);
                    };

                    [metaTitle, metaDesc, keyword, titleFallback, excerptFallback].forEach(input => {
                        if (input) {
                            input.addEventListener('input', update);
                        }
                    });

                    update();
                });
            });
        </script>
    @endpush
@endonce
