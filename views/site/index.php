<?php include ROOT . '/views/layouts/header.php'; ?>

<div class="container container_shadow">
	<div class="block">
		<div class="card card_content">
			<span class="card__title">
				Список задач
			</span>
			<p class="two__buttons">
                <button id="btn" class="btn btn_add" data-shot="filterMenu">Фильтр</button>
                <button id="btn" class="btn btn_add" data-shot="addMenu">Добавить</button>
			</p>
            <?php foreach($tasksList as $task) : ?>
                <table>
                    <thead>
                        <button id="btn" 
                            class="input__inLine"
                            style="color: <?= ($task['status'] == 'Выполнено') ? 'green' : ((date('Y-m-d') > date($task['end_date'])) ? 'red' : 'darkgrey'); ?>"
                            data-shot="editTaskMenu"
                            data-id="<?=$task['id'] ?>">
                            <?= $task['title'] ?>
                        </button>
                    </thead>

                     <div class="parent">
                        <div class="div1">Приоритет: <?=$task['priority'] ?></div>
                        <div class="div1">Статус: <?=$task['status'] ?></div>
                        <div class="div1">Срок: <?=date("d.m.Y", strtotime($task['end_date'])) ?></div>
                    </div>
                </table>
            <?php endforeach; ?>
		</div>
	</div>
</div>
<!--  -->
<div class="modal-overlay">
    <div class="modal sortMenu" data-target="filterMenu">
        <div>
        <form action="/" method="POST">
            <h2>Фильтр</h2>
            <select class="input__inLine" name="date_filter">
                <option hidden value="">Фильтр по сроку</option>
                <option value="today">Сегодня</option>
                <option value="week">Ближайшая неделя</option>
                <option value="all">Всё время</option>
            </select>
            <?php if ($isDirector) : ?>
                <select class="input__inLine" name="responsible_filter">
                    <option hidden value="">Ответственное лицо</option>
                        <option value="<?=$_SESSION['user'] ?>">Мои задачи</option>
                    <?php foreach ($employersList as $responsible) : ?>
                        <option value="<?=$responsible['id'] ?>"><?=$responsible['first_name'] . " " . $responsible['second_name']  ?></option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>
            <input type="submit" name="filter" value="Применить" class="btn btn-default"/>
        </form>
        </div>
    </div>
    
    <div class="modal taskEditor" data-target="addMenu">
        <div>
            <h2>Редактор задач</h2>
            <form id="my_form" action="/task/create/" method="POST">
                <input name="id" hidden/><input name="creator" hidden/>
                <input class="input__inLine" type="text" name="title" placeholder="Заголовок задачи" required value="<?= isset($title) ? $title : '' ?>"/>
                <input class="input__inLine" type="text" name="description" placeholder="Описание" required value="<?= isset($description) ? $description  : '' ?>"/>
                <input class="input__inLine" type="date" name="end_date" placeholder="Дата окончания" required value="<?= isset($endDate) ? $endDate : '' ?>"/>
                <input class="input__inLine" type="date" name="create_date" placeholder="Дата окончания" required disabled value="<?= isset($endDate) ? $endDate : '' ?>"/>
                <input class="input__inLine" type="date" name="update_date" placeholder="Дата окончания" required disabled value="<?= isset($endDate) ? $endDate : '' ?>"/>
                
                <!-- <input class="input__inLine" type="number" max="3" min="1" name="priority" placeholder="Приоритет задачи" required value="<?= isset($priority) ? $priority  : '' ?>"/> -->
                <select class="input__inLine" name="priority" required>
                    <option hidden value="">Приоритет</option>
                    <option value="high">Высокий приоритет</option>
                    <option value="medium">Средний приоритет</option>
                    <option value="low">Низкий приоритет</option>
                </select>


                    <select class="input__inLine" name="status" required>
                        <option hidden value="">Статус задачи</option>
                        <option value="to_be_executed">К выполнению</option>
                        <option value="in_progress">Выполняется</option>
                        <option value="completed">Выполнено</option>
                        <option value="canceled">Отменено</option>
                    </select>
                <?php if ($isDirector) : ?>
                    <select class="input__inLine" name="responsible" required>
                        <option hidden value="">Ответственное лицо</option>
                        <option value="<?=$_SESSION['user'] ?>">Это моя задача</option>
                        <?php foreach ($employersList as $responsible) : ?>
                            <option value="<?=$responsible['id'] ?>"><?=$responsible['first_name'] . " " . $responsible['second_name']  ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>
                <input type="submit" name="submit" value="Создать" class="btn btn-default"/>
            </form>
        </div>
    </div>

    <div class="modal taskEditor" data-target="editTaskMenu">
        <div>
            <h2>Редактор задач</h2>
            <form id="my_form" action="/task/update/" method="POST">

                <input name="id" hidden/><input name="creator" hidden/>
                <input class="input__inLine" type="text" name="title" placeholder="Заголовок задачи" <?= (!$isDirector) ? "disabled" : '' ?> required value="<?= isset($title) ? $title : '' ?>"/>
                <input class="input__inLine" type="text" name="description" placeholder="Описание" <?= (!$isDirector) ? 'disabled' : '' ?> required value="<?= isset($description) ? $description  : '' ?>"/>
                <input class="input__inLine" type="date" name="end_date" placeholder="Дата окончания" <?= (!$isDirector) ? 'disabled' : '' ?> required value="<?= isset($endDate) ? $endDate : '' ?>"/>
                <input class="input__inLine" type="date" name="create_date" placeholder="Дата окончания" required disabled value="<?= isset($endDate) ? $endDate : '' ?>"/>
                <input class="input__inLine" type="date" name="update_date" placeholder="Дата окончания" required disabled value="<?= isset($endDate) ? $endDate : '' ?>"/>
                <select class="input__inLine" name="priority" <?= (!$isDirector) ? "disabled" : '' ?> required>
                    <option hidden value="">Приоритет</option>
                    <option value="high">Высокий приоритет</option>
                    <option value="medium">Средний приоритет</option>
                    <option value="low">Низкий приоритет</option>
                </select>

                <select class="input__inLine" name="status" required>
                    <option hidden value="">Статус задачи</option>
                    <option value="to_be_executed">К выполнению</option>
                    <option value="in_progress">Выполняется</option>
                    <option value="completed">Выполнено</option>
                    <option value="canceled">Отменено</option>
                </select>


                <select class="input__inLine" name="responsible" required <?= (!$isDirector) ? 'disabled' : '' ?>>
                        <option hidden value="">Ответственное лицо</option>
                        <option value="<?=$_SESSION['user'] ?>">Это моя задача</option>
                    <?php foreach ($employersList as $responsible) : ?>
                        <?= '123' ?>
                        <option name="responsible" value="<?=$responsible['id'] ?>"><?=$responsible['first_name'] . " " . $responsible['second_name']  ?></option>
                    <?php endforeach; ?>
                </select>
                <?php                     
                ?>
                <input type="submit" name="submit" value="Обновить" class="btn btn-default"/>
            </form>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
<script src="/resources/js/modal.js"></script>

<?php include ROOT . '/views/layouts/footer.php'; ?>