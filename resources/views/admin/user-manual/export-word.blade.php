<html xmlns:o="urn:schemas-microsoft-com:office:office"
      xmlns:w="urn:schemas-microsoft-com:office:word"
      xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta charset="utf-8">
    <title>{{ $siteName }} — Website User Manual</title>
    <!--[if gte mso 9]>
    <xml>
        <w:WordDocument>
            <w:View>Print</w:View>
            <w:Zoom>100</w:Zoom>
            <w:DoNotOptimizeForBrowser/>
        </w:WordDocument>
    </xml>
    <![endif]-->
    <style>
        @page { margin: 2cm; }
        body {
            font-family: Calibri, Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.45;
            color: #1e293b;
        }
        h1 { font-size: 22pt; color: #3d2f1d; margin: 0 0 12pt; }
        h2 { font-size: 14pt; color: #3d2f1d; margin: 24pt 0 8pt; page-break-after: avoid; }
        h3 { font-size: 12pt; margin: 14pt 0 6pt; page-break-after: avoid; }
        p { margin: 0 0 8pt; }
        ul, ol { margin: 0 0 10pt 18pt; }
        table { border-collapse: collapse; width: 100%; margin: 10pt 0 14pt; }
        th, td { border: 1px solid #cbd5e1; padding: 6pt 8pt; text-align: left; vertical-align: top; font-size: 10pt; }
        th { background: #f1f5f9; font-weight: bold; }
        a { color: #1a56db; }
        code { font-family: Consolas, monospace; font-size: 9.5pt; background: #f1f5f9; padding: 1pt 3pt; }
        .cover-meta { color: #64748b; font-size: 10pt; margin-bottom: 18pt; }
        .toc { margin: 0 0 20pt 18pt; }
        .toc li { margin-bottom: 4pt; }
        .manual-callout {
            border-left: 3pt solid #b79860;
            background: #faf8f4;
            padding: 8pt 10pt;
            margin: 10pt 0;
        }
        .manual-callout--info { border-left-color: #0ea5e9; background: #f0f9ff; }
        .manual-callout--warn { border-left-color: #f59e0b; background: #fffbeb; }
        .manual-figure { margin: 12pt 0; text-align: center; }
        .manual-figure img { max-width: 100%; height: auto; }
        .manual-figure figcaption { font-size: 9pt; color: #64748b; margin-top: 6pt; }
        .manual-section { margin-bottom: 18pt; page-break-inside: avoid; }
        kbd { font-family: Calibri, sans-serif; font-size: 9pt; border: 1px solid #ccc; padding: 1pt 4pt; }
    </style>
</head>
<body>

    <h1>{{ $siteName }} — Website User Manual</h1>
    <p class="cover-meta">
        Exported on {{ $exportedAt->format('F j, Y') }}.
        Open this file in Microsoft Word to read, print, or edit.
        Admin links below work when you are online and logged in.
    </p>

    <h2>Contents</h2>
    <ol class="toc">
        <li><a href="#intro">Welcome</a></li>
        <li><a href="#admin-basics">Using the admin panel</a></li>
        <li><a href="#site-map">How the site is built</a></li>
        <li><a href="#services">Services</a></li>
        <li><a href="#testimonials">Testimonials</a></li>
        <li><a href="#brands">Brands</a></li>
        <li><a href="#other-content">Other content</a></li>
        <li><a href="#content-hub">Content Hub (home page)</a></li>
        <li><a href="#menus-logos">Menus &amp; logos</a></li>
        <li><a href="#seo">SEO</a></li>
        <li><a href="#communication">Messages &amp; email</a></li>
        <li><a href="#tips">Tips &amp; when to call your developer</a></li>
        <li><a href="#quick-links">Quick links</a></li>
    </ol>

    @include('admin.user-manual.partials.content', ['wordExport' => true])

</body>
</html>
