document.getElementById('toggleForm').addEventListener('click', function () {
    const formContainer = document.getElementById('formContainer');
    formContainer.style.display = formContainer.style.display === 'block' ? 'none' : 'block';
});

document.getElementById('closeForm').addEventListener('click', function () {
    const formContainer = document.getElementById('formContainer');
    formContainer.style.display = 'none';
});

document.getElementById('contactForm').addEventListener('submit', function (event) {
    event.preventDefault();
    
    const name = document.getElementById('name').value;
    const surname = document.getElementById('surname').value; // Добавлено поле фамилии
    const email = document.getElementById('email').value;
    const message = document.getElementById('message').value;
    const subject = document.getElementById('subject').value; // Получаем выбранную тему
    const phone = document.getElementById('phone').value; // Получаем номер телефона (если есть)

    // Проверка заполнения обязательных полей
    if (name === '' || surname === '' || email === '' || message === '' || subject === '') {
        alert('Пожалуйста, заполните все обязательные поля.');
        return;
    }

    // Простая валидация формата email
    const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    if (!emailPattern.test(email)) {
        alert('Пожалуйста, введите корректный адрес электронной почты.');
        return;
    }

    // Если выбрана тема "Заявка на поступление", проверяем телефон
    if (subject === 'admission' && phone === '') {
        alert('Пожалуйста, укажите номер телефона при выборе темы "Заявка на поступление".');
        return;
    }

    this.submit(); // Отправка формы, если все проверки пройдены
});

// Обработчик изменения темы обращения
document.getElementById('subject').addEventListener('change', function() {
    const phoneGroup = document.getElementById('phoneGroup');
    if (this.value === 'admission') {
        phoneGroup.style.display = 'block'; // Показываем поле для телефона
    } else {
        phoneGroup.style.display = 'none'; // Скрываем поле для телефона
    }
});