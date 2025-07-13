<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="منصة مين فلو كونكت لإدارة الصيانة الذكية - حلول متكاملة لتحسين عمليات الصيانة وإدارة الأصول">
    <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="stylesheet" href="{{ asset('css/landing.css') }}">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" href="https://via.placeholder.com/32x32/0F2348/FFFFFF?text=MF" type="image/png">
    <title>مين فلو كونكت | إدارة الصيانة الذكية</title>
  
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container header-container">
            <a href="index.html" class="logo">
                    <img src="{{ asset('Images/logo.jpeg') }}" alt="شعار الشركة"> 
                مين فلو كونكت
            </a>
            
            <nav class="nav">
                <a href="#features" class="nav-link">الميزات</a>
                <a href="#solution" class="nav-link">الحلول</a>
                <a href="#process" class="nav-link">العملية</a>
                <a href="#testimonials" class="nav-link">آراء العملاء</a>
                
                <div class="header-actions">
                    <li><a href="{{ route('login.form') }}" class="btn btn-secondary">تسجيل الدخول</a></li>

                </div>
            </nav>
            
            <button class="mobile-menu-toggle" aria-label="Toggle menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-shape"></div>
        <div class="hero-shape-2"></div>
        
        <div class="container hero-container">
            <div class="hero-content animate-on-scroll">
                <span class="hero-badge animate-on-scroll delay-1">صيانة ذكية ومتكاملة</span>
                <h1 class="hero-title">حوّل عمليات الصيانة إلى تجربة ذكية وسلسة</h1>
                <p class="hero-description">منصة مين فلو كونكت تقدم حلولاً متكاملة لإدارة الصيانة والأصول، مما يمكنك من تحسين العمليات، خفض التكاليف، وزيادة الإنتاجية.</p>
                
                <div class="hero-cta">
                    <a href="auth/login.html" class="btn btn-primary btn-lg">ابدأ تجربة مجانية</a>
                    <a href="#features" class="btn btn-outline-light btn-lg">استكشف الميزات</a>
                </div>
                
                <div class="hero-stats">
                    <div class="stat-item animate-on-scroll delay-2">
                        <div class="stat-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <div>
                            <div class="stat-number">+150</div>
                            <div class="stat-label">شركة وثقت بنا</div>
                        </div>
                    </div>
                    
                    <div class="stat-item animate-on-scroll delay-3">
                        <div class="stat-icon">
                            <i class="fas fa-wrench"></i>
                        </div>
                        <div>
                            <div class="stat-number">+10K</div>
                            <div class="stat-label">طلب صيانة شهرياً</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="hero-image animate-on-scroll delay-1">
             <img src="{{ asset('Images/ferrari.png') }}" alt="سيارة رياضية حديثة" class="floating">
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="section-padding features-section">
        <div class="container">
            <div class="section-header text-center animate-on-scroll">
                <h2>ميزات منصة مين فلو كونكت</h2>
                <p>نقدم مجموعة متكاملة من الأدوات الذكية لتحويل عمليات الصيانة إلى تجربة فعالة وسهلة</p>
            </div>
            
            <div class="features-grid">
                <div class="feature-card animate-on-scroll delay-1">
                    <div class="feature-icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <h3>إدارة الطلبات الذكية</h3>
                    <p>نظام متكامل لإنشاء، تتبع، وإدارة طلبات الصيانة بسهولة وكفاءة، مع إمكانية التصنيف والأولوية.</p>
                </div>
                
                <div class="feature-card animate-on-scroll delay-2">
                    <div class="feature-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h3>جدولة المواعيد</h3>
                    <p>خطط وجدول مواعيد الصيانة الدورية والطارئة مع إشعارات تلقائية للفرق والعملاء.</p>
                </div>
                
                <div class="feature-card animate-on-scroll delay-3">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>تقارير وتحليلات</h3>
                    <p>لوحات تحكم تفاعلية وتقارير مفصلة تساعدك على اتخاذ قرارات مدعومة بالبيانات.</p>
                </div>
                
                <div class="feature-card animate-on-scroll delay-1">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3>تطبيق متنقل</h3>
                    <p>إدارة الصيانة من أي مكان عبر تطبيقنا المتنقل المتوافق مع جميع الأجهزة الذكية.</p>
                </div>
                
                <div class="feature-card animate-on-scroll delay-2">
                    <div class="feature-icon">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <h3>تكاملات متقدمة</h3>
                    <p>تكامل سهل مع أنظمتك الحالية مثل ERP وCRM ونظم إدارة المستودعات.</p>
                </div>
                
                <div class="feature-card animate-on-scroll delay-3">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>أمان وحماية</h3>
                    <p>نظام حماية متكامل مع تشفير البيانات والنسخ الاحتياطي التلقائي للحفاظ على أمان معلوماتك.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Solution Section -->
    <section id="solution" class="section-padding solution-section">
        <div class="container solution-container">
            <div class="solution-content animate-on-scroll">
                <h2>حلول مصممة لاحتياجاتك</h2>
                <p>نفهم تحديات إدارة الصيانة ونقدم حلولاً ذكية تلبي احتياجات مختلف القطاعات والشركات، سواء كانت صغيرة أو متوسطة أو كبيرة.</p>
                
                <div class="solution-list">
                    <div class="solution-item animate-on-scroll delay-1">
                        <div class="solution-item-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="solution-item-content">
                            <h4>إدارة العقود والميزانيات</h4>
                            <p>تحكم كامل في عقود الصيانة والميزانيات مع تقارير الإنفاق والتحذيرات التلقائية.</p>
                        </div>
                    </div>
                    
                    <div class="solution-item animate-on-scroll delay-2">
                        <div class="solution-item-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="solution-item-content">
                            <h4>تتبع قطع الغيار</h4>
                            <p>نظام متكامل لإدارة المخزون وقطع الغيار مع إمكانية الربط مع نظم المستودعات.</p>
                        </div>
                    </div>
                    
                    <div class="solution-item animate-on-scroll delay-3">
                        <div class="solution-item-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="solution-item-content">
                            <h4>إدارة الفرق الميدانية</h4>
                            <p>توزيع المهام، متابعة الأداء، وإدارة الفرق الميدانية بكفاءة عالية.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="solution-image animate-on-scroll delay-1">
               <img src="{{ asset('Images/ferrari.png') }}" alt="حلول مين فلو كونكت" class="floating-2">
            </div>
        </div>
    </section>

    <!-- Process Section -->
    <section id="process" class="section-padding process-section">
        <div class="container">
            <div class="section-header text-center animate-on-scroll">
                <h2>كيف تعمل المنصة؟</h2>
                <p>عملية بسيطة وواضحة لإدارة الصيانة بكفاءة وفعالية</p>
            </div>
            
            <div class="process-timeline">
                <div class="process-item animate-on-scroll delay-1">
                    <div class="process-number">1</div>
                    <div class="process-content">
                        <h4>تقديم الطلب</h4>
                        <p>يقوم العميل أو المسؤول بتقديم طلب الصيانة عبر المنصة أو التطبيق مع تفاصيل المشكلة.</p>
                    </div>
                </div>
                
                <div class="process-item animate-on-scroll delay-2">
                    <div class="process-number">2</div>
                    <div class="process-content">
                        <h4>التقييم والتعيين</h4>
                        <p>يقوم المسؤول بتقييم الطلب، التحقق من الميزانية، وتعيين الفريق المناسب.</p>
                    </div>
                </div>
                
                <div class="process-item animate-on-scroll delay-3">
                    <div class="process-number">3</div>
                    <div class="process-content">
                        <h4>تنفيذ الصيانة</h4>
                        <p>يقوم الفني بتنفيذ المهمة، تسجيل قطع الغيار المستخدمة، وتوثيق العمل المنجز.</p>
                    </div>
                </div>
                
                <div class="process-item animate-on-scroll delay-4">
                    <div class="process-number">4</div>
                    <div class="process-content">
                        <h4>الإغلاق والتقييم</h4>
                        <p>يتم إغلاق الطلب بعد التأكد من إتمام العمل، مع إمكانية تقييم الخدمة من قبل العميل.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section-padding cta-section">
        <div class="cta-shape"></div>
        <div class="cta-shape-2"></div>
        
        <div class="container cta-container">
            <div class="cta-content animate-on-scroll">
                <h2 class="cta-title text-white">جاهز لتحويل عمليات الصيانة لديك؟</h2>
                <p class="cta-description">ابدأ رحلتك مع مين فلو كونكت اليوم واستمتع بفوائد نظام إدارة الصيانة الذكي والمتكامل.</p>
                
                <div class="cta-buttons">
                    <a href="auth/login.html" class="btn btn-secondary btn-lg">سجل مجاناً</a>
                    <a href="#contact" class="btn btn-outline-light btn-lg">تواصل معنا</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact" class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <a href="#" class="footer-logo">مين فلو كونكت</a>
                    <p class="footer-description">منصة رائدة في إدارة الصيانة والأصول، تقدم حلولاً ذكية لتحسين العمليات وخفض التكاليف.</p>
                    
                    <div class="social-links">
                        <a href="#" class="social-link" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="social-link" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                
                <div class="footer-links">
                    <h4>روابط سريعة</h4>
                    <ul>
                        <li><a href="#features">الميزات</a></li>
                        <li><a href="#solution">الحلول</a></li>
                        <li><a href="#process">كيف تعمل</a></li>
                        <li><a href="#testimonials">آراء العملاء</a></li>
                        <li><a href="auth/login.html">تسجيل الدخول</a></li>
                    </ul>
                </div>
                
                <div class="footer-links">
                    <h4>الشركة</h4>
                    <ul>
                        <li><a href="#">من نحن</a></li>
                        <li><a href="#">وظائف</a></li>
                        <li><a href="#">المدونة</a></li>
                        <li><a href="#">الأسئلة الشائعة</a></li>
                        <li><a href="#">اتفاقية الخصوصية</a></li>
                    </ul>
                </div>
                
                <div class="footer-contact">
                    <h4>تواصل معنا</h4>
                    
                    <div class="contact-info">
                        <i class="fas fa-map-marker-alt contact-icon"></i>
                        <p class="contact-text">الرياض، المملكة العربية السعودية</p>
                    </div>
                    
                    <div class="contact-info">
                        <i class="fas fa-phone-alt contact-icon"></i>
                        <p class="contact-text">+966 12 345 6789</p>
                    </div>
                    
                    <div class="contact-info">
                        <i class="fas fa-envelope contact-icon"></i>
                        <p class="contact-text">info@mainflowconnect.com</p>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <div class="copyright">
                    &copy; <span id="current-year"></span> مين فلو كونكت. جميع الحقوق محفوظة.
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Mobile Menu Toggle
        const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
        const nav = document.querySelector('.nav');
        
        mobileMenuToggle.addEventListener('click', () => {
            mobileMenuToggle.classList.toggle('active');
            nav.classList.toggle('active');
        });
        
        // Close mobile menu when clicking on a nav link
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenuToggle.classList.remove('active');
                nav.classList.remove('active');
            });
        });
        
        // Sticky Header
        const header = document.querySelector('.header');
        
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
        
        // Scroll Animation
        const animateElements = document.querySelectorAll('.animate-on-scroll');
        
        const animateOnScroll = () => {
            animateElements.forEach(element => {
                const elementPosition = element.getBoundingClientRect().top;
                const windowHeight = window.innerHeight;
                
                if (elementPosition < windowHeight - 100) {
                    element.classList.add('animated');
                }
            });
        };
        
        // Run once on page load
        animateOnScroll();
        
        // Then on scroll
        window.addEventListener('scroll', animateOnScroll);
        
        // Update current year in footer
        document.getElementById('current-year').textContent = new Date().getFullYear();
        
        // Simple testimonial slider
        let currentTestimonial = 0;
        const testimonials = document.querySelectorAll('.testimonial-card');
        
        function showTestimonial(index) {
            testimonials.forEach((testimonial, i) => {
                testimonial.style.display = i === index ? 'block' : 'none';
            });
        }
        
        function nextTestimonial() {
            currentTestimonial = (currentTestimonial + 1) % testimonials.length;
            showTestimonial(currentTestimonial);
        }
        
        // Show first testimonial initially
        showTestimonial(0);
        
        // Auto-rotate testimonials every 7 seconds
        setInterval(nextTestimonial, 7000);
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    const headerHeight = document.querySelector('.header').offsetHeight;
                    const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - headerHeight - 20;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>
</html>