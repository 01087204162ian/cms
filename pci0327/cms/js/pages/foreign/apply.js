document.addEventListener('DOMContentLoaded', function() {
    loadApplyList();
});

function loadApplyList() {
    const listContent = document.getElementById('listData');
    
    // 로딩 표시
    listContent.innerHTML = '<tr><td colspan="8" class="loading">데이터를 불러오는 중...</td></tr>';

    // API 호출
    fetchAPI('/cms/api/foreign/apply.php')
        .then(response => {
            if (response.data.length === 0) {
                listContent.innerHTML = '<tr><td colspan="8" class="no-data">데이터가 없습니다.</td></tr>';
                return;
            }

            listContent.innerHTML = response.data.map(item => `
                <tr>
                    <td>${item.id}</td>
                    <td>${formatDate(item.apply_date)}</td>
                    <td>${item.name}</td>
                    <td>${item.passport_no}</td>
                    <td>${formatPhoneNumber(item.phone)}</td>
                    <td>${item.insurance_period}</td>
                    <td><span class="status-${item.status.toLowerCase()}">${item.status_text}</span></td>
                    <td>
                        <button onclick="viewDetail(${item.id})" class="btn-detail">상세</button>
                        <button onclick="editItem(${item.id})" class="btn-edit">수정</button>
                    </td>
                </tr>
            `).join('');

            // 페이지네이션 생성
            const pagination = document.getElementById('pagination');
            pagination.innerHTML = createPagination(response.total, response.per_page, response.current_page);
        })
        .catch(error => {
            listContent.innerHTML = '<tr><td colspan="8" class="error">데이터 로드 실패</td></tr>';
            console.error('Error:', error);
        });
}

function viewDetail(id) {
    Modal.show('detail-modal');
    fetchAPI(`/cms/api/foreign/detail.php?id=${id}`)
        .then(response => {
            Modal.setContent('detail-content', createDetailView(response.data));
        })
        .catch(error => {
            Modal.setContent('detail-content', '<div class="error">데이터를 불러올 수 없습니다.</div>');
        });
}

function editItem(id) {
    Modal.show('edit-modal');
    fetchAPI(`/cms/api/foreign/detail.php?id=${id}`)
        .then(response => {
            Modal.setContent('edit-content', createEditForm(response.data));
        })
        .catch(error => {
            Modal.setContent('edit-content', '<div class="error">데이터를 불러올 수 없습니다.</div>');
        });
} 