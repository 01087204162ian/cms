document.addEventListener('DOMContentLoaded', function() {
    const manualNav = document.querySelector('.manual-nav');
    const manualContent = document.querySelector('.manual-content');
    const navLinks = document.querySelectorAll('.practice-insurance-nav-link');
    const sidebar = document.querySelector('.sidebar');
    const sections = document.querySelectorAll('.manual-section');

    // 현재 섹션 ID 가져오기
    function getCurrentSectionId() {
        const hash = window.location.hash;
        return hash ? hash.substring(1) : 'basic-info';
    }

    // 섹션 콘텐츠 로드
    async function loadSectionContent(sectionId) {
        try {
            const response = await fetch(`/05/manual/product/pages/sections/${sectionId}.html`);
            if (!response.ok) throw new Error('섹션을 불러올 수 없습니다.');
            const content = await response.text();
            manualContent.innerHTML = content;
            
            // 콘텐츠 로드 후 스크롤 위치 조정
            const targetSection = document.getElementById(sectionId);
            if (targetSection) {
                targetSection.scrollIntoView({ behavior: 'smooth' });
            }
        } catch (error) {
            console.error('섹션 로드 중 오류 발생:', error);
            manualContent.innerHTML = '<p>콘텐츠를 불러오는 중 오류가 발생했습니다.</p>';
        }
    }

    // 활성 링크 설정
    function setActiveLink(sectionId) {
        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === `#${sectionId}`) {
                link.classList.add('active');
            }
        });
    }

    // 스크롤 처리
    function handleScroll() {
        const scrollPosition = window.scrollY;
        
        // 네비게이션 스타일 변경
        if (scrollPosition > 50) {
            manualNav.classList.add('scrolled');
        } else {
            manualNav.classList.remove('scrolled');
        }

        // 현재 보이는 섹션 찾기
        let currentSection = null;
        sections.forEach(section => {
            const sectionTop = section.offsetTop - 100;
            const sectionBottom = sectionTop + section.offsetHeight;
            
            if (scrollPosition >= sectionTop && scrollPosition < sectionBottom) {
                currentSection = section.id;
            }
        });

        if (currentSection) {
            setActiveLink(currentSection);
            history.replaceState(null, null, `#${currentSection}`);
        }
    }

    // 스크롤 이벤트에 디바운스 적용
    let scrollTimeout;
    window.addEventListener('scroll', function() {
        if (scrollTimeout) {
            window.cancelAnimationFrame(scrollTimeout);
        }
        scrollTimeout = window.requestAnimationFrame(handleScroll);
    });

    // 네비게이션 링크 클릭 이벤트
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetSection = document.getElementById(targetId);
            
            if (targetSection) {
                // 부드러운 스크롤
                targetSection.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
                
                // URL 해시 업데이트
                history.pushState(null, null, `#${targetId}`);
                
                // 활성 링크 설정
                setActiveLink(targetId);
            }
        });
    });

    // 브라우저 뒤로가기/앞으로가기 처리
    window.addEventListener('popstate', function() {
        const sectionId = getCurrentSectionId();
        const targetSection = document.getElementById(sectionId);
        
        if (targetSection) {
            targetSection.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
            setActiveLink(sectionId);
        }
    });

    // 초기 섹션 로드
    const initialSectionId = getCurrentSectionId();
    setActiveLink(initialSectionId);
    
    // 초기 스크롤 위치 설정
    const targetSection = document.getElementById(initialSectionId);
    if (targetSection) {
        targetSection.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }

    // 좌측 메뉴 토글 시 상단 메뉴 위치 조정
    function adjustNavPosition() {
        if (sidebar.classList.contains('collapsed')) {
            manualNav.style.left = '0';
            document.querySelector('.manual-container').style.marginLeft = '0';
        } else {
            manualNav.style.left = '250px';
            document.querySelector('.manual-container').style.marginLeft = '250px';
        }
    }

    // 좌측 메뉴 토글 이벤트 리스너
    const menuToggle = document.querySelector('.menu-toggle');
    if (menuToggle) {
        menuToggle.addEventListener('click', adjustNavPosition);
    }
}); 