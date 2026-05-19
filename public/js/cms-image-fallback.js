(function () {
    var root = document.documentElement;
    var defaultSrc = root && root.dataset ? root.dataset.cmsDefaultImage : '';
    if (!defaultSrc) {
        return;
    }

    function applyFallback(img) {
        if (!img || img.tagName !== 'IMG') {
            return;
        }
        if (img.dataset.cmsFallbackApplied === '1') {
            return;
        }
        var current = img.currentSrc || img.src || '';
        if (current && current.indexOf(defaultSrc) !== -1) {
            return;
        }
        img.dataset.cmsFallbackApplied = '1';
        img.src = defaultSrc;
    }

    document.addEventListener(
        'error',
        function (event) {
            applyFallback(event.target);
        },
        true
    );

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', scanImages);
    } else {
        scanImages();
    }

    function scanImages() {
        document.querySelectorAll('img').forEach(function (img) {
            if (img.complete && img.naturalWidth === 0 && img.src) {
                applyFallback(img);
            }
        });
    }
})();
