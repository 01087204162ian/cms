// 날짜/시간 관리 함수들

let clockInterval; // 인터벌 저장용 변수
let retryCount = 0;
const MAX_RETRIES = 5;

// 시계 관리
const clock = {
    element: null,
    timer: null,

    // 시간 문자열 생성
    getTimeString() {
        const now = new Date();
        const days = ['일', '월', '화', '수', '목', '금', '토'];
        
        return [
            now.getFullYear(),
            '-',
            String(now.getMonth() + 1).padStart(2, '0'),
            '-',
            String(now.getDate()).padStart(2, '0'),
            '(',
            days[now.getDay()],
            ') ',
            String(now.getHours()).padStart(2, '0'),
            ':',
            String(now.getMinutes()).padStart(2, '0')
        ].join('');
    },

    // 시간 업데이트
    update() {
        if (!this.element) {
            this.element = document.getElementById('currentDateTime');
        }
        if (this.element) {
            this.element.textContent = this.getTimeString();
        }
    },

    // 시계 시작
    start() {
        // 이전 타이머 정리
        this.stop();
        
        // 즉시 한 번 업데이트
        this.update();
        
        // 1분마다 업데이트
        this.timer = setInterval(() => this.update(), 60000);
    },

    // 시계 정지
    stop() {
        if (this.timer) {
            clearInterval(this.timer);
            this.timer = null;
        }
    }
};

// 시계 초기화 함수
function initClock() {
    clock.start();
}

// 날짜 범위 체크
function isDateInRange(date, startDate, endDate) {
    const checkDate = new Date(date);
    const start = new Date(startDate);
    const end = new Date(endDate);
    return checkDate >= start && checkDate <= end;
}

// 날짜 차이 계산 (일 수)
function getDaysDifference(date1, date2) {
    const d1 = new Date(date1);
    const d2 = new Date(date2);
    const diffTime = Math.abs(d2 - d1);
    return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
}

// 월 차이 계산
function getMonthsDifference(date1, date2) {
    const d1 = new Date(date1);
    const d2 = new Date(date2);
    return (d2.getFullYear() - d1.getFullYear()) * 12 + d2.getMonth() - d1.getMonth();
}

// 날짜에 일수 더하기
function addDays(date, days) {
    const result = new Date(date);
    result.setDate(result.getDate() + days);
    return result;
}

// 날짜에 월수 더하기
function addMonths(date, months) {
    const result = new Date(date);
    result.setMonth(result.getMonth() + months);
    return result;
}

// 날짜 포맷 변환 (YYYY-MM-DD)
function formatDateYMD(date) {
    const d = new Date(date);
    const year = d.getFullYear();
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

// 시간 포맷 변환 (HH:MM)
function formatTimeHM(date) {
    const d = new Date(date);
    const hours = String(d.getHours()).padStart(2, '0');
    const minutes = String(d.getMinutes()).padStart(2, '0');
    return `${hours}:${minutes}`;
}

// 날짜 유효성 검사
function isValidDate(dateString) {
    const date = new Date(dateString);
    return date instanceof Date && !isNaN(date);
}

// 한국어 날짜 포맷
function formatDateKorean(date) {
    const d = new Date(date);
    const year = d.getFullYear();
    const month = d.getMonth() + 1;
    const day = d.getDate();
    return `${year}년 ${month}월 ${day}일`;
}

// 요일 반환
function getDayOfWeek(date) {
    const days = ['일', '월', '화', '수', '목', '금', '토'];
    const d = new Date(date);
    return days[d.getDay()];
}

// 현재 시간이 업무시간인지 체크 (9:00-18:00)
function isBusinessHours() {
    const now = new Date();
    const hours = now.getHours();
    return hours >= 9 && hours < 18;
}

// 주말인지 체크
function isWeekend(date) {
    const d = new Date(date);
    const day = d.getDay();
    return day === 0 || day === 6; // 0: 일요일, 6: 토요일
} 