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
        showProjectsLoader: function () {
            const projectsLoading = document.getElementById('projectsLoading');
            const projectsContainer = document.getElementById('projectsContainer');
            const projectsEmptyState = document.getElementById('projectsEmptyState');
            if (projectsLoading) projectsLoading.classList.remove('hidden');
            if (projectsContainer) projectsContainer.classList.add('hidden');
            if (projectsEmptyState) projectsEmptyState.classList.add('hidden');
        },
        hideProjectsLoader: function () {
            const projectsLoading = document.getElementById('projectsLoading');
            const projectsContainer = document.getElementById('projectsContainer');
            if (projectsLoading) projectsLoading.classList.add('hidden');
            if (projectsContainer) projectsContainer.classList.remove('hidden');
        },
        showEmptyState: function () {
            const projectsEmptyState = document.getElementById('projectsEmptyState');
            const projectsContainer = document.getElementById('projectsContainer');
            const projectsLoading = document.getElementById('projectsLoading');
            if (projectsEmptyState) projectsEmptyState.classList.remove('hidden');
            if (projectsContainer) projectsContainer.classList.add('hidden');
            if (projectsLoading) projectsLoading.classList.add('hidden');
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

    Alpine.data('projectsSection', () => ({
        projectsData: [],
        apiBaseUrl: typeof API_CONFIG !== 'undefined' && API_CONFIG.BASE_URL_Renter ? API_CONFIG.BASE_URL_Renter : 'http://127.0.0.1:8000',

        async init() {
            await this.$nextTick();
            this.fetchProjectsData();
        },

        async fetchProjectsData() {
            try {
                loadingIndicator.showProjectsLoader();
                const token = localStorage.getItem('authToken') || 'your-test-token';
                if (!token) {
                    coloredToast('danger', 'Authentication token missing');
                    loadingIndicator.hideProjectsLoader();
                    loadingIndicator.showEmptyState();
                    return;
                }

                const response = await fetch(`${this.apiBaseUrl}/api/projects/index`, {
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
                    this.projectsData = data.data || [];
                    if (this.projectsData.length === 0) {
                        loadingIndicator.showEmptyState();
                    } else {
                        this.populateProjectsSection();
                        loadingIndicator.hideProjectsLoader();
                    }
                } else {
                    throw new Error(data.message || 'Invalid response format');
                }
            } catch (error) {
                loadingIndicator.hideProjectsLoader();
                loadingIndicator.showEmptyState();
                coloredToast('danger', error.message || 'Failed to fetch projects data');
            }
        },

        populateProjectsSection() {
            const projectsContainer = document.querySelector('.case-study-fancy-wrapper');
            if (!projectsContainer) {
                return;
            }

            projectsContainer.innerHTML = ''; // Clear static content

            this.projectsData.forEach((project, index) => {
                const imagePath = project.images[0].image_path;
                const isActive = index === 0 ? 'active' : ''; // Make first project active, as in original HTML
                const projectItem = document.createElement('div');
                projectItem.className = `case-studies-wrapper case-studies-style-1 ${isActive}`;

                console.log(imagePath);

                projectItem.className = `case-studies-wrapper case-studies-style-1 ${isActive}`;
                projectItem.innerHTML = `
                    <div class="case-studies-img">
                        <img
                            class="img-fluid"
                            src="${imagePath}"
                            alt="${project.title || 'Project'}"
                        />
                    </div>
                    <div class="case-studies-info">
                        <div class="case-studies-info-inner">
                            <h3 class="case-studies-title">
                                <a href="#">${project.title || 'N/A'}</a>
                            </h3>
                            <div class="case-studies-content">
                                <div class="case-studies-description">${project.description || 'No description available'}</div>
                            </div>
                            <div class="case-study-link">
                                <a class="btn-arrow" href="project.html?id=${project.id}">
                                    <svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="25" cy="25" r="25" fill="#F8F8F8"/>
                                        <path d="M15 25H35" stroke="#191A23" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M27 17L35 25L27 33" stroke="#191A23" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                `;
                projectsContainer.appendChild(projectItem);
            });
        }
    }));
});
