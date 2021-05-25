// кнопки Фильтр, Добавить и каждая из задач
const btns = document.querySelectorAll('#btn');

// div, включающий в себя все модальные окна
const modalOverlay = document.querySelector('.modal-overlay');

// сами модальные окна
const modals = document.querySelectorAll('.modal');

// модальное окно редактирования задачи
const parent = document.querySelector('[data-target="editTaskMenu"]');

// формы добавления и редактирования задачи
const form = parent.querySelector('#my_form'); 

// для каждой кнопки el из btns
btns.forEach((el) => {
	// отслеживаем клик по ней
	el.addEventListener('click', (e) => {
		// если это кнопка задачи (нажимаем, если хотим отредактировать задачу), то выполняем AJAX скрипт
		if (el.classList.contains('input__inLine')) {
			$.ajax({
				// создаём URL таким, чтобы можно было удобно его обработать в Router.php
				url: "task/edit/" + event.target.dataset.id,

				// методом запроса будет POST
				type: 'POST',

				// отправляемые данные не указываю, т.к. они указаны в url
				data: {},

				// сообщаем скрипту, что форматом полученных данных будет JSON 
				dataType: "json",
			})
			// если запрос выполнен успешно, то мы получили data - инфу в формате JSON о задаче, по которой мы кликнули
			.done(function(data) {
				// меняем action формы опять же так, чтобы было удобно его обработать в Router.php
				form.setAttribute('action', '/task/update/' + data.id);

				// для каждой записи в data мы подставляем в нужное поле формы нужное значения и выбираем selected в <select>
				$.each(data, function(kk, vv) {
				    console.log('name = ' + kk + '. value = ' + vv);
					parent.querySelector(`[name="`+kk+`"]`).value = vv;
					parent.querySelector(`[name="`+kk+`"]`).setAttribute('selected', '');
				});
			})
			// на случай непредвиденных обстоятельств подготовили ошибку
			.fail(function() {
				alert('error');
			})
		}
		// записываем в переменную значение аттрибута data-shot (это аттрибут, который указан во всех btns)
		let shot = e.currentTarget.getAttribute('data-shot');

		// убираем "видимость" из классов всех модальных окон
		modals.forEach((el) => {
			el.classList.remove('modal--visible');
		});

		// и добавляем "видимость" в те модальные окна, у которых аттрибут data-target равен тому, что и у нажатой кнопки
		document.querySelector(`[data-target="${shot}"]`).classList.add('modal--visible');

		// оверлей, соответственно, тоже делаем видимым
		modalOverlay.classList.add('modal-overlay--visible');
	});
});

// при клике на оверлей, снимается "видимость" с самого оверлея и с модальных окон
modalOverlay.addEventListener('click', (e) => {
	if (e.target == modalOverlay) {
		modalOverlay.classList.remove('modal-overlay--visible');
		modals.forEach((el) => {
			el.classList.remove('modal--visible');
		});
	}
});