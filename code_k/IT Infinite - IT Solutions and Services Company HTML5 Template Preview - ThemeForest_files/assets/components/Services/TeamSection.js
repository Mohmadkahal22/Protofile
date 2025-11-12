document.addEventListener('alpine:init', () => {
    // إضافة أنماط CSS للعناصر المخفية
    const style = document.createElement('style');
    style.textContent = `
        .hidden { display: none !important; }
        .team-item { transition: all 0.3s ease; }
        .team-item:hover { transform: translateY(-5px); }
        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.3);
            opacity: 0;
            transition: opacity 0.3s ease;
            border-radius: 8px;
        }
        .team-img:hover .image-overlay { opacity: 1; }
        .team-img { position: relative; overflow: hidden; border-radius: 8px; }
        .team-social { position: absolute; bottom: 15px; right: 15px; z-index: 10; }
        .share-icon ul {
            display: none;
            position: absolute;
            bottom: 100%;
            right: 0;
            background: rgba(0,0,0,0.9);
            border-radius: 5px;
            padding: 10px;
            min-width: 150px;
        }
        .share-icon:hover ul { display: block; }
        .share-icon ul li { margin: 5px 0; }
        .share-icon ul li a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .share-icon ul li a:hover { color: #007bff; }
    `;
    document.head.appendChild(style);

    const loadingIndicator = {
        showTeamLoader() {
            document.getElementById('teamLoading').classList.remove('hidden');
            document.getElementById('teamContainer').classList.add('hidden');
            document.getElementById('teamEmptyState').classList.add('hidden');
            document.getElementById('teamErrorState').classList.add('hidden');
        },
        hideTeamLoader() {
            document.getElementById('teamLoading').classList.add('hidden');
        },
        showTeamContainer() {
            document.getElementById('teamContainer').classList.remove('hidden');
        },
        showEmptyState() {
            document.getElementById('teamEmptyState').classList.remove('hidden');
            document.getElementById('teamContainer').classList.add('hidden');
            document.getElementById('teamLoading').classList.add('hidden');
            document.getElementById('teamErrorState').classList.add('hidden');
        },
        showErrorState() {
            document.getElementById('teamErrorState').classList.remove('hidden');
            document.getElementById('teamContainer').classList.add('hidden');
            document.getElementById('teamLoading').classList.add('hidden');
            document.getElementById('teamEmptyState').classList.add('hidden');
        }
    };
    function getDriveEmbedUrl(driveUrl) {
        try {
            if (!driveUrl || typeof driveUrl !== 'string') return '';

            // استخراج fileId باستخدام الدالة السابقة
            const directUrl = getDirectDriveLink(driveUrl);
            const fileIdMatch = directUrl.match(/id=([^&]+)/);
            const fileId = fileIdMatch ? fileIdMatch[1] : '';

            if (fileId) {
                // استخدام رابط embed للصور
                return `https://drive.google.com/thumbnail?id=${fileId}&sz=w1000`;
            }

            return driveUrl;
        } catch (error) {
            console.error('Error generating embed URL:', error);
            return driveUrl;
        }
    }


    // 🔗 دالة محسنة لتحويل رابط Google Drive إلى رابط مباشر للعرض
    function getDirectDriveLink(driveUrl) {
        try {
            if (!driveUrl || typeof driveUrl !== 'string') return '';

            console.log('Processing Drive URL:', driveUrl);

            // تنظيف الرابط من أي بارامترات إضافية
            const cleanUrl = driveUrl.split('?')[0];

            // التعامل مع مختلف تنسيقات روابط Google Drive
            let fileId = '';

            // النمط 1: /file/d/FILE_ID/view (النمط الشائع)
            if (cleanUrl.includes('/file/d/')) {
                const match = cleanUrl.match(/\/file\/d\/([^/]+)/);
                fileId = match ? match[1] : '';
            }
            // النمط 2: /drive/folders/FILE_ID (للمجلدات)
            else if (cleanUrl.includes('/drive/folders/')) {
                const match = cleanUrl.match(/\/drive\/folders\/([^/?]+)/);
                fileId = match ? match[1] : '';
            }
            // النمط 3: /open?id=FILE_ID
            else if (cleanUrl.includes('/open?id=')) {
                const match = cleanUrl.match(/\/open\?id=([^&]+)/);
                fileId = match ? match[1] : '';
            }
            // النمط 4: رابط مباشر يحتوي على FILE_ID
            else {
                // البحث عن أي نمط يحتوي على معرف ملف
                const patterns = [
                    /[a-zA-Z0-9_-]{25,}/, // نمط معرف Google Drive النموذجي
                ];

                for (const pattern of patterns) {
                    const match = cleanUrl.match(pattern);
                    if (match && match[0].length >= 25) {
                        fileId = match[0];
                        break;
                    }
                }
            }

            console.log('Extracted File ID:', fileId);

            if (fileId) {
                // استخدام رابط العرض المباشر مع إضافة timestamp لمنع التخزين المؤقت
                const timestamp = new Date().getTime();
                const directUrl = `https://drive.google.com/uc?export=view&id=${fileId}&t=${timestamp}`;
                console.log('Generated Direct URL:', directUrl);
                return directUrl;
            }

            // إذا لم نتمكن من استخراج معرف الملف، نعيد الرابط الأصلي
            console.log('No file ID found, returning original URL');
            return driveUrl;

        } catch (error) {
            console.error('Error processing Drive URL:', error);
            return driveUrl || '';
        }
    } async function getBestImageUrl(originalUrl) {
        if (!originalUrl) return 'assets/images/person1.jpg';

        const alternatives = [
            getDriveEmbedUrl(originalUrl), // الأولوية للـ embed
            getDirectDriveLink(originalUrl), // ثم الرابط المباشر
            originalUrl // وأخيراً الرابط الأصلي
        ];

        for (const url of alternatives) {
            try {
                const isValid = await checkImage(url);
                if (isValid) {
                    console.log(`✅ Image loaded successfully: ${url}`);
                    return url;
                }
            } catch (error) {
                console.log(`❌ Image failed: ${url}`);
                continue;
            }
        }

        return 'assets/images/person1.jpg';
    }

    // دالة محسنة للتحقق من صحة الصورة
    function checkImage(url) {
        return new Promise((resolve) => {
            // إذا كان الرابط فارغاً أو غير صالح
            if (!url || url === '#') {
                resolve(false);
                return;
            }

            const img = new Image();
            let timeoutId;

            img.onload = function () {
                clearTimeout(timeoutId);
                // التحقق من أن الصورة محملة بشكل صحيح
                if (img.width > 0 && img.height > 0) {
                    resolve(true);
                } else {
                    resolve(false);
                }
            };

            img.onerror = function () {
                clearTimeout(timeoutId);
                resolve(false);
            };

            // timeout بعد 5 ثواني
            timeoutId = setTimeout(() => {
                img.onload = img.onerror = null; // إلغاء معالجات الأحداث
                resolve(false);
            }, 5000);

            img.src = url;
        });
    }

    // دالة لتحميل الصورة مع إعادة المحاولة
    async function loadImageWithRetry(url, retries = 2) {
        for (let i = 0; i < retries; i++) {
            const isValid = await checkImage(url);
            if (isValid) {
                return url;
            }

            // الانتظار قبل إعادة المحاولة
            if (i < retries - 1) {
                await new Promise(resolve => setTimeout(resolve, 1000 * (i + 1)));
            }
        }
        return null;
    }

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
        } else {
            // fallback إذا لم يكن Swal متاحاً
            console.log(`${color}: ${message}`);
        }
    }

    Alpine.data('teamSection', () => ({
        teamData: [],
        apiBaseUrl: 'http://127.0.0.1:8000',
        isLoading: false,

        async init() {
            await this.$nextTick();
            this.fetchTeamData();
        },

        async fetchTeamData() {
            if (this.isLoading) return;

            this.isLoading = true;
            try {
                loadingIndicator.showTeamLoader();

                // استخدام token تجريبي للاختبار
                const token = localStorage.getItem('authToken') || 'test-token-123';

                console.log('Fetching team data from:', `${this.apiBaseUrl}/api/teams/index`);

                const response = await fetch(`${this.apiBaseUrl}/api/teams/index`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json'
                    },
                });

                if (!response.ok) {
                    throw new Error(`خطأ في الشبكة: ${response.status} ${response.statusText}`);
                }

                const data = await response.json();
                console.log('Team data received:', data);

                if (data.status === 'success' && data.data) {
                    this.teamData = Array.isArray(data.data) ? data.data : [];
                    console.log('Processed team data:', this.teamData);

                    if (this.teamData.length === 0) {
                        loadingIndicator.showEmptyState();
                    } else {
                        await this.populateTeamSection();
                        loadingIndicator.hideTeamLoader();
                        loadingIndicator.showTeamContainer();
                    }
                } else {
                    throw new Error(data.message || 'تنسيق الاستجابة غير صالح');
                }
            } catch (error) {
                console.error('Error fetching team data:', error);
                loadingIndicator.showErrorState();
                coloredToast('error', error.message || 'فشل في جلب بيانات الفريق');
            } finally {
                this.isLoading = false;
            }
        },

        async populateTeamSection() {
            const teamContainers = document.querySelectorAll('.team-boxs.grid-wrapper');
            if (teamContainers.length < 2) {
                console.error('Team containers not found');
                return;
            }

            // مسح المحتوى الحالي
            teamContainers.forEach(container => container.innerHTML = '');

            const leftContainer = teamContainers[0];
            const rightContainer = teamContainers[1];

            // تحميل الصور بشكل غير متزامن
            const teamPromises = this.teamData.map(async (member, index) => {
                const fullName = `${member.first_name || ''} ${member.last_name || ''}`.trim();

                console.log(`Processing member: ${fullName}`, member);

                // ⚡ استخدام الدالة الجديدة للحصول على أفضل رابط للصورة
                const finalPhotoUrl = await getBestImageUrl(member.photo);

                // معالجة ملف السيرة الذاتية
                const cvUrl = member.cv_file ? getDirectDriveLink(member.cv_file) : null;

                const teamItem = document.createElement('div');
                teamItem.className = 'team-item team-style-2 mb-4';
                teamItem.innerHTML = `
                    <div class="team-img">
                        <img
                            loading="lazy"
                            class="img-fluid"
                            src="${finalPhotoUrl || 'assets/images/person1.jpg'}"
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


                // إضافة إلى العمود المناسب
                const targetContainer = index % 2 === 0 ? leftContainer : rightContainer;
                targetContainer.appendChild(teamItem);

                return teamItem;
            });

            await Promise.all(teamPromises);
            console.log('Team section populated successfully');
        }
    }));

    // جعل teamSection متاحاً globally لإعادة المحاولة
    setTimeout(() => {
        window.teamSection = Alpine.$data(document.querySelector('#ourteam'));
    }, 1000);
});


