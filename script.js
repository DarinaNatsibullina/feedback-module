// Функция для открытия/закрытия формы
document.getElementById("toggleForm").addEventListener("click", function () {
    var formContainer = document.getElementById("formContainer");
    formContainer.style.display = formContainer.style.display === "block" ? "none" : "block";
});

// Закрытие формы по клику на крестик
document.getElementById("closeForm").addEventListener("click", function () {
    document.getElementById("formContainer").style.display = "none";
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

// Проверка максимального веса файла
document.getElementById('file').addEventListener('change', function() {
    var file = this.files[0]; // Получаем файл
    var maxSize = 5 * 1024 * 1024; // 5 MB в байтах

    if (file && file.size > maxSize) {
        alert('Размер файла превышает 5 МБ. Пожалуйста, выберите файл меньшего размера.');
        this.value = ''; // Очищаем поле загрузки
    }
});
    
// Основная обработка формы
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

    // AJAX отправка формы
    var formData = new FormData(this); // Собираем данные формы

    // Отправка данных через AJAX
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "submit.php", true);
    xhr.onload = function () {
        var responseContainer = document.createElement("div"); // Контейнер для сообщения
        responseContainer.classList.add("message");

        if (xhr.status === 200) {
            // Если ответ успешный
            responseContainer.innerHTML = `
                <h3 style="color:green;font-size:40px">&#128077; Отправлено!</h3>
                <p style="color: #132041;font-size:20px">Для срочных вопросов к приёмной комиссии, вы можете связаться с нами через WhatsApp <a href="https://wa.me/74950330363"><img src="img/whatsapp.svg" alt="WhatsApp" class="iconc" style="filter: invert(30%) sepia(15%) saturate(500%) hue-rotate(200deg) brightness(100%) contrast(100%);"></a></p>
                <p style="color: #132041;font-size:20px">Присоединяйтесь к нашим социальным сетям и погружайтесь в увлекательную университетскую жизнь!</p>
                <div class="iconsc">
                    <a href="https://vk.com/mosvitte"><img src="img/vk.svg" alt="VK" class="iconc" style="filter: invert(30%) sepia(15%) saturate(500%) hue-rotate(200deg) brightness(100%) contrast(100%);"></a>
                    <a href="https://t.me/mosvitte"><img src="img/tg.svg" alt="Telegram" class="iconc" style="filter: invert(30%) sepia(15%) saturate(500%) hue-rotate(200deg) brightness(100%) contrast(100%);"></a>
                    <a href="https://www.youtube.com/@muiv_moscow"><img src="img/yt.svg" alt="YouTube" class="iconc" style="filter: invert(30%) sepia(15%) saturate(500%) hue-rotate(200deg) brightness(100%) contrast(100%);"></a>
                </div>
            `;
        } else {
            // Если произошла ошибка
            responseContainer.innerHTML = `
                <h3 style="color:red;font-size:40px">&#128078; Ошибка!</h3>
                <p style="color: #132041;font-size:20px">Для срочных вопросов к приёмной комиссии, вы можете связаться с нами через WhatsApp <a href="https://wa.me/74950330363"><img src="img/whatsapp.svg" alt="WhatsApp" class="iconc" style="filter: invert(30%) sepia(15%) saturate(500%) hue-rotate(200deg) brightness(100%) contrast(100%);"></a></p>
                <p style="color: #132041;font-size:20px">Присоединяйтесь к нашим социальным сетям и погружайтесь в увлекательную университетскую жизнь!</p>
                <div class="iconsc">
                    <a href="https://vk.com/mosvitte"><img src="img/vk.svg" alt="VK" class="iconc" style="filter: invert(30%) sepia(15%) saturate(500%) hue-rotate(200deg) brightness(100%) contrast(100%);"></a>
                    <a href="https://t.me/mosvitte"><img src="img/tg.svg" alt="Telegram" class="iconc" style="filter: invert(30%) sepia(15%) saturate(500%) hue-rotate(200deg) brightness(100%) contrast(100%);"></a>
                    <a href="https://www.youtube.com/@muiv_moscow"><img src="img/yt.svg" alt="YouTube" class="iconc" style="filter: invert(30%) sepia(15%) saturate(500%) hue-rotate(200deg) brightness(100%) contrast(100%);"></a>
                </div>
            `;
        }

        // Очищаем форму и показываем сообщение
        document.getElementById("contactForm").reset();
        document.getElementById("formContainer").innerHTML = "";
        document.getElementById("formContainer").appendChild(responseContainer);
    };

    xhr.send(formData); // Отправляем данные формы
});
