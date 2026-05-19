@php
    use App\Support\UserManualUrls;
    $abs = !empty($wordExport);
@endphp
    <div class="card">
        <div class="card-body p-3 p-lg-4">

            <section id="intro" class="manual-section">
                <h2 class="h4 mb-3">Welcome</h2>
                <p>This website is managed through your <strong>admin panel</strong> (the area you are in now). You can update text, images, services, testimonials, partner brands, blog posts, menus, and SEO settings yourself. Your developer handles server setup, new features, and anything that breaks.</p>
                <div class="manual-callout manual-callout--info">
                    <strong>Login:</strong> Go to <code>/admin/login</code> on your domain (for example <code>https://yourdomain.com/admin/login</code>). Use the email and password your developer gave you. If you forget your password, use <strong>Forgot password</strong> on that page.
                </div>
                <p>After you save changes, click <strong>View site</strong> in the top bar to check the live website in a new tab. If images or menus look old, click <strong>Refresh site caches</strong> first, then view the site again.</p>
            </section>

            <section id="admin-basics" class="manual-section">
                <h2 class="h4 mb-3">Using the admin panel</h2>
                <p>The screen has two main areas:</p>
                <ul>
                    <li><strong>Left sidebar</strong> — All tools grouped under Content, Appearance, Content Hub, and Settings.</li>
                    <li><strong>Top bar</strong> — Refresh caches, open this manual, view the public site, and your account menu.</li>
                </ul>
                <figure class="manual-figure">
                    <img src="{{ UserManualUrls::asset('img/user-manual/admin-topbar.svg', $abs) }}" alt="Diagram of the admin top bar showing User manual beside View site" width="720" height="72" loading="lazy">
                    <figcaption>Top bar: use <strong>User manual</strong> anytime; <strong>View site</strong> opens your live homepage.</figcaption>
                </figure>
                <h3 class="h6 mt-4">Common actions on list pages</h3>
                <ul class="small text-muted">
                    <li><strong>Add</strong> — Creates a new item (service, testimonial, blog post, etc.).</li>
                    <li><strong>Edit (pen icon)</strong> — Change an existing item.</li>
                    <li><strong>Delete (trash)</strong> — Removes it permanently; confirm when asked.</li>
                    <li><strong>Active / Inactive</strong> — Inactive items are hidden on the website but kept in the admin.</li>
                    <li><strong>Sort order</strong> — Lower numbers appear first where ordering applies.</li>
                </ul>
            </section>

            <section id="site-map" class="manual-section">
                <h2 class="h4 mb-3">How the site is built</h2>
                <p>Visitors see a <strong>homepage</strong> made of sections (hero, services, testimonials, brands, and more). Other main pages include Services, Finishes, Portfolio, Gallery, Blog, About, and Contact. Extra pages can be added under <strong>Appearance → Pages</strong>.</p>
                <figure class="manual-figure">
                    <img src="{{ UserManualUrls::asset('img/user-manual/home-sections-map.svg', $abs) }}" alt="Diagram listing home page sections from hero to blog preview" width="640" height="520" loading="lazy">
                    <figcaption>Home page order (top to bottom). Highlighted boxes are covered in detail below.</figcaption>
                </figure>
                <div class="table-responsive">
                    <table class="table table-sm manual-table align-middle">
                        <thead>
                            <tr>
                                <th>What visitors see</th>
                                <th>Where you edit content</th>
                                <th>Where you edit section headings</th>
                            </tr>
                        </thead>
                        <tbody class="small">
                            <tr>
                                <td>Large hero banner at top</td>
                                <td><a href="{{ UserManualUrls::route('admin.sliders.index', [], $abs) }}">Content → Sliders</a></td>
                                <td>— (text is on each slide)</td>
                            </tr>
                            <tr>
                                <td>Services cards (usually 3 on home)</td>
                                <td><a href="{{ UserManualUrls::route('admin.services.index', [], $abs) }}">Content → Services</a></td>
                                <td><a href="{{ UserManualUrls::route('admin.theme-options.home.index', [], $abs) }}">Content Hub → Home → Services</a></td>
                            </tr>
                            <tr>
                                <td>Client testimonials slider</td>
                                <td><a href="{{ UserManualUrls::route('admin.testimonials.index', [], $abs) }}">Content → Testimonials</a></td>
                                <td><a href="{{ UserManualUrls::route('admin.theme-options.home.index', [], $abs) }}">Content Hub → Home → Testimonials</a></td>
                            </tr>
                            <tr>
                                <td>Partner / brand logos strip</td>
                                <td><a href="{{ UserManualUrls::route('admin.brands.index', [], $abs) }}">Content → Brands</a></td>
                                <td><a href="{{ UserManualUrls::route('admin.theme-options.home.index', [], $abs) }}">Content Hub → Home → Brands Strip</a></td>
                            </tr>
                            <tr>
                                <td>Header menu &amp; footer links</td>
                                <td><a href="{{ UserManualUrls::route('admin.menus.index', [], $abs) }}">Appearance → Menus</a> &amp; <a href="{{ UserManualUrls::route('admin.footer-navigation.edit', [], $abs) }}">Footer navigation</a></td>
                                <td>—</td>
                            </tr>
                            <tr>
                                <td>Site name, email, address, logos</td>
                                <td><a href="{{ UserManualUrls::route('admin.settings.index', [], $abs) }}">Settings → Site settings</a></td>
                                <td>—</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <section id="services" class="manual-section">
                <h2 class="h4 mb-3">Services</h2>
                <p><strong>What they are:</strong> Your main offerings (for example plaster types, design packages, or installation services). Each service has a title, short description, images, and a full detail page.</p>
                <p><strong>Where they show:</strong></p>
                <ul>
                    <li><strong>Home page</strong> — The first <strong>three active</strong> services (by sort order) appear as cards in the Services section.</li>
                    <li><strong>Services page</strong> (<code>/services</code>) — Lists all active services.</li>
                    <li><strong>Single service page</strong> (<code>/services/your-slug</code>) — Full description when a visitor clicks a card.</li>
                </ul>
                <figure class="manual-figure">
                    <img src="{{ UserManualUrls::asset('img/user-manual/services-brands-testimonials.svg', $abs) }}" alt="Illustration of services cards, testimonial slider, and brand logos" width="720" height="280" loading="lazy">
                    <figcaption>Services (left), Testimonials (centre), Brands (right) — three separate areas on the home page.</figcaption>
                </figure>
                <h3 class="h6">How to add or edit a service</h3>
                <ol class="manual-steps">
                    <li>Open <a href="{{ UserManualUrls::route('admin.services.index', [], $abs) }}">Content → Services</a>.</li>
                    <li>Click <strong>Add Service</strong> or the pen icon on an existing row.</li>
                    <li>Fill in <strong>Title</strong> and <strong>Short description</strong> (used on cards). Add a <strong>main image</strong> and optional hover image.</li>
                    <li>Use <strong>Full description</strong> for the detail page. Add <strong>Feature highlights</strong> for bullet points on the listing page.</li>
                    <li>Set <strong>Sort order</strong> — lower numbers appear first. Keep <strong>Active</strong> on to show on the site.</li>
                    <li>Scroll to <strong>SEO Analysis</strong> (see <a href="#seo">SEO section</a>), then click <strong>Save</strong>.</li>
                    <li>Click <strong>View site</strong> and check Home and Services pages.</li>
                </ol>
                <div class="manual-callout">
                    <strong>Section headings on home</strong> (eyebrow text, two-line headline, “View all” button) are edited in <a href="{{ UserManualUrls::route('admin.theme-options.home.index', [], $abs) }}">Content Hub → Home Page</a> → <strong>Services</strong> tab — not on the individual service form.
                </div>
                <p class="mb-0 small text-muted">Services page intro text: <a href="{{ UserManualUrls::route('admin.theme-options.services.index', [], $abs) }}">Content Hub → Services page</a>.</p>
            </section>

            <section id="testimonials" class="manual-section">
                <h2 class="h4 mb-3">Testimonials</h2>
                <p><strong>What they are:</strong> Quotes from clients — name, role, company, message, and a photo.</p>
                <p><strong>Where they show:</strong> On the <strong>home page</strong> in the testimonials slider (only if you have at least one testimonial saved). The left panel image and headlines come from <strong>Content Hub → Home → Testimonials</strong>.</p>
                <h3 class="h6">How to add a testimonial</h3>
                <ol class="manual-steps">
                    <li>Go to <a href="{{ UserManualUrls::route('admin.testimonials.index', [], $abs) }}">Content → Testimonials</a> → <strong>Add Testimonial</strong>.</li>
                    <li>Enter <strong>Client name</strong> and <strong>Message</strong> (required).</li>
                    <li>Upload a <strong>client photo</strong> — required for display on the home slider.</li>
                    <li>Optional: position, company, star rating, sort order.</li>
                    <li>Save, then check the home page testimonial section.</li>
                </ol>
                <div class="manual-callout manual-callout--warn">
                    If testimonials do not appear, confirm you have saved at least one with a photo, and that the home testimonials section is enabled in <a href="{{ UserManualUrls::route('admin.theme-options.home.index', [], $abs) }}">Home Page → Testimonials</a>.
                </div>
            </section>

            <section id="brands" class="manual-section">
                <h2 class="h4 mb-3">Brands (partner logos)</h2>
                <p><strong>What they are:</strong> Logos of partners, suppliers, or press — usually shown in a scrolling strip.</p>
                <p><strong>Where they show:</strong> Near the <strong>bottom of the home page</strong> (Brands strip), if the section is enabled and you have active brands with logos.</p>
                <h3 class="h6">How to add a brand</h3>
                <ol class="manual-steps">
                    <li>Open <a href="{{ UserManualUrls::route('admin.brands.index', [], $abs) }}">Content → Brands</a> → <strong>Add Brand</strong>.</li>
                    <li>Enter <strong>Brand name</strong> and upload a <strong>logo</strong> (PNG with transparent background works best).</li>
                    <li>Set <strong>Sort order</strong> (must be unique). Turn <strong>Active</strong> on.</li>
                    <li>Optional website URL (for your records; logos may not be clickable on the front).</li>
                    <li>Edit strip title and “show section” in <a href="{{ UserManualUrls::route('admin.theme-options.home.index', [], $abs) }}">Home Page → Brands Strip</a>.</li>
                </ol>
            </section>

            <section id="other-content" class="manual-section">
                <h2 class="h4 mb-3">Other content you may update</h2>
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead class="manual-table">
                            <tr><th>Module</th><th>Purpose</th><th>Front-end location</th></tr>
                        </thead>
                        <tbody class="small">
                            <tr>
                                <td><a href="{{ UserManualUrls::route('admin.sliders.index', [], $abs) }}">Sliders</a></td>
                                <td>Hero banner slides (image, headline, buttons)</td>
                                <td>Top of home page</td>
                            </tr>
                            <tr>
                                <td><a href="{{ UserManualUrls::route('admin.finishes.index', [], $abs) }}">Finishes</a></td>
                                <td>Material / finish types with images</td>
                                <td>Home preview + <code>/finishes</code></td>
                            </tr>
                            <tr>
                                <td><a href="{{ UserManualUrls::route('admin.portfolio.index', [], $abs) }}">Portfolio</a></td>
                                <td>Project case studies</td>
                                <td><code>/portfolio</code></td>
                            </tr>
                            <tr>
                                <td><a href="{{ UserManualUrls::route('admin.gallery.index', [], $abs) }}">Gallery</a></td>
                                <td>Photo gallery with categories</td>
                                <td><code>/gallery</code></td>
                            </tr>
                            <tr>
                                <td><a href="{{ UserManualUrls::route('admin.blog.index', [], $abs) }}">Blog</a></td>
                                <td>News and articles</td>
                                <td><code>/blog</code> + home blog preview</td>
                            </tr>
                            <tr>
                                <td><a href="{{ UserManualUrls::route('admin.pages.index', [], $abs) }}">Pages</a></td>
                                <td>Custom pages (About, FAQ, policies)</td>
                                <td><code>/page-slug</code></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p class="small text-muted mb-0">Each listing page has its own headings and intro in <strong>Content Hub</strong> (Finishes page, Portfolio page, Gallery page, About page, Contact page, etc.).</p>
            </section>

            <section id="content-hub" class="manual-section">
                <h2 class="h4 mb-3">Content Hub (home page sections)</h2>
                <p><strong>Content Hub → Home Page</strong> controls the <em>labels and layout settings</em> for each home section — not the individual services or testimonials themselves.</p>
                <ul>
                    <li>Each tab (Atelier, Finishes, Services, Why, Process, Testimonials, Brands Strip, etc.) matches a block on the homepage.</li>
                    <li>Use the <strong>Show section on home page</strong> switch to hide a whole block without deleting data.</li>
                    <li>One <strong>Save</strong> at the bottom updates every tab — you do not need to visit each tab before saving.</li>
                </ul>
                <p>Open: <a href="{{ UserManualUrls::route('admin.theme-options.home.index', [], $abs) }}">Content Hub → Home Page settings</a></p>
            </section>

            <section id="menus-logos" class="manual-section">
                <h2 class="h4 mb-3">Menus &amp; logos</h2>

                <h3 class="h6">Header menu</h3>
                <ol class="manual-steps">
                    <li>Go to <a href="{{ UserManualUrls::route('admin.menus.index', [], $abs) }}">Appearance → Menus</a>.</li>
                    <li>Drag the reorder handle (grip icon) to change top-level menu order.</li>
                    <li>Click <strong>Add Menu Item</strong> or edit an existing row.</li>
                    <li>Choose a <strong>page</strong> from the list or pick <strong>Custom URL</strong> for external links (full <code>https://</code> or paths like <code>/contact</code>).</li>
                    <li>Set <strong>Active</strong> on. Parent items can have sub-menus (indented rows).</li>
                </ol>

                <h3 class="h6 mt-4">Footer menu</h3>
                <p>Edit columns and links at <a href="{{ UserManualUrls::route('admin.footer-navigation.edit', [], $abs) }}">Appearance → Footer navigation</a>.</p>

                <h3 class="h6 mt-4">Logos &amp; favicon</h3>
                <ol class="manual-steps">
                    <li>Open <a href="{{ UserManualUrls::route('admin.settings.index', [], $abs) }}?tab=logos">Site settings → Site logos</a> tab.</li>
                    <li><strong>Header logo</strong> — main site logo (light background).</li>
                    <li><strong>Backend logo</strong> — admin sidebar only.</li>
                    <li><strong>Footer logo</strong> — light version for dark footer.</li>
                    <li><strong>Favicon</strong> — small icon in browser tabs (32×32 PNG recommended).</li>
                    <li>Click <strong>Save settings</strong>, then <strong>Refresh site caches</strong> if the old logo still shows on the live site.</li>
                </ol>
                <figure class="manual-figure">
                    <img src="{{ UserManualUrls::asset('img/user-manual/site-settings-logos.svg', $abs) }}" alt="Diagram of Site logos settings tab" width="520" height="240" loading="lazy">
                    <figcaption>Site settings → Site logos tab. One save updates all settings tabs.</figcaption>
                </figure>

                <h3 class="h6 mt-4">General site details</h3>
                <p>In <a href="{{ UserManualUrls::route('admin.settings.index', [], $abs) }}">Site settings → General</a>: site name, contact email, phone, address. <strong>Social</strong> tab: Instagram, Facebook, etc. These often appear in the footer and contact areas.</p>
            </section>

            <section id="seo" class="manual-section">
                <h2 class="h4 mb-3">SEO (search engines)</h2>
                <p>SEO helps Google and other search engines understand your pages. Many content forms include an expandable <strong>SEO Analysis</strong> panel with a score, Google preview, and checklist.</p>
                <figure class="manual-figure">
                    <img src="{{ UserManualUrls::asset('img/user-manual/seo-panel.svg', $abs) }}" alt="Diagram of SEO analysis panel with score and Google preview" width="560" height="300" loading="lazy">
                    <figcaption>Click the SEO Analysis header to expand. Aim for a green score before publishing important pages.</figcaption>
                </figure>

                <h3 class="h6">Fields explained (plain language)</h3>
                <ul class="small">
                    <li><strong>Focus keyword</strong> — The main phrase you want to rank for (e.g. “ornate plaster London”). Use it naturally in the title and first paragraph.</li>
                    <li><strong>Meta title</strong> — Blue clickable title in Google (about 30–60 characters). Empty defaults to the page title.</li>
                    <li><strong>Meta description</strong> — Grey text under the title in Google (about 120–165 characters).</li>
                    <li><strong>Canonical URL</strong> — Leave blank unless your developer says otherwise.</li>
                    <li><strong>Robots</strong> — Use <code>noindex</code> only for private or thank-you pages you do not want in Google.</li>
                    <li><strong>Open Graph / Twitter</strong> — How the page looks when shared on social media (optional images and text).</li>
                </ul>

                <h3 class="h6 mt-3">Where SEO is available</h3>
                <p class="small">Services, Portfolio, Blog posts, and custom Pages (and similar content types). Fill the main title and body first — the checklist updates as you type.</p>

                <h3 class="h6 mt-3">Site-wide SEO</h3>
                <p class="small mb-0">Your site automatically provides <code>/sitemap.xml</code> and <code>/robots.txt</code> for search engines. Ask your developer before changing server or domain settings.</p>
            </section>

            <section id="communication" class="manual-section">
                <h2 class="h4 mb-3">Messages &amp; email</h2>
                <ul>
                    <li><strong>Communication → Email</strong> — Contact form submissions (<a href="{{ UserManualUrls::route('admin.enquiries.index', [], $abs) }}">Enquiries</a>). Open a message to read and reply. Export if needed.</li>
                    <li><strong>Newsletter</strong> — Subscribers from the footer form; export or remove addresses.</li>
                    <li><strong>Email templates</strong> — Wording of automated emails; use shortcodes as shown in the template guide. Ask your developer before major template changes.</li>
                    <li><strong>Site settings → SMTP</strong> — Outgoing mail server; usually set once by your developer.</li>
                </ul>
            </section>

            <section id="tips" class="manual-section">
                <h2 class="h4 mb-3">Tips &amp; when to call your developer</h2>
                <h3 class="h6">You can usually do yourself</h3>
                <ul>
                    <li>Update text, images, services, testimonials, brands, blog posts</li>
                    <li>Reorder menus, change logos, edit home section headings</li>
                    <li>Improve SEO fields on existing pages</li>
                    <li>Reply to contact enquiries and manage newsletter list</li>
                </ul>
                <h3 class="h6">Contact your developer when</h3>
                <ul>
                    <li>The site is down, shows errors, or admin login fails</li>
                    <li>You need a new page layout, form field, or payment integration</li>
                    <li>Email stops sending after SMTP or domain changes</li>
                    <li>You change domain name, SSL, or hosting</li>
                    <li>Something saved in admin but never appears after cache refresh</li>
                </ul>
                <div class="manual-callout">
                    <strong>After most updates:</strong> Save → <strong>Refresh site caches</strong> → <strong>View site</strong> → hard-refresh browser (<kbd>Ctrl</kbd>+<kbd>F5</kbd> on Windows) if needed.
                </div>
            </section>

            <section id="quick-links" class="manual-section">
                <h2 class="h4 mb-3">Quick links</h2>
                <div class="manual-link-grid">
                    <a href="{{ UserManualUrls::route('admin.dashboard', [], $abs) }}"><i class="fas fa-th-large me-2 text-muted"></i>Dashboard</a>
                    <a href="{{ UserManualUrls::route('admin.sliders.index', [], $abs) }}"><i class="fas fa-images me-2 text-muted"></i>Sliders</a>
                    <a href="{{ UserManualUrls::route('admin.services.index', [], $abs) }}"><i class="fas fa-concierge-bell me-2 text-muted"></i>Services</a>
                    <a href="{{ UserManualUrls::route('admin.testimonials.index', [], $abs) }}"><i class="fas fa-quote-right me-2 text-muted"></i>Testimonials</a>
                    <a href="{{ UserManualUrls::route('admin.brands.index', [], $abs) }}"><i class="fas fa-star me-2 text-muted"></i>Brands</a>
                    <a href="{{ UserManualUrls::route('admin.theme-options.home.index', [], $abs) }}"><i class="fas fa-house me-2 text-muted"></i>Home Page</a>
                    <a href="{{ UserManualUrls::route('admin.menus.index', [], $abs) }}"><i class="fas fa-bars me-2 text-muted"></i>Menus</a>
                    <a href="{{ UserManualUrls::route('admin.settings.index', [], $abs) }}"><i class="fas fa-sliders-h me-2 text-muted"></i>Site settings</a>
                    <a href="{{ UserManualUrls::route('admin.enquiries.index', [], $abs) }}"><i class="fas fa-envelope me-2 text-muted"></i>Enquiries</a>
                    <a href="{{ UserManualUrls::route('admin.blog.index', [], $abs) }}"><i class="fas fa-pen-nib me-2 text-muted"></i>Blog</a>
                    <a href="{{ UserManualUrls::route('home', [], $abs) }}" target="_blank" rel="noopener"><i class="fas fa-external-link-alt me-2 text-muted"></i>View live site</a>
                </div>
                @if($abs)
                <p class="small mt-4 mb-0">Last updated: {{ $exportedAt->format('F j, Y') ?? now()->format('F j, Y') }}. Re-download from the admin panel User manual page when content changes.</p>
                @else
                <p class="text-muted small mt-4 mb-0">Last updated: {{ now()->format('F j, Y') }}. Use <strong>Open in Microsoft Word</strong> above to download a copy for printing or sharing.</p>
                @endif
            </section>
