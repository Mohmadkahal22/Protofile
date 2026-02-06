@extends('layouts.website')

@section('title', 'All Projects - HexaTerminal')

@section('content')
<!-- ═══ PAGE HERO ═══ -->
<section style="padding:8rem 0 3rem;background:var(--dark-bg-2);position:relative;overflow:hidden;">
    <div class="glow-dot" style="width:500px;height:500px;background:var(--primary);top:-200px;right:-100px;"></div>
    <div class="glow-dot" style="width:400px;height:400px;background:var(--accent);bottom:-100px;left:-100px;opacity:0.04;"></div>
    <div class="glow-line" style="width:100%;bottom:0;left:0;"></div>

    <div class="container" style="text-align:center;">
        <span class="section-badge" data-aos="fade-up"><i class="fas fa-briefcase"></i> Portfolio</span>
        <h1 style="font-size:clamp(2.5rem,5vw,3.5rem);font-weight:800;margin:1rem 0 1.25rem;color:var(--text-primary);letter-spacing:-0.03em;line-height:1.15;" data-aos="fade-up" data-aos-delay="100">
            Our <span class="text-gradient">Projects</span>
        </h1>
        <p style="color:var(--text-secondary);font-size:1.05rem;max-width:580px;margin:0 auto;line-height:1.75;" data-aos="fade-up" data-aos-delay="200">
            Explore our complete portfolio of innovative digital solutions built with cutting-edge technologies
        </p>

        <!-- Breadcrumb -->
        <nav style="display:flex;justify-content:center;align-items:center;gap:0.5rem;margin-top:2rem;font-size:0.85rem;" data-aos="fade-up" data-aos-delay="300">
            <a href="{{ url('/') }}" style="color:var(--text-muted);text-decoration:none;transition:var(--transition-fast);" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text-muted)'">
                <i class="fas fa-home" style="margin-right:0.3rem;"></i> Home
            </a>
            <i class="fas fa-chevron-right" style="color:var(--text-muted);font-size:0.55rem;"></i>
            <span style="color:var(--primary);font-weight:600;">Projects</span>
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
            <div style="width:100px;height:100px;margin:0 auto 2rem;border-radius:50%;background:rgba(43,155,255,0.06);display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-folder-open" style="font-size:2.5rem;color:var(--primary);opacity:0.5;"></i>
            </div>
            <h3 style="font-size:1.4rem;font-weight:700;color:var(--text-primary);margin-bottom:0.75rem;">No Projects Yet</h3>
            <p style="color:var(--text-secondary);font-size:0.95rem;max-width:400px;margin:0 auto 2rem;">We're working on something amazing. Check back soon!</p>
            <a href="{{ url('/') }}" class="btn btn-outline" style="padding:0.8rem 2rem;">
                <i class="fas fa-arrow-left"></i> <span>Back to Home</span>
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
    background: linear-gradient(to top, rgba(6,9,24,0.95) 0%, rgba(6,9,24,0.3) 40%, transparent 70%);
    display: flex; flex-direction: column; justify-content: flex-end; padding: 1.5rem;
    opacity: 0; transition: opacity 0.4s;
}
.card:hover .project-card-overlay { opacity: 1; }
.project-card-overlay .view-btn {
    display: inline-flex; align-items: center; gap: 0.5rem;
    padding: 0.5rem 1rem; background: rgba(43,155,255,0.2); border: 1px solid rgba(43,155,255,0.3);
    border-radius: var(--radius-sm); color: var(--primary); font-size: 0.8rem; font-weight: 600;
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
    background: linear-gradient(135deg, rgba(43,155,255,0.1), rgba(168,85,247,0.06));
    border: 1px solid rgba(43,155,255,0.12); color: var(--primary); border-radius: 999px;
    font-size: 0.72rem; font-weight: 600; letter-spacing: 0.5px;
}
.project-card-placeholder {
    width: 100%; height: 100%;
    background: linear-gradient(135deg, rgba(43,155,255,0.15), rgba(168,85,247,0.15));
    display: flex; align-items: center; justify-content: center;
}
.pagination-wrap {
    display: flex; justify-content: center; align-items: center; gap: 0.5rem;
    margin-top: 4rem; flex-wrap: wrap;
}
.page-btn {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 44px; height: 44px; padding: 0.5rem 1rem;
    background: var(--card-bg); border: 1px solid var(--card-border);
    border-radius: var(--radius-sm); color: var(--text-secondary);
    font-size: 0.88rem; font-weight: 600; text-decoration: none;
    cursor: pointer; transition: var(--transition-fast);
}
.page-btn:hover { border-color: var(--primary); color: var(--primary); background: rgba(43,155,255,0.06); }
.page-btn.active {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    border-color: transparent; color: #fff;
    box-shadow: 0 4px 15px rgba(43,155,255,0.25);
}
.page-btn.disabled { opacity: 0.4; cursor: not-allowed; pointer-events: none; }
.pagination-info {
    display: block; text-align: center; margin-top: 1rem;
    color: var(--text-muted); font-size: 0.82rem;
}

/* Light theme */
[data-theme="light"] .page-btn { background: #fff; border-color: rgba(0,0,0,0.08); box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
[data-theme="light"] .page-btn:hover { background: rgba(37,99,235,0.04); border-color: #2563eb; }
[data-theme="light"] .page-btn.active { background: linear-gradient(135deg, #2563eb, #6366f1); border-color: transparent; box-shadow: 0 4px 12px rgba(37,99,235,0.2); }
[data-theme="light"] .project-card-placeholder { background: linear-gradient(135deg, rgba(37,99,235,0.1), rgba(139,92,246,0.08)); }
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

        // Show skeleton on page change
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
                    card.className = 'card project-card';
                    card.setAttribute('data-aos', 'fade-up');
                    card.setAttribute('data-aos-delay', (index % 3 * 80).toString());
                    card.onclick = function() { window.location.href = '/project/' + (project.id || 0); };

                    card.innerHTML =
                        '<div class="project-card-img-wrap">' +
                            (imageUrl
                                ? '<img src="' + imageUrl + '" alt="' + (project.title || 'Project') + '" class="project-card-img" loading="lazy">'
                                : '<div class="project-card-placeholder"><i class="fas fa-image" style="font-size:3rem;color:rgba(43,155,255,0.3);"></i></div>'
                            ) +
                            '<div class="project-card-overlay">' +
                                '<span class="view-btn"><i class="fas fa-eye"></i> View Details</span>' +
                            '</div>' +
                        '</div>' +
                        '<div class="project-card-body">' +
                            '<h3 class="project-card-title">' + (project.title || 'Untitled Project') + '</h3>' +
                            '<p class="project-card-desc">' + (project.description || 'No description available.') + '</p>' +
                            '<div style="display:flex;gap:0.5rem;flex-wrap:wrap;">' +
                                (project.service ? '<span class="project-tag"><i class="fas fa-cogs"></i> ' + (project.service.title || project.service.name || 'Service') + '</span>' : '') +
                                (project.category ? '<span class="project-tag"><i class="fas fa-tag"></i> ' + project.category + '</span>' : '') +
                            '</div>' +
                        '</div>';
                    grid.appendChild(card);
                });

                // Render pagination
                if (pag && pag.last_page > 1) {
                    renderPagination(pag);
                }

                // Scroll to top of grid
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

        // Prev
        var prev = document.createElement('button');
        prev.className = 'page-btn' + (pag.current_page <= 1 ? ' disabled' : '');
        prev.innerHTML = '<i class="fas fa-chevron-left" style="font-size:0.7rem;"></i>';
        if (pag.current_page > 1) {
            prev.onclick = function() { navigateToPage(pag.current_page - 1); };
        }
        wrap.appendChild(prev);

        // Page numbers with ellipsis
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

        // Next
        var next = document.createElement('button');
        next.className = 'page-btn' + (pag.current_page >= pag.last_page ? ' disabled' : '');
        next.innerHTML = '<i class="fas fa-chevron-right" style="font-size:0.7rem;"></i>';
        if (pag.current_page < pag.last_page) {
            next.onclick = function() { navigateToPage(pag.current_page + 1); };
        }
        wrap.appendChild(next);

        // Info text
        var info = document.createElement('span');
        info.className = 'pagination-info';
        info.textContent = 'Page ' + pag.current_page + ' of ' + pag.last_page + ' (' + pag.total + ' projects)';
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

    // Handle browser back/forward
    window.addEventListener('popstate', function() {
        var params = new URLSearchParams(window.location.search);
        var page = parseInt(params.get('page') || '1');
        loadPage(page);
    });
})();
</script>
@endpush
