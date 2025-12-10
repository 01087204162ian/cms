<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
require_once './includes/db.php';
$db = new DatabaseConnection();
$pdo = $db->pdo;

// 필터 처리
$categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';
$sql = "SELECT * FROM checklist";
if (!empty($categoryFilter)) {
    $sql .= " WHERE category = :category";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['category' => $categoryFilter]);
} else {
    $stmt = $pdo->query($sql);
}
$rows = $stmt->fetchAll();

// 진행률 계산
$totalItems = count($rows);
$completedItems = 0;
foreach ($rows as $row) {
    if (!empty($row['result']) && !empty($row['dev_status'])) {
        $completedItems++;
    }
}
$progressPercentage = $totalItems > 0 ? round(($completedItems / $totalItems) * 100) : 0;
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>보안 점검표 입력</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Noto Sans KR', 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 25px 30px;
            margin-bottom: 25px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #4a6fa5, #36588c);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 18px;
        }

        .welcome-text {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }

        .logout-btn {
            background: linear-gradient(135deg, #ff6b6b, #ee5a24);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(238, 90, 36, 0.3);
        }

        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(238, 90, 36, 0.4);
        }

        .controls {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 25px 30px;
            margin-bottom: 25px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .progress-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .progress-circle {
            position: relative;
            width: 60px;
            height: 60px;
        }

        .progress-ring {
            transform: rotate(-90deg);
        }

        .progress-ring-circle {
            stroke: #e6e6e6;
            fill: transparent;
            stroke-width: 6;
        }

        .progress-ring-progress {
            stroke: #4a6fa5;
            fill: transparent;
            stroke-width: 6;
            stroke-dasharray: 157;
            stroke-dashoffset: <?= 157 - (157 * $progressPercentage / 100) ?>;
            transition: stroke-dashoffset 0.3s ease;
        }

        .progress-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-weight: bold;
            font-size: 14px;
            color: #4a6fa5;
        }

        .progress-info {
            color: #666;
        }

        .filter-form {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-select {
            padding: 12px 16px;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            background: white;
            font-size: 14px;
            font-weight: 500;
            color: #333;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 150px;
        }

        .filter-select:focus {
            outline: none;
            border-color: #4a6fa5;
            box-shadow: 0 0 0 3px rgba(74, 111, 165, 0.1);
        }

        .message {
            background: linear-gradient(135deg, #00b894, #00a085);
            color: white;
            padding: 15px 25px;
            border-radius: 15px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(0, 184, 148, 0.3);
        }

        .main-content {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: #333;
            margin-bottom: 30px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .checklist-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .checklist-table th {
            background: linear-gradient(135deg, #4a6fa5, #36588c);
            color: white;
            padding: 18px 15px;
            font-weight: 600;
            text-align: center;
            border: none;
            font-size: 14px;
        }

        .checklist-table td {
            padding: 20px 15px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: top;
        }

        .checklist-table tr:hover {
            background-color: #f8f9ff;
            transition: background-color 0.2s ease;
        }

        .row-completed {
            background: linear-gradient(135deg, #e8f5e8, #f0f8f0) !important;
            border-left: 4px solid #00b894;
        }

        .row-number {
            background: linear-gradient(135deg, #4a6fa5, #36588c);
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
            margin: 0 auto;
        }

        .category-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            color: white;
        }

        .category-backend {
            background: linear-gradient(135deg, #6c5ce7, #5f3dc4);
        }

        .category-frontend {
            background: linear-gradient(135deg, #fd79a8, #e84393);
        }

        .input-group {
            position: relative;
            margin-bottom: 15px;
        }

        .input-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
            font-size: 13px;
        }

        .textarea-enhanced {
            width: 100%;
            min-height: 100px;
            padding: 15px;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            font-size: 14px;
            font-family: inherit;
            resize: vertical;
            transition: all 0.3s ease;
            background: white;
        }

        .textarea-enhanced:focus {
            outline: none;
            border-color: #4a6fa5;
            box-shadow: 0 0 0 3px rgba(74, 111, 165, 0.1);
        }

        .textarea-result {
            background: linear-gradient(135deg, #e3f2fd, #f8fbff);
            border-color: #2196f3;
        }

        .textarea-result:focus {
            border-color: #1976d2;
            box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.1);
        }

        .textarea-dev {
            background: linear-gradient(135deg, #fff8e1, #fffbf0);
            border-color: #ff9800;
        }

        .textarea-dev:focus {
            border-color: #f57c00;
            box-shadow: 0 0 0 3px rgba(255, 152, 0, 0.1);
        }

        .char-counter {
            position: absolute;
            bottom: 8px;
            right: 12px;
            font-size: 11px;
            color: #999;
            background: rgba(255, 255, 255, 0.9);
            padding: 2px 6px;
            border-radius: 8px;
        }

        .submit-section {
            margin-top: 40px;
            text-align: center;
            padding: 30px;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 15px;
        }

        .submit-btn {
            background: linear-gradient(135deg, #00b894, #00a085);
            color: white;
            padding: 15px 40px;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(0, 184, 148, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 184, 148, 0.4);
        }

        .submit-btn:active {
            transform: translateY(-1px);
        }

        .save-individual-btn {
            margin-top: 10px;
            padding: 8px 16px;
            border: none;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            width: 100%;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .save-result-btn {
            background: linear-gradient(135deg, #2196f3, #1976d2);
            color: white;
            box-shadow: 0 2px 8px rgba(33, 150, 243, 0.3);
        }

        .save-result-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(33, 150, 243, 0.4);
        }

        .save-dev-btn {
            background: linear-gradient(135deg, #ff9800, #f57c00);
            color: white;
            box-shadow: 0 2px 8px rgba(255, 152, 0, 0.3);
        }

        .save-dev-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(255, 152, 0, 0.4);
        }

        .save-individual-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }

        .save-individual-btn.saving {
            background: #95a5a6 !important;
            color: white;
        }

        .save-individual-btn.saved {
            background: linear-gradient(135deg, #00b894, #00a085) !important;
            color: white;
        }

        .save-individual-btn.saved::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            animation: shine 0.6s ease-out;
        }

        @keyframes shine {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        .individual-save-indicator {
            position: fixed;
            top: 70px;
            right: 20px;
            background: white;
            color: #333;
            padding: 12px 18px;
            border-radius: 25px;
            font-size: 13px;
            font-weight: 600;
            opacity: 0;
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            border-left: 4px solid #00b894;
        }
            position: fixed;
            top: 20px;
            right: 20px;
            background: #4a6fa5;
            color: white;
            padding: 10px 15px;
            border-radius: 25px;
            font-size: 12px;
            font-weight: 600;
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 1000;
        }

        .auto-save-indicator.show {
            opacity: 1;
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .header, .controls, .main-content {
                padding: 20px;
            }

            .checklist-table th,
            .checklist-table td {
                padding: 12px 8px;
                font-size: 13px;
            }

            .page-title {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="auto-save-indicator" id="autoSaveIndicator">
        <i class="fas fa-check-circle"></i> 자동 저장됨
    </div>

    <div class="individual-save-indicator" id="individualSaveIndicator">
        <i class="fas fa-check-circle"></i> <span id="saveMessage">저장되었습니다</span>
    </div>

    <div class="container">
        <div class="header">
            <div class="user-info">
                <div class="user-avatar">
                    <?= mb_substr($_SESSION['admin'], 0, 1, 'UTF-8') ?>
                </div>
                <div class="welcome-text">
                    <strong><?= htmlspecialchars($_SESSION['admin']) ?>님</strong> 환영합니다!
                </div>
            </div>
            <a href="./includes/logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> 로그아웃
            </a>
        </div>

        <div class="controls">
            <div class="progress-section">
                <div class="progress-circle">
                    <svg class="progress-ring" width="60" height="60">
                        <circle class="progress-ring-circle" cx="30" cy="30" r="25"></circle>
                        <circle class="progress-ring-progress" cx="30" cy="30" r="25"></circle>
                    </svg>
                    <div class="progress-text"><?= $progressPercentage ?>%</div>
                </div>
                <div class="progress-info">
                    <div><strong>완료: <?= $completedItems ?></strong> / <?= $totalItems ?>개</div>
                    <div style="font-size: 12px; color: #888;">점검 진행률</div>
                </div>
            </div>

            <form method="get" class="filter-form">
                <label for="category" style="font-weight: 600; color: #555;">
                    <i class="fas fa-filter"></i> 유형 필터:
                </label>
                <select name="category" class="filter-select" onchange="this.form.submit()">
                    <option value="">전체 보기</option>
                    <option value="백엔드" <?= $categoryFilter === '백엔드' ? 'selected' : '' ?>>백엔드</option>
                    <option value="프론트엔드" <?= $categoryFilter === '프론트엔드' ? 'selected' : '' ?>>프론트엔드</option>
                </select>
            </form>
        </div>

        <?php if (isset($_GET['saved'])): ?>
            <div class="message">
                <i class="fas fa-check-circle"></i>
                점검 결과가 성공적으로 저장되었습니다!
            </div>
        <?php endif; ?>

        <div class="main-content">
            <h1 class="page-title">
                <i class="fas fa-shield-alt"></i>
                보안 점검표 - 결과 입력/수정
            </h1>

            <form method="post" action="save_result.php" id="checklistForm">
                <table class="checklist-table">
                    <thead>
                        <tr>
                            <th style="width: 60px;">순번</th>
                            <th style="width: 120px;">구분</th>
                            <th style="width: 100px;">유형</th>
                            <th style="width: 300px;">점검 항목</th>
                            <th style="width: 250px;">점검 결과</th>
                            <th style="width: 250px;">개발 현황</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $i => $row): ?>
                        <tr class="<?= (!empty($row['result']) && !empty($row['dev_status'])) ? 'row-completed' : '' ?>">
                            <td style="text-align: center;">
                                <div class="row-number"><?= $i + 1 ?></div>
                            </td>
                            <td style="text-align: center;">
                                <?= htmlspecialchars($row['system_area']) ?>
                            </td>
                            <td style="text-align: center;">
                                <span class="category-badge <?= $row['category'] === '백엔드' ? 'category-backend' : 'category-frontend' ?>">
                                    <?= htmlspecialchars($row['category']) ?>
                                </span>
                            </td>
                            <td>
                                <div style="font-weight: 600; line-height: 1.5;">
                                    <?= nl2br(htmlspecialchars($row['item'])) ?>
                                </div>
                            </td>
                            <td>
                                <div class="input-group">
                                    <label class="input-label">
                                        <i class="fas fa-clipboard-check"></i> 점검 결과
                                    </label>
                                    <textarea 
                                        class="textarea-enhanced textarea-result" 
                                        id="result_<?= $row['id'] ?>"
                                        data-auto-save="result_<?= $row['id'] ?>"
                                        data-item-id="<?= $row['id'] ?>"
                                        data-field="result"
                                        placeholder="점검 결과를 상세히 입력해주세요..."
                                    ><?= htmlspecialchars($row['result']) ?></textarea>
                                    <div class="char-counter" data-target="result_<?= $row['id'] ?>">0자</div>
                                    <button type="button" class="save-individual-btn save-result-btn" 
                                            onclick="saveIndividual(<?= $row['id'] ?>, 'result')"
                                            data-item-id="<?= $row['id'] ?>">
                                        <i class="fas fa-save"></i> 점검결과 저장
                                    </button>
                                </div>
                            </td>
                            <td>
                                <div class="input-group">
                                    <label class="input-label">
                                        <i class="fas fa-code"></i> 개발 현황
                                    </label>
                                    <textarea 
                                        class="textarea-enhanced textarea-dev" 
                                        id="dev_status_<?= $row['id'] ?>"
                                        data-auto-save="dev_status_<?= $row['id'] ?>"
                                        data-item-id="<?= $row['id'] ?>"
                                        data-field="dev_status"
                                        placeholder="개발 현황 및 계획을 입력해주세요..."
                                    ><?= htmlspecialchars($row['dev_status']) ?></textarea>
                                    <div class="char-counter" data-target="dev_status_<?= $row['id'] ?>">0자</div>
                                    <button type="button" class="save-individual-btn save-dev-btn" 
                                            onclick="saveIndividual(<?= $row['id'] ?>, 'dev_status')"
                                            data-item-id="<?= $row['id'] ?>">
                                        <i class="fas fa-save"></i> 개발현황 저장
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="submit-section">
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-save"></i>
                        전체 일괄 저장하기
                    </button>
                    <p style="margin-top: 15px; color: #666; font-size: 14px;">
                        <i class="fas fa-info-circle"></i>
                        개별 저장 또는 전체 일괄 저장이 가능합니다. 작성 중인 내용은 자동으로 임시 저장됩니다.
                    </p>
                </div>
            </form>
        </div>
    </div>

    <script>
        // 개별 저장 함수
        async function saveIndividual(itemId, fieldType) {
            const textarea = document.getElementById(fieldType + '_' + itemId);
            const button = document.querySelector(`button[onclick="saveIndividual(${itemId}, '${fieldType}')"]`);
            const originalText = button.innerHTML;
            
            if (!textarea.value.trim()) {
                alert('내용을 입력해주세요.');
                return;
            }

            // 버튼 상태 변경
            button.disabled = true;
            button.classList.add('saving');
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> 저장 중...';

            try {
                const formData = new FormData();
                formData.append('item_id', itemId);
                formData.append('field_type', fieldType);
                formData.append('content', textarea.value);

                const response = await fetch('save_individual.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    // 성공 상태로 버튼 변경
                    button.classList.remove('saving');
                    button.classList.add('saved');
                    button.innerHTML = '<i class="fas fa-check"></i> 저장 완료!';
                    
                    // 성공 메시지 표시
                    showIndividualSaveIndicator(
                        fieldType === 'result' ? '점검 결과가 저장되었습니다' : '개발 현황이 저장되었습니다'
                    );

                    // 진행률 업데이트
                    updateProgress();

                    // 2초 후 원래 버튼으로 복구
                    setTimeout(() => {
                        button.classList.remove('saved');
                        button.innerHTML = originalText;
                        button.disabled = false;
                    }, 2000);

                } else {
                    throw new Error(result.message || '저장에 실패했습니다.');
                }

            } catch (error) {
                console.error('저장 오류:', error);
                alert('저장 중 오류가 발생했습니다: ' + error.message);
                
                // 버튼 원상복구
                button.classList.remove('saving');
                button.innerHTML = originalText;
                button.disabled = false;
            }
        }

        // 개별 저장 성공 알림
        function showIndividualSaveIndicator(message) {
            const indicator = document.getElementById('individualSaveIndicator');
            const messageSpan = document.getElementById('saveMessage');
            messageSpan.textContent = message;
            indicator.classList.add('show');
            setTimeout(() => {
                indicator.classList.remove('show');
            }, 3000);
        }

        // 진행률 업데이트 함수
        async function updateProgress() {
            try {
                const response = await fetch('get_progress.php');
                const data = await response.json();
                
                if (data.success) {
                    // 진행률 원형 그래프 업데이트
                    const progressRing = document.querySelector('.progress-ring-progress');
                    const progressText = document.querySelector('.progress-text');
                    const progressInfo = document.querySelector('.progress-info div');
                    
                    const circumference = 157;
                    const offset = circumference - (circumference * data.percentage / 100);
                    
                    progressRing.style.strokeDashoffset = offset;
                    progressText.textContent = data.percentage + '%';
                    progressInfo.innerHTML = `<strong>완료: ${data.completed}</strong> / ${data.total}개`;

                    // 완료된 행 하이라이트 업데이트
                    document.querySelectorAll('tr').forEach(row => {
                        row.classList.remove('row-completed');
                    });
                    
                    data.completed_items.forEach(itemId => {
                        const resultTextarea = document.getElementById('result_' + itemId);
                        const devTextarea = document.getElementById('dev_status_' + itemId);
                        if (resultTextarea && devTextarea) {
                            const row = resultTextarea.closest('tr');
                            if (resultTextarea.value.trim() && devTextarea.value.trim()) {
                                row.classList.add('row-completed');
                            }
                        }
                    });
                }
            } catch (error) {
                console.error('진행률 업데이트 오류:', error);
            }
        }

        // 글자 수 카운터 및 기본 기능
        document.addEventListener('DOMContentLoaded', function() {
            const textareas = document.querySelectorAll('.textarea-enhanced');
            const counters = document.querySelectorAll('.char-counter');

            textareas.forEach(textarea => {
                const counter = textarea.parentElement.querySelector('.char-counter');
                
                function updateCounter() {
                    const length = textarea.value.length;
                    counter.textContent = length.toLocaleString() + '자';
                }

                updateCounter();
                textarea.addEventListener('input', updateCounter);
            });

            // 자동 저장 기능 (로컬 스토리지는 개별 저장과 별개로 임시 보관용)
            textareas.forEach(textarea => {
                const autoSaveKey = textarea.dataset.autoSave;
                if (autoSaveKey) {
                    // 페이지 로드 시 저장된 내용 복원
                    const savedContent = localStorage.getItem(autoSaveKey);
                    if (savedContent && textarea.value === '') {
                        textarea.value = savedContent;
                        textarea.dispatchEvent(new Event('input'));
                    }

                    // 입력 시 자동 저장
                    let saveTimeout;
                    textarea.addEventListener('input', function() {
                        clearTimeout(saveTimeout);
                        saveTimeout = setTimeout(() => {
                            localStorage.setItem(autoSaveKey, textarea.value);
                            showAutoSaveIndicator();
                        }, 1000);
                    });
                }
            });

            // 폼 제출 시 로컬 스토리지 정리
            document.getElementById('checklistForm').addEventListener('submit', function() {
                textareas.forEach(textarea => {
                    const autoSaveKey = textarea.dataset.autoSave;
                    if (autoSaveKey) {
                        localStorage.removeItem(autoSaveKey);
                    }
                });
            });
        });

        function showAutoSaveIndicator() {
            const indicator = document.getElementById('autoSaveIndicator');
            indicator.classList.add('show');
            setTimeout(() => {
                indicator.classList.remove('show');
            }, 2000);
        }

        // 폼 제출 전 확인 (일괄 저장)
        document.getElementById('checklistForm').addEventListener('submit', function(e) {
            const textareas = document.querySelectorAll('.textarea-enhanced');
            let hasContent = false;
            
            textareas.forEach(textarea => {
                if (textarea.value.trim()) {
                    hasContent = true;
                }
            });

            if (hasContent) {
                if (!confirm('모든 내용을 일괄 저장하시겠습니까?')) {
                    e.preventDefault();
                }
            }
        });
    </script>
</body>
</html>