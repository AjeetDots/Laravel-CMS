{{--
    Reusable SEO Panel — include in any admin form.

    Required variables passed via @include:
        $seo            — SeoMeta model or null (eager-loaded by controller)
        $titleFieldId   — ID of the page/post title <input>   (default: 'titleInput')
        $slugFieldId    — ID of the slug <input>              (default: 'slugInput')
        $contentFieldId — ID of the content <textarea>        (default: 'postContent')

    All SEO fields are submitted as seo[field_name] so they don't pollute the
    parent model's validated data.
--}}
@php
    if (!isset($seo))            { $seo            = null; }
    if (!isset($titleFieldId))   { $titleFieldId   = 'titleInput'; }
    if (!isset($slugFieldId))    { $slugFieldId    = 'slugInput'; }
    if (!isset($contentFieldId)) { $contentFieldId = 'postContent'; }
@endphp

<div class="card mt-4" id="seoPanel">
    {{-- ── Header / score badge ──────────────────────────────────────────────── --}}
    <div class="card-header d-flex align-items-center justify-content-between"
         style="cursor:pointer" data-bs-toggle="collapse" data-bs-target="#seoBody"
         aria-expanded="false">
        <span class="fw-semibold">
            <i class="fas fa-search-plus me-2 text-primary"></i>SEO Analysis
        </span>
        <span id="seoScoreBadge" class="badge rounded-pill bg-secondary px-3 py-2" style="font-size:.8rem;">
            — / 100
        </span>
    </div>

    <div class="collapse" id="seoBody">
        <div class="card-body">

            {{-- ── Google SERP Preview ─────────────────────────────────────────── --}}
            <div class="mb-4 p-3 rounded" style="background:#f8f9fa;border:1px solid #e0e0e0;">
                <div class="text-muted small mb-1">Google Search Preview</div>
                <div id="serpUrl"    class="text-truncate" style="color:#1a0dab;font-size:.85rem;"></div>
                <div id="serpTitle"  class="fw-semibold"   style="color:#1a0dab;font-size:1rem;line-height:1.3;"></div>
                <div id="serpDesc"   class="text-muted small mt-1" style="font-size:.82rem;line-height:1.4;"></div>
            </div>

            {{-- ── Focus keyword ───────────────────────────────────────────────── --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">
                    <i class="fas fa-key me-1 text-warning"></i>Focus Keyword
                </label>
                <input type="text" name="seo[focus_keyword]" id="seoKeyword" class="form-control"
                       value="{{ old('seo.focus_keyword', $seo?->focus_keyword) }}"
                       placeholder="e.g. web design agency">
                <div class="form-text">The main term this page should rank for.</div>
            </div>

            {{-- ── Meta Title ──────────────────────────────────────────────────── --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Meta Title
                    <span id="metaTitleCount" class="text-muted fw-normal small ms-1">(0 / 70)</span>
                </label>
                <input type="text" name="seo[meta_title]" id="seoMetaTitle" class="form-control"
                       value="{{ old('seo.meta_title', $seo?->meta_title) }}"
                       maxlength="70" placeholder="Defaults to page title if empty">
                <div class="progress mt-1" style="height:4px;">
                    <div id="metaTitleBar" class="progress-bar" style="width:0%;transition:width .2s;"></div>
                </div>
                <div class="form-text">Optimal: 30–60 characters. Shown in Google results.</div>
            </div>

            {{-- ── Meta Description ────────────────────────────────────────────── --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Meta Description
                    <span id="metaDescCount" class="text-muted fw-normal small ms-1">(0 / 165)</span>
                </label>
                <textarea name="seo[meta_description]" id="seoMetaDesc" class="form-control" rows="3"
                          maxlength="165" placeholder="Defaults to excerpt/description if empty">{{ old('seo.meta_description', $seo?->meta_description) }}</textarea>
                <div class="progress mt-1" style="height:4px;">
                    <div id="metaDescBar" class="progress-bar" style="width:0%;transition:width .2s;"></div>
                </div>
                <div class="form-text">Optimal: 120–165 characters. Shown under the title in Google.</div>
            </div>

            {{-- ── Canonical URL ────────────────────────────────────────────────── --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Canonical URL</label>
                <input type="url" name="seo[canonical_url]" class="form-control"
                       value="{{ old('seo.canonical_url', $seo?->canonical_url) }}"
                       placeholder="https://yourdomain.com/page-slug">
                <div class="form-text">Leave blank to use the current page URL.</div>
            </div>

            {{-- ── Robots ───────────────────────────────────────────────────────── --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">Robots Meta</label>
                <div class="row g-2">
                    <div class="col-6">
                        <select name="seo[robots_index]" class="form-select form-select-sm">
                            <option value="index"   {{ old('seo.robots_index',   $seo?->robots_index   ?? 'index')   === 'index'   ? 'selected' : '' }}>index</option>
                            <option value="noindex" {{ old('seo.robots_index',   $seo?->robots_index   ?? 'index')   === 'noindex' ? 'selected' : '' }}>noindex</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <select name="seo[robots_follow]" class="form-select form-select-sm">
                            <option value="follow"   {{ old('seo.robots_follow', $seo?->robots_follow ?? 'follow')   === 'follow'   ? 'selected' : '' }}>follow</option>
                            <option value="nofollow" {{ old('seo.robots_follow', $seo?->robots_follow ?? 'follow')   === 'nofollow' ? 'selected' : '' }}>nofollow</option>
                        </select>
                    </div>
                </div>
                <div class="form-text">Use <code>noindex</code> to hide drafts or thank-you pages from Google.</div>
            </div>

            {{-- ── SEO Checklist ────────────────────────────────────────────────── --}}
            <div class="mb-4">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="fw-semibold small">SEO Checklist</span>
                    <span id="seoRatingLabel" class="badge bg-secondary small">—</span>
                </div>
                <ul id="seoChecklist" class="list-unstyled mb-0 small" style="max-height:280px;overflow-y:auto;">
                    <li class="text-muted fst-italic">Fill in the focus keyword to start analysis…</li>
                </ul>
            </div>

            {{-- ── Open Graph ───────────────────────────────────────────────────── --}}
            <div class="border-top pt-3 mt-2">
                <div class="d-flex align-items-center justify-content-between mb-3"
                     data-bs-toggle="collapse" data-bs-target="#ogSection" style="cursor:pointer">
                    <span class="fw-semibold small">
                        <i class="fab fa-facebook me-1 text-primary"></i>Open Graph (Facebook / LinkedIn)
                    </span>
                    <i class="fas fa-chevron-down small text-muted"></i>
                </div>
                <div class="collapse" id="ogSection">
                    <div class="mb-3">
                        <label class="form-label small">OG Title</label>
                        <input type="text" name="seo[og_title]" class="form-control form-control-sm"
                               value="{{ old('seo.og_title', $seo?->og_title) }}"
                               maxlength="95" placeholder="Defaults to meta title">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">OG Description</label>
                        <textarea name="seo[og_description]" class="form-control form-control-sm" rows="2"
                                  maxlength="200" placeholder="Defaults to meta description">{{ old('seo.og_description', $seo?->og_description) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">OG Image URL</label>
                        <input type="text" name="seo[og_image]" class="form-control form-control-sm"
                               value="{{ old('seo.og_image', $seo?->og_image) }}"
                               placeholder="https://… (1200×630 px recommended)">
                        <div class="form-text">If blank, the featured image is used.</div>
                    </div>
                </div>
            </div>

            {{-- ── Twitter Card ─────────────────────────────────────────────────── --}}
            <div class="border-top pt-3 mt-2">
                <div class="d-flex align-items-center justify-content-between mb-3"
                     data-bs-toggle="collapse" data-bs-target="#twitterSection" style="cursor:pointer">
                    <span class="fw-semibold small">
                        <i class="fab fa-twitter me-1" style="color:#1da1f2;"></i>Twitter Card
                    </span>
                    <i class="fas fa-chevron-down small text-muted"></i>
                </div>
                <div class="collapse" id="twitterSection">
                    <div class="mb-3">
                        <label class="form-label small">Card Type</label>
                        <select name="seo[twitter_card]" class="form-select form-select-sm">
                            <option value="summary_large_image" {{ old('seo.twitter_card', $seo?->twitter_card ?? 'summary_large_image') === 'summary_large_image' ? 'selected' : '' }}>Summary with Large Image</option>
                            <option value="summary"             {{ old('seo.twitter_card', $seo?->twitter_card ?? 'summary_large_image') === 'summary'             ? 'selected' : '' }}>Summary</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Twitter Title</label>
                        <input type="text" name="seo[twitter_title]" class="form-control form-control-sm"
                               value="{{ old('seo.twitter_title', $seo?->twitter_title) }}"
                               maxlength="70" placeholder="Defaults to OG / meta title">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Twitter Description</label>
                        <textarea name="seo[twitter_description]" class="form-control form-control-sm" rows="2"
                                  maxlength="200" placeholder="Defaults to OG / meta description">{{ old('seo.twitter_description', $seo?->twitter_description) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Twitter Image URL</label>
                        <input type="text" name="seo[twitter_image]" class="form-control form-control-sm"
                               value="{{ old('seo.twitter_image', $seo?->twitter_image) }}"
                               placeholder="https://… (1200×628 px recommended)">
                    </div>
                </div>
            </div>

            {{-- ── Schema / JSON-LD ─────────────────────────────────────────────── --}}
            <div class="border-top pt-3 mt-2">
                <div class="d-flex align-items-center justify-content-between mb-3"
                     data-bs-toggle="collapse" data-bs-target="#schemaSection" style="cursor:pointer">
                    <span class="fw-semibold small">
                        <i class="fas fa-code me-1 text-success"></i>Schema / JSON-LD
                    </span>
                    <i class="fas fa-chevron-down small text-muted"></i>
                </div>
                <div class="collapse" id="schemaSection">
                    <textarea name="seo[schema_markup]" class="form-control form-control-sm font-monospace" rows="5"
                              placeholder='{"@@context":"https://schema.org","@@type":"Article","name":"..."}'>{{ old('seo.schema_markup', $seo?->schema_markup) }}</textarea>
                    <div class="form-text">Paste valid JSON-LD. It will be injected in a &lt;script type="application/ld+json"&gt; tag.</div>
                </div>
            </div>

        </div>{{-- /card-body --}}
    </div>{{-- /collapse --}}
</div>{{-- /card --}}

<script>
window._seoCfg = {
    titleId:   '{{ $titleFieldId }}',
    slugId:    '{{ $slugFieldId }}',
    contentId: '{{ $contentFieldId }}'
};
</script>
@verbatim
<script>
(function () {
    if (window.__seoAnalyzerBound) return;
    window.__seoAnalyzerBound = true;

    var cfg         = window._seoCfg;
    var titleField  = document.getElementById(cfg.titleId);
    var slugField   = document.getElementById(cfg.slugId);
    var contentField= document.getElementById(cfg.contentId);
    var kwField     = document.getElementById('seoKeyword');
    var metaTitle   = document.getElementById('seoMetaTitle');
    var metaDesc    = document.getElementById('seoMetaDesc');
    var serpUrl     = document.getElementById('serpUrl');
    var serpTitle   = document.getElementById('serpTitle');
    var serpDesc    = document.getElementById('serpDesc');
    var checklist   = document.getElementById('seoChecklist');
    var scoreBadge  = document.getElementById('seoScoreBadge');
    var ratingLabel = document.getElementById('seoRatingLabel');
    var metaTitleCount = document.getElementById('metaTitleCount');
    var metaDescCount  = document.getElementById('metaDescCount');
    var metaTitleBar   = document.getElementById('metaTitleBar');
    var metaDescBar    = document.getElementById('metaDescBar');

    function updateCounter(input, countEl, barEl, min, max) {
        var len = input.value.length;
        countEl.textContent = '(' + len + ' / ' + input.maxLength + ')';
        var pct = Math.min(100, Math.round((len / input.maxLength) * 100));
        barEl.style.width = pct + '%';
        if (len >= min && len <= max)  { barEl.className = 'progress-bar bg-success'; }
        else if (len > 0)              { barEl.className = 'progress-bar bg-warning'; }
        else                           { barEl.className = 'progress-bar bg-secondary'; }
    }

    metaTitle.addEventListener('input', function(){ updateCounter(metaTitle, metaTitleCount, metaTitleBar, 30, 60); });
    metaDesc.addEventListener('input',  function(){ updateCounter(metaDesc,  metaDescCount,  metaDescBar,  120, 165); });
    updateCounter(metaTitle, metaTitleCount, metaTitleBar, 30, 60);
    updateCounter(metaDesc,  metaDescCount,  metaDescBar,  120, 165);

    function updateSerp() {
        var title = metaTitle.value || (titleField ? titleField.value : '') || '(Page title)';
        var desc  = metaDesc.value  || '(Meta description will appear here...)';
        var slug  = (slugField ? slugField.value : '') || window.location.pathname;
        serpTitle.textContent = title.substring(0, 60);
        serpDesc.textContent  = desc.substring(0, 160);
        serpUrl.textContent   = window.location.hostname + '/' + slug.replace(/^\//, '');
    }

    [metaTitle, metaDesc, titleField, slugField].forEach(function(el) {
        if (el) el.addEventListener('input', updateSerp);
    });
    updateSerp();

    var STOP_WORDS = ['a','an','the','and','or','but','in','on','at','to','for','of','with','by','from','is','it'];

    function wordCount(text) {
        text = text.trim();
        return text === '' ? 0 : text.split(/\s+/).length;
    }

    function kwDensity(content, kw) {
        if (!kw || !content) return 0;
        var kwWords = wordCount(kw);
        var total   = wordCount(content);
        if (total === 0) return 0;
        var occur = content.toLowerCase().split(kw.toLowerCase()).length - 1;
        return Math.round((occur * kwWords / total) * 10000) / 100;
    }

    function runAnalysis() {
        var kw      = kwField.value.trim().toLowerCase();
        var title   = (metaTitle.value || (titleField ? titleField.value : '') || '').toLowerCase();
        var desc    = metaDesc.value.toLowerCase();
        var content = contentField ? contentField.value : '';
        var slug    = ((slugField ? slugField.value : '') || '').toLowerCase();

        var checks = [], points = 0, total = 0;

        function pass(label, w) { checks.push({label:label, status:'good'}); points += w; total += w; }
        function warn(label, w, pts) { checks.push({label:label, status:'ok'}); points += (pts !== undefined ? pts : Math.floor(w/2)); total += w; }
        function fail(label, w) { checks.push({label:label, status:'poor'}); total += w; }

        total += 5;
        if (kw) { checks.push({label:'Focus keyword is set', status:'good'}); points += 5; }
        else    { fail('No focus keyword — add one above', 5); renderChecklist(checks, 0); return; }

        title.indexOf(kw) >= 0   ? pass('Keyword in meta title', 15)       : fail('Keyword missing from meta title', 15);
        desc.indexOf(kw) >= 0    ? pass('Keyword in meta description', 10)  : fail('Include keyword in meta description', 10);

        var kwSlug = kw.replace(/\s+/g, '-');
        slug.indexOf(kwSlug) >= 0 ? pass('Keyword in URL slug', 10)         : warn('Keyword not in slug — consider renaming', 10);

        var intro = content.replace(/<[^>]+>/g, '').substring(0, 300).toLowerCase();
        intro.indexOf(kw) >= 0   ? pass('Keyword in introduction', 10)      : warn('Use keyword in first paragraph', 10);

        var d = kwDensity(content.replace(/<[^>]+>/g, ''), kw);
        if      (d >= 0.5 && d <= 3.0) pass('Keyword density: ' + d + '% (0.5-3%)', 10);
        else if (d > 0)                 warn('Keyword density: ' + d + '% — ' + (d < 0.5 ? 'too low' : 'too high'), 10, 5);
        else                            fail('Keyword not found in content body', 10);

        var wc = wordCount(content.replace(/<[^>]+>/g, ''));
        if      (wc >= 600) pass('Content: ' + wc + ' words (excellent)', 15);
        else if (wc >= 300) warn('Content: ' + wc + ' words — aim for 600+', 15, 8);
        else                fail('Content too short: ' + wc + ' words (min 300)', 15);

        var tl = metaTitle.value.length;
        if      (tl >= 30 && tl <= 60) pass('Meta title: ' + tl + ' chars (good)', 10);
        else if (tl > 0)               warn('Meta title: ' + tl + ' chars — ' + (tl < 30 ? 'too short' : 'too long'), 10, 4);
        else                           fail('Meta title is empty', 10);

        var dl = metaDesc.value.length;
        if      (dl >= 120 && dl <= 165) pass('Meta description: ' + dl + ' chars (good)', 10);
        else if (dl > 0)                 warn('Meta description: ' + dl + ' chars — ' + (dl < 120 ? 'too short' : 'too long'), 10, 4);
        else                             fail('Meta description is empty', 10);

        var parts   = slug.split('-').filter(function(p){ return p.length > 0; });
        var hasStop = parts.some(function(p){ return STOP_WORDS.indexOf(p) >= 0; });
        var tooLong = slug.length > 75;
        if (!hasStop && !tooLong && slug.length > 0) pass('URL slug is clean', 5);
        else if (tooLong) warn('Slug too long — shorten the URL', 5);
        else              warn('Remove stop words from slug', 5);

        var score = total > 0 ? Math.round((points / total) * 100) : 0;
        renderChecklist(checks, score);
    }

    function renderChecklist(checks, score) {
        var rating = score >= 80 ? 'good' : score >= 50 ? 'ok' : 'poor';
        var colors  = {good:'#28a745', ok:'#fd7e14', poor:'#dc3545'};
        var labels  = {good:'Good', ok:'Needs work', poor:'Poor'};
        var icons   = {good:'&#9989;', ok:'&#128993;', poor:'&#10060;'};

        scoreBadge.textContent        = score + ' / 100';
        scoreBadge.style.background   = colors[rating];
        ratingLabel.textContent       = labels[rating];
        ratingLabel.style.background  = colors[rating];

        checklist.innerHTML = checks.map(function(c){
            return '<li class="mb-1" style="color:' + colors[c.status] + '">' + icons[c.status] + ' ' + c.label + '</li>';
        }).join('');
    }

    [kwField, metaTitle, metaDesc, titleField, slugField, contentField].forEach(function(el){
        if (el) el.addEventListener('input', runAnalysis);
    });

    runAnalysis();
    updateSerp();
}());
</script>
@endverbatim
