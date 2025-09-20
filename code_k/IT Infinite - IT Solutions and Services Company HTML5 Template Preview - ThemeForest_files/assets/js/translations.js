// تهيئة i18next مع تحسينات إدارة الخطوط
i18next
  .use(i18nextBrowserLanguageDetector)
  .use(i18nextHttpBackend)
  .init({
    fallbackLng: 'en',
    debug: false, // تغيير إلى false للإنتاج
    ns: ['translation'],
    defaultNS: 'translation',
    backend: {
      loadPath: './translations/{{lng}}.json'
    },
    detection: {
      order: ['localStorage', 'navigator'],
      caches: ['localStorage']
    }
  }, function(err, t) {
    updateContent();
    applyLanguageStyles(i18next.language); // تطبيق الأنماط عند التحميل الأولي
  });

// وظيفة محسنة لتحديث المحتوى والأنماط
function updateContent() {
  // ترجمة النصوص
  document.querySelectorAll('[data-i18n]').forEach(el => {
    const key = el.getAttribute('data-i18n');
    el.textContent = i18next.t(key);
  });
  
  // ترجمة العناصر الأخرى مثل placeholder
  document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
    const key = el.getAttribute('data-i18n-placeholder');
    el.placeholder = i18next.t(key);
  });
  
  // تحديث عنصر اختيار اللغة
  document.querySelectorAll('.language-select option').forEach(option => {
    const lang = option.value;
    option.textContent = i18next.t(`header.languages.${lang}`);
  });
}

// تطبيق أنماط اللغة (الجديدة)
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
  
  // إعادة تحميل الخطوط إذا لزم الأمر
  if (typeof WebFont !== 'undefined') {
    WebFont.load({
      google: {
        families: lng === 'ar' ? ['Cairo'] : ['Jost']
      }
    });
  }
}

// تغيير اللغة مع تحسينات الأداء
function changeLanguage(lng) {
  i18next.changeLanguage(lng, (err, t) => {
    if (err) return console.error('Error changing language:', err);
    
    updateContent();
    applyLanguageStyles(lng);
    localStorage.setItem('selectedLanguage', lng);
    
    // إعادة تحميل المكونات الديناميكية إذا لزم الأمر
    if (window.reloadComponents) {
      window.reloadComponents();
    }
  });
}

// تحميل اللغة المحفوظة مع تحسينات
document.addEventListener('DOMContentLoaded', function() {
  const savedLanguage = localStorage.getItem('selectedLanguage') || 
                       i18next.options.fallbackLng;
  
  // تأخير طفيف لضمان تحميل جميع الموارد
  setTimeout(() => {
    changeLanguage(savedLanguage);
  }, 50);
});

// لجعل الدالة متاحة globally إذا لزم الأمر
window.changeLanguage = changeLanguage;