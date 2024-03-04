const carousel = document.querySelector('.carousel');
    const slides = Array.from(carousel.querySelectorAll('.slide'));
    const indicators = Array.from(carousel.querySelectorAll('.carousel-indicators li'));
    let currentIndex = 0;

    function showSlide(index) {
      slides.forEach((slide, i) => {
        slide.classList.toggle('active', i === index);
      });
      
      indicators.forEach((indicator, i) => {
        indicator.classList.toggle('active', i === index);
      });
    }

    function changeSlide(index) {
      currentIndex = index;
      showSlide(currentIndex);
    }

    setInterval(() => {
      currentIndex = (currentIndex + 1) % slides.length;
      showSlide(currentIndex);
    }, 3000);

    showSlide(currentIndex);

		const showPasswordIcon = document.getElementById('show-password-icon');
		const currentPasswordInput = document.getElementById('current-password-input');

		showPasswordIcon.addEventListener('click', function () {
			if (currentPasswordInput.type === 'password') {
				currentPasswordInput.type = 'text';
				showPasswordIcon.src = 'img/hide-password.png';
			} else {
				currentPasswordInput.type = 'password';
				showPasswordIcon.src = 'img/show-password.png';
			}
		});
		const showPasswordIcon = document.getElementById('show-password-icon');
		const newPasswordInput = document.getElementById('new-password-input');

		showPasswordIcon.addEventListener('click', function () {
			if (newPasswordInput.type === 'password') {
				newPasswordInput.type = 'text';
				showPasswordIcon.src = 'img/hide-password.png';
			} else {
				newPasswordInput.type = 'password';
				showPasswordIcon.src = 'img/show-password.png';
			}
		});
		const showPasswordIcon = document.getElementById('show-password-icon');
		const confirmPasswordInput = document.getElementById('confirm-password-input');

		showPasswordIcon.addEventListener('click', function () {
			if (confirmPasswordInput.type === 'password') {
				confirmPasswordInput.type = 'text';
				showPasswordIcon.src = 'img/hide-password.png';
			} else {
				confirmPasswordInput.type = 'password';
				showPasswordIcon.src = 'img/show-password.png';
			}
		});