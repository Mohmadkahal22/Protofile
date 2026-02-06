<!-- ═══ SERVICES SECTION ═══ -->
<section id="services" class="section" style="background: var(--dark-bg-2); position:relative; overflow:hidden;">
    <div class="glow-dot" style="width:500px;height:500px;background:var(--gold);top:-150px;left:-150px;opacity:0.03;"></div>
    <div class="glow-dot" style="width:400px;height:400px;background:var(--gold);bottom:-100px;right:-100px;opacity:0.03;"></div>
    <div class="glow-line" style="width:100%;top:0;left:0;"></div>

    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-badge"><i class="fas fa-gem"></i> <span data-i18n="section_services_badge">What We Do</span></span>
            <h2 class="section-title" data-i18n="section_services_title">Our Services</h2>
            <p class="section-subtitle" data-i18n="section_services_subtitle">Innovative software solutions tailored to drive your business forward</p>
        </div>

        <div id="services-skeleton" class="grid-3">
            @for($i = 0; $i < 6; $i++)
            <div class="skeleton skeleton-card"></div>
            @endfor
        </div>

        <div id="services-grid" class="grid-3" style="display:none;"></div>

        <div id="services-empty" style="display:none;text-align:center;padding:4rem 0;">
            <div style="width:80px;height:80px;margin:0 auto 1.5rem;border-radius:50%;background:rgba(212,175,55,0.06);display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-gem" style="font-size:2rem;color:var(--gold);"></i>
            </div>
            <p style="font-size:1.1rem;color:var(--text-secondary);font-weight:500;">Services coming soon!</p>
        </div>
    </div>
</section>

@push('styles')
<style>
.service-card { position: relative; padding: 2.25rem; transition: var(--transition); }
.service-card::after {
    content: ''; position: absolute; bottom: 0; left: 2rem; right: 2rem;
    height: 2px; background: linear-gradient(90deg, var(--gold), var(--gold-dark));
    transform: scaleX(0); transition: transform 0.4s ease; border-radius: 1px;
}
.card:hover .service-card::after { transform: scaleX(1); }
.service-icon {
    width: 64px; height: 64px;
    background: linear-gradient(135deg, rgba(212,175,55,0.08), rgba(74,143,231,0.06));
    border: 1px solid rgba(212,175,55,0.12);
    border-radius: var(--radius-md);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.6rem; margin-bottom: 1.5rem; transition: var(--transition);
}
.card:hover .service-icon {
    background: linear-gradient(135deg, var(--gold), var(--gold-dark));
    border-color: transparent; transform: scale(1.05);
    box-shadow: 0 8px 25px rgba(212,175,55,0.2);
}
.card:hover .service-icon i { color: #fff !important; }
.service-title { font-size: 1.2rem; font-weight: 700; margin-bottom: 0.75rem; color: var(--text-primary); line-height: 1.3; }
.service-desc { color: var(--text-secondary); font-size: 0.88rem; line-height: 1.75; }
.card:hover .service-view-link { opacity: 1 !important; }
.card:hover .service-view-link i { transform: translateX(4px); }

[data-theme="light"] .service-icon { background: linear-gradient(135deg, rgba(184,148,31,0.06), rgba(45,95,168,0.04)); border-color: rgba(184,148,31,0.08); }
[data-theme="light"] .card:hover .service-icon { background: linear-gradient(135deg, var(--gold), var(--gold-dark)); box-shadow: 0 8px 20px rgba(184,148,31,0.15); }
</style>
@endpush

@push('scripts')
<script>
(function() {
    var serviceIcons = [
        '<i class="fas fa-code" style="color:var(--gold);"></i>',
        '<i class="fas fa-paint-brush" style="color:var(--gold);"></i>',
        '<i class="fas fa-mobile-alt" style="color:var(--green);"></i>',
        '<i class="fas fa-cloud" style="color:var(--primary-light);"></i>',
        '<i class="fas fa-shield-alt" style="color:var(--gold);"></i>',
        '<i class="fas fa-rocket" style="color:var(--accent-2);"></i>',
        '<i class="fas fa-database" style="color:var(--green);"></i>',
        '<i class="fas fa-chart-line" style="color:var(--gold);"></i>',
        '<i class="fas fa-cog" style="color:var(--gold);"></i>'
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
                    ? '<span style="display:inline-flex;align-items:center;gap:0.35rem;margin-top:1rem;padding:0.3rem 0.75rem;background:rgba(212,175,55,0.06);border:1px solid rgba(212,175,55,0.1);border-radius:999px;font-size:0.72rem;font-weight:600;color:var(--gold);">' +
                        '<i class="fas fa-briefcase" style="font-size:0.65rem;"></i> ' + projectCount + ' Project' + (projectCount > 1 ? 's' : '') +
                      '</span>'
                    : '';

                card.innerHTML =
                    '<div class="service-card">' +
                        '<div class="service-icon">' + (service.icon || serviceIcons[index % serviceIcons.length]) + '</div>' +
                        '<h3 class="service-title">' + (service.title || service.name || 'Service') + '</h3>' +
                        '<p class="service-desc">' + (service.description || t('no_desc')) + '</p>' +
                        projectBadge +
                        '<div style="margin-top:1rem;display:flex;align-items:center;gap:0.4rem;color:var(--gold);font-size:0.82rem;font-weight:600;opacity:0;transition:opacity 0.3s;" class="service-view-link">' +
                            t('view_service') + ' <i class="fas fa-arrow-right" style="font-size:0.7rem;transition:transform 0.3s;"></i>' +
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
