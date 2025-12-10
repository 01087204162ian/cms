// 공통 유틸리티 함수들

// 날짜 포맷팅 함수
function formatDate(date) {
    const d = new Date(date);
    const year = d.getFullYear();
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');
    const hours = String(d.getHours()).padStart(2, '0');
    const minutes = String(d.getMinutes()).padStart(2, '0');
    
    return `${year}-${month}-${day} ${hours}:${minutes}`;
}

// 전화번호 포맷팅 함수
function formatPhoneNumber(phone) {
    return phone.replace(/(\d{3})(\d{4})(\d{4})/, '$1-$2-$3');
}

// API 호출 함수
async function fetchAPI(url, options = {}) {
    try {
        const response = await fetch(url, {
            ...options,
            headers: {
                'Content-Type': 'application/json',
                ...options.headers
            }
        });
        
        const data = await response.json();
        
        if (!data.success) {
            throw new Error(data.message || '요청 처리 중 오류가 발생했습니다.');
        }
        
        return data;
    } catch (error) {
        console.error('API Error:', error);
        throw error;
    }
}

// 검색 함수
function search() {
    const type = document.getElementById('search-type').value;
    const keyword = document.getElementById('search-input').value;
    const currentMenu = document.getElementById('current-menu').textContent;
    const currentSubmenu = document.getElementById('current-submenu').textContent;

    fetchAPI(`/cms/api/search.php?menu=${encodeURIComponent(currentMenu)}&type=${type}&keyword=${encodeURIComponent(keyword)}`)
        .then(data => {
            updateListContent(data);
        })
        .catch(error => {
            showError('검색 중 오류가 발생했습니다.');
        });
}

// 에러 메시지 표시
function showError(message) {
    alert(message);
}

// 성공 메시지 표시
function showSuccess(message) {
    alert(message);
}

// 확인 대화상자 함수명 변경
function showConfirm(message) {
    return window.confirm(message);
}

// 모달 관련 함수들
const Modal = {
    show(modalId) {
        document.getElementById(modalId).style.display = 'block';
    },

    hide(modalId) {
        document.getElementById(modalId).style.display = 'none';
    },

    setContent(modalId, content) {
        document.querySelector(`#${modalId} .modal-body`).innerHTML = content;
    }
};

// 테이블 정렬 함수
function sortTable(table, column, type = 'string') {
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    rows.sort((a, b) => {
        let aVal = a.cells[column].textContent;
        let bVal = b.cells[column].textContent;
        
        if (type === 'number') {
            return Number(aVal) - Number(bVal);
        } else if (type === 'date') {
            return new Date(aVal) - new Date(bVal);
        } else {
            return aVal.localeCompare(bVal);
        }
    });
    
    rows.forEach(row => tbody.appendChild(row));
}

// 페이지네이션 함수
function createPagination(totalItems, itemsPerPage, currentPage) {
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    let html = '<div class="pagination">';
    
    for (let i = 1; i <= totalPages; i++) {
        html += `<button class="${i === currentPage ? 'active' : ''}" 
                        onclick="changePage(${i})">${i}</button>`;
    }
    
    html += '</div>';
    return html;
}

// 숫자 포맷팅 (천단위 콤마)
function formatNumber(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// 파일 크기 포맷팅
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
} 