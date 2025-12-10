function kjDashboard() {
  const pageContent = document.getElementById('page-content');
  if (!pageContent) return;
  
  pageContent.innerHTML = `
    <div class="kj-dashboard">
      <h2>KJ대리운전 대시보드</h2>
      <!-- 대시보드 콘텐츠 -->
      <p>새로운 KJ대리운전 대시보드 페이지입니다.</p>
    </div>
  `;
}