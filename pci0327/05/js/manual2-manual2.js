// Manual Modal 시스템 - ensureModalExists() 제거 버전

// ===== 메뉴 모달 함수들 =====

// 메인 메뉴 모달 함수 - submenuId 유무에 따라 편집/추가 분기
function openAddMenuModal(submenuId) {
    console.log('openAddMenuModal 호출됨, submenuId:', submenuId);
    
    if (submenuId && submenuId.trim() !== '') {
        // 기존 메뉴 편집 모드
        openEditMenuModal(submenuId);
    } else {
        // 새 메뉴 추가 모드
        openNewMenuModal();
    }
}

// 새 메뉴 추가 모달
function openNewMenuModal() {
    console.log('새 메뉴 추가 모달 열기');
    
    const formHTML = `
        <div class="modal-header">
            <span class="modal-title">새 메뉴 추가</span>
        </div>
        <form class="modal-form" onsubmit="handleAddMenu(event)">
            <div class="form-row">
                <div class="form-group">
                    <label for="menu1st">1차 메뉴명:</label>
                    <input type="text" id="menu1st" name="menu1st" required placeholder="예: 자동차보험">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="menu2nd">2차 메뉴명:</label>
                    <input type="text" id="menu2nd" name="menu2nd" placeholder="예: 개인용">
                </div>
                <div class="form-group">
                    <label for="menu3rd">3차 메뉴명:</label>
                    <input type="text" id="menu3rd" name="menu3rd" placeholder="예: 종합보험">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="menuFolder">폴더명:</label>
                    <input type="text" id="menuFolder" name="folder" placeholder="예: personal-insurance (영문/숫자/하이픈만)">
                </div>
                <div class="form-group">
                    <label for="menuWriter">담당자:</label>
                    <input type="text" id="menuWriter" name="writer" placeholder="담당자명">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group full-width">
                    <label for="menuDescription">메뉴 설명:</label>
                    <textarea id="menuDescription" name="description" rows="3" placeholder="메뉴에 대한 설명을 입력하세요"></textarea>
                </div>
            </div>
            <div class="form-actions">
                <button type="button" class="btn-cancel" onclick="closeModal('manual-modal')">취소</button>
                <button type="submit" class="btn-submit">메뉴 추가</button>
            </div>
        </form>
    `;
    
    // 콘텐츠 삽입
    const modalContent = document.getElementById('m_manual');
    if (modalContent) {
        modalContent.innerHTML = formHTML;
        console.log('폼 HTML 삽입 완료');
    } else {
        console.error('m_manual 요소를 찾을 수 없습니다');
        return;
    }
    
    // 모달 표시
    showModal('manual-modal');
    
    // 포커스 설정
    setTimeout(() => {
        const firstInput = document.getElementById('menu1st');
        if (firstInput) {
            firstInput.focus();
            console.log('첫 번째 입력 필드에 포커스 설정');
        }
    }, 100);
}

// 기존 메뉴 편집 모달
function openEditMenuModal(submenuId) {
    console.log('편집 모드 - submenuId:', submenuId);
    
    // 로딩 상태 표시
    const loadingHTML = `
        <div class="modal-header">
            <span class="modal-title">메뉴 편집</span>
        </div>
        <div style="padding: 40px; text-align: center;">
            <div style="font-size: 16px; color: #666; margin-bottom: 20px;">
                메뉴 정보를 불러오는 중...
            </div>
        </div>
    `;
    
    const modalContent = document.getElementById('m_manual');
    if (modalContent) {
        modalContent.innerHTML = loadingHTML;
    } else {
        console.error('m_manual 요소를 찾을 수 없습니다');
        return;
    }
    
    showModal('manual-modal');
    
    // 서버에서 메뉴 정보 가져오기
    fetchMenuDataForEdit(submenuId);
}

// 편집할 메뉴 데이터 가져오기
async function fetchMenuDataForEdit(submenuId) {
    try {
        // 임시 ID 형태인지 확인 (menu1st:menu2nd)
        if (submenuId.includes(':')) {
            const [menu1st, menu2nd] = submenuId.split(':');
            console.log(`임시 ID를 사용한 편집 요청: ${menu1st} > ${menu2nd}`);
            
            // 임시로 클라이언트에서 데이터 생성
            const tempMenuData = {
                menu_1st: menu1st,
                menu_2nd: menu2nd,
                menu_3rd: '',
                folder: menu2nd.toLowerCase().replace(/\s+/g, '-'),
                description: `${menu1st} > ${menu2nd} 메뉴`,
                manual_writer: ''
            };
            
            displayEditMenuForm(tempMenuData, submenuId);
            return;
        }
        
        // 실제 ID가 있는 경우 - 저장된 메뉴 데이터에서 찾기
        const menuData = findMenuDataById(submenuId);
        if (menuData) {
            console.log(`메뉴 데이터 발견 (ID: ${submenuId}):`, menuData);
            displayEditMenuForm(menuData, submenuId);
            return;
        }
        
        // 서버에서 데이터 조회 (백업)
        const response = await fetch(`/api/manual/getMenuDetail.php?id=${encodeURIComponent(submenuId)}`, {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' },
            credentials: 'include'
        });
        
        if (!response.ok) throw new Error('메뉴 정보 조회 실패: ' + response.status);
        
        const result = await response.json();
        
        if (result.success && result.data) {
            displayEditMenuForm(result.data, submenuId);
        } else {
            throw new Error(result.message || '메뉴 정보를 찾을 수 없습니다');
        }
    } catch (error) {
        console.error('메뉴 정보 조회 중 오류:', error);
        displayEditMenuError(error.message);
    }
}

// 저장된 메뉴 데이터에서 ID로 메뉴 찾기
function findMenuDataById(submenuId) {
    // menuState.subMenuData에서 모든 서브메뉴 검색
    if (typeof menuState !== 'undefined' && menuState.subMenuData) {
        for (const [menu1st, subMenus] of menuState.subMenuData.entries()) {
            for (const submenu of subMenus) {
                if (submenu.items && submenu.items.length > 0) {
                    const item = submenu.items.find(item => item.id && item.id.toString() === submenuId);
                    if (item) {
                        return {
                            id: item.id,
                            menu_1st: item.menu_1st,
                            menu_2nd: item.menu_2nd,
                            menu_3rd: item.menu_3rd,
                            folder: item.folder,
                            description: item.description,
                            manual_writer: item.manual_writer
                        };
                    }
                }
            }
        }
    }
    return null;
}

// 편집 폼 표시
function displayEditMenuForm(menuData, submenuId) {
    const formHTML = `
        <div class="modal-header">
            <span class="modal-title">메뉴 편집</span>
        </div>
        <form class="modal-form" onsubmit="handleEditMenu(event, '${submenuId}')">
            <div class="form-row">
                <div class="form-group">
                    <label for="edit_menu1st">1차 메뉴명:</label>
                    <input type="text" id="edit_menu1st" name="menu1st" required 
                           value="${escapeHtml(menuData.menu_1st || '')}" placeholder="예: 자동차보험">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="edit_menu2nd">2차 메뉴명:</label>
                    <input type="text" id="edit_menu2nd" name="menu2nd" 
                           value="${escapeHtml(menuData.menu_2nd || '')}" placeholder="예: 개인용">
                </div>
                <div class="form-group">
                    <label for="edit_menu3rd">3차 메뉴명:</label>
                    <input type="text" id="edit_menu3rd" name="menu3rd" 
                           value="${escapeHtml(menuData.menu_3rd || '')}" placeholder="예: 종합보험">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="edit_menuFolder">폴더명:</label>
                    <input type="text" id="edit_menuFolder" name="folder" 
                           value="${escapeHtml(menuData.folder || '')}" placeholder="예: personal-insurance (영문/숫자/하이픈만)">
                </div>
                <div class="form-group">
                    <label for="edit_menuWriter">담당자:</label>
                    <input type="text" id="edit_menuWriter" name="writer" 
                           value="${escapeHtml(menuData.manual_writer || '')}" placeholder="담당자명">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group full-width">
                    <label for="edit_menuDescription">메뉴 설명:</label>
                    <textarea id="edit_menuDescription" name="description" rows="3" 
                              placeholder="메뉴에 대한 설명을 입력하세요">${escapeHtml(menuData.description || '')}</textarea>
                </div>
            </div>
            <div class="form-actions">
                <button type="button" class="btn-cancel" onclick="closeModal('manual-modal')">취소</button>
                <button type="submit" class="btn-submit">수정 완료</button>
            </div>
        </form>
    `;
    
    document.getElementById('m_manual').innerHTML = formHTML;
    
    // 포커스 설정
    setTimeout(() => {
        const firstInput = document.getElementById('edit_menu1st');
        if (firstInput) firstInput.focus();
    }, 100);
}

// 편집 오류 표시
function displayEditMenuError(errorMessage) {
    const errorHTML = `
        <div class="modal-header">
            <span class="modal-title">오류</span>
        </div>
        <div style="padding: 40px; text-align: center;">
            <div style="font-size: 18px; color: #dc3545; margin-bottom: 20px;">
                ⚠️ 메뉴 정보를 불러올 수 없습니다
            </div>
            <div style="color: #666; margin-bottom: 30px;">
                ${errorMessage}
            </div>
            <button type="button" class="btn-primary" onclick="closeModal('manual-modal')" 
                    style="padding: 10px 30px; background-color: #228B22; color: white; border: none; border-radius: 4px; cursor: pointer;">
                확인
            </button>
        </div>
    `;
    
    document.getElementById('m_manual').innerHTML = errorHTML;
}

// HTML 이스케이프 함수
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// 새 메뉴 추가 처리
function handleAddMenu(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const menuData = {
        menu_1st: formData.get('menu1st'),
        menu_2nd: formData.get('menu2nd') || null,
        menu_3rd: formData.get('menu3rd') || null,
        folder: formData.get('folder') || null,
        description: formData.get('description') || null,
        manual_writer: formData.get('writer') || null
    };
    
    if (!menuData.menu_1st.trim()) {
        alert('1차 메뉴명은 필수입니다.');
        return;
    }
    
    // folder 유효성 검사
    if (menuData.folder && menuData.folder.trim() !== '') {
        const folderRegex = /^[a-zA-Z0-9-]+$/;
        if (!folderRegex.test(menuData.folder.trim())) {
            alert('폴더명은 영문, 숫자, 하이픈(-)만 사용할 수 있습니다.');
            return;
        }
    }
    
    console.log('새 메뉴 데이터:', menuData);
    submitNewMenu(menuData);
}

// 메뉴 편집 처리
function handleEditMenu(event, submenuId) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const menuData = {
        id: submenuId,
        menu_1st: formData.get('menu1st'),
        menu_2nd: formData.get('menu2nd') || null,
        menu_3rd: formData.get('menu3rd') || null,
        folder: formData.get('folder') || null,
        description: formData.get('description') || null,
        manual_writer: formData.get('writer') || null
    };
    
    if (!menuData.menu_1st.trim()) {
        alert('1차 메뉴명은 필수입니다.');
        return;
    }
    
    // folder 유효성 검사
    if (menuData.folder && menuData.folder.trim() !== '') {
        const folderRegex = /^[a-zA-Z0-9-]+$/;
        if (!folderRegex.test(menuData.folder.trim())) {
            alert('폴더명은 영문, 숫자, 하이픈(-)만 사용할 수 있습니다.');
            return;
        }
    }
    
    console.log('수정할 메뉴 데이터:', menuData);
    submitEditMenu(menuData);
}

// 새 메뉴 추가 API 호출
async function submitNewMenu(menuData) {
    try {
        const response = await fetch('/api/manual/addMenu.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            credentials: 'include',
            body: JSON.stringify(menuData)
        });
        
        if (!response.ok) throw new Error('메뉴 추가 실패: ' + response.status);
        
        const result = await response.json();
        
        if (result.success) {
            alert('메뉴가 성공적으로 추가되었습니다.');
            closeModal('manual-modal');
            refreshMenuList();
        } else {
            throw new Error(result.message || '메뉴 추가 실패');
        }
    } catch (error) {
        console.error('메뉴 추가 중 오류:', error);
        alert('메뉴 추가 중 오류가 발생했습니다: ' + error.message);
    }
}

// 메뉴 편집 API 호출
async function submitEditMenu(menuData) {
    try {
        const response = await fetch('/api/manual/updateMenu.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            credentials: 'include',
            body: JSON.stringify(menuData)
        });
        
        if (!response.ok) throw new Error('메뉴 수정 실패: ' + response.status);
        
        const result = await response.json();
        
        if (result.success) {
            alert('메뉴가 성공적으로 수정되었습니다.');
            closeModal('manual-modal');
            refreshMenuList();
        } else {
            throw new Error(result.message || '메뉴 수정 실패');
        }
    } catch (error) {
        console.error('메뉴 수정 중 오류:', error);
        alert('메뉴 수정 중 오류가 발생했습니다: ' + error.message);
    }
}

// 메뉴 목록 새로고침
function refreshMenuList() {
    if (typeof fetchFirstLevelMenus === 'function') {
        fetchFirstLevelMenus(); // 1차 메뉴 목록 새로고침
    }
}
// 거래처 등록 모달 - 나중에 다시 구현 예정
function openProcesModal() {
    const placeholderHTML = `
        <div class="modal-header">
            <span class="modal-title">거래처 등록</span>
        </div>
        <div style="padding: 40px; text-align: center;">
            <div style="font-size: 18px; color: #666; margin-bottom: 20px;">
                🚧 거래처 등록 기능은 현재 개발 중입니다
            </div>
            <div style="color: #999; margin-bottom: 30px;">
                이 기능은 곧 업데이트될 예정입니다.
            </div>
            <button type="button" class="btn-primary" onclick="closeModal('manual-modal')" 
                    style="padding: 10px 30px; background-color: #228B22; color: white; border: none; border-radius: 4px; cursor: pointer;">
                확인
            </button>
        </div>
    `;
    
    document.getElementById('m_manual').innerHTML = placeholderHTML;
    showModal('manual-modal');
}

// 거래처 등록 모달 - itemId 매개변수 추가manual2-manual3.js 구현
/*function openAddClientModal(itemId) {
    console.log('거래처 등록 모달 열기 - Item ID:', itemId);
    
    const placeholderHTML = `
        <div class="modal-header">
            <span class="modal-title">거래처 등록 (ID: ${itemId})</span>
        </div>
        <div style="padding: 40px; text-align: center;">
            <div style="font-size: 18px; color: #666; margin-bottom: 20px;">
                🚧 거래처 등록 기능은 현재 개발 중입니다
            </div>
            <div style="color: #999; margin-bottom: 15px;">
                메뉴 ID: <strong>${itemId}</strong>
            </div>
            <div style="color: #999; margin-bottom: 30px;">
                이 기능은 곧 업데이트될 예정입니다.
            </div>
            <button type="button" class="btn-primary" onclick="closeModal('manual-modal')" 
                    style="padding: 10px 30px; background-color: #228B22; color: white; border: none; border-radius: 4px; cursor: pointer;">
                확인
            </button>
        </div>
    `;
    
    document.getElementById('m_manual').innerHTML = placeholderHTML;
    showModal('manual-modal');
}*/
//거래처 등록 모달 - itemId 매개변수 추가manual2-manual3.js 구현
// 거래처 리스트 모달 - itemId 매개변수 추가
/*function openClientListModal(itemId) {
    console.log('거래처 리스트 모달 열기 - Item ID:', itemId);
    
    const placeholderHTML = `
        <div class="modal-header">
            <span class="modal-title">거래처 리스트 (ID: ${itemId})</span>
        </div>
        <div style="padding: 40px; text-align: center;">
            <div style="font-size: 18px; color: #666; margin-bottom: 20px;">
                🚧 거래처 리스트 기능은 현재 개발 중입니다
            </div>
            <div style="color: #999; margin-bottom: 15px;">
                메뉴 ID: <strong>${itemId}</strong>
            </div>
            <div style="color: #999; margin-bottom: 30px;">
                이 기능은 곧 업데이트될 예정입니다.
            </div>
            <button type="button" class="btn-primary" onclick="closeModal('manual-modal')" 
                    style="padding: 10px 30px; background-color: #228B22; color: white; border: none; border-radius: 4px; cursor: pointer;">
                확인
            </button>
        </div>
    `;
    
    document.getElementById('m_manual').innerHTML = placeholderHTML;
    showModal('manual-modal');
}*/

// 프로세스 처리 버튼 핸들러 함수 - itemId 매개변수 추가
function handleProcessButton(itemId) {
    console.log('프로세스 처리 버튼 클릭 - Item ID:', itemId);
    
    const placeholderHTML = `
        <div class="modal-header">
            <span class="modal-title">프로세스 처리 (ID: ${itemId})</span>
        </div>
        <div style="padding: 40px; text-align: center;">
            <div style="font-size: 18px; color: #666; margin-bottom: 20px;">
                🚧 프로세스 처리 기능은 현재 개발 중입니다
            </div>
            <div style="color: #999; margin-bottom: 15px;">
                메뉴 ID: <strong>${itemId}</strong>
            </div>
            <div style="color: #999; margin-bottom: 30px;">
                이 기능은 곧 업데이트될 예정입니다.
            </div>
            <button type="button" class="btn-primary" onclick="closeModal('manual-modal')" 
                    style="padding: 10px 30px; background-color: #228B22; color: white; border: none; border-radius: 4px; cursor: pointer;">
                확인
            </button>
        </div>
    `;
    
    document.getElementById('m_manual').innerHTML = placeholderHTML;
    showModal('manual-modal');
}

// 버튼 핸들러 함수들 - itemId를 받아서 해당 모달 함수 호출
function handleAddClientButton(itemId) {
    console.log('거래처 등록 버튼 클릭 - Item ID:', itemId);
    openAddClientModal(itemId,1);  // menu_clients id
}

function handleClientListButton(itemId) {
    console.log('거래처 리스트 버튼 클릭 - Item ID:', itemId);
    openClientListModal(itemId);
}

// 테스트 함수
function testModal() {
    console.log('모달 테스트 시작');
    openNewMenuModal();
}

// DOMContentLoaded에서 전역 함수 등록 부분 수정
document.addEventListener('DOMContentLoaded', function() {
    console.log('Manual Modal 시스템이 준비되었습니다.');
    
    // 메뉴 관련 함수들 등록
    window.openAddMenuModal = openAddMenuModal;
    window.openNewMenuModal = openNewMenuModal;
    window.openEditMenuModal = openEditMenuModal;
    window.handleAddMenu = handleAddMenu;
    window.handleEditMenu = handleEditMenu;
    window.submitNewMenu = submitNewMenu;
    window.submitEditMenu = submitEditMenu;
    window.fetchMenuDataForEdit = fetchMenuDataForEdit;
    
    // 거래처 관련 함수들 - itemId 매개변수를 받는 버전
    window.openAddClientModal = openAddClientModal;
    window.openClientListModal = openClientListModal;
    
    // 프로세스 처리 함수 추가
    window.handleProcessButton = handleProcessButton;
    
    // 버튼 핸들러 함수들 - itemId를 받아서 처리
    window.handleAddClientButton = handleAddClientButton;
    window.handleClientListButton = handleClientListButton;
    
    // 테스트 함수
    window.testModal = testModal;
    
    console.log('메뉴 편집/추가 기능이 manual-modal로 구현되었습니다.');
    console.log('- 테스트: testModal() 함수 호출');
    console.log('- 새 메뉴: openNewMenuModal() 또는 openAddMenuModal()');
    console.log('- 편집: openAddMenuModal(submenuId)');
    console.log('- 거래처 등록: handleAddClientButton(itemId)');
    console.log('- 거래처 리스트: handleClientListButton(itemId)');
    console.log('- 프로세스 처리: handleProcessButton(itemId)');
});