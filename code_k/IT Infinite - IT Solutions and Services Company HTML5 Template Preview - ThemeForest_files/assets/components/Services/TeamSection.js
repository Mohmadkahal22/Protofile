document.addEventListener('alpine:init', () => {
    const loadingIndicator = {
        show: function () {
            const indicator = document.getElementById('loadingIndicator');
            if (indicator) indicator.classList.remove('hidden');
        },
        hide: function () {
            const indicator = document.getElementById('loadingIndicator');
            if (indicator) indicator.classList.add('hidden');
        },
        showTeamLoader: function () {
            const teamLoading = document.getElementById('teamLoading');
            const teamContainer = document.getElementById('teamContainer');
            const teamEmptyState = document.getElementById('teamEmptyState');
            if (teamLoading) teamLoading.classList.remove('hidden');
            if (teamContainer) teamContainer.classList.add('hidden');
            if (teamEmptyState) teamEmptyState.classList.add('hidden');
        },
        hideTeamLoader: function () {
            const teamLoading = document.getElementById('teamLoading');
            const teamContainer = document.getElementById('teamContainer');
            if (teamLoading) teamLoading.classList.add('hidden');
            if (teamContainer) teamContainer.classList.remove('hidden');
        },
        showEmptyState: function () {
            const teamEmptyState = document.getElementById('teamEmptyState');
            const teamContainer = document.getElementById('teamContainer');
            const teamLoading = document.getElementById('teamLoading');
            if (teamEmptyState) teamEmptyState.classList.remove('hidden');
            if (teamContainer) teamContainer.classList.add('hidden');
            if (teamLoading) teamLoading.classList.add('hidden');
        }
    };

    function coloredToast(color, message) {
        if (typeof Swal !== 'undefined') {
            const toast = Swal.mixin({
                toast: true,
                position: 'bottom-start',
                icon: color === 'success' ? 'success' : 'error',
                title: message,
                showConfirmButton: false,
                timer: 3000,
                showCloseButton: true,
                customClass: { popup: `color-${color}` },
            });
            toast.fire();
        }
    }

    Alpine.data('teamSection', () => ({
        teamData: [],
        apiBaseUrl: typeof API_CONFIG !== 'undefined' && API_CONFIG.BASE_URL_Renter ? API_CONFIG.BASE_URL_Renter : 'http://127.0.0.1:8000',

        async init() {
            await this.$nextTick();
            this.fetchTeamData();
        },

        async fetchTeamData() {
            try {
                loadingIndicator.showTeamLoader();
                const token = localStorage.getItem('authToken') || 'your-test-token';
                if (!token) {
                    coloredToast('danger', 'Authentication token missing');
                    loadingIndicator.hideTeamLoader();
                    loadingIndicator.showEmptyState();
                    return;
                }

                const response = await fetch(`${this.apiBaseUrl}/api/teams/index`, {
                    method: 'GET',
                    headers: {
                        Accept: 'application/json',
                        Authorization: `Bearer ${token}`,
                    },
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                if (data.status === 'success' && data.data) {
                    this.teamData = data.data || [];
                    if (this.teamData.length === 0) {
                        loadingIndicator.showEmptyState();
                    } else {
                        this.populateTeamSection();
                        loadingIndicator.hideTeamLoader();
                    }
                } else {
                    throw new Error(data.message || 'Invalid response format');
                }
            } catch (error) {
                loadingIndicator.hideTeamLoader();
                loadingIndicator.showEmptyState();
                coloredToast('danger', error.message || 'Failed to fetch team data');
            }
        },

        populateTeamSection() {
            const teamContainers = document.querySelectorAll('.team-boxs.grid-wrapper');
            if (teamContainers.length < 2) {
                return;
            }

            teamContainers.forEach(container => container.innerHTML = '');
            const leftContainer = teamContainers[0];
            const rightContainer = teamContainers[1];

            this.teamData.forEach((member, index) => {
                const fullName = `${member.first_name || ''} ${member.last_name || ''}`.trim();
                const teamItem = document.createElement('div');
                teamItem.className = 'team-item team-style-2';
                teamItem.innerHTML = `
                    <div class="team-img">
                        <img
                            loading="lazy"
                            class="img-fluid"
                            src="${member.photo || 'assets/images/person1.jpg'}"
                            alt="${fullName || 'Team Member'}"
                            onerror="this.src='assets/images/person1.jpg';"
                        />
                        <div class="image-overlay"></div>
                        <div class="team-social">
                            <div class="share-icon">
                                <img class="img-fluid" src="assets/icons/share.svg" alt="Share" />
                                <ul>
                                    <li>
                                        <a href="${member.github_url || '#'}" target="_blank">
                                            <span data-i18n="social.github">GitHub</span>
                                            <i class="fa-brands fa-github"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" target="_blank">
                                            <span data-i18n="social.linkedin">LinkedIn</span>
                                            <i class="fa-brands fa-linkedin-in"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" target="_blank">
                                            <span data-i18n="social.facebook">Facebook</span>
                                            <i class="fa-brands fa-facebook-f"></i>
                                        </a>
                                    </li>
                                    ${member.cv_file ? `
                                        <li>
                                            <a href="${member.cv_file}" target="_blank" download>
                                                <span data-i18n="social.cv">Download CV</span>
                                                <i class="fa-solid fa-file-pdf"></i>
                                            </a>
                                        </li>
                                    ` : ''}
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="team-info">
                        <a href="#" class="team-title">${fullName || 'Unknown'}</a>
                        <span class="team-destination">${member.position || 'N/A'}</span>
                        <div class="team-details">
                            <p><strong>Email:</strong> <a href="mailto:${member.email || '#'}" class="${member.email ? '' : 'text-muted'}">${member.email || 'N/A'}</a></p>
                            <p><strong>Phone:</strong> <a href="tel:${member.phone || '#'}" class="${member.phone ? '' : 'text-muted'}">${member.phone || 'N/A'}</a></p>
                            <p><strong>Specialization:</strong> ${member.specialization || 'N/A'}</p>
                        </div>
                    </div>
                `;
                if (index % 2 === 0) {
                    leftContainer.appendChild(teamItem);
                } else {
                    rightContainer.appendChild(teamItem);
                }
            });
        }
    }));
});
