@extends('layouts.website')

@section('title', 'All Projects - HexaTerminal')

@section('content')
<!-- ═══ PAGE HERO ═══ -->
<section style="padding:8rem 0 3rem;background:var(--dark-bg-2);position:relative;overflow:hidden;">
    <div class="glow-dot" style="width:500px;height:500px;background:var(--gold);top:-200px;right:-100px;opacity:0.03;"></div>
    <div class="glow-dot" style="width:400px;height:400px;background:var(--gold-light);bottom:-100px;left:-100px;opacity:0.02;"></div>
    <div class="glow-line" style="width:100%;bottom:0;left:0;background:linear-gradient(90deg,transparent,rgba(212,175,55,0.06),transparent);"></div>

    <div class="container" style="text-align:center;">
        <span class="section-badge" data-aos="fade-up"><i class="fas fa-trophy"></i> <span data-i18n="portfolio">Portfolio</span></span>
        <h1 style="font-size:clamp(2.5rem,5vw,3.5rem);font-weight:800;margin:1rem 0 1.25rem;letter-spacing:-0.03em;line-height:1.15;" data-aos="fade-up" data-aos-delay="100">
            <span data-i18n="our">Our</span> <span class="text-gradient-gold" data-i18n="nav_projects">Projects</span>
        </h1>
        <p style="color:var(--text-secondary);font-size:1.05rem;max-width:580px;margin:0 auto;line-height:1.75;" data-aos="fade-up" data-aos-delay="200" data-i18n="projects_page_subtitle">
            Explore our complete portfolio of innovative digital solutions built with cutting-edge technologies
        </p>

        <!-- Breadcrumb -->
        <nav style="display:flex;justify-content:center;align-items:center;gap:0.5rem;margin-top:2rem;font-size:0.85rem;" data-aos="fade-up" data-aos-delay="300">
            <a href="{{ url('/') }}" style="color:var(--text-muted);text-decoration:none;transition:var(--transition-fast);" onmouseover="this.style.color='var(--gold)'" onmouseout="this.style.color='var(--text-muted)'">
                <i class="fas fa-home" style="margin-right:0.3rem;"></i> <span data-i18n="nav_home">Home</span>
            </a>
            <i class="fas fa-chevron-right" style="color:var(--text-muted);font-size:0.55rem;"></i>
            <span style="color:var(--gold);font-weight:600;" data-i18n="nav_projects">Projects</span>
        </nav>
    </div>
</section>

<!-- ═══ PROJECTS GRID ═══ -->
<section class="section" style="background:var(--dark-bg);padding-top:4rem;">
    <div class="container">
        <!-- Skeleton -->
        <div id="all-projects-skeleton" class="grid-3">
            @for($i = 0; $i < 9; $i++)
            <div class="skeleton skeleton-card"></div>
            @endfor
        </div>

        <!-- Actual content -->
        <div id="all-projects-grid" class="grid-3" style="display:none;"></div>

        <!-- Pagination -->
        <div id="all-projects-pagination" style="display:none;"></div>

        <!-- Empty state -->
        <div id="all-projects-empty" style="display:none;text-align:center;padding:5rem 0;">
            <div style="width:100px;height:100px;margin:0 auto 2rem;border-radius:50%;background:rgba(212,175,55,0.06);display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-folder-open" style="font-size:2.5rem;color:var(--gold);opacity:0.4;"></i>
            </div>
            <h3 style="font-size:1.4rem;font-weight:700;color:var(--text-primary);margin-bottom:0.75rem;" data-i18n="no_projects_yet">No Projects Yet</h3>
            <p style="color:var(--text-secondary);font-size:0.95rem;max-width:400px;margin:0 auto 2rem;" data-i18n="projects_coming_soon_desc">We're working on something amazing. Check back soon!</p>
            <a href="{{ url('/') }}" class="btn btn-outline" style="padding:0.8rem 2rem;">
                <i class="fas fa-arrow-left"></i> <span data-i18n="back_to_home">Back to Home</span>
            </a>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.project-card { cursor: pointer; overflow: hidden; }
.project-card-img-wrap { position: relative; overflow: hidden; height: 260px; }
.project-card-img {
    width: 100%; height: 100%; object-fit: cover;
    transition: transform 0.6s cubic-bezier(0.25,0.46,0.45,0.94), filter 0.4s ease;
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
.project-card-title { font-size: 1.15rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem; line-height: 1.3; }
.project-card-desc {
    color: var(--text-secondary); font-size: 0.85rem; line-height: 1.65;
    display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
    margin-bottom: 1rem;
}
.project-tag {
    display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.3rem 0.8rem;
    background: linear-gradient(135deg, rgba(212,175,55,0.08), rgba(183,134,11,0.04));
    border: 1px solid rgba(212,175,55,0.1); color: var(--gold); border-radius: 999px;
    font-size: 0.72rem; font-weight: 600; letter-spacing: 0.5px;
}
.project-card-placeholder {
    width: 100%; height: 100%;
    background: linear-gradient(135deg, rgba(212,175,55,0.08), rgba(183,134,11,0.06));
    display: flex; align-items: center; justify-content: center;
}
.pagination-wrap {
    display: flex; justify-content: center; align-items: center; gap: 0.5rem;
    margin-top: 4rem; flex-wrap: wrap;
}
.page-btn {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 44px; height: 44px; padding: 0.5rem 1rem;
    background: var(--card-bg); border: 1px solid rgba(212,175,55,0.08);
    border-radius: var(--radius-sm); color: var(--text-secondary);
    font-size: 0.88rem; font-weight: 600; text-decoration: none;
    cursor: pointer; transition: var(--transition-fast);
}
.page-btn:hover { border-color: var(--gold); color: var(--gold); background: rgba(212,175,55,0.06); }
.page-btn.active {
    background: linear-gradient(135deg, var(--gold), var(--gold-dark));
    border-color: transparent; color: #0a0a1a;
    box-shadow: 0 4px 15px rgba(212,175,55,0.25);
}
.page-btn.disabled { opacity: 0.4; cursor: not-allowed; pointer-events: none; }
.pagination-info {
    display: block; text-align: center; margin-top: 1rem;
    color: var(--text-muted); font-size: 0.82rem;
}

/* Light theme */
[data-theme="light"] .page-btn { background: var(--card-bg); border-color: rgba(183,134,11,0.08); }
[data-theme="light"] .page-btn:hover { background: rgba(183,134,11,0.04); border-color: var(--gold); }
[data-theme="light"] .page-btn.active { background: linear-gradient(135deg, var(--gold), var(--gold-dark)); border-color: transparent; color: #fff; box-shadow: 0 4px 12px rgba(183,134,11,0.2); }
[data-theme="light"] .project-card-placeholder { background: linear-gradient(135deg, rgba(183,134,11,0.06), rgba(212,175,55,0.04)); }
</style>
@endpush

@push('scripts')
<script>
(function() {
    var perPage = 9;
    var params = new URLSearchParams(window.location.search);
    var currentPage = parseInt(params.get('page') || '1');

    loadPage(currentPage);

    function loadPage(page) {
        var skeleton = document.getElementById('all-projects-skeleton');
        var grid = document.getElementById('all-projects-grid');
        var pagination = document.getElementById('all-projects-pagination');
        var empty = document.getElementById('all-projects-empty');

        skeleton.style.display = 'grid';
        grid.style.display = 'none';
        pagination.style.display = 'none';

        axios.get(API_BASE + '/projects/index?page=' + page + '&per_page=' + perPage)
            .then(function(response) {
                var projects = response.data.data || [];
                var pag = response.data.pagination;

                skeleton.style.display = 'none';

                if (projects.length === 0 && page === 1) {
                    empty.style.display = 'block';
                    return;
                }

                grid.innerHTML = '';
                grid.style.display = 'grid';

                projects.forEach(function(project, index) {
                    var images = project.images || [];
                    var firstImage = images.length > 0 ? (images[0].image_path || images[0]) : '';
                    var imageUrl = getImageUrl(firstImage);

                    var card = document.createElement('div');
                    card.className = 'card card-gold-border project-card';
                    card.setAttribute('data-aos', 'fade-up');
                    card.setAttribute('data-aos-delay', (index % 3 * 80).toString());
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
                            '<div style="display:flex;gap:0.5rem;flex-wrap:wrap;">' +
                                (project.service ? '<span class="project-tag"><i class="fas fa-cogs"></i> ' + (project.service.title || project.service.name || 'Service') + '</span>' : '') +
                                (project.category ? '<span class="project-tag"><i class="fas fa-tag"></i> ' + project.category + '</span>' : '') +
                            '</div>' +
                        '</div>';
                    grid.appendChild(card);
                });

                if (pag && pag.last_page > 1) {
                    renderPagination(pag);
                }

                if (page > 1) {
                    grid.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }

                AOS.refresh();
            })
            .catch(function(error) {
                console.error('Failed to load projects:', error);
                skeleton.style.display = 'none';
                empty.style.display = 'block';
            });
    }

    function renderPagination(pag) {
        var pagination = document.getElementById('all-projects-pagination');
        pagination.style.display = 'block';
        pagination.innerHTML = '<div class="pagination-wrap"></div>';
        var wrap = pagination.querySelector('.pagination-wrap');

        var prev = document.createElement('button');
        prev.className = 'page-btn' + (pag.current_page <= 1 ? ' disabled' : '');
        prev.innerHTML = '<i class="fas fa-chevron-left" style="font-size:0.7rem;"></i>';
        if (pag.current_page > 1) {
            prev.onclick = function() { navigateToPage(pag.current_page - 1); };
        }
        wrap.appendChild(prev);

        var pages = generatePageNumbers(pag.current_page, pag.last_page);
        pages.forEach(function(p) {
            if (p === '...') {
                var ellipsis = document.createElement('span');
                ellipsis.className = 'page-btn disabled';
                ellipsis.textContent = '...';
                ellipsis.style.cursor = 'default';
                wrap.appendChild(ellipsis);
            } else {
                var btn = document.createElement('button');
                btn.className = 'page-btn' + (p === pag.current_page ? ' active' : '');
                btn.textContent = p;
                btn.onclick = (function(pageNum) {
                    return function() { navigateToPage(pageNum); };
                })(p);
                wrap.appendChild(btn);
            }
        });

        var next = document.createElement('button');
        next.className = 'page-btn' + (pag.current_page >= pag.last_page ? ' disabled' : '');
        next.innerHTML = '<i class="fas fa-chevron-right" style="font-size:0.7rem;"></i>';
        if (pag.current_page < pag.last_page) {
            next.onclick = function() { navigateToPage(pag.current_page + 1); };
        }
        wrap.appendChild(next);

        var info = document.createElement('span');
        info.className = 'pagination-info';
        info.textContent = t('page') + ' ' + pag.current_page + ' / ' + pag.last_page + ' (' + pag.total + ' ' + t('projects').toLowerCase() + ')';
        pagination.appendChild(info);
    }

    function generatePageNumbers(current, total) {
        if (total <= 7) {
            var arr = [];
            for (var i = 1; i <= total; i++) arr.push(i);
            return arr;
        }
        var pages = [1];
        if (current > 3) pages.push('...');
        for (var i = Math.max(2, current - 1); i <= Math.min(total - 1, current + 1); i++) {
            pages.push(i);
        }
        if (current < total - 2) pages.push('...');
        pages.push(total);
        return pages;
    }

    function navigateToPage(page) {
        currentPage = page;
        var url = new URL(window.location);
        url.searchParams.set('page', page);
        history.pushState({}, '', url);
        loadPage(page);
    }

    window.addEventListener('popstate', function() {
        var params = new URLSearchParams(window.location.search);
        var page = parseInt(params.get('page') || '1');
        loadPage(page);
    });
})();
</script>
@endpush
