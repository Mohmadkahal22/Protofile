<!-- ═══ SERVICES SECTION ═══ -->
<section id="services" class="section" style="background: var(--dark-bg-2); position:relative; overflow:hidden;">
    <div class="glow-dot" style="width:500px;height:500px;background:var(--primary);top:-150px;left:-150px;"></div>
    <div class="glow-dot" style="width:400px;height:400px;background:var(--accent);bottom:-100px;right:-100px;opacity:0.04;"></div>
    <div class="glow-line" style="width:100%;top:0;left:0;"></div>

    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-badge"><i class="fas fa-cogs"></i> What We Do</span>
            <h2 class="section-title">Our Services</h2>
            <p class="section-subtitle">Innovative software solutions tailored to drive your business forward</p>
        </div>

        <!-- Skeleton -->
        <div id="services-skeleton" class="grid-3">
            @for($i = 0; $i < 6; $i++)
            <div class="skeleton skeleton-card"></div>
            @endfor
        </div>

        <!-- Actual content -->
        <div id="services-grid" class="grid-3" style="display:none;"></div>

        <!-- Empty state -->
        <div id="services-empty" style="display:none;text-align:center;padding:4rem 0;">
            <div style="width:80px;height:80px;margin:0 auto 1.5rem;border-radius:50%;background:rgba(43,155,255,0.08);display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-cogs" style="font-size:2rem;color:var(--primary);"></i>
            </div>
            <p style="font-size:1.1rem;color:var(--text-secondary);font-weight:500;">Services coming soon!</p>
            <p style="font-size:0.85rem;color:var(--text-muted);margin-top:0.5rem;">We're preparing something amazing.</p>
        </div>
    </div>
</section>

@push('styles')
<style>
.service-card {
    position: relative;
    padding: 2.25rem;
    transition: var(--transition);
}
.service-card::after {
    content: ''; position: absolute; bottom: 0; left: 2rem; right: 2rem;
    height: 2px; background: linear-gradient(90deg, var(--primary), var(--accent));
    transform: scaleX(0); transition: transform 0.4s ease;
    border-radius: 1px;
}
.card:hover .service-card::after { transform: scaleX(1); }
.service-icon {
    width: 64px; height: 64px;
    background: linear-gradient(135deg, rgba(43,155,255,0.12), rgba(168,85,247,0.08));
    border: 1px solid rgba(43,155,255,0.15);
    border-radius: var(--radius-md);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.6rem; margin-bottom: 1.5rem;
    transition: var(--transition);
}
.card:hover .service-icon {
    background: linear-gradient(135deg, var(--primary), var(--accent));
    border-color: transparent;
    transform: scale(1.05);
    box-shadow: 0 8px 25px rgba(43,155,255,0.25);
}
.service-title {
    font-size: 1.2rem; font-weight: 700; margin-bottom: 0.75rem;
    color: var(--text-primary); line-height: 1.3;
}
.service-desc {
    color: var(--text-secondary); font-size: 0.88rem; line-height: 1.75;
}
.card:hover .service-view-link { opacity: 1 !important; }
.card:hover .service-view-link i { transform: translateX(4px); }
</style>
@endpush

@push('scripts')
<script>
(function() {
    var serviceIcons = [
        '<i class="fas fa-code" style="color:var(--primary);"></i>',
        '<i class="fas fa-paint-brush" style="color:var(--accent);"></i>',
        '<i class="fas fa-mobile-alt" style="color:var(--green);"></i>',
        '<i class="fas fa-cloud" style="color:var(--primary-light);"></i>',
        '<i class="fas fa-shield-alt" style="color:var(--gold);"></i>',
        '<i class="fas fa-rocket" style="color:var(--accent-2);"></i>',
        '<i class="fas fa-database" style="color:var(--green);"></i>',
        '<i class="fas fa-chart-line" style="color:var(--primary);"></i>',
        '<i class="fas fa-cog" style="color:var(--accent);"></i>'
    ];

    axios.get(API_BASE + '/services/index?all=1')
        .then(function(response) {
            var services = response.data.data || [];
            var skeleton = document.getElementById('services-skeleton');
            var grid = document.getElementById('services-grid');
            var empty = document.getElementById('services-empty');

            skeleton.style.display = 'none';

            if (services.length === 0) { empty.style.display = 'block'; return; }

            grid.style.display = 'grid';

            services.forEach(function(service, index) {
                var card = document.createElement('div');
                card.className = 'card';
                card.style.cursor = 'pointer';
                card.setAttribute('data-aos', 'fade-up');
                card.setAttribute('data-aos-delay', (index * 80).toString());
                card.onclick = function() { window.location.href = '/service/' + service.id; };

                var projectCount = (service.projects || []).length;
                var projectBadge = projectCount > 0
                    ? '<span style="display:inline-flex;align-items:center;gap:0.35rem;margin-top:1rem;padding:0.3rem 0.75rem;background:rgba(43,155,255,0.06);border:1px solid rgba(43,155,255,0.1);border-radius:999px;font-size:0.72rem;font-weight:600;color:var(--primary);">' +
                        '<i class="fas fa-briefcase" style="font-size:0.65rem;"></i> ' + projectCount + ' Project' + (projectCount > 1 ? 's' : '') +
                      '</span>'
                    : '';

                card.innerHTML =
                    '<div class="service-card">' +
                        '<div class="service-icon">' + (service.icon || serviceIcons[index % serviceIcons.length]) + '</div>' +
                        '<h3 class="service-title">' + (service.title || service.name || 'Service') + '</h3>' +
                        '<p class="service-desc">' + (service.description || 'No description available.') + '</p>' +
                        projectBadge +
                        '<div style="margin-top:1rem;display:flex;align-items:center;gap:0.4rem;color:var(--primary);font-size:0.82rem;font-weight:600;opacity:0;transition:opacity 0.3s;" class="service-view-link">' +
                            'View Service <i class="fas fa-arrow-right" style="font-size:0.7rem;transition:transform 0.3s;"></i>' +
                        '</div>' +
                    '</div>';
                grid.appendChild(card);
            });

            AOS.refresh();
        })
        .catch(function(error) {
            console.error('Failed to load services:', error);
            document.getElementById('services-skeleton').style.display = 'none';
            document.getElementById('services-empty').style.display = 'block';
        });
})();
</script>
@endpush
