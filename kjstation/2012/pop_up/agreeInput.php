<?session_start();

//if($_SESSION['ssID']){
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>보험 조회</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1 {
            text-align: center;
            color: #555;
        }
        .form-group {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
		.form-group2 {
            display: flex;
            align-items: left;
            justify-content: space-between;
            margin-top: 20px;
        }
        label {
            margin-right: 10px;
            font-weight: bold;
        }
        input[type="text"] {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            margin-right: 10px;
        }
        button {
            padding: 10px 20px;
            border: none;
            background: #007bff;
            color: #fff;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
		.button2 {
            padding: 10px 20px;
            border: none;
            background: #fc49fc;
            color: #fff;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }
        .button2:hover {
            background: #fc59fc;
        }
        .result {
            margin-top: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        .table th {
            background-color: #f4f4f9;
        }
        .message-input {
            display: flex;
            margin-top: 10px;
        }
        .message-input input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-right: 10px;
        }
		.ptext{
			text-align: center;
		}
		#result2 {
			display: block;
		}

		.loading {
        display: none;
        text-align: center;
        margin-top: 20px;
		}
		.loading span {
			display: inline-block;
			width: 10px;
			height: 10px;
			margin: 0 5px;
			background: #007bff;
			border-radius: 50%;
			animation: bounce 1.5s infinite ease-in-out;
		}
		.loading span:nth-child(2) {
			animation-delay: 0.3s;
		}
		.loading span:nth-child(3) {
			animation-delay: 0.6s;
		}
		@keyframes bounce {
			0%, 80%, 100% {
				transform: scale(0);
			}
			40% {
				transform: scale(1);
			}
		}
    </style>
</head>
<body>
    <div class="container">
       
        <h1>보험 조회</h1>
		
           
       
		<p class='ptext'>본 페이지는 갱신 후 증권에 해당하는 대리기사 상태를 파악하여 체결이행 동의 URL을 발송합니다.</p>
        <div class="form-group">
            <label for="policyNumber">증권번호 입력:</label>
            <input type="text" id="policyNumber" placeholder="증권번호를 입력하세요..." onclick="Policy()">
            <button onclick="lookupPolicy()">검색</button>
			
        </div>
		<div class="loading" id="loading">
			<span></span><span></span><span></span>
		</div>
        <div class="result" id="result">
            <!-- 결과가 여기에 표시됩니다 -->
        </div>

		<div class="result" id="result2">
           <!-- 결과가 여기에 표시됩니다 -->
        </div>
		
    </div>

    <script>
		function Policy(){

				 const resultDiv = document.getElementById("result");
				 resultDiv.innerHTML =``;
				 document.getElementById("policyNumber").value='';
				 
		}
        function lookupPolicy() {
			
				const policyNumber = document.getElementById("policyNumber").value;
			
            if (!policyNumber.trim()) {
                alert("증권번호를 입력해주세요.");
                return;
            }

            fetch("../../_db/search_policy.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ policyNumber })
            })
            .then(response => response.json())
            .then(policyData => {
                const resultDiv = document.getElementById("result");
				//alert(policyData.company);
                if (!policyData || !policyData.company) {
                    resultDiv.innerHTML = "<p>해당 증권번호에 대한 데이터를 찾을 수 없습니다.</p>";
                    return;
                }
				
                resultDiv.innerHTML = `
                   
                    <table class="table">
                        <tr>
                            <th with="5%">항목</th>
                            <th with="45%">내용</th>
					        <th with="5%">항목</th>
                            <th with="45%">내용</th>
                        </tr>
                        <tr>
                            <td>회사</td>
                            <td>${policyData.company}</td>
					         <td>보험 기간</td>
                            <td>${policyData.sigi}~${policyData.endDay}</td>
                        </tr>
                        <tr>
                            <td>증권번호</td>
                            <td>${policyData.certi}</td>
							<td>보험사</td>
                            <td>${policyData.InCompany}</td>
                        </tr>
						
                    </table>			
                `
						const resultDiv2 = document.getElementById("result2");
				if(policyData.agree.wdate){	
					resultDiv2.innerHTML =`${policyData.agree.result}명${policyData.agree.wdate} 체결동의 함`
		
				}else{
					resultDiv2.innerHTML =	`<div class="form-group2">
						<label>${policyData.inWon} 명 체결동의 보낼까요</label>
						<button class="button2" id='submitButton' onclick="hcurl('${policyData.InCompany}', '${policyData.cName}', '${policyData.startyDay}', '${policyData.endDay}', '${policyData.certi}')">
						체결동의</button>
					</div>`
				};
            })
            .catch(error => {
                console.error("데이터 가져오기 오류:", error);
                document.getElementById("result").innerHTML = "<p>데이터를 가져오는 중 오류가 발생했습니다. 나중에 다시 시도해주세요.</p>";
            });
        }

		function hcurl(inCompany, cName, startDay, endDay, policyNum) {
			       const loadingDiv = document.getElementById("loading");
					loadingDiv.style.display = "block"; // 로딩바 표시
			// 입력값 처리
					const company = inCompany.trim();

					// 조건 확인
					if (company !== "현대") {
						alert("체결동의는 현대해상만 적용합니다.");
						return;
					}

			// 서버에 요청 보내기
			// 전달받은 값들을 출력하거나 필요한 로직 실행
				console.log("InCompany:", inCompany);
				console.log("Company Name:", cName);
				console.log("Start Day:", startDay);
				console.log("End Day:", endDay);
				console.log("Policy Number:", policyNum);

				// 서버에 요청 보내기
				fetch("../../_db/agreement_endpoint.php", {
					method: "POST",
					headers: {
						"Content-Type": "application/json"
					},
					body: JSON.stringify({
						inCompany,
						cName,
						startDay,
						endDay,
						policyNum
					})
				})
				.then(response => response.json())
				.then(data => {
				
					if (data.success) {
						alert("체결 동의가 성공적으로 완료되었습니다.");

					const resultDiv2 = document.getElementById("result2");
						if (resultDiv2) {
							resultDiv2.innerHTML = data.totalElements+"명 체결 동의 완료함";
						} else {
							console.error("result2 요소를 찾을 수 없습니다.");
						}
					} else {
						alert("체결 동의에 실패했습니다: " + (data.error || "알 수 없는 오류"));
					}
				})
				.catch(error => {
					console.error("오류 발생:", error);
					alert("요청 처리 중 오류가 발생했습니다.");
				})
				.finally(() => {
					loadingDiv.style.display = "none"; // 로딩바 숨기기
				});
		}
        function sendMessage(memberId) {
            const messageInput = document.getElementById(`message-${memberId}`);
            const message = messageInput.value;
            if (message.trim() === "") {
                alert("메시지를 입력해주세요.");
                return;
            }
            alert(`ID ${memberId} 가입자에게 메시지를 전송했습니다: "${message}"`);
            messageInput.value = ""; // 입력란 초기화
        }
    </script>
</body>
</html>
<?
//}else{
//		header("Content-Type: application/json; charset=UTF-8");
//		echo "잘못된 접근입니다";
//}

?>
