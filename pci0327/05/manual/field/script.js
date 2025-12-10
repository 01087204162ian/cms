// DOM Content Loaded Event
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all features
    initMobileMenu();
    initSmoothScrolling();
    initScrollToTop();
    initActiveNavigation();
    
    initCollapsibleSections();
    initTooltips();
});

// Mobile Menu Toggle
function initMobileMenu() {
    const nav = document.getElementById('nav');
    
    // 모바일에서만 메뉴 토글 버튼 생성
    if (window.innerWidth <= 768) {
        const headerActions = document.querySelector('.header-actions');
        if (headerActions && nav) {
            const menuToggle = document.createElement('button');
            menuToggle.className = 'menu-toggle';
            menuToggle.id = 'menuToggle';
            menuToggle.innerHTML = '<i class="fas fa-bars"></i>';
            
            menuToggle.addEventListener('click', function() {
                nav.classList.toggle('show');
                
                // Change hamburger icon
                const icon = menuToggle.querySelector('i');
                if (nav.classList.contains('show')) {
                    icon.className = 'fas fa-times';
                } else {
                    icon.className = 'fas fa-bars';
                }
            });
            
            headerActions.appendChild(menuToggle);
        }
    }
    
    if (nav) {
        // Close menu when clicking on nav links (mobile only)
        const navLinks = nav.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                // Only close menu on mobile devices (768px or less)
                if (window.innerWidth <= 768) {
                    nav.classList.remove('show');
                    const menuToggle = document.getElementById('menuToggle');
                    if (menuToggle) {
                        const icon = menuToggle.querySelector('i');
                        icon.className = 'fas fa-bars';
                    }
                }
            });
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            const menuToggle = document.getElementById('menuToggle');
            if (menuToggle && !nav.contains(event.target) && !menuToggle.contains(event.target)) {
                nav.classList.remove('show');
                const icon = menuToggle.querySelector('i');
                icon.className = 'fas fa-bars';
            }
        });
    }

    // Handle window resize - reset menu state when switching to desktop
    window.addEventListener('resize', function() {
        const menuToggle = document.getElementById('menuToggle');
        if (window.innerWidth > 768) {
            if (nav) nav.classList.remove('show');
            if (menuToggle) menuToggle.remove();
        } else if (!menuToggle) {
            initMobileMenu(); // 모바일로 전환 시 버튼 다시 생성
        }
    });
}

// Smooth Scrolling for Navigation Links
function initSmoothScrolling() {
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            const targetSection = document.querySelector(targetId);
            
            if (targetSection) {
                const headerHeight = document.querySelector('.header').offsetHeight;
                const navHeight = document.querySelector('.nav').offsetHeight;
                const offset = headerHeight + navHeight + 20;
                
                const targetPosition = targetSection.offsetTop - offset;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
}

// Scroll to Top Button
function initScrollToTop() {
    const scrollTopBtn = document.getElementById('scrollTop');
    
    if (scrollTopBtn) {
        // Show/hide scroll to top button
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                scrollTopBtn.classList.add('show');
            } else {
                scrollTopBtn.classList.remove('show');
            }
        });
        
        // Scroll to top functionality
        scrollTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
}

// Active Navigation Highlighting
function initActiveNavigation() {
    const navLinks = document.querySelectorAll('.nav-link');
    const sections = document.querySelectorAll('.section');
    
    if (navLinks.length && sections.length) {
        window.addEventListener('scroll', function() {
            const scrollPosition = window.pageYOffset;
            const headerHeight = document.querySelector('.header').offsetHeight;
            const navHeight = document.querySelector('.nav').offsetHeight;
            const offset = headerHeight + navHeight + 50;
            
            sections.forEach(section => {
                const sectionTop = section.offsetTop - offset;
                const sectionBottom = sectionTop + section.offsetHeight;
                const sectionId = section.getAttribute('id');
                
                if (scrollPosition >= sectionTop && scrollPosition < sectionBottom) {
                    // Remove active class from all nav links
                    navLinks.forEach(link => {
                        link.classList.remove('active');
                    });
                    
                    // Add active class to current nav link
                    const activeLink = document.querySelector(`.nav-link[href="#${sectionId}"]`);
                    if (activeLink) {
                        activeLink.classList.add('active');
                    }
                }
            });
        });
    }
}

// Utility function to get text nodes
function getTextNodes(node) {
    const textNodes = [];
    const walker = document.createTreeWalker(
        node,
        NodeFilter.SHOW_TEXT,
        null,
        false
    );
    
    let currentNode;
    while (currentNode = walker.nextNode()) {
        if (currentNode.textContent.trim()) {
            textNodes.push(currentNode);
        }
    }
    
    return textNodes;
}

// Utility function to escape regex
function escapeRegExp(string) {
    return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
}

// Collapsible Sections
function initCollapsibleSections() {
    // Add collapsible functionality to detailed steps
    const detailedSteps = document.querySelectorAll('.detailed-steps .step-item');
    
    detailedSteps.forEach((step, index) => {
        if (index > 4) { // Collapse steps after the 5th one
            step.style.display = 'none';
        }
    });
    
    // Add show more/less button for detailed steps
    const detailedStepsContainer = document.querySelector('.detailed-steps');
    if (detailedStepsContainer && detailedSteps.length > 5) {
        const toggleBtn = document.createElement('button');
        toggleBtn.className = 'toggle-btn';
        toggleBtn.innerHTML = '<i class="fas fa-chevron-down"></i> 더 보기';
        toggleBtn.style.cssText = `
            background: #667eea;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        `;
        
        let expanded = false;
        
        toggleBtn.addEventListener('click', function() {
            expanded = !expanded;
            
            detailedSteps.forEach((step, index) => {
                if (index > 4) {
                    step.style.display = expanded ? 'flex' : 'none';
                }
            });
            
            if (expanded) {
                toggleBtn.innerHTML = '<i class="fas fa-chevron-up"></i> 접기';
            } else {
                toggleBtn.innerHTML = '<i class="fas fa-chevron-down"></i> 더 보기';
            }
        });
        
        detailedStepsContainer.appendChild(toggleBtn);
    }
}

// Tooltips for Complex Terms
function initTooltips() {
    const tooltipTerms = {
        '설계번호': '보험 상품을 설계할 때 부여되는 고유 번호입니다.',
        '증권번호': '보험 계약이 성립된 후 발급되는 보험증권의 고유 번호입니다.',
        '인수심사': '보험사가 위험을 평가하여 보험 가입을 승인하는 과정입니다.',
        '화특업무': '화재보험 특수업무를 의미합니다.',
        '워딩': '보험약관에 포함되는 특별한 조건이나 문구입니다.'
    };
    
    Object.keys(tooltipTerms).forEach(term => {
        const regex = new RegExp(`\\b${term}\\b`, 'g');
        const sections = document.querySelectorAll('.section');
        
        sections.forEach(section => {
            const html = section.innerHTML;
            const newHtml = html.replace(regex, `<span class="tooltip" data-tooltip="${tooltipTerms[term]}">${term}</span>`);
            section.innerHTML = newHtml;
        });
    });
    
    // Add tooltip styles
    const style = document.createElement('style');
    style.textContent = `
        .tooltip {
            position: relative;
            color: #667eea;
            font-weight: 600;
            cursor: help;
            border-bottom: 1px dotted #667eea;
        }
        
        .tooltip:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
            background: #2d3748;
            color: white;
            padding: 0.5rem;
            border-radius: 4px;
            white-space: nowrap;
            z-index: 1000;
            font-size: 0.8rem;
            font-weight: normal;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        
        .tooltip:hover::before {
            content: '';
            position: absolute;
            bottom: 120%;
            left: 50%;
            transform: translateX(-50%);
            border: 5px solid transparent;
            border-top-color: #2d3748;
            z-index: 1000;
        }
        
        .search-container {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .search-input {
            padding: 0.5rem;
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 4px;
            background: rgba(255,255,255,0.1);
            color: white;
            width: 200px;
            transition: all 0.3s ease;
        }
        
        .search-input::placeholder {
            color: rgba(255,255,255,0.7);
        }
        
        .search-input:focus {
            outline: none;
            background: rgba(255,255,255,0.2);
            border-color: rgba(255,255,255,0.5);
        }
        
        .search-btn {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            padding: 0.5rem;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .search-btn:hover {
            background: rgba(255,255,255,0.3);
        }
        
        .search-highlight {
            background: #ffd700;
            padding: 0.1rem 0.2rem;
            border-radius: 2px;
            font-weight: bold;
        }
        
        .nav-link.active {
            background-color: #667eea;
            color: white;
        }
        
        @media (max-width: 768px) {
            .search-container {
                display: none;
            }
        }
    `;
    document.head.appendChild(style);
}

// Keyboard Navigation
document.addEventListener('keydown', function(e) {
    // Ctrl + F for search
    if (e.ctrlKey && e.key === 'f') {
        e.preventDefault();
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.focus();
        }
    }
    
    // Escape to close mobile menu
    if (e.key === 'Escape') {
        const nav = document.getElementById('nav');
        const menuToggle = document.getElementById('menuToggle');
        if (nav && nav.classList.contains('show')) {
            nav.classList.remove('show');
            const icon = menuToggle.querySelector('i');
            icon.className = 'fas fa-bars';
        }
    }
});

// Performance optimization: Debounce scroll events
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Apply debouncing to scroll events
const debouncedScrollHandler = debounce(function() {
    // Re-run active navigation check
    const event = new Event('scroll');
    window.dispatchEvent(event);
}, 10);

window.addEventListener('scroll', debouncedScrollHandler); 