document.addEventListener("DOMContentLoaded", function () {
    // 모든 모달 ID 목록
   const modalIds = [
        "subscription-modal"
    ];

    // 모든 모달 숨기기
    // 모든 모달 숨기기 및 외부 클릭 이벤트 설정
    modalIds.forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (modal) {
            // 페이지 로드 시 모달 숨기기
            modal.style.display = "none";
            
            // 모달 외부 클릭 시 닫기 기능 추가
            modal.addEventListener("click", function(event) {
                // 중요: 클릭한 요소가 모달 자체인 경우에만 닫기
                if (event.target === this) {
                    modal.style.display = "none";
                    console.log(`${modalId} 모달 외부 클릭으로 닫힘`);
                }
            });
        }
    });
    // 닫기 버튼들에 대한 매핑 정보 정의
    const closeButtonMapping = [
        { buttonClass: '.close-subscriptionModal', modalId: 'subscription-modal' },
   
	
    ];

    // 모든 닫기 버튼에 이벤트 리스너 추가
    closeButtonMapping.forEach(mapping => {
        const closeButton = document.querySelector(mapping.buttonClass);
        if (closeButton) {
            closeButton.addEventListener('click', function() {
                const modal = document.getElementById(mapping.modalId);
                if (modal) {
                    modal.style.display = "none";
                }
            });
        } else {
            console.warn(`⚠️ '${mapping.buttonClass}' 버튼을 찾을 수 없습니다.`);
        }
    });

    // 모달 열기 함수 전역으로 정의 (필요시 사용)
    window.openModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = "block";
        } else {
            console.warn(`⚠️ '${modalId}' 모달을 찾을 수 없습니다.`);
        }
    };

    // 모달 닫기 함수 전역으로 정의 (필요시 사용)
    window.closeModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = "none";
        } else {
            console.warn(`⚠️ '${modalId}' 모달을 찾을 수 없습니다.`);
        }
    };
});




   
