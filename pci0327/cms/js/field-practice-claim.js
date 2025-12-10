// 클레임 관련 API 및 함수들
window.claimAPI = {
    // 클레임 리스트 조회
    async fetchClaimList(page = 1, limit = 15) {
        try {
            const timestamp = new Date().getTime();
            const response = await fetch(`/cms/api/field-practice/claims.php?page=${page}&limit=${limit}&timestamp=${timestamp}`);
            return await response.json();
        } catch (error) {
            console.error('클레임 데이터 로드 실패:', error);
            throw error;
        }
    },

    // 클레임 리스트 렌더링
    renderClaimList(data) {
        const listContent = document.getElementById('claimList');
        if (!listContent) return;

        listContent.innerHTML = data.map(item => `
            <tr>
                <td>${item.num}</td>
                <td>${item.school1}</td>
                <td>${item.certi_ || '-'}</td>
                <td>${item.student || '-'}</td>
                <td>${item.wdate_3 || '-'}</td>
                <td>${item.claimNumber || '-'}</td>
                <td class="text-right">${formatNumber(item.claimAmout) || '-'}</td>
                <td>${item.wdate_2 || '-'}</td>
                <td class="text-left">${item.accidentDescription || '-'}</td>
                <td>${item.damdanga_ || '-'}</td>
                <td>${item.damdangat_ || '-'}</td>
            </tr>
        `).join('');
    }
};

// 클레임 리스트 검색
async function searchClaimList() {
    const searchType = document.getElementById('searchType').value;
    const keyword = document.getElementById('searchKeyword').value;

    try {
        const response = await fetch(`/cms/api/field-practice/search_claims.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `type=${searchType}&keyword=${encodeURIComponent(keyword)}`
        });

        const data = await response.json();
        claimAPI.renderClaimList(data);
    } catch (error) {
        console.error('검색 실패:', error);
        alert('검색 중 오류가 발생했습니다.');
    }
}

// 페이지 로드 시 클레임 리스트 초기화
document.addEventListener('DOMContentLoaded', async () => {
    try {
        const result = await claimAPI.fetchClaimList();
        claimAPI.renderClaimList(result.data);
    } catch (error) {
        console.error('초기 데이터 로드 실패:', error);
    }
});

// 숫자 포맷팅 함수
function formatNumber(number) {
    if (!number) return '0';
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
} 