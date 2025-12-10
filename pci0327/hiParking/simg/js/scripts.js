document.addEventListener("DOMContentLoaded", function() {
    // 사이드바 동적 로드
    fetch("/simg/sidebar/side.html")
        .then(response => response.text())
        .then(data => {
            document.getElementById("sidebar").innerHTML = data;
            
        });

    
});


let currentMenu = null;
let currentSubmenuItem = null;
let isSidebarOpen = false;

// 사이드바 토글 함수
function toggleSidebar() {
	const sidebar = document.querySelector('.sidebar');
	const overlay = document.querySelector('.overlay');
	
	isSidebarOpen = !isSidebarOpen;
	
	if (isSidebarOpen) {
		sidebar.classList.add('active');
		overlay.classList.add('active');
		document.body.style.overflow = 'hidden';
	} else {
		sidebar.classList.remove('active');
		overlay.classList.remove('active');
		document.body.style.overflow = '';
	}
}

// 화면 크기 변경 감지
window.addEventListener('resize', () => {
	if (window.innerWidth > 768 && isSidebarOpen) {
		toggleSidebar();
	}
});

/*function toggleMenu(button) {
	// 이전 메뉴가 있고, 현재 클릭한 메뉴와 다르다면 이전 메뉴 닫기
	if (currentMenu && currentMenu !== button) {
		currentMenu.classList.remove('active');
		currentMenu.nextElementSibling.classList.remove('active');
	}

	// 현재 메뉴 토글
	button.classList.toggle('active');
	button.nextElementSibling.classList.toggle('active');
	
	// 현재 메뉴 저장
	currentMenu = button;


}
*/

function toggleMenu(button) {
    // 이전 메뉴가 있고, 현재 클릭한 메뉴와 다르다면 이전 메뉴 닫기
    if (currentMenu && currentMenu !== button) {
        currentMenu.classList.remove('active');
        if (currentMenu.nextElementSibling) {
            currentMenu.nextElementSibling.classList.remove('active');
        }
    }

    // 현재 버튼의 다음 요소(submenu)가 존재하는지 확인 후 실행
    const submenu = button.nextElementSibling;
    if (submenu && submenu.classList) {
        button.classList.toggle('active');
        submenu.classList.toggle('active');
    }

    // 현재 메뉴 저장
    currentMenu = button;
}
/*
function selectMenu(item, mainMenu, subMenu) {
	// 이전 선택된 항목이 있다면 active 제거
	if (currentSubmenuItem) {
		currentSubmenuItem.classList.remove('active');
	}

	// 현재 항목 active 추가
	item.classList.add('active');
	currentSubmenuItem = item;

	// 페이지 제목 및 내용 업데이트
	const pageTitle = document.querySelector('.page-title');
	/*const content = document.getElementById('content');

	pageTitle.textContent = `${mainMenu} > ${subMenu}`;
	content.textContent = `${mainMenu}의 ${subMenu} 페이지입니다.`;


	switch (subMenu) {
        case "question": 
            question();             // 현장실습 질문서
            break;
		 case "claim": 
            claim();					// 사고리스트
            break;
		case "employeeList": 
            employee();              //직원리스트
            break;
        default:
            console.warn("Unknown subMenu type:", subMenu);
            break;
    }
}*/
function selectMenu(item, mainMenu, subMenu) {
	console.log('item', item,'mainMenu', mainMenu,'subMenu',subMenu);
    // 이전 선택된 항목이 있다면 active 제거
    if (currentSubmenuItem) {
        currentSubmenuItem.classList.remove("active");
    }

    // 현재 항목 active 추가
    item.classList.add("active");
    currentSubmenuItem = item;

    // 다른 메뉴 닫기 (현재 선택한 메뉴는 제외)
    closeOtherMenus(item);

    // 페이지 제목 및 내용 업데이트
    const pageTitle = document.querySelector(".page-title");

    switch (subMenu) {
        case "question":
            question(); // 현장실습 질문서
            break;
        case "claim":
            claim(); // 사고리스트
            break;
        case "employeeList":
            employee(); // 직원리스트
            break;
        default:
            console.warn("Unknown subMenu type:", subMenu);
            break;
    }
}

function closeOtherMenus(selectedItem) {
    const menuItems = document.querySelectorAll(".menu-item");

    menuItems.forEach((menuItem) => {
        const button = menuItem.querySelector(".menu-button");
        const submenu = menuItem.querySelector(".submenu");

        // 선택한 메뉴가 아니라면 닫기
        if (!menuItem.contains(selectedItem)) {
            if (button) button.classList.remove("active");
            if (submenu) submenu.classList.remove("active");
        }
    });

    // 선택한 메뉴의 부모 메뉴 열기
    const selectedMenu = selectedItem.closest(".menu-item");
    if (selectedMenu) {
        const button = selectedMenu.querySelector(".menu-button");
        const submenu = selectedMenu.querySelector(".submenu");
        if (button) button.classList.add("active");
        if (submenu) submenu.classList.add("active");
    }
}


async function loadContent(url, scriptSrc, initFunctionName) {
    try {
        // 기존 스크립트 제거
        const existingScript = document.querySelector(`script[src="${scriptSrc}"]`);
        if (existingScript) {
            existingScript.remove();
        }

        const contentArea = document.getElementById("content-container");
        contentArea.innerHTML = "<p>로딩 중...</p>";

        const response = await fetch(url);
        if (!response.ok) throw new Error(`HTTP 오류: ${response.status}`);

        const data = await response.text();
        contentArea.innerHTML = data;

        // 새 스크립트 추가 및 초기화
        const script = document.createElement("script");
        script.src = scriptSrc;
        script.onload = () => {
            if (typeof window[initFunctionName] === 'function') {
                window[initFunctionName]();
            }
        };
        document.body.appendChild(script);

    } catch (error) {
        console.error("로드 오류:", error);
        contentArea.innerHTML = "<p>로드 실패</p>";
    }
}

async function question() {
    await loadContent(
        "/simg/contents/internship/questionList.html", 
        "/simg/contents/internship/js/question.js",
        "initializeQuestionScripts"
    );
}

async function claim() {
    await loadContent(
        "/simg/contents/internship/claimList.html",
        "/simg/contents/internship/js/claim.js",
        "initializeClaimScripts"
    );
}

async function employee() {
    await loadContent(
        "/simg/contents/employee/employeeList.html",
        "/simg/contents/employee/js/employee.js",
        "initializeEmployeeScripts"
    );
}
