<!-- ═══ ABOUT SECTION ═══ -->
<section id="about" class="section" style="background: var(--dark-bg-2); position:relative; overflow:hidden;">
    <div class="glow-dot" style="width:500px;height:500px;background:var(--accent);bottom:-150px;right:-150px;"></div>
    <div class="glow-dot" style="width:400px;height:400px;background:var(--primary);top:-100px;left:-100px;opacity:0.04;"></div>
    <div class="glow-line" style="width:100%;top:0;left:0;"></div>

    <div class="container">
        <!-- Skeleton -->
        <div id="about-skeleton" class="about-grid">
            <div>
                <div class="skeleton skeleton-text" style="width:130px;height:32px;margin-bottom:1.5rem;border-radius:999px;"></div>
                <div class="skeleton skeleton-text" style="width:320px;height:42px;margin-bottom:2rem;"></div>
                <div class="skeleton skeleton-text" style="height:16px;margin-bottom:0.5rem;"></div>
                <div class="skeleton skeleton-text" style="height:16px;margin-bottom:0.5rem;"></div>
                <div class="skeleton skeleton-text short" style="height:16px;"></div>
            </div>
            <div class="skeleton" style="height:420px;border-radius:var(--radius-xl);"></div>
        </div>

        <!-- Actual content -->
        <div id="about-content" style="display:none;"></div>
    </div>
</section>

@push('styles')
<style>
.about-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 4.5rem; align-items: center; }
.about-image-wrap {
    position: relative; border-radius: var(--radius-xl); overflow: hidden;
}
.about-image-wrap::after {
    content: ''; position: absolute; inset: 0;
    border-radius: inherit;
    border: 2px solid rgba(43,155,255,0.12);
    pointer-events: none;
}
.about-image-wrap img {
    width: 100%; display: block; border-radius: var(--radius-xl);
    transition: transform 0.6s ease;
}
.about-image-wrap:hover img { transform: scale(1.03); }
.about-image-badge {
    position: absolute; bottom: 1.5rem; left: 1.5rem;
    background: rgba(6,9,24,0.85); backdrop-filter: blur(12px);
    border: 1px solid rgba(43,155,255,0.15);
    padding: 0.85rem 1.25rem; border-radius: var(--radius-md);
    display: flex; align-items: center; gap: 0.75rem;
}
.about-mission {
    margin-top: 2rem; padding: 1.5rem 1.75rem;
    background: linear-gradient(135deg, rgba(43,155,255,0.06), rgba(168,85,247,0.03));
    border-left: 3px solid var(--primary); border-radius: 0 var(--radius-md) var(--radius-md) 0;
}
.about-features {
    display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;
    margin-top: 2rem;
}
.about-feature {
    display: flex; align-items: center; gap: 0.75rem;
    padding: 0.75rem; border-radius: var(--radius-sm);
    background: rgba(43,155,255,0.03);
    border: 1px solid rgba(43,155,255,0.05);
    transition: var(--transition-fast);
}
.about-feature:hover { border-color: rgba(43,155,255,0.15); background: rgba(43,155,255,0.06); }
.about-feature i { color: var(--green); font-size: 0.85rem; flex-shrink: 0; }
.about-feature span { font-size: 0.85rem; color: var(--text-secondary); font-weight: 500; }

@media (max-width: 768px) {
    .about-grid { grid-template-columns: 1fr !important; gap: 2.5rem !important; }
    .about-features { grid-template-columns: 1fr; }
}
</style>
@endpush

@push('scripts')
<script>
(function() {
    axios.get(API_BASE + '/about_us/show')
        .then(function(response) {
            var aboutUs = response.data.data;
            var skeleton = document.getElementById('about-skeleton');
            var content = document.getElementById('about-content');

            skeleton.style.display = 'none';
            if (!aboutUs) return;
            content.style.display = 'block';

            var imageHtml = aboutUs.image
                ? '<div data-aos="fade-left" data-aos-delay="200">' +
                    '<div class="about-image-wrap">' +
                        '<img src="' + getImageUrl(aboutUs.image) + '" alt="About HexaTerminal" loading="lazy">' +
                        '<div class="about-image-badge">' +
                            '<i class="fas fa-check-circle" style="color:var(--green);font-size:1.2rem;"></i>' +
                            '<div><span style="font-size:0.75rem;color:var(--text-muted);">Trusted by</span><br>' +
                            '<span style="font-size:0.95rem;font-weight:700;color:var(--text-primary);">100+ Clients</span></div>' +
                        '</div>' +
                    '</div>' +
                  '</div>'
                : '';

            var missionHtml = aboutUs.mission
                ? '<div class="about-mission">' +
                    '<h3 style="color:var(--primary);margin-bottom:0.5rem;font-weight:700;font-size:1.05rem;">' +
                        '<i class="fas fa-bullseye" style="margin-right:0.5rem;"></i>Our Mission</h3>' +
                    '<p style="color:var(--text-secondary);line-height:1.85;font-size:0.92rem;">' + aboutUs.mission + '</p>' +
                  '</div>'
                : '';

            content.innerHTML =
                '<div class="about-grid">' +
                    '<div data-aos="fade-right">' +
                        '<span class="section-badge"><i class="fas fa-info-circle"></i> Who We Are</span>' +
                        '<h2 style="font-size:clamp(2rem,3.5vw,2.75rem);font-weight:800;margin-bottom:1.5rem;color:var(--text-primary);line-height:1.2;letter-spacing:-0.02em;">About Us</h2>' +
                        '<div style="color:var(--text-secondary);line-height:1.9;font-size:0.92rem;">' +
                            (aboutUs.description || aboutUs.content || 'No description available.') +
                        '</div>' +
                        missionHtml +
                        '<div class="about-features">' +
                            '<div class="about-feature"><i class="fas fa-check-circle"></i><span>Expert Team</span></div>' +
                            '<div class="about-feature"><i class="fas fa-check-circle"></i><span>Modern Tech</span></div>' +
                            '<div class="about-feature"><i class="fas fa-check-circle"></i><span>24/7 Support</span></div>' +
                            '<div class="about-feature"><i class="fas fa-check-circle"></i><span>Fast Delivery</span></div>' +
                        '</div>' +
                    '</div>' +
                    imageHtml +
                '</div>';

            AOS.refresh();
        })
        .catch(function(error) {
            console.error('Failed to load about us:', error);
            document.getElementById('about-skeleton').style.display = 'none';
        });
})();
</script>
@endpush
