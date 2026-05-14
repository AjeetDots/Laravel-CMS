<?php
namespace Database\Seeders;
use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder {
    public function run(): void {
        $pages = [
            [
                'title'       => 'About Us',
                'slug'        => 'about',
                'template'    => 'default',
                'meta_title'  => 'About Us - Bespoke Ornate Plaster',
                'meta_description' => 'Learn more about Bespoke Ornate Plaster, our team, mission, and values.',
                'content'     => '<h2>About Bespoke Ornate Plaster</h2>
<p>We are a professional services company with over 10 years of experience delivering innovative digital solutions to businesses worldwide.</p>
<h3>Our Mission</h3>
<p>Our mission is to empower businesses with cutting-edge technology solutions that drive growth, efficiency, and competitive advantage.</p>
<h3>Our Team</h3>
<p>Our team of expert developers, designers, and consultants work collaboratively to bring your vision to life. We believe in building long-term partnerships with our clients.</p>
<h3>Why Choose Us?</h3>
<ul>
<li>10+ years of industry experience</li>
<li>500+ successfully delivered projects</li>
<li>24/7 dedicated support</li>
<li>Transparent and agile processes</li>
<li>Competitive and flexible pricing</li>
</ul>',
                'is_active'   => true,
            ],
            [
                'title'       => 'FAQ',
                'slug'        => 'faq',
                'template'    => 'default',
                'meta_title'  => 'Frequently Asked Questions - Bespoke Ornate Plaster',
                'meta_description' => 'Find answers to the most common questions about our services.',
                'content'     => '<h2>Frequently Asked Questions</h2>
<div class="faq-item">
<h4>How long does a typical project take?</h4>
<p>Project timelines vary based on scope and complexity. A simple website typically takes 2-4 weeks, while complex web applications can take 3-6 months.</p>
</div>
<div class="faq-item">
<h4>What technologies do you use?</h4>
<p>We use modern technologies including Laravel, React, Vue.js, Node.js, MySQL, and cloud platforms like AWS and Google Cloud.</p>
</div>
<div class="faq-item">
<h4>Do you provide post-launch support?</h4>
<p>Yes, we offer flexible support and maintenance packages to ensure your application runs smoothly after launch.</p>
</div>
<div class="faq-item">
<h4>How do I get started?</h4>
<p>Simply contact us through our contact form or give us a call. We will schedule a free consultation to discuss your requirements.</p>
</div>
<div class="faq-item">
<h4>What is your payment structure?</h4>
<p>We typically work with a 30% upfront deposit, 40% at mid-project milestone, and 30% upon completion and delivery.</p>
</div>',
                'is_active'   => true,
            ],
            [
                'title'       => 'Documentation',
                'slug'        => 'docs',
                'template'    => 'default',
                'meta_title'  => 'Documentation - Bespoke Ornate Plaster',
                'meta_description' => 'Technical documentation and guides for Bespoke Ornate Plaster.',
                'content'     => '<h2>Documentation</h2>
<p>Welcome to the Bespoke Ornate Plaster documentation center. Here you will find guides, tutorials, and technical references.</p>
<h3>Getting Started</h3>
<p>Follow our step-by-step guides to get up and running with our services quickly and efficiently.</p>
<h3>API Reference</h3>
<p>Detailed API documentation for developers integrating with our platform.</p>
<h3>User Guides</h3>
<p>Comprehensive user guides for all our products and services.</p>
<h3>Video Tutorials</h3>
<p>Watch our video tutorials for visual walkthroughs of key features and workflows.</p>
<p>For specific documentation, please contact our support team at <strong>docs@bespokeornateplaster.com</strong>.</p>',
                'is_active'   => true,
            ],
            [
                'title'       => 'Help Desk',
                'slug'        => 'help',
                'template'    => 'default',
                'meta_title'  => 'Help Desk - Bespoke Ornate Plaster',
                'meta_description' => 'Get help and support from the Bespoke Ornate Plaster team.',
                'content'     => '<h2>Help Desk</h2>
<p>Our support team is available 24/7 to assist you with any questions or issues.</p>
<h3>Contact Support</h3>
<ul>
<li><strong>Email:</strong> support@bespokeornateplaster.com</li>
<li><strong>Phone:</strong> +1 (555) 123-4567</li>
<li><strong>Live Chat:</strong> Available on the bottom right of every page</li>
</ul>
<h3>Support Hours</h3>
<p>Our team is available Monday through Friday, 9:00 AM to 6:00 PM EST. Emergency support is available 24/7 for critical issues.</p>
<h3>Submit a Ticket</h3>
<p>Use our contact form to submit a support ticket and our team will respond within 4 business hours.</p>',
                'is_active'   => true,
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(['slug' => $page['slug']], $page);
        }

        Page::updateOrCreate(
            ['slug' => 'contact-us'],
            [
                'title' => 'Contact Us',
                'slug' => 'contact-us',
                'template' => Page::TEMPLATE_CONTACT,
                'meta_title' => 'Contact Us',
                'meta_description' => 'Get in touch with Bespoke Ornate Plaster.',
                'content' => null,
                'body_order' => Page::BODY_ORDER_CONTENT_FIRST,
                'is_active' => true,
            ]
        );
    }
}
