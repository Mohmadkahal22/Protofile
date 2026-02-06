@extends('layouts.website')

@section('title', 'Project Details - HexaTerminal')

@section('content')
<!-- ═══ PAGE HERO ═══ -->
<section style="padding:8rem 0 2.5rem;background:var(--dark-bg-2);position:relative;overflow:hidden;">
    <div class="glow-dot" style="width:500px;height:500px;background:var(--accent);top:-200px;left:-100px;"></div>
    <div class="glow-line" style="width:100%;bottom:0;left:0;"></div>

    <div class="container">
        <!-- Breadcrumb -->
        <nav style="display:flex;align-items:center;gap:0.5rem;font-size:0.85rem;" data-aos="fade-up">
            <a href="{{ url('/') }}" style="color:var(--text-muted);text-decoration:none;transition:var(--transition-fast);" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text-muted)'">
                <i class="fas fa-home" style="margin-right:0.3rem;"></i> Home
            </a>
            <i class="fas fa-chevron-right" style="color:var(--text-muted);font-size:0.55rem;"></i>
            <a href="{{ route('projects') }}" style="color:var(--text-muted);text-decoration:none;transition:var(--transition-fast);" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text-muted)'">
                Projects
            </a>
            <i class="fas fa-chevron-right" style="color:var(--text-muted);font-size:0.55rem;"></i>
            <span id="breadcrumb-title" style="color:var(--primary);font-weight:600;">Loading...</span>
        </nav>
    </div>
</section>

<!-- ═══ PROJECT DETAIL ═══ -->
<section class="section" style="background:var(--dark-bg);padding-top:3rem;">
    <div class="container" style="max-width:1200px;">
        <!-- Skeleton -->
        <div id="project-skeleton" data-aos="fade-up">
            <div class="skeleton" style="height:480px;border-radius:var(--radius-xl);margin-bottom:2rem;"></div>
            <div style="display:flex;gap:0.75rem;margin-bottom:2rem;">
                @for($i = 0; $i < 4; $i++)
                <div class="skeleton" style="width:100px;height:80px;border-radius:var(--radius-sm);"></div>
                @endfor
            </div>
            <div class="skeleton skeleton-text" style="height:42px;width:60%;margin-bottom:1.5rem;"></div>
            <div class="skeleton skeleton-text" style="height:18px;margin-bottom:0.75rem;"></div>
            <div class="skeleton skeleton-text" style="height:18px;margin-bottom:0.75rem;"></div>
            <div class="skeleton skeleton-text" style="height:18px;width:80%;"></div>
        </div>

        <!-- Actual content -->
        <div id="project-content" style="display:none;"></div>

        <!-- Not found -->
        <div id="project-not-found" style="display:none;text-align:center;padding:5rem 0;">
            <div style="width:100px;height:100px;margin:0 auto 2rem;border-radius:50%;background:rgba(239,68,68,0.06);display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-search" style="font-size:2.5rem;color:var(--danger);opacity:0.5;"></i>
            </div>
            <h3 style="font-size:1.5rem;font-weight:700;color:var(--text-primary);margin-bottom:0.75rem;">Project Not Found</h3>
            <p style="color:var(--text-secondary);font-size:0.95rem;max-width:400px;margin:0 auto 2rem;">
                The project you're looking for doesn't exist or may have been removed.
            </p>
            <a href="{{ route('projects') }}" class="btn btn-primary" style="padding:0.85rem 2.25rem;">
                <span>Browse All Projects</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.detail-hero-image {
    width: 100%; height: 480px; object-fit: cover; display: block;
    border-radius: var(--radius-xl); cursor: pointer;
    transition: transform 0.5s ease;
}
.detail-hero-wrap {
    position: relative; border-radius: var(--radius-xl); overflow: hidden;
    border: 1px solid var(--card-border); margin-bottom: 1.5rem;
}
.detail-hero-wrap::after {
    content: ''; position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(6,9,24,0.3), transparent 30%);
    pointer-events: none;
}
.detail-gallery {
    display: flex; gap: 0.75rem; overflow-x: auto; padding-bottom: 0.5rem;
    margin-bottom: 2.5rem; scrollbar-width: thin;
    scrollbar-color: var(--primary) transparent;
}
.detail-gallery::-webkit-scrollbar { height: 4px; }
.detail-gallery::-webkit-scrollbar-thumb { background: var(--primary); border-radius: 2px; }
.detail-thumb {
    width: 100px; height: 75px; border-radius: var(--radius-sm); object-fit: cover;
    cursor: pointer; border: 2px solid transparent; opacity: 0.5;
    transition: all 0.3s ease; flex-shrink: 0;
}
.detail-thumb:hover, .detail-thumb.active { opacity: 1; border-color: var(--primary); }
.detail-meta {
    display: flex; flex-wrap: wrap; gap: 1rem; margin-bottom: 2.5rem;
}
.detail-meta-item {
    display: flex; align-items: center; gap: 0.6rem;
    padding: 0.6rem 1.15rem; background: var(--card-bg);
    border: 1px solid var(--card-border); border-radius: var(--radius-md);
    font-size: 0.82rem; color: var(--text-secondary);
}
.detail-meta-item i { color: var(--primary); font-size: 0.8rem; }
.detail-title {
    font-size: clamp(1.8rem, 3.5vw, 2.5rem); font-weight: 800;
    color: var(--text-primary); line-height: 1.2; letter-spacing: -0.02em;
    margin-bottom: 1.5rem;
}
.detail-description {
    color: var(--text-secondary); font-size: 1rem; line-height: 2;
    margin-bottom: 2.5rem;
}
.features-grid {
    display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 0.85rem;
}
.feature-item {
    display: flex; align-items: center; gap: 0.85rem;
    padding: 1rem 1.25rem; background: var(--card-bg);
    border: 1px solid var(--card-border); border-radius: var(--radius-md);
    transition: var(--transition-fast);
}
.feature-item:hover { border-color: rgba(43,155,255,0.2); background: rgba(43,155,255,0.04); }
.feature-check {
    width: 28px; height: 28px; border-radius: 50%; flex-shrink: 0;
    background: linear-gradient(135deg, rgba(0,208,132,0.12), rgba(34,197,94,0.06));
    border: 1px solid rgba(0,208,132,0.2);
    display: flex; align-items: center; justify-content: center;
    color: var(--green); font-size: 0.7rem;
}
.feature-text { color: var(--text-secondary); font-size: 0.88rem; font-weight: 500; }
.detail-nav {
    margin-top: 3.5rem; padding-top: 2.5rem;
    border-top: 1px solid rgba(255,255,255,0.04);
    display: flex; justify-content: space-between; align-items: center;
}
@media (max-width: 768px) {
    .detail-hero-image { height: 280px; }
    .detail-nav { flex-direction: column; gap: 1rem; }
}
</style>
@endpush

@push('scripts')
<script>
(function() {
    var projectId = {{ $id }};

    axios.get(API_BASE + '/projects/show/' + projectId)
        .then(function(response) {
            var project = response.data.data;
            var skeleton = document.getElementById('project-skeleton');
            var content = document.getElementById('project-content');
            var notFound = document.getElementById('project-not-found');

            skeleton.style.display = 'none';

            if (!project) {
                notFound.style.display = 'block';
                return;
            }

            document.title = (project.title || 'Project') + ' - HexaTerminal';
            document.getElementById('breadcrumb-title').textContent = project.title || 'Project';

            content.style.display = 'block';

            var images = project.images || [];
            var mainImageUrl = images.length > 0 ? getImageUrl(images[0].image_path || images[0]) : '';

            // Gallery
            var galleryHtml = '';
            if (images.length > 1) {
                galleryHtml = '<div class="detail-gallery" data-aos="fade-up" data-aos-delay="100">';
                for (var i = 0; i < images.length; i++) {
                    var thumbUrl = getImageUrl(images[i].image_path || images[i]);
                    galleryHtml += '<img src="' + thumbUrl + '" alt="Image ' + (i + 1) + '" ' +
                        'class="detail-thumb' + (i === 0 ? ' active' : '') + '" loading="lazy" ' +
                        'onclick="switchProjectImage(this, \'' + thumbUrl + '\')">';
                }
                galleryHtml += '</div>';
            }

            // Meta info
            var metaHtml = '<div class="detail-meta" data-aos="fade-up" data-aos-delay="150">';
            if (project.category) {
                metaHtml += '<div class="detail-meta-item"><i class="fas fa-tag"></i> ' + project.category + '</div>';
            }
            if (project.created_at) {
                var date = new Date(project.created_at);
                metaHtml += '<div class="detail-meta-item"><i class="fas fa-calendar"></i> ' +
                    date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) + '</div>';
            }
            if (images.length > 0) {
                metaHtml += '<div class="detail-meta-item"><i class="fas fa-images"></i> ' + images.length + ' Image' + (images.length > 1 ? 's' : '') + '</div>';
            }
            if (project.features && project.features.length > 0) {
                metaHtml += '<div class="detail-meta-item"><i class="fas fa-star"></i> ' + project.features.length + ' Feature' + (project.features.length > 1 ? 's' : '') + '</div>';
            }
            metaHtml += '</div>';

            // Features
            var featuresHtml = '';
            if (project.features && project.features.length > 0) {
                featuresHtml =
                    '<div data-aos="fade-up" data-aos-delay="250">' +
                        '<h2 style="font-size:1.5rem;font-weight:700;color:var(--text-primary);margin-bottom:1.25rem;display:flex;align-items:center;gap:0.75rem;">' +
                            '<span style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,rgba(43,155,255,0.1),rgba(168,85,247,0.06));border:1px solid rgba(43,155,255,0.15);display:flex;align-items:center;justify-content:center;">' +
                                '<i class="fas fa-list-check" style="color:var(--primary);font-size:0.85rem;"></i>' +
                            '</span> Project Features' +
                        '</h2>' +
                        '<div class="features-grid">';
                project.features.forEach(function(feature) {
                    var featureName = feature.feature_text || feature.name || feature;
                    featuresHtml +=
                        '<div class="feature-item">' +
                            '<div class="feature-check"><i class="fas fa-check"></i></div>' +
                            '<span class="feature-text">' + featureName + '</span>' +
                        '</div>';
                });
                featuresHtml += '</div></div>';
            }

            content.innerHTML =
                (mainImageUrl
                    ? '<div class="detail-hero-wrap" data-aos="fade-up">' +
                        '<img src="' + mainImageUrl + '" alt="' + (project.title || 'Project') + '" class="detail-hero-image" id="main-project-image">' +
                      '</div>'
                    : '') +
                galleryHtml +
                metaHtml +
                '<h1 class="detail-title" data-aos="fade-up" data-aos-delay="200">' + (project.title || 'Untitled Project') + '</h1>' +
                '<div class="detail-description" data-aos="fade-up" data-aos-delay="200">' +
                    (project.description || 'No description available.').replace(/\n/g, '<br>') +
                '</div>' +
                featuresHtml +
                '<div class="detail-nav" data-aos="fade-up">' +
                    '<a href="' + '{{ route("projects") }}' + '" class="btn btn-outline" style="padding:0.8rem 1.75rem;">' +
                        '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>' +
                        '<span>All Projects</span>' +
                    '</a>' +
                    '<a href="#contact" class="btn btn-primary" style="padding:0.8rem 1.75rem;">' +
                        '<span>Start a Project Like This</span>' +
                        '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>' +
                    '</a>' +
                '</div>';

            AOS.refresh();
        })
        .catch(function(error) {
            console.error('Failed to load project:', error);
            document.getElementById('project-skeleton').style.display = 'none';
            document.getElementById('project-not-found').style.display = 'block';
        });
})();

function switchProjectImage(thumb, imageUrl) {
    document.getElementById('main-project-image').src = imageUrl;
    document.querySelectorAll('.detail-thumb').forEach(function(t) { t.classList.remove('active'); });
    thumb.classList.add('active');
}
</script>
@endpush
