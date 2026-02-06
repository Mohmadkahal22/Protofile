<!-- ═══ TEAM SECTION ═══ -->
<section id="team" class="section" style="background: var(--dark-bg); position:relative; overflow:hidden;">
    <div class="glow-dot" style="width:400px;height:400px;background:var(--gold);top:50%;left:-100px;opacity:0.03;"></div>
    <div class="glow-dot" style="width:300px;height:300px;background:var(--gold-light);bottom:-100px;right:-100px;opacity:0.02;"></div>

    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-badge"><i class="fas fa-crown"></i> <span data-i18n="section_team_badge">Our People</span></span>
            <h2 class="section-title" data-i18n="section_team_title">Meet the Team</h2>
            <p class="section-subtitle" data-i18n="section_team_subtitle">The talented professionals driving our innovation and success</p>
        </div>

        <!-- Skeleton -->
        <div id="team-skeleton" class="grid-4">
            @for($i = 0; $i < 4; $i++)
            <div class="skeleton skeleton-card" style="height:380px;"></div>
            @endfor
        </div>

        <!-- Actual content -->
        <div id="team-grid" class="grid-4" style="display:none;"></div>

        <!-- Empty state -->
        <div id="team-empty" style="display:none;text-align:center;padding:4rem 0;">
            <div style="width:80px;height:80px;margin:0 auto 1.5rem;border-radius:50%;background:rgba(212,175,55,0.06);display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-users" style="font-size:2rem;color:var(--gold);"></i>
            </div>
            <p style="font-size:1.1rem;color:var(--text-secondary);font-weight:500;">Team info coming soon!</p>
        </div>
    </div>
</section>

@push('styles')
<style>
.team-card { padding: 2.25rem 1.75rem; text-align: center; position: relative; }
.team-card::before {
    content: ''; position: absolute; top: 0; left: 50%; transform: translateX(-50%);
    width: 60px; height: 3px; border-radius: 2px;
    background: linear-gradient(90deg, var(--gold), var(--gold-dark));
    opacity: 0; transition: all 0.4s;
}
.card:hover .team-card::before { opacity: 1; width: 80px; }

.team-photo-wrap {
    width: 120px; height: 120px; margin: 0 auto 1.5rem;
    border-radius: 50%; position: relative;
}
.team-photo-wrap::before {
    content: ''; position: absolute; inset: -4px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--gold), var(--gold-dark));
    opacity: 0.3; transition: opacity 0.4s;
}
.card:hover .team-photo-wrap::before { opacity: 0.6; }
.team-photo-inner {
    width: 100%; height: 100%; border-radius: 50%; overflow: hidden;
    position: relative; z-index: 1;
    border: 3px solid var(--dark-bg);
}
.team-photo-inner img { width: 100%; height: 100%; object-fit: cover; }
.team-initials {
    width: 100%; height: 100%;
    background: linear-gradient(135deg, var(--gold), var(--gold-dark));
    display: flex; align-items: center; justify-content: center;
    font-size: 2.5rem; font-weight: 700; color: white;
}
.team-name { font-size: 1.15rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.25rem; }
.team-position {
    font-size: 0.82rem; font-weight: 600; margin-bottom: 0.5rem;
    background: linear-gradient(135deg, var(--gold), var(--gold-dark));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
}
.team-spec { color: var(--text-muted); font-size: 0.78rem; margin-bottom: 1rem; }
.team-social {
    display: flex; justify-content: center; gap: 0.5rem;
}
.team-social a {
    width: 36px; height: 36px; border-radius: var(--radius-xs);
    background: rgba(212,175,55,0.04); border: 1px solid rgba(212,175,55,0.08);
    display: flex; align-items: center; justify-content: center;
    color: var(--text-secondary); text-decoration: none;
    transition: var(--transition-fast); font-size: 0.82rem;
}
.team-social a:hover {
    background: var(--gold); color: #070A18; border-color: var(--gold);
    transform: translateY(-2px);
}

/* Light theme */
[data-theme="light"] .team-photo-inner { border-color: #FDFCF9; }
[data-theme="light"] .team-social a { background: rgba(184,148,31,0.03); border-color: rgba(184,148,31,0.06); }
[data-theme="light"] .team-social a:hover { background: var(--gold); color: #fff; }
</style>
@endpush

@push('scripts')
<script>
(function() {
    axios.get(API_BASE + '/teams/index')
        .then(function(response) {
            var teams = response.data.data || [];
            var skeleton = document.getElementById('team-skeleton');
            var grid = document.getElementById('team-grid');
            var empty = document.getElementById('team-empty');

            skeleton.style.display = 'none';
            if (teams.length === 0) { empty.style.display = 'block'; return; }
            grid.style.display = 'grid';

            teams.forEach(function(member, index) {
                var card = document.createElement('div');
                card.className = 'card';
                card.style.cursor = 'pointer';
                card.setAttribute('data-aos', 'fade-up');
                card.setAttribute('data-aos-delay', (index * 80).toString());
                card.onclick = function() { window.location.href = '/team/' + member.id; };

                var photoUrl = member.photo ? getImageUrl(member.photo) : '';
                var initials = (member.first_name || 'U').charAt(0).toUpperCase();
                var fullName = ((member.first_name || '') + ' ' + (member.last_name || '')).trim();

                var socialHtml = '<div class="team-social">';
                if (member.github_url) socialHtml += '<a href="' + member.github_url + '" target="_blank" rel="noopener" aria-label="GitHub" onclick="event.stopPropagation();"><i class="fab fa-github"></i></a>';
                if (member.linkedin_url) socialHtml += '<a href="' + member.linkedin_url + '" target="_blank" rel="noopener" aria-label="LinkedIn" onclick="event.stopPropagation();"><i class="fab fa-linkedin-in"></i></a>';
                if (member.twitter_url) socialHtml += '<a href="' + member.twitter_url + '" target="_blank" rel="noopener" aria-label="Twitter" onclick="event.stopPropagation();"><i class="fab fa-twitter"></i></a>';
                socialHtml += '<a href="/team/' + member.id + '" aria-label="View Profile" onclick="event.stopPropagation();"><i class="fas fa-arrow-right"></i></a>';
                socialHtml += '</div>';

                var photoHtml;
                if (photoUrl) {
                    photoHtml = '<img src="' + photoUrl + '" alt="' + fullName + '" loading="lazy" ' +
                        'onerror="this.style.display=\'none\';this.nextElementSibling.style.display=\'flex\';" ' +
                        'referrerpolicy="no-referrer">' +
                        '<div class="team-initials" style="display:none;">' + initials + '</div>';
                } else {
                    photoHtml = '<div class="team-initials">' + initials + '</div>';
                }

                card.innerHTML =
                    '<div class="team-card">' +
                        '<div class="team-photo-wrap">' +
                            '<div class="team-photo-inner">' +
                                photoHtml +
                            '</div>' +
                        '</div>' +
                        '<h3 class="team-name">' + (fullName || 'Team Member') + '</h3>' +
                        '<p class="team-position">' + (member.position || 'Team Member') + '</p>' +
                        (member.specialization ? '<p class="team-spec">' + member.specialization + '</p>' : '') +
                        socialHtml +
                    '</div>';
                grid.appendChild(card);
            });

            AOS.refresh();
        })
        .catch(function(error) {
            console.error('Failed to load team:', error);
            document.getElementById('team-skeleton').style.display = 'none';
            document.getElementById('team-empty').style.display = 'block';
        });
})();
</script>
@endpush
