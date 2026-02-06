@extends('layouts.website')

@section('title', 'Team Member - HexaTerminal')

@section('content')
<!-- ═══ PAGE HERO ═══ -->
<section style="padding:8rem 0 2.5rem;background:var(--dark-bg-2);position:relative;overflow:hidden;">
    <div class="glow-dot" style="width:500px;height:500px;background:var(--secondary);top:-200px;left:-100px;"></div>
    <div class="glow-line" style="width:100%;bottom:0;left:0;"></div>

    <div class="container">
        <nav style="display:flex;align-items:center;gap:0.5rem;font-size:0.85rem;" data-aos="fade-up">
            <a href="{{ url('/') }}" style="color:var(--text-muted);text-decoration:none;transition:var(--transition-fast);" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text-muted)'">
                <i class="fas fa-home" style="margin-right:0.3rem;"></i> Home
            </a>
            <i class="fas fa-chevron-right" style="color:var(--text-muted);font-size:0.55rem;"></i>
            <a href="{{ url('/') }}#team" style="color:var(--text-muted);text-decoration:none;transition:var(--transition-fast);" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text-muted)'">
                Team
            </a>
            <i class="fas fa-chevron-right" style="color:var(--text-muted);font-size:0.55rem;"></i>
            <span id="breadcrumb-name" style="color:var(--primary);font-weight:600;">Loading...</span>
        </nav>
    </div>
</section>

<!-- ═══ TEAM MEMBER DETAIL ═══ -->
<section class="section" style="background:var(--dark-bg);padding-top:3rem;">
    <div class="container" style="max-width:1000px;">
        <!-- Skeleton -->
        <div id="member-skeleton" data-aos="fade-up">
            <div style="display:grid;grid-template-columns:300px 1fr;gap:3rem;align-items:start;">
                <div>
                    <div class="skeleton" style="width:280px;height:280px;border-radius:50%;margin:0 auto;"></div>
                </div>
                <div>
                    <div class="skeleton skeleton-text" style="height:42px;width:60%;margin-bottom:1rem;"></div>
                    <div class="skeleton skeleton-text" style="height:20px;width:40%;margin-bottom:1.5rem;"></div>
                    <div class="skeleton skeleton-text" style="height:16px;margin-bottom:0.75rem;"></div>
                    <div class="skeleton skeleton-text" style="height:16px;margin-bottom:0.75rem;"></div>
                    <div class="skeleton skeleton-text" style="height:16px;width:80%;margin-bottom:2rem;"></div>
                    <div style="display:flex;gap:0.75rem;">
                        <div class="skeleton" style="width:140px;height:44px;border-radius:var(--radius-sm);"></div>
                        <div class="skeleton" style="width:140px;height:44px;border-radius:var(--radius-sm);"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actual content -->
        <div id="member-content" style="display:none;"></div>

        <!-- Not found -->
        <div id="member-not-found" style="display:none;text-align:center;padding:5rem 0;">
            <div style="width:100px;height:100px;margin:0 auto 2rem;border-radius:50%;background:rgba(239,68,68,0.06);display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-user-slash" style="font-size:2.5rem;color:var(--danger);opacity:0.5;"></i>
            </div>
            <h3 style="font-size:1.5rem;font-weight:700;color:var(--text-primary);margin-bottom:0.75rem;">Member Not Found</h3>
            <p style="color:var(--text-secondary);font-size:0.95rem;max-width:400px;margin:0 auto 2rem;">
                The team member you're looking for doesn't exist or may have been removed.
            </p>
            <a href="{{ url('/') }}#team" class="btn btn-primary" style="padding:0.85rem 2.25rem;">
                <span>View All Team</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.member-profile-grid {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 3.5rem;
    align-items: start;
}
.member-photo-container {
    position: relative;
    width: 280px;
    height: 280px;
    margin: 0 auto;
}
.member-photo-ring {
    position: absolute;
    inset: -6px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--accent));
    opacity: 0.35;
    animation: pulse-glow 3s ease-in-out infinite;
}
.member-photo-img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    position: relative;
    z-index: 1;
    border: 4px solid var(--dark-bg);
}
.member-initials-large {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--accent));
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 6rem;
    font-weight: 800;
    color: white;
    position: relative;
    z-index: 1;
    border: 4px solid var(--dark-bg);
}
.member-position-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1.25rem;
    background: linear-gradient(135deg, rgba(43,155,255,0.12), rgba(168,85,247,0.08));
    border: 1px solid rgba(43,155,255,0.2);
    border-radius: 999px;
    font-size: 0.88rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
}
.member-info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin: 2rem 0;
}
.member-info-item {
    display: flex;
    align-items: center;
    gap: 0.85rem;
    padding: 1rem 1.25rem;
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    border-radius: var(--radius-md);
    transition: var(--transition-fast);
}
.member-info-item:hover {
    border-color: rgba(43,155,255,0.2);
    background: rgba(43,155,255,0.04);
}
.member-info-icon {
    width: 40px;
    height: 40px;
    border-radius: var(--radius-sm);
    background: linear-gradient(135deg, rgba(43,155,255,0.1), rgba(168,85,247,0.06));
    border: 1px solid rgba(43,155,255,0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
    font-size: 0.9rem;
    flex-shrink: 0;
}
.member-info-label {
    font-size: 0.72rem;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.8px;
    font-weight: 600;
    margin-bottom: 0.1rem;
}
.member-info-value {
    font-size: 0.9rem;
    color: var(--text-primary);
    font-weight: 500;
}
.member-actions {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
    margin-top: 2rem;
}

/* Light theme */
[data-theme="light"] .member-photo-img { border-color: #fff; }
[data-theme="light"] .member-initials-large { border-color: #fff; }
[data-theme="light"] .member-info-item { background: #fff; border-color: rgba(0,0,0,0.06); box-shadow: 0 1px 4px rgba(0,0,0,0.04); }
[data-theme="light"] .member-info-item:hover { border-color: rgba(37,99,235,0.15); background: rgba(37,99,235,0.02); }
[data-theme="light"] .member-info-icon { background: linear-gradient(135deg, rgba(37,99,235,0.06), rgba(139,92,246,0.04)); border-color: rgba(37,99,235,0.1); }
[data-theme="light"] .member-position-badge { background: linear-gradient(135deg, rgba(37,99,235,0.08), rgba(139,92,246,0.05)); border-color: rgba(37,99,235,0.15); }

@media (max-width: 768px) {
    .member-profile-grid {
        grid-template-columns: 1fr !important;
        text-align: center;
    }
    .member-photo-container {
        width: 200px;
        height: 200px;
    }
    .member-initials-large {
        font-size: 4rem;
    }
    .member-info-grid {
        grid-template-columns: 1fr;
    }
    .member-actions {
        justify-content: center;
    }
    .member-position-badge {
        margin-left: auto;
        margin-right: auto;
    }
}
</style>
@endpush

@push('scripts')
<script>
(function() {
    var memberId = {{ $id }};

    axios.get(API_BASE + '/teams/show/' + memberId)
        .then(function(response) {
            var member = response.data.data;
            var skeleton = document.getElementById('member-skeleton');
            var content = document.getElementById('member-content');
            var notFound = document.getElementById('member-not-found');

            skeleton.style.display = 'none';

            if (!member) {
                notFound.style.display = 'block';
                return;
            }

            var fullName = ((member.first_name || '') + ' ' + (member.last_name || '')).trim();
            document.title = fullName + ' - HexaTerminal Team';
            document.getElementById('breadcrumb-name').textContent = fullName;

            content.style.display = 'block';

            var photoUrl = member.photo ? getImageUrl(member.photo) : '';
            var initial = (member.first_name || 'U').charAt(0).toUpperCase();

            // Photo HTML with onerror fallback
            var photoImgHtml;
            if (photoUrl) {
                photoImgHtml = '<img src="' + photoUrl + '" alt="' + fullName + '" class="member-photo-img" referrerpolicy="no-referrer" ' +
                    'onerror="this.style.display=\'none\';this.nextElementSibling.style.display=\'flex\';">' +
                    '<div class="member-initials-large" style="display:none;">' + initial + '</div>';
            } else {
                photoImgHtml = '<div class="member-initials-large">' + initial + '</div>';
            }

            // Info items
            var infoItems = '';
            if (member.email) {
                infoItems += '<div class="member-info-item" data-aos="fade-up" data-aos-delay="100">' +
                    '<div class="member-info-icon"><i class="fas fa-envelope"></i></div>' +
                    '<div><div class="member-info-label">Email</div>' +
                    '<div class="member-info-value">' + member.email + '</div></div></div>';
            }
            if (member.phone) {
                infoItems += '<div class="member-info-item" data-aos="fade-up" data-aos-delay="150">' +
                    '<div class="member-info-icon"><i class="fas fa-phone"></i></div>' +
                    '<div><div class="member-info-label">Phone</div>' +
                    '<div class="member-info-value">' + member.phone + '</div></div></div>';
            }
            if (member.specialization) {
                infoItems += '<div class="member-info-item" data-aos="fade-up" data-aos-delay="200">' +
                    '<div class="member-info-icon"><i class="fas fa-code"></i></div>' +
                    '<div><div class="member-info-label">Specialization</div>' +
                    '<div class="member-info-value">' + member.specialization + '</div></div></div>';
            }
            if (member.position) {
                infoItems += '<div class="member-info-item" data-aos="fade-up" data-aos-delay="250">' +
                    '<div class="member-info-icon"><i class="fas fa-briefcase"></i></div>' +
                    '<div><div class="member-info-label">Position</div>' +
                    '<div class="member-info-value">' + member.position + '</div></div></div>';
            }

            // Actions
            var actions = '';
            if (member.github_url) {
                actions += '<a href="' + member.github_url + '" target="_blank" rel="noopener" class="btn btn-outline" style="padding:0.75rem 1.5rem;">' +
                    '<i class="fab fa-github"></i> <span>GitHub Profile</span></a>';
            }
            if (member.cv_file) {
                actions += '<a href="' + member.cv_file + '" target="_blank" rel="noopener" class="btn btn-primary" style="padding:0.75rem 1.5rem;">' +
                    '<i class="fas fa-file-alt"></i> <span>Download CV</span></a>';
            }
            if (member.email) {
                actions += '<a href="mailto:' + member.email + '" class="btn btn-outline" style="padding:0.75rem 1.5rem;">' +
                    '<i class="fas fa-envelope"></i> <span>Send Email</span></a>';
            }

            content.innerHTML =
                '<div class="member-profile-grid" data-aos="fade-up">' +
                    '<div>' +
                        '<div class="member-photo-container">' +
                            '<div class="member-photo-ring"></div>' +
                            photoImgHtml +
                        '</div>' +
                    '</div>' +
                    '<div>' +
                        '<h1 style="font-size:clamp(2rem,4vw,2.75rem);font-weight:800;color:var(--text-primary);line-height:1.2;letter-spacing:-0.02em;margin-bottom:0.75rem;">' +
                            fullName +
                        '</h1>' +
                        '<div class="member-position-badge">' +
                            '<i class="fas fa-star" style="color:var(--primary);font-size:0.75rem;"></i>' +
                            '<span class="text-gradient">' + (member.position || 'Team Member') + '</span>' +
                        '</div>' +
                        (member.specialization
                            ? '<p style="color:var(--text-secondary);font-size:1rem;line-height:1.85;margin-bottom:0.5rem;">' +
                                '<i class="fas fa-code" style="color:var(--primary);margin-right:0.5rem;font-size:0.85rem;"></i>' +
                                member.specialization +
                              '</p>'
                            : '') +
                        (infoItems ? '<div class="member-info-grid">' + infoItems + '</div>' : '') +
                        (actions ? '<div class="member-actions">' + actions + '</div>' : '') +
                    '</div>' +
                '</div>' +
                '<div style="margin-top:3.5rem;padding-top:2.5rem;border-top:1px solid rgba(255,255,255,0.04);display:flex;justify-content:space-between;align-items:center;" data-aos="fade-up">' +
                    '<a href="' + '{{ url("/") }}#team' + '" class="btn btn-outline" style="padding:0.8rem 1.75rem;">' +
                        '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>' +
                        '<span>Back to Team</span>' +
                    '</a>' +
                    '<a href="#contact" class="btn btn-primary" style="padding:0.8rem 1.75rem;">' +
                        '<span>Get in Touch</span>' +
                        '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>' +
                    '</a>' +
                '</div>';

            AOS.refresh();
        })
        .catch(function(error) {
            console.error('Failed to load team member:', error);
            document.getElementById('member-skeleton').style.display = 'none';
            document.getElementById('member-not-found').style.display = 'block';
        });
})();
</script>
@endpush
