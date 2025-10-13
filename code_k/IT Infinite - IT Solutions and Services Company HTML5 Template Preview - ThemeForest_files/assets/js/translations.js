// تهيئة i18next
i18next
    .use(i18nextBrowserLanguageDetector)
    .use(i18nextHttpBackend)
    .init({
        fallbackLng: 'en',
        debug: false, // false في الإنتاج
        ns: ['translation'],
        defaultNS: 'translation',
        backend: {
            loadPath: './translations/{{lng}}.json'
        },
        detection: {
            order: ['localStorage', 'navigator'],
            caches: ['localStorage']
        }
    }, function (err, t) {
        if (err) console.error(err);
        updateContent();
        applyLanguageStyles(i18next.language);
    });

// تحديث النصوص في الصفحة
function updateContent() {
    document.querySelectorAll('[data-i18n]').forEach(el => {
        const key = el.getAttribute('data-i18n');
        el.textContent = i18next.t(key);
    });

    document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
        const key = el.getAttribute('data-i18n-placeholder');
        el.placeholder = i18next.t(key);
    });
}

// تطبيق اتجاه اللغة والخط
function applyLanguageStyles(lng) {
    const html = document.documentElement;
    const body = document.body;

    if (lng === 'ar') {
        html.dir = 'rtl';
        html.lang = 'ar';
        body.classList.add('rtl', 'arabic-font');
        body.classList.remove('ltr', 'latin-font');
    } else {
        html.dir = 'ltr';
        html.lang = lng;
        body.classList.add('ltr', 'latin-font');
        body.classList.remove('rtl', 'arabic-font');
    }

    // تحميل الخطوط باستخدام WebFont
    if (typeof WebFont !== 'undefined') {
        WebFont.load({
            google: {
                families: lng === 'ar' ? ['Cairo:400,700'] : ['Jost:400,700']
            }
        });
    }
}

// دالة تغيير اللغة
function changeLanguage(lng) {
    i18next.changeLanguage(lng, (err, t) => {
        if (err) return console.error('Error changing language:', err);
        updateContent();
        applyLanguageStyles(lng);
        localStorage.setItem('selectedLanguage', lng);

        // تحديث أي مكونات ديناميكية إضافية
        if (window.reloadComponents) {
            window.reloadComponents();
        }
    });
}

// زر اللغة المخصص Dropdown
const langDropdown = document.querySelector('.custom-language-dropdown');
const langItems = document.querySelectorAll('.lang-options li');



// اختيار اللغة
langItems.forEach(item => {
    item.addEventListener('click', () => {
        const selectedLang = item.dataset.lang;
        changeLanguage(selectedLang);
        langDropdown.classList.remove('active');
        // تحديث النص داخل الزر
        langBtn.innerHTML = item.textContent + ' <i class="fa-solid fa-chevron-down"></i>';
    });
});

// إغلاق القائمة عند النقر خارجها
document.addEventListener('click', e => {
    if (!langDropdown.contains(e.target)) {
        langDropdown.classList.remove('active');
    }
});

// تحميل اللغة المحفوظة عند بدء الصفحة
document.addEventListener('DOMContentLoaded', () => {
    const savedLanguage = localStorage.getItem('selectedLanguage') || i18next.options.fallbackLng;
    setTimeout(() => {
        changeLanguage(savedLanguage);
    }, 50);
});

// إتاحة الدالة عالميًا إذا لزم
window.changeLanguage = changeLanguage;
