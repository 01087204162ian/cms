// 전역 변수
        let companionCount = 0;
        let isFormDisabled = false;
        
        // 페이지 전환 기능
        const navSignup = document.getElementById('nav-signup');
        const navClaim = document.getElementById('nav-claim');
        const navFaq = document.getElementById('nav-faq');
        const navTerms = document.getElementById('nav-terms');
        
        const signupPage = document.getElementById('signup-page');
        const claimPage = document.getElementById('claim-page');
        const faqPage = document.getElementById('faq-page');
        const termsPage = document.getElementById('terms-page');
        
        // 네비게이션 탭 클릭 이벤트
        navSignup.addEventListener('click', function() {
            showPage(signupPage);
            setActiveTab(navSignup);
        });
        
        navClaim.addEventListener('click', function() {
            showPage(claimPage);
            setActiveTab(navClaim);
        });
        
        navFaq.addEventListener('click', function() {
            showPage(faqPage);
            setActiveTab(navFaq);
        });
        
        navTerms.addEventListener('click', function() {
            showPage(termsPage);
            setActiveTab(navTerms);
        });
        
        // 페이지 표시 함수
        function showPage(page) {
            // 모든 페이지 숨기기
            signupPage.style.display = 'none';
            claimPage.style.display = 'none';
            faqPage.style.display = 'none';
            termsPage.style.display = 'none';
            
            // 선택한 페이지 표시
            page.style.display = 'block';
        }
        
        // 탭 활성화 함수
        function setActiveTab(tab) {
            // 모든 탭 비활성화
            navSignup.classList.remove('active');
            navClaim.classList.remove('active');
            navFaq.classList.remove('active');
            navTerms.classList.remove('active');
            
            // 선택한 탭 활성화
            tab.classList.add('active');
        }

        // 폼 비활성화 함수 추가
        function disableForm() {
            isFormDisabled = true;
            const formElements = [
                'name', 'phone', 'golf-course', 'tee-time', 
                'terms-checkbox', 'signup-button'
            ];
            
            formElements.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.disabled = true;
                }
            });
            
            // 동반자 추가 버튼도 비활성화
            const addCompanionBtn = document.getElementById('add-companion');
            if (addCompanionBtn) {
                addCompanionBtn.disabled = true;
                addCompanionBtn.style.opacity = '0.5';
                addCompanionBtn.style.cursor = 'not-allowed';
            }
        }

        // 폼 활성화 함수 추가
        function enableForm() {
            isFormDisabled = false;
            const formElements = [
                'name', 'phone', 'golf-course', 'tee-time', 
                'terms-checkbox', 'signup-button'
            ];
            
            formElements.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.disabled = false;
                }
            });
            
            // 동반자 추가 버튼도 활성화
            const addCompanionBtn = document.getElementById('add-companion');
            if (addCompanionBtn) {
                addCompanionBtn.disabled = false;
                addCompanionBtn.style.opacity = '1';
                addCompanionBtn.style.cursor = 'pointer';
            }
        }
        
        // 골프공 애니메이션
        const golfBall = document.querySelector('.golf-ball');

        function playGolfBallAnimation() {
			const holeRing = document.querySelector('.hole-ring');
			golfBall.style.animation = 'none'; // 애니메이션 초기화
			golfBall.offsetHeight; // 리플로우 강제 발생
			golfBall.style.animation = 'golf-ball-roll 2.5s linear'; // 애니메이션 재적용
			
			// 공이 홀에 들어가는 시점에 홀 반짝임 효과 추가
			setTimeout(() => {
				holeRing.classList.add('hole-active');
				setTimeout(() => {
					holeRing.classList.remove('hole-active');
				}, 1000);
			}, 2000);
			
			// 애니메이션 실행 후 재귀 호출
			setTimeout(playGolfBallAnimation, 3000);
		}
        
        // 초기 애니메이션 실행
        playGolfBallAnimation();

        // 폭죽 애니메이션
        const fireworks = document.querySelector('.fireworks');
        const fireworksSpans = document.querySelectorAll('.fireworks span');
        const directions = [
            [0, -1], [0.7, -0.7], [1, 0], [0.7, 0.7], [0, 1],
            [-0.7, 0.7], [-1, 0], [-0.7, -0.7]
        ];

        fireworksSpans.forEach((span, i) => {
            span.style.setProperty('--dx', directions[i][0]);
            span.style.setProperty('--dy', directions[i][1]);
        });

        function triggerFireworks() {
            fireworks.style.opacity = '1';

            fireworksSpans.forEach(span => {
                span.style.animation = 'none';
                span.offsetHeight; // 리플로우 강제 발생
                span.style.animation = 'fireworks-burst 1.5s ease forwards';
            });

            setTimeout(() => {
                fireworks.style.opacity = '0';
            }, 1500);
        }

        // 골프공 애니메이션 종료 시 폭죽 실행
        golfBall.addEventListener('animationend', triggerFireworks);

        // 페이지 로드 시 쿠폰 검증 실행 (디버깅 추가)
		window.addEventListener('load', function() {
			console.log('페이지 로드 완료');
			
			// URL 파라미터에서 쿠폰 번호를 가져옵니다
			const urlParams = new URLSearchParams(window.location.search);
			const couponFromUrl = urlParams.get('coupon') || 'VIP-2025-1234';
			
			console.log('쿠폰 번호:', couponFromUrl);
			
			// 쿠폰 번호 필드에 값 설정
			const couponNumberField = document.getElementById('coupon-number');
			couponNumberField.value = couponFromUrl;
			
			// validateCouponNumber 함수 존재 확인
			if (typeof validateCouponNumber !== 'function') {
				console.error('validateCouponNumber 함수가 정의되지 않았습니다!');
				console.log('외부 JS 파일이 로드되지 않았을 수 있습니다.');
				disableForm();
				return;
			}
			
			console.log('쿠폰 검증 시작...');
			
			// 쿠폰 사전 검증 실행
			validateCouponNumber(
				couponFromUrl,
				// 성공 시
				function(couponData) {
					console.log('✅ 쿠폰 검증 성공:', couponData);
					CouponUI.showSuccess(couponData);
					enableForm(); // 폼 활성화
					// 필요시 고객 정보 자동 입력
					if (couponData.customerInfo && couponData.customerInfo.name) {
						document.getElementById('name').value = couponData.customerInfo.name;
					}
				},
				// 실패 시  
				function(errorMessage) {
					console.error('❌ 쿠폰 검증 실패:', errorMessage);
					CouponUI.showError(errorMessage);
					disableForm(); // 입력 폼 비활성화
				},
				// 로딩 중
				function(isLoading) {
					console.log('로딩 상태:', isLoading);
					CouponUI.showLoading(isLoading);
				}
			);
		});
        
        // 동반자 추가 기능
       /* const addCompanionBtn = document.getElementById('add-companion');
        const companionsContainer = document.getElementById('companions-container');
        
        addCompanionBtn.addEventListener('click', function() {
            if (isFormDisabled) return; // 폼이 비활성화된 경우 실행하지 않음
            
			if (companionCount < 3) {
				companionCount++;
				
				const companionItem = document.createElement('div');
				companionItem.className = 'companion-item';
				companionItem.innerHTML = `
					<div class="companion-header">
						<div class="companion-title">동반자 ${companionCount}</div>
						<button class="remove-companion" type="button">&times;</button>
					</div>
					<div class="companion-fields">
						<input type="text" class="form-control" placeholder="이름" required>
						<input type="text" class="form-control phone-number" placeholder="휴대폰 번호" required>
					</div>
				`;
				
				companionsContainer.appendChild(companionItem);
				
				// 휴대폰 번호 형식 변환 이벤트 추가
				const phoneInput = companionItem.querySelector('.phone-number');
				phoneInput.addEventListener('input', function(e) {
					// 현재 커서 위치 저장
					const cursorPosition = e.target.selectionStart;
					
					// 입력된 값에서 하이픈 제거
					let value = e.target.value.replace(/-/g, '');
					
					// 숫자만 입력받기
					value = value.replace(/[^0-9]/g, '');
					
					// 최대 11자리까지만 허용
					if (value.length > 11) {
						value = value.slice(0, 11);
					}
					
					// 형식화된 번호 생성
					let formattedValue = '';
					if (value.length > 7) {
						formattedValue = value.slice(0, 3) + '-' + value.slice(3, 7) + '-' + value.slice(7);
					} else if (value.length > 3) {
						formattedValue = value.slice(0, 3) + '-' + value.slice(3);
					} else {
						formattedValue = value;
					}
					
					// 이전 값과 다를 경우에만 업데이트
					if (e.target.value !== formattedValue) {
						// 하이픈이 추가된 경우 커서 위치 조정
						const addedHyphens = (formattedValue.match(/-/g) || []).length - (e.target.value.match(/-/g) || []).length;
						
						e.target.value = formattedValue;
						
						// 커서 위치 복원 (하이픈이 추가된 경우 조정)
						if (cursorPosition <= 3) {
							// 첫 번째 구역 (000)
							e.target.setSelectionRange(cursorPosition, cursorPosition);
						} else if (cursorPosition <= 8) {
							// 두 번째 구역 (000-0000)
							e.target.setSelectionRange(cursorPosition + addedHyphens, cursorPosition + addedHyphens);
						} else {
							// 세 번째 구역 (000-0000-0000)
							e.target.setSelectionRange(cursorPosition + addedHyphens, cursorPosition + addedHyphens);
						}
					}
				});
				
				// 동반자 삭제 버튼 이벤트
				const removeBtn = companionItem.querySelector('.remove-companion');
				removeBtn.addEventListener('click', function() {
					companionsContainer.removeChild(companionItem);
					companionCount--;
					
					// 동반자 수가 3명 미만이면 추가 버튼 활성화
					if (companionCount < 3) {
						addCompanionBtn.style.display = 'flex';
					}
				});
			}
			
			// 동반자 수가 3명이면 추가 버튼 비활성화
			if (companionCount >= 3) {
				addCompanionBtn.style.display = 'none';
			}
		});*/
					
        // FAQ 아코디언 기능
        const faqQuestions = document.querySelectorAll('.faq-question');
        
        faqQuestions.forEach(question => {
            question.addEventListener('click', function() {
                // 현재 질문 활성화/비활성화 토글
                this.classList.toggle('active');
                
                // 답변 표시/숨김 토글
                const answer = this.nextElementSibling;
                answer.classList.toggle('show');
            });
        });
        
        // 휴대폰 번호 포맷팅
        const phoneInputs = document.querySelectorAll('input[type="tel"]');
        
        phoneInputs.forEach(input => {
            input.addEventListener('input', function(e) {
                // 숫자만 추출
                let value = this.value.replace(/[^\d]/g, '');
                
                // 포맷팅
                if (value.length <= 3) {
                    this.value = value;
                } else if (value.length <= 7) {
                    this.value = value.slice(0, 3) + '-' + value.slice(3);
                } else {
                    this.value = value.slice(0, 3) + '-' + value.slice(3, 7) + '-' + value.slice(7, 11);
                }
            });
        });
        
        // 파일 업로드 기능
        const uploadPhoto = document.getElementById('upload-photo');
        const uploadCertificate = document.getElementById('upload-certificate');
        const photoInput = document.getElementById('photo-input');
        const certificateInput = document.getElementById('certificate-input');
        
        uploadPhoto.addEventListener('click', function() {
            photoInput.click();
        });
        
        uploadCertificate.addEventListener('click', function() {
            certificateInput.click();
        });
        
        photoInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                const fileName = this.files[0].name;
                uploadPhoto.querySelector('.file-upload-text').textContent = fileName;
                uploadPhoto.style.borderColor = '#4CAF50';
            }
        });
        
        certificateInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                const fileName = this.files[0].name;
                uploadCertificate.querySelector('.file-upload-text').textContent = fileName;
                uploadCertificate.style.borderColor = '#4CAF50';
            }
        });
        
        // 팝업 기능
        const popupOverlay = document.getElementById('popup-overlay');
        const popupTitle = document.getElementById('popup-title');
        const popupMessage = document.getElementById('popup-message');
        const popupButton = document.getElementById('popup-button');
        
        function showPopup(title, message) {
            popupTitle.textContent = title;
            popupMessage.textContent = message;
            popupOverlay.classList.add('show');
        }
        
        popupButton.addEventListener('click', function() {
            popupOverlay.classList.remove('show');
        });
        
        // 가입 신청 제출
        const signupButton = document.getElementById('signup-button');
        
        // 가입 신청 제출 이벤트 핸들러
		signupButton.addEventListener('click', function() {
            if (isFormDisabled) {
                showPopup('오류', '쿠폰 검증이 완료되지 않았습니다.');
                return;
            }
            
			const couponNumber = document.getElementById('coupon-number').value;
			const name = document.getElementById('name').value;
			const phone = document.getElementById('phone').value;
			const golfCourse = document.getElementById('golf-course').value;
			const teeTime = document.getElementById('tee-time').value;
			const termsCheckbox = document.getElementById('terms-checkbox').checked;
			
			// 필수 입력 검증
			if (!name || !phone || !golfCourse || !teeTime) {
				showPopup('입력 오류', '모든 필수 항목을 입력해주세요.');
				return;
			}
			
			if (!termsCheckbox) {
				showPopup('약관 동의', '약관 및 개인정보 활용 동의가 필요합니다.');
				return;
			}
			
			// 쿠폰 번호 검증 (필요시)
			if (!couponNumber) {
				showPopup('쿠폰 번호 오류', '유효한 쿠폰 번호가 필요합니다.');
				return;
			}
			
			// 동반자 정보 수집
			const companions = [];
			document.querySelectorAll('.companion-item').forEach(item => {
				const fields = item.querySelectorAll('.form-control');
				const companionName = fields[0].value;
				const companionPhone = fields[1].value;
				
				if (companionName && companionPhone) {
					companions.push({
						name: companionName,
						phone: companionPhone
					});
				}
			});
			
			// FormData 객체 생성 및 데이터 추가
			const formData = new FormData();
			formData.append('couponNumber', couponNumber);
			formData.append('name', name);
			formData.append('phone', phone);
			formData.append('golfCourse', golfCourse);
			formData.append('teeTime', teeTime);
			formData.append('termsAgreed', termsCheckbox);
			formData.append('companions', JSON.stringify(companions));
			
			// fetch를 사용하여 서버로 데이터 전송
			fetch('api/customer/applyAce.php', {
				method: 'POST',
				body: formData
			})
			.then(response => {
				if (!response.ok) {
					throw new Error('서버 응답이 올바르지 않습니다.');
				}
				return response.json();
			})
			.then(data => {
				// 성공적으로 서버에 데이터가 전송된 경우
				showPopup('가입 완료', '홀인원 보험 가입이 완료되었습니다. 즐거운 라운딩 되세요!');
				
				// 폼 초기화 - 단, 쿠폰 번호는 초기화하지 않음
				document.getElementById('name').value = '';
				document.getElementById('phone').value = '';
				document.getElementById('golf-course').value = '';
				document.getElementById('tee-time').value = '';
				document.getElementById('terms-checkbox').checked = false;
				
				// 동반자 정보 초기화
				companionsContainer.innerHTML = '';
				companionCount = 0;
				addCompanionBtn.style.display = 'flex';
			})
			.catch(error => {
				// 오류 발생 시 처리
				console.error('데이터 전송 중 오류가 발생했습니다:', error);
				showPopup('오류 발생', '서버와의 통신 중 문제가 발생했습니다. 다시 시도해 주세요.');
			});
		});
        
        // 보상 신청 제출
        const claimButton = document.getElementById('claim-button');
        
        claimButton.addEventListener('click', function() {
            const name = document.getElementById('claim-name').value;
            const phone = document.getElementById('claim-phone').value;
            const golfCourse = document.getElementById('claim-golf-course').value;
            const date = document.getElementById('claim-date').value;
            const hole = document.getElementById('claim-hole').value;
            const termsCheckbox = document.getElementById('claim-terms-checkbox').checked;
            
            // 필수 입력 검증
            if (!name || !phone || !golfCourse || !date || !hole) {
                showPopup('입력 오류', '모든 필수 항목을 입력해주세요.');
                return;
            }
            
            if (!termsCheckbox) {
                showPopup('약관 동의', '개인정보 활용 동의가 필요합니다.');
                return;
            }
            
            // 파일 업로드 검증
            if (photoInput.files.length === 0 || certificateInput.files.length === 0) {
                showPopup('파일 누락', '홀인원 사진과 확인증을 모두 첨부해주세요.');
                return;
            }
            
            // 서버 전송 대신 성공 팝업 표시
            showPopup('신청 완료', '홀인원 보상 신청이 완료되었습니다. 영업일 기준 3~5일 내에 검토 후 보상금이 지급됩니다.');
            
            // 폼 초기화
            document.getElementById('claim-name').value = '';
            document.getElementById('claim-phone').value = '';
            document.getElementById('claim-golf-course').value = '';
            document.getElementById('claim-date').value = '';
            document.getElementById('claim-hole').value = '';
            document.getElementById('claim-terms-checkbox').checked = false;
            
            // 파일 업로드 초기화
            photoInput.value = '';
            certificateInput.value = '';
            uploadPhoto.querySelector('.file-upload-text').textContent = '홀인원 사진';
            uploadCertificate.querySelector('.file-upload-text').textContent = '확인증(스코어카드)';
            uploadPhoto.style.borderColor = '#ccc';
            uploadCertificate.style.borderColor = '#ccc';
        });
