@php
    use App\Support\UserManualUrls;
    $abs = !empty($wordExport);
@endphp
    <div class="card">
        <div class="card-body p-3 p-lg-4">

            <section id="intro" class="manual-section">
                <h2 class="h4 mb-3">Welcome</h2>
                <p>This website is managed through your <strong>admin panel</strong> (the area you are in now). You can update text, images, hero sliders, services, finishes, portfolio, gallery, testimonials, partner brands, blog posts, menus, contact messages, and SEO settings yourself. Your developer handles server setup, new features, and anything that breaks.</p>
                <div class="manual-callout manual-callout--info">
                    <strong>Login:</strong> Go to <code>/admin/login</code> on your domain (for example <code>https://yourdomain.com/admin/login</code>). Use the email and password for your admin account. If you forget your password, see <a href="#account-security">Login, password &amp; account</a> below for the full reset steps.
                </div>
                <p>After you save changes, click <strong>View site</strong> in the top bar to check the live website in a new tab. If images or menus look old, click <strong>Refresh site caches</strong> first, then view the site again.</p>
            </section>

            <section id="account-security" class="manual-section">
                <h2 class="h4 mb-3">Login, password &amp; account</h2>
                <p>Your admin sign-in is separate from the public website. Only people with an admin account can open <code>/admin</code>. This section explains how to reset a forgotten password by email, and how to change your email or password while you are already signed in.</p>

                <h3 class="h6 mt-4">Forgot your password?</h3>
                <p>Use this when you are <strong>signed out</strong> and cannot log in. You do not need your old password — the system emails you a secure link instead.</p>
                <ol class="manual-steps">
                    <li>Open your admin login page: <code>/admin/login</code> on your domain (same address you normally use, for example <code>https://yourdomain.com/admin/login</code>).</li>
                    <li>Click <strong>Forgot password?</strong> under the password field (or go directly to <code>/admin/forgot-password</code>).</li>
                    <li>Enter the <strong>email address on your admin account</strong> — it must match exactly what is stored for your user. Click <strong>Send reset link</strong>.</li>
                    <li>Check that inbox (and spam/junk). You should receive an email with a button or link to reset your password. If nothing arrives after a few minutes, confirm the email is correct and try again, or ask your developer to check that outgoing mail is configured on the server.</li>
                    <li>Click the link in the email. Your browser opens a <strong>Choose a new password</strong> page. The email field is filled in automatically from the link — do not change it.</li>
                    <li>Enter a new password (at least 8 characters) and confirm it, then click <strong>Update password</strong>.</li>
                    <li>Return to <strong>sign in</strong> and log in with your email and the new password you just set.</li>
                </ol>
                <div class="manual-callout manual-callout--warn">
                    <strong>Reset links expire.</strong> Each link is valid for about {{ (int) config('auth.passwords.users.expire', 60) }} minutes and works only once. If it says the link is invalid or expired, go back to <strong>Forgot password?</strong> and request a fresh email. Open the link from the newest message — older emails will not work.
                </div>
                <p class="small text-muted mb-0">For security, the forgot-password page does not tell you whether an email exists in the system. If you use the wrong address, you will not receive a reset email.</p>

                <h3 class="h6 mt-4">Change email or password while signed in</h3>
                <p>When you are already in the admin panel, open <a href="{{ UserManualUrls::route('admin.profile.edit', [], $abs) }}">Settings → Account</a> in the left sidebar (or click your name in the top-right corner and choose <strong>Account</strong>).</p>

                <h4 class="h6 mt-3">Change your password</h4>
                <ol class="manual-steps">
                    <li>On the Account page, expand <strong>Change password</strong> (optional).</li>
                    <li>Enter your <strong>current password</strong>, then your <strong>new password</strong> and <strong>confirm</strong> it (minimum 8 characters).</li>
                    <li>Click <strong>Save changes</strong> at the bottom of the form.</li>
                </ol>
                <p class="small text-muted">Leave the password fields empty if you only want to update your name or photo. If you enter the wrong current password three times, password changes are blocked for 24 hours — sign out and use <strong>Forgot password?</strong> on the login page if you need access sooner.</p>

                <h4 class="h6 mt-3">Change your login email</h4>
                <p>Your login email is protected: a change is not final until you prove you control the <strong>current</strong> inbox.</p>
                <ol class="manual-steps">
                    <li>On the Account page, edit <strong>Email address</strong> to the new address you want.</li>
                    <li>Click <strong>Save changes</strong>. The system sends a <strong>6-digit verification code</strong> to your <em>current</em> email (the one you are signed in with now), not the new address yet.</li>
                    <li>Check that inbox for the code. In the <strong>Confirm new email</strong> box on the same page, enter the six digits and submit <strong>Confirm email</strong>.</li>
                    <li>When the code is accepted, your login email updates. Use the new address the next time you sign in.</li>
                </ol>
                <div class="manual-callout manual-callout--info">
                    <strong>Email change tips:</strong> Each code expires in {{ (int) config('cms.admin_email_change_otp_ttl', 15) }} minutes and can only be used once. You can use <strong>Resend code</strong> after that same {{ (int) config('cms.admin_email_change_otp_ttl', 15) }}-minute window if needed. Three wrong codes block email verification for 24 hours. Use <strong>Cancel email change</strong> if you want to keep your current address.
                </div>
                <p class="mb-0 small text-muted">Changing your profile photo or display name does not require a verification code — just save the form.</p>
            </section>

            <section id="admin-basics" class="manual-section">
                <h2 class="h4 mb-3">Using the admin panel</h2>
                <p>The screen has two main areas:</p>
                <ul>
                    <li><strong>Left sidebar</strong> — All tools grouped under Content, Appearance, Content Hub, and Settings. Some items expand to show sub-menus (Gallery, Communication, Blog, Appearance, Content Hub).</li>
                    <li><strong>Top bar</strong> — Refresh caches, open this manual, view the public site, and your account menu (Account, Sign out).</li>
                </ul>
                <figure class="manual-figure">
                    <img src="{{ UserManualUrls::asset('img/user-manual/admin-topbar.svg', $abs) }}" alt="Diagram of the admin top bar showing User manual beside View site" width="720" height="72" loading="lazy">
                    <figcaption>Top bar: use <strong>User manual</strong> anytime; <strong>View site</strong> opens your live homepage.</figcaption>
                </figure>

                <h3 class="h6 mt-4" id="dashboard">Dashboard</h3>
                <p>The <a href="{{ UserManualUrls::route('admin.dashboard', [], $abs) }}">Dashboard</a> is your home screen after login. It shows quick counts (enquiries, services, finishes, portfolio, gallery) and recent contact messages. Click any stat card to jump to that section. Unread enquiries show a red badge on <strong>Communication → Email</strong> in the sidebar.</p>

                <h3 class="h6 mt-4">Refresh site caches</h3>
                <p>After saving content, logos, menus, or Content Hub settings, click <strong>Refresh site caches</strong> in the top bar before you check the live site. This clears stored copies so visitors see your latest changes. It is safe to run anytime; wait until the button finishes (it briefly shows “Refreshing…”). If something still looks old, use <strong>View site</strong> and hard-refresh the browser (<kbd>Ctrl</kbd>+<kbd>F5</kbd> on Windows).</p>

                <h3 class="h6 mt-4">Where to find everything (sidebar map)</h3>
                <div class="table-responsive">
                    <table class="table table-sm manual-table align-middle small">
                        <thead><tr><th>Sidebar</th><th>What it does</th></tr></thead>
                        <tbody>
                            <tr><td>Dashboard</td><td>Overview and recent enquiries</td></tr>
                            <tr><td>Content → Sliders, Services, Finishes, Portfolio</td><td>Individual items shown on the site</td></tr>
                            <tr><td>Content → Gallery / Gallery Categories</td><td>Photos and filters for <code>/gallery</code></td></tr>
                            <tr><td>Content → Testimonials, Brands</td><td>Home slider quotes and partner logos</td></tr>
                            <tr><td>Content → Communication</td><td>Enquiries, email templates, newsletter list</td></tr>
                            <tr><td>Content → Blog / Categories</td><td>Articles and blog groupings</td></tr>
                            <tr><td>Appearance → Pages, Menus, Footer navigation</td><td>Extra pages, header menu, footer columns</td></tr>
                            <tr><td>Content Hub</td><td>Page intros and home section headings (not the items themselves)</td></tr>
                            <tr><td>Settings → Site settings</td><td>Contact details, logos, social, mail, notifications</td></tr>
                            <tr><td>Settings → Account</td><td>Your login email, password, profile photo</td></tr>
                        </tbody>
                    </table>
                </div>

                <h3 class="h6 mt-4">Common actions on list pages</h3>
                <ul class="small text-muted">
                    <li><strong>Add</strong> — Creates a new item (service, testimonial, blog post, etc.).</li>
                    <li><strong>Edit (pen icon)</strong> — Change an existing item.</li>
                    <li><strong>Delete (trash)</strong> — Removes it permanently; confirm when asked.</li>
                    <li><strong>Active / Inactive</strong> — Inactive items are hidden on the website but kept in the admin.</li>
                    <li><strong>Sort order</strong> — Lower numbers appear first where ordering applies.</li>
                    <li><strong>Search / filters</strong> — Many lists include a toolbar to filter by status or search by title.</li>
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
                                <td>Atelier / intro block</td>
                                <td>—</td>
                                <td><a href="{{ UserManualUrls::route('admin.theme-options.home.index', [], $abs) }}">Content Hub → Home → Atelier</a></td>
                            </tr>
                            <tr>
                                <td>Finishes preview on home</td>
                                <td><a href="{{ UserManualUrls::route('admin.finishes.index', [], $abs) }}">Content → Finishes</a></td>
                                <td><a href="{{ UserManualUrls::route('admin.theme-options.home.index', [], $abs) }}">Content Hub → Home → Finishes</a></td>
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
                                <td>Why choose us / Process / Commissions</td>
                                <td>—</td>
                                <td><a href="{{ UserManualUrls::route('admin.theme-options.home.index', [], $abs) }}">Content Hub → Home → Why / Process / Commissions</a></td>
                            </tr>
                            <tr>
                                <td>Partner / brand logos strip</td>
                                <td><a href="{{ UserManualUrls::route('admin.brands.index', [], $abs) }}">Content → Brands</a></td>
                                <td><a href="{{ UserManualUrls::route('admin.theme-options.home.index', [], $abs) }}">Content Hub → Home → Brands Strip</a></td>
                            </tr>
                            <tr>
                                <td>Begin CTA / Contact band / Blog preview</td>
                                <td><a href="{{ UserManualUrls::route('admin.blog.index', [], $abs) }}">Content → Blog</a> (for posts)</td>
                                <td><a href="{{ UserManualUrls::route('admin.theme-options.home.index', [], $abs) }}">Content Hub → Home → Begin CTA / Contact band / Blog preview</a></td>
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
                <p>Besides Services, Testimonials, and Brands (covered above), these modules manage the rest of your site content.</p>

                <h3 class="h6" id="sliders">Hero sliders</h3>
                <p><a href="{{ UserManualUrls::route('admin.sliders.index', [], $abs) }}">Content → Sliders</a> controls the rotating banner at the top of the home page. Each slide has a background image, headline, optional lead paragraph, subtitle (eyebrow), and up to two buttons (primary gold and secondary outline). Lower <strong>sort order</strong> appears first. Only <strong>active</strong> slides rotate.</p>
                <ol class="manual-steps">
                    <li>Add or edit a slide → upload a wide hero image (landscape works best).</li>
                    <li>Fill <strong>Title</strong> (main headline). Leave button text blank to hide a button on the live site.</li>
                    <li>Set button links to internal paths (e.g. <code>/contact</code>) or full <code>https://</code> URLs.</li>
                    <li>Save, refresh caches, and check the home page hero.</li>
                </ol>

                <h3 class="h6 mt-4" id="finishes-portfolio">Finishes &amp; portfolio</h3>
                <p><strong>Finishes</strong> (<a href="{{ UserManualUrls::route('admin.finishes.index', [], $abs) }}">Content → Finishes</a>) — material or style types with images; shown on <code>/finishes</code> and in the home finishes preview. <strong>Portfolio</strong> (<a href="{{ UserManualUrls::route('admin.portfolio.index', [], $abs) }}">Content → Portfolio</a>) — project case studies on <code>/portfolio</code>. Both use title, descriptions, images, sort order, active status, and SEO panels similar to services.</p>
                <p class="small text-muted">Listing page titles and intros: <a href="{{ UserManualUrls::route('admin.theme-options.finishes.index', [], $abs) }}">Content Hub → Finishes page</a> and <a href="{{ UserManualUrls::route('admin.theme-options.portfolio.index', [], $abs) }}">Portfolio page</a>.</p>

                <h3 class="h6 mt-4" id="gallery">Gallery &amp; categories</h3>
                <p>Under <strong>Content → Gallery</strong>:</p>
                <ul class="small">
                    <li><a href="{{ UserManualUrls::route('admin.gallery-categories.index', [], $abs) }}">Gallery Categories</a> — create groups visitors can filter by (e.g. Residential, Commercial).</li>
                    <li><a href="{{ UserManualUrls::route('admin.gallery.index', [], $abs) }}">All Gallery</a> — upload images, assign a category, caption, and sort order.</li>
                </ul>
                <p class="small text-muted mb-0">Gallery page headings: <a href="{{ UserManualUrls::route('admin.theme-options.gallery.index', [], $abs) }}">Content Hub → Gallery page</a>.</p>

                <h3 class="h6 mt-4" id="blog">Blog posts &amp; categories</h3>
                <p><a href="{{ UserManualUrls::route('admin.blog.index', [], $abs) }}">Content → Blog → All Posts</a> — articles on <code>/blog</code> and the home blog preview section. Use <strong>Add Post</strong> for new articles: title, slug (auto from title if empty), excerpt, full content (rich editor), featured image, category, publish date, and active status.</p>
                <p><a href="{{ UserManualUrls::route('admin.categories.index', [], $abs) }}">Blog → Categories</a> — organise posts (e.g. News, Tips). Assign a category when editing each post. Latest active posts can appear in the home <strong>Blog preview</strong> section (controlled in Content Hub → Home).</p>

                <h3 class="h6 mt-4" id="pages">Custom pages</h3>
                <p><a href="{{ UserManualUrls::route('admin.pages.index', [], $abs) }}">Appearance → Pages</a> — extra pages at <code>/your-page-slug</code> (policies, FAQs, landing pages).</p>
                <ul class="small">
                    <li><strong>Default / Full width / With sidebar</strong> — general content pages you build with sections.</li>
                    <li><strong>System templates</strong> (About, Contact, Home, Services, etc.) — tied to main site routes; editing the page record sets the URL slug, while most visible copy lives in <strong>Content Hub</strong> for that template. The page edit screen may show a shortcut link to the matching Content Hub screen.</li>
                </ul>
                <p>On editable pages, use <strong>Add section</strong> to stack blocks (text, images, columns). Collapse sections to work on one at a time. Set SEO on the page form before publishing.</p>
                <div class="manual-callout manual-callout--warn">
                    Do not delete system pages (About, Contact, Home) unless your developer agrees — they power main navigation routes.
                </div>
            </section>

            <section id="content-hub" class="manual-section">
                <h2 class="h4 mb-3">Content Hub</h2>
                <p><strong>Content Hub</strong> is where you edit <em>page intros, section headings, and home layout labels</em> — not the individual services, slides, or blog posts (those live under <strong>Content</strong>).</p>

                <h3 class="h6">Home Page tabs</h3>
                <p><a href="{{ UserManualUrls::route('admin.theme-options.home.index', [], $abs) }}">Content Hub → Home Page</a> has one tab per homepage block (top to bottom on the live site):</p>
                <ul class="small">
                    <li><strong>Atelier</strong> — intro / story block below the hero</li>
                    <li><strong>Finishes</strong> — headings for the finishes preview (items from Content → Finishes)</li>
                    <li><strong>Services</strong> — section eyebrow, headline, “view all” link</li>
                    <li><strong>Why</strong>, <strong>Process</strong>, <strong>Commissions</strong> — supporting story sections</li>
                    <li><strong>Testimonials</strong> — left panel image and titles (quotes from Content → Testimonials)</li>
                    <li><strong>Begin CTA</strong>, <strong>Contact band</strong> — call-to-action strips</li>
                    <li><strong>Brands Strip</strong> — strip title and show/hide (logos from Content → Brands)</li>
                    <li><strong>Blog preview</strong> — headings for latest posts (posts from Content → Blog)</li>
                </ul>
                <p>On each tab, use <strong>Show section on home page</strong> to hide a block without deleting underlying content. One <strong>Save</strong> at the bottom saves all tabs together.</p>

                <h3 class="h6 mt-4">Other Content Hub screens</h3>
                <ul class="small mb-0">
                    <li><a href="{{ UserManualUrls::route('admin.theme-options.services.index', [], $abs) }}">Services page</a> — top of <code>/services</code></li>
                    <li><a href="{{ UserManualUrls::route('admin.theme-options.finishes.index', [], $abs) }}">Finishes page</a> — top of <code>/finishes</code></li>
                    <li><a href="{{ UserManualUrls::route('admin.theme-options.portfolio.index', [], $abs) }}">Portfolio page</a> — top of <code>/portfolio</code></li>
                    <li><a href="{{ UserManualUrls::route('admin.theme-options.gallery.index', [], $abs) }}">Gallery page</a> — top of <code>/gallery</code></li>
                    <li><a href="{{ UserManualUrls::route('admin.theme-options.about.index', [], $abs) }}">About page</a> — main About content areas</li>
                    <li><a href="{{ UserManualUrls::route('admin.theme-options.contact.index', [], $abs) }}">Contact page</a> — contact layout, map text, form labels</li>
                    <li><a href="{{ UserManualUrls::route('admin.theme-options.newsletter-footer.index', [], $abs) }}">Footer newsletter</a> — footer signup wording and visibility</li>
                </ul>
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
                <p>In <a href="{{ UserManualUrls::route('admin.settings.index', [], $abs) }}">Site settings</a>, use the left tabs — <strong>one Save settings button updates every tab at once</strong>:</p>
                <ul class="small">
                    <li><strong>General</strong> — site name, public contact email, phone, address (shown on contact areas and used as fallback mail).</li>
                    <li><strong>Notifications</strong> — <em>admin notification email</em>: where copies of contact-form submissions and optional newsletter signup alerts are sent. Leave empty to use the General contact email.</li>
                    <li><strong>Social</strong> — Instagram, Facebook, LinkedIn, etc. for footer and sharing.</li>
                    <li><strong>Site logos</strong> — header, admin sidebar, footer, favicon (see above).</li>
                    <li><strong>SMTP</strong> — outgoing mail server; usually configured once by your developer. Required for password resets, enquiry emails, and email-change codes.</li>
                </ul>
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
                <p class="small">Services, Finishes, Portfolio, Blog posts, and custom Pages (and similar content types). Fill the main title and body first — the checklist updates as you type.</p>

                <h3 class="h6 mt-3">Site-wide SEO</h3>
                <p class="small mb-0">Your site automatically provides <code>/sitemap.xml</code> and <code>/robots.txt</code> for search engines. Ask your developer before changing server or domain settings.</p>
            </section>

            <section id="communication" class="manual-section">
                <h2 class="h4 mb-3">Messages &amp; email</h2>

                <h3 class="h6">Contact enquiries</h3>
                <p><a href="{{ UserManualUrls::route('admin.enquiries.index', [], $abs) }}">Communication → Email</a> lists messages from your website contact form. Unread messages show a badge on the sidebar.</p>
                <ol class="manual-steps">
                    <li>Click a row to open <strong>View Message</strong> — read the full text and sender details.</li>
                    <li>Reply to the visitor in your normal email app using the address shown (mailto link).</li>
                    <li>Expand <strong>Reply / Follow-up Log</strong> to record how you responded (email, phone, or other) and notes for your team. This is an internal log only — it does not email the visitor.</li>
                    <li>Use <strong>Export</strong> on the list page for a spreadsheet backup if needed.</li>
                    <li><strong>Delete</strong> removes the enquiry from the inbox permanently.</li>
                </ol>
                <p class="small text-muted">The detail view shows <strong>Client mail</strong> and <strong>Admin mail</strong> status (whether automatic acknowledgement emails were sent). An <strong>Email Delivery Log</strong> lists each automated send attempt.</p>

                <h3 class="h6 mt-4">Newsletter subscribers</h3>
                <p><a href="{{ UserManualUrls::route('admin.newsletter.index', [], $abs) }}">Communication → Newsletter</a> — emails collected from the footer signup. <strong>Export CSV</strong> downloads the list. Trash icon removes a subscriber (they may sign up again later). Footer signup text is edited in <a href="{{ UserManualUrls::route('admin.theme-options.newsletter-footer.index', [], $abs) }}">Content Hub → Footer newsletter</a>.</p>

                <h3 class="h6 mt-4">Email templates</h3>
                <p><a href="{{ UserManualUrls::route('admin.email-templates.index', [], $abs) }}">Communication → Email Templates</a> — wording for automated emails (contact confirmations, admin alerts, etc.). Tabs group templates by audience (visitor vs admin). Each template shows detected <strong>placeholders</strong> (e.g. visitor name) — keep those tags in the text so data fills in correctly.</p>
                <div class="manual-callout manual-callout--warn">
                    If a template is <strong>Inactive</strong>, that email is not sent. Admin copies use the address from <strong>Site settings → Notifications</strong> when configured.
                </div>
            </section>

            <section id="tips" class="manual-section">
                <h2 class="h4 mb-3">Tips &amp; when to call your developer</h2>
                <h3 class="h6">You can usually do yourself</h3>
                <ul>
                    <li>Update text, images, sliders, services, finishes, portfolio, gallery, testimonials, brands, blog posts</li>
                    <li>Reorder menus, change logos, edit Content Hub section headings</li>
                    <li>Improve SEO fields on existing pages</li>
                    <li>Read and log contact enquiries, export newsletter subscribers</li>
                    <li>Change your account password or email (with verification)</li>
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
                    <a href="{{ UserManualUrls::route('admin.finishes.index', [], $abs) }}"><i class="fas fa-paint-brush me-2 text-muted"></i>Finishes</a>
                    <a href="{{ UserManualUrls::route('admin.portfolio.index', [], $abs) }}"><i class="fas fa-briefcase me-2 text-muted"></i>Portfolio</a>
                    <a href="{{ UserManualUrls::route('admin.gallery.index', [], $abs) }}"><i class="fas fa-photo-video me-2 text-muted"></i>Gallery</a>
                    <a href="{{ UserManualUrls::route('admin.services.index', [], $abs) }}"><i class="fas fa-concierge-bell me-2 text-muted"></i>Services</a>
                    <a href="{{ UserManualUrls::route('admin.testimonials.index', [], $abs) }}"><i class="fas fa-quote-right me-2 text-muted"></i>Testimonials</a>
                    <a href="{{ UserManualUrls::route('admin.brands.index', [], $abs) }}"><i class="fas fa-star me-2 text-muted"></i>Brands</a>
                    <a href="{{ UserManualUrls::route('admin.theme-options.home.index', [], $abs) }}"><i class="fas fa-house me-2 text-muted"></i>Home Page</a>
                    <a href="{{ UserManualUrls::route('admin.pages.index', [], $abs) }}"><i class="fas fa-file-alt me-2 text-muted"></i>Pages</a>
                    <a href="{{ UserManualUrls::route('admin.menus.index', [], $abs) }}"><i class="fas fa-bars me-2 text-muted"></i>Menus</a>
                    <a href="{{ UserManualUrls::route('admin.theme-options.contact.index', [], $abs) }}"><i class="fas fa-envelope me-2 text-muted"></i>Contact page</a>
                    <a href="{{ UserManualUrls::route('admin.email-templates.index', [], $abs) }}"><i class="fas fa-mail-bulk me-2 text-muted"></i>Email templates</a>
                    <a href="{{ UserManualUrls::route('admin.newsletter.index', [], $abs) }}"><i class="fas fa-paper-plane me-2 text-muted"></i>Newsletter</a>
                    <a href="{{ UserManualUrls::route('admin.settings.index', [], $abs) }}"><i class="fas fa-sliders-h me-2 text-muted"></i>Site settings</a>
                    <a href="{{ UserManualUrls::route('admin.profile.edit', [], $abs) }}"><i class="fas fa-user-circle me-2 text-muted"></i>Account</a>
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
