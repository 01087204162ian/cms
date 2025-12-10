<!DOCTYPE html>
<html lang="ko">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>보험 검색 및 업데이트</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
            background-color: #f9f9f9;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            margin-bottom: 20px;
            width: 100%;
            box-sizing: border-box;
        }
        .form-group {
            display: flex;
            align-items: center;
            margin-right: 10px;
        }
        .form-group label {
            margin-right: 10px;
            font-weight: bold;
            min-width: 100px;
        }
        input[type="text"] {
            width: 200px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #f4f4f4;
            font-weight: bold;
            text-align: center;
        }
        td {
            text-align: right;
        }
        .btn {
            display: inline-block;
            padding: 10px 15px;
            color: #fff;
            background-color: #007BFF;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        #searchForm {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            box-sizing: border-box;
        }

        #newPolicyForm {
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        #newPolicyForm.visible {
            opacity: 1;
            visibility: visible;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            font-size: 14px;
        }

        th {
            background-color: #007BFF;
            color: white;
            font-weight: bold;
            text-align: center;
        }

        td {
            text-align: right;
            color: #333;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
            cursor: pointer;
        }

        table caption {
            caption-side: top;
            text-align: left;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>증권 관리</h1>

        <!-- 검색 폼 -->
        <form id="searchForm">
            <div class="form-group">
                <label for="policyNumber">증권번호:</label>
                <input type="text" id="policyNumber" name="policyNumber" placeholder="4자리-7자리 형식">
            </div>
            <div class="form-group">
                <label for="insurancePeriod">보험시기:</label>
                <input type="text" id="insurancePeriod" name="insurancePeriod" placeholder="YYYY-MM-DD 형식">
            </div>
            <button type="submit" class="btn">검색</button>
        </form>
        <div id="loading" style="display: none; text-align: center; margin-top: 20px;">
            <div style="width: 100%; background-color: #f3f3f3; border-radius: 5px; overflow: hidden; height: 20px; margin-top: 10px;">
                <div id="progressBar" style="width: 0; height: 100%; background-color: #007BFF; transition: width 0.4s;"></div>
            </div>
            <p>로딩 중...</p>
        </div>
        <!-- 결과 테이블 -->
        <table id="resultsTable">
            <thead>
                <tr>
                    <th>대리운전회사 개수</th>
                    <th>대리기사 인원</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <!-- 신규 입력 폼 -->
        <form id="newPolicyForm" style="margin-top: 20px;">
            <div class="form-group">
                <label for="newPolicyNumber">새 증권번호:</label>
                <input type="text" id="newPolicyNumber" name="newPolicyNumber" placeholder="4자리-7자리 형식">
            </div>
            <div class="form-group">
                <label for="newInsurancePeriod">새 보험시기:</label>
                <input type="text" id="newInsurancePeriod" name="newInsurancePeriod" placeholder="YYYY-MM-DD 형식">
            </div>
            <button type="button" class="btn" id="updatePolicyBtn">업데이트</button>
        </form>
    </div>

    <script>
        document.getElementById('searchForm').addEventListener('submit', async function (event) {
            event.preventDefault(); // 리로드 방지

            const policyNumber = document.getElementById('policyNumber');
            const insurancePeriod = document.getElementById('insurancePeriod');
            const loading = document.getElementById('loading');
            const progressBar = document.getElementById('progressBar');
            const tableBody = document.querySelector('#resultsTable tbody');
            const newPolicyForm = document.getElementById('newPolicyForm');

            // 초기화
            newPolicyForm.classList.remove('visible');

            // 증권번호 형식 검증
            const policyNumberPattern = /^[A-Za-z0-9]{4}-[A-Za-z0-9]{7}$/;
            if (!policyNumberPattern.test(policyNumber.value)) {
                alert('증권번호는 "4자리-7자리" 형식이어야 하며, 숫자와 문자를 포함할 수 있습니다.');
                policyNumber.focus();
                return;
            }

            // 보험시기 형식 검증
            const insurancePeriodPattern = /^\d{4}-\d{2}-\d{2}$/;
            if (!insurancePeriodPattern.test(insurancePeriod.value)) {
                alert('보험시기는 "YYYY-MM-DD" 형식이어야 합니다.');
                insurancePeriod.focus();
                return;
            }

            // 로딩바 활성화
            loading.style.display = 'block';
            progressBar.style.width = '0%'; // 초기화
            setTimeout(() => {
                progressBar.style.width = '50%'; // 시작 시 50% 진행
            }, 200);

            try {
                const response = await fetch('../../_db/search.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        policyNumber: policyNumber.value,
                        insurancePeriod: insurancePeriod.value
                    })
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();

                // 결과 표시
                tableBody.innerHTML = '';
                if (data.length > 0) {
                    data.forEach(item => {
                        const row = `<tr><td>${item.compnayCount}</td><td>${item.inwon}</td></tr>`;
                        tableBody.innerHTML += row;
                    });

                    // 추가 행 삽입
                    const additionalRow = `<tr><td colspan="2" style="text-align:center;">새로운 증권으로 교체 할까요?</td></tr>`;
                    tableBody.innerHTML += additionalRow;

                    // 신규 입력 폼 활성화
                    newPolicyForm.classList.add('visible');
                } else {
                    tableBody.innerHTML = '<tr><td colspan="2">검색 결과가 없습니다.</td></tr>';
                    newPolicyForm.classList.remove('visible');
                }

                // 로딩바 완료
                progressBar.style.width = '100%';
            } catch (error) {
                console.error('검색 중 오류가 발생했습니다:', error);
            } finally {
                // 로딩바 숨기기 (지연 후)
                setTimeout(() => {
                    loading.style.display = 'none';
                    progressBar.style.width = '0%'; // 초기화
                }, 500);
            }
        });

        document.getElementById('updatePolicyBtn').addEventListener('click', async function () {
            const newPolicyNumber = document.getElementById('newPolicyNumber').value;
            const newInsurancePeriod = document.getElementById('newInsurancePeriod').value;
			const policyNumber = document.getElementById('policyNumber').value;
            const insurancePeriod = document.getElementById('insurancePeriod').value;

            // 증권번호 형식 검증
            const policyNumberPattern = /^[A-Za-z0-9]{4}-[A-Za-z0-9]{7}$/;
            if (!policyNumberPattern.test(newPolicyNumber)) {
                alert('새 증권번호는 "4자리-7자리" 형식이어야 합니다.');
                return;
            }

            const insurancePeriodPattern = /^\d{4}-\d{2}-\d{2}$/;
            if (!insurancePeriodPattern.test(newInsurancePeriod)) {
                alert('새 보험시기는 "YYYY-MM-DD" 형식이어야 합니다.');
                return;
            }

            try {
                const response = await fetch('../../_db/update.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
						policyNumber,
                        newPolicyNumber,
                        newInsurancePeriod,
						insurancePeriod
                    })
                });

                if (!response.ok) {
                    throw new Error('업데이트 실패');
                }

                alert('새 증권 정보가 성공적으로 업데이트되었습니다.');
                document.getElementById('newPolicyForm').reset();
            } catch (error) {
                console.error('업데이트 중 오류가 발생했습니다:', error);
                alert('업데이트 중 오류가 발생했습니다.');
            }
        });
    </script>
</body>
</html>
