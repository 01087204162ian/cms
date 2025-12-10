document.addEventListener("DOMContentLoaded", function () {
    // 모든 모달 ID 목록
    const modalIds = [
        "perModal",
        "second-modal",
        "q_3_Modal",
        "uploadModal",
        "third-modal",
        "kj-endose1-modal",
        "kj-DaeriCompany-modal",
        "kj-premium-modal",
        "kj-endorse-modal",
        "kj-new-modal",
        "kj-sms-modal",
        "kj-damdangja-modal",
        "kj-id-modal",
        "kj-date-modal",
        "kj-smsList-modal",
        "kj-dailyEndose-modal",
        "kj-endorseCheck-modal",
        "kj-endorseSituation-modal",
        "policyNum-modal",
        "po-premium-modal",
        "gisaList-modal",
        "adjustment-modal",
        "adjustmentMothly-modal",
        "settlementMothly-modal",
        "hollinwon-modal",
        "manual-modal", // 추가됨
        "dambo-modal"   // 누락되었던 것 추가
    ];

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
                    closeModalSafely(modalId);
                    console.log(`${modalId} 모달 외부 클릭으로 닫힘`);
                }
            });
        }
    });

    // 닫기 버튼들에 대한 매핑 정보 정의
    const closeButtonMapping = [
        { buttonClass: '.close-endorseModal', modalId: 'kj-endorse-modal' },
        { buttonClass: '.close-newModal', modalId: 'kj-new-modal' },
        { buttonClass: '.close-premiumModal', modalId: 'kj-premium-modal' },
        { buttonClass: '.close-damdangjaModal', modalId: 'kj-damdangja-modal' },
        { buttonClass: '.close-idModal', modalId: 'kj-id-modal' },
        { buttonClass: '.close-dateModal', modalId: 'kj-date-modal' },
        { buttonClass: '.close-smsListModal', modalId: 'kj-smsList-modal' },
        { buttonClass: '.close-endorseCheckModal', modalId: 'kj-endorseCheck-modal' },
        { buttonClass: '.close-policyPremiumModal', modalId: 'po-premium-modal' },
        { buttonClass: '.close-adjustmentMothlyModal', modalId: 'adjustmentMothly-modal' },
        { buttonClass: '.close-settlementMothlyModal', modalId: 'settlementMothly-modal' },
        { buttonClass: '.close-hollinwonModal', modalId: 'hollinwon-modal' },
        { buttonClass: '.close-manualModal', modalId: 'manual-modal' },
        { buttonClass: '.close-damboModal', modalId: 'dambo-modal' }
    ];

    // 모든 닫기 버튼에 이벤트 리스너 추가
    closeButtonMapping.forEach(mapping => {
        const closeButton = document.querySelector(mapping.buttonClass);
        if (closeButton) {
            closeButton.addEventListener('click', function() {
                closeModalSafely(mapping.modalId);
            });
        } else {
            console.warn(`⚠️ '${mapping.buttonClass}' 버튼을 찾을 수 없습니다.`);
        }
    });

    // ESC 키로 모달 닫기
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            // 현재 열려있는 모달 찾아서 닫기
            modalIds.forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (modal && (modal.style.display === 'block' || modal.style.display === 'flex' || modal.classList.contains('show'))) {
                    closeModalSafely(modalId);
                }
            });
        }
    });

    // 안전한 모달 닫기 함수
    function closeModalSafely(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            // 다양한 방식으로 모달 숨기기
            modal.style.display = "none";
            modal.classList.remove('show');
            
            // 바디 스크롤 복원
            document.body.style.overflow = '';
            
            console.log(`${modalId} 모달이 닫혔습니다`);
        }
    }

    // 안전한 모달 열기 함수
    function openModalSafely(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            // 먼저 다른 모든 모달 닫기
            modalIds.forEach(id => {
                if (id !== modalId) {
                    closeModalSafely(id);
                }
            });
            
            // manual-modal의 경우 flex, 나머지는 block
            if (modalId === 'manual-modal' || modalId === 'dambo-modal') {
                modal.style.display = "flex";
            } else {
                modal.style.display = "block";
            }
            
            modal.classList.add('show');
            
            // 바디 스크롤 방지
            document.body.style.overflow = 'hidden';
            
            console.log(`${modalId} 모달이 열렸습니다`);
        } else {
            console.warn(`⚠️ '${modalId}' 모달을 찾을 수 없습니다.`);
        }
    }

    // 모달 열기 함수 전역으로 정의 - 기존 코드와 호환성 유지
    window.openModal = function(modalId) {
        openModalSafely(modalId);
    };

    // 모달 닫기 함수 전역으로 정의 - 기존 코드와 호환성 유지
    window.closeModal = function(modalId) {
        closeModalSafely(modalId);
    };

    // Manual Modal 전용 함수들 (Manual Modal JavaScript와 충돌 방지)
    window.showModal = function(modalId) {
        openModalSafely(modalId);
    };

    window.hideModal = function(modalId) {
        closeModalSafely(modalId);
    };

    console.log('모달 매니저가 초기화되었습니다.');
    console.log('지원하는 모달:', modalIds);
});