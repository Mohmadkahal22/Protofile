<!-- ═══ PROJECTS SECTION ═══ -->
<section id="projects" class="section" style="background: var(--dark-bg); position:relative; overflow:hidden;">
    <div class="glow-dot" style="width:500px;height:500px;background:var(--gold);top:-100px;right:-100px;opacity:0.03;"></div>

    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-badge"><i class="fas fa-trophy"></i> <span data-i18n="section_projects_badge">Our Work</span></span>
            <h2 class="section-title" data-i18n="section_projects_title">Latest Projects</h2>
            <p class="section-subtitle" data-i18n="section_projects_subtitle">Explore our innovative projects that deliver exceptional digital experiences</p>
        </div>

        <div id="projects-skeleton" class="grid-3">
            @for($i = 0; $i < 6; $i++)
            <div class="skeleton skeleton-card"></div>
            @endfor
        </div>

        <div id="projects-grid" class="grid-3" style="display:none;"></div>

        <div id="projects-empty" style="display:none;text-align:center;padding:4rem 0;">
            <div style="width:80px;height:80px;margin:0 auto 1.5rem;border-radius:50%;background:rgba(212,175,55,0.06);display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-briefcase" style="font-size:2rem;color:var(--gold);"></i>
            </div>
            <p style="font-size:1.1rem;color:var(--text-secondary);font-weight:500;">Projects coming soon!</p>
        </div>

        <div id="projects-view-all" style="display:none;text-align:center;margin-top:3.5rem;" data-aos="fade-up">
            <a href="{{ route('projects') }}" class="btn btn-outline" style="padding:0.9rem 2.5rem;border-color:rgba(212,175,55,0.2);color:var(--gold);">
                <span data-i18n="view_all_projects">View All Projects</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</section>

@push('styles')
<style>
.project-card { cursor: pointer; overflow: hidden; }
.project-card-img-wrap { position: relative; overflow: hidden; height: 240px; }
.project-card-img {
    width: 100%; height: 100%; object-fit: cover;
    transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94), filter 0.4s ease;
}
.card:hover .project-card-img { transform: scale(1.08); filter: brightness(0.7); }
.project-card-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(7,10,24,0.95) 0%, rgba(7,10,24,0.3) 40%, transparent 70%);
    display: flex; flex-direction: column; justify-content: flex-end; padding: 1.5rem;
    opacity: 0; transition: opacity 0.4s;
}
.card:hover .project-card-overlay { opacity: 1; }
.project-card-overlay .view-btn {
    display: inline-flex; align-items: center; gap: 0.5rem;
    padding: 0.5rem 1rem; background: rgba(212,175,55,0.15); border: 1px solid rgba(212,175,55,0.3);
    border-radius: var(--radius-sm); color: var(--gold); font-size: 0.8rem; font-weight: 600;
    width: fit-content; backdrop-filter: blur(8px);
}
.project-card-body { padding: 1.5rem; }
.project-card-title { font-size: 1.15rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.4rem; line-height: 1.3; }
.project-card-desc {
    color: var(--text-secondary); font-size: 0.85rem; line-height: 1.65;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    margin-bottom: 1rem;
}
.project-tag {
    display: inline-flex; align-items: center; gap: 0.3rem;
    padding: 0.3rem 0.8rem;
    background: linear-gradient(135deg, rgba(212,175,55,0.08), rgba(74,143,231,0.05));
    border: 1px solid rgba(212,175,55,0.1);
    color: var(--gold); border-radius: 999px;
    font-size: 0.72rem; font-weight: 600; letter-spacing: 0.5px;
}
.project-card-placeholder {
    width: 100%; height: 100%;
    background: linear-gradient(135deg, rgba(212,175,55,0.08), rgba(74,143,231,0.08));
    display: flex; align-items: center; justify-content: center;
}
</style>
@endpush

@push('scripts')
<script>
(function() {
    axios.get(API_BASE + '/projects/index?all=4')
        .then(function(response) {
            var allProjects = response.data.data || [];
            var skeleton = document.getElementById('projects-skeleton');
            var grid = document.getElementById('projects-grid');
            var empty = document.getElementById('projects-empty');
            var viewAll = document.getElementById('projects-view-all');

            allProjects.sort(function(a, b) {
                return new Date(b.updated_at || b.created_at || 0) - new Date(a.updated_at || a.created_at || 0);
            });

            var projects = allProjects.slice(0, 6);
            skeleton.style.display = 'none';

            if (projects.length === 0) { empty.style.display = 'block'; return; }

            grid.style.display = 'grid';
            if (allProjects.length > 6) viewAll.style.display = 'block';

            projects.forEach(function(project, index) {
                var images = project.images || [];
                var firstImage = images.length > 0 ? (images[0].image_path || images[0]) : '';
                var imageUrl = getImageUrl(firstImage);

                var card = document.createElement('div');
                card.className = 'card project-card';
                card.setAttribute('data-aos', 'fade-up');
                card.setAttribute('data-aos-delay', (index * 80).toString());
                card.onclick = function() { window.location.href = '/project/' + (project.id || 0); };

                card.innerHTML =
                    '<div class="project-card-img-wrap">' +
                        (imageUrl
                            ? '<img src="' + imageUrl + '" alt="' + (project.title || 'Project') + '" class="project-card-img" loading="lazy">'
                            : '<div class="project-card-placeholder"><i class="fas fa-image" style="font-size:3rem;color:rgba(212,175,55,0.2);"></i></div>'
                        ) +
                        '<div class="project-card-overlay">' +
                            '<span class="view-btn"><i class="fas fa-eye"></i> ' + t('view_details') + '</span>' +
                        '</div>' +
                    '</div>' +
                    '<div class="project-card-body">' +
                        '<h3 class="project-card-title">' + (project.title || 'Untitled Project') + '</h3>' +
                        '<p class="project-card-desc">' + (project.description || t('no_desc')) + '</p>' +
                        (project.category ? '<span class="project-tag"><i class="fas fa-tag"></i> ' + project.category + '</span>' : '') +
                    '</div>';
                grid.appendChild(card);
            });

            AOS.refresh();
        })
        .catch(function(error) {
            console.error('Failed to load projects:', error);
            document.getElementById('projects-skeleton').style.display = 'none';
            document.getElementById('projects-empty').style.display = 'block';
        });
})();
</script>
@endpush
