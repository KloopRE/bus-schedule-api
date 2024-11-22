<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление маршрутами и расписаниями</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        form {
            margin-bottom: 20px;
            border: 1px solid #ccc;
            padding: 15px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input, select, button {
            margin-bottom: 15px;
            width: 100%;
            padding: 4px;
            font-size: 14px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Управление маршрутами и расписаниями</h1>

    <form id="add-route-form" method="POST" action="/routes">
        <h2>Добавить маршрут</h2>

        <label for="route-name">Название маршрута:</label>
        <input type="text" id="route-name" name="name" placeholder="Введите название маршрута" required>

        <label for="route-direction">Направление маршрута.:</label>
        <input type="text" id="route-direction" name="direction" placeholder="Введите направление" required>

        <button type="submit">Добавить маршрут</button>
    </form>

    <form id="add-stop-form" method="POST" action="/stops">
        <h2>Добавить остановку</h2>
        <label for="stop-name">Название остановки:</label>
        <input type="text" id="stop-name" name="name" placeholder="Введите название остановки" required>

        <label for="stop-location">Местоположение:</label>
        <input type="text" id="stop-location" name="location" placeholder="Введите местоположение" required>

        <button type="submit">Добавить остановку</button>
    </form>

    <form id="add-route-stop-form" method="POST" action="/route-stops">
        <h2>Добавить остановки к маршруту</h2>
        <label for="route-id-stop">Маршрут:</label>
        <select id="route-id-stop" name="route_id" required>
            <option value="" disabled selected>Выберите маршрут</option>
        </select>

        <label for="stop-id-order">Остановка:</label>
        <select id="stop-id-order" name="stop_id" required>
            <option value="" disabled selected>Выберите остановку</option>
        </select>

        <label for="stop-order">Порядок следования:</label>
        <input type="number" id="stop-order" name="order" placeholder="Введите порядок" required>

        <button type="submit">Добавить остановку к маршруту</button>
    </form>

    <form id="add-schedule-form" method="POST" action="/schedule">
        <h2>Добавить расписание</h2>
        <label for="route-id">Маршрут:</label>
        <select id="route-id" name="route_id" required>
            <!-- Эти опции должны заполняться динамически -->
            <option value="" disabled selected>Выберите маршрут</option>
        </select>

        <label for="stop-id">Остановка:</label>
        <select id="stop-id" name="stop_id" required>
            <!-- Эти опции должны заполняться динамически -->
            <option value="" disabled selected>Выберите остановку</option>
        </select>

        <label for="arrival-time">Время прибытия:</label>
        <input type="time" id="arrival-time" name="arrival_time" required>

        <label for="departure-time">Время отправления:</label>
        <input type="time" id="departure-time" name="departure_time" required>

        <button type="submit">Добавить расписание</button>
    </form>

    <h2>Поиск автобусов</h2>
    <form id="find-bus-form">
        <label for="from-stop">От остановки:</label>
        <select id="from-stop" name="from" required>
            <!-- Эти опции будут заполняться динамически -->
            <option value="" disabled selected>Выберите исходную остановку</option>
        </select>

        <label for="to-stop">До остановки:</label>
        <select id="to-stop" name="to" required>
            <!-- Эти опции будут заполняться динамически -->
            <option value="" disabled selected>Выберите конечную остановку</option>
        </select>

        <button type="submit">Найти автобусы</button>
    </form>

    <h3>Результаты:</h3>
    <div id="bus-results"></div>


    <script>

    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    document.getElementById('add-route-form').addEventListener('submit', async (event) => {
        event.preventDefault();
        const formData = new FormData(event.target);
        const data = Object.fromEntries(formData.entries());

        try {
            const response = await fetch('/routes', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(data),
            });

            if (response.ok) {
                alert('Маршрут успешно добавлен!');
                event.target.reset();
            } else {
                const error = await response.json();
                alert(`Ошибка: ${error.message}`);
            }
        } catch (err) {
            console.error('Ошибка добавления маршрута:', err);
            alert('Не удалось добавить маршрут. Проверьте соединение с сервером.');
        }
    });

    document.getElementById('add-stop-form').addEventListener('submit', async (event) => {
        event.preventDefault();
        const formData = new FormData(event.target);
        const data = Object.fromEntries(formData.entries());

        try {
            const response = await fetch('/stops', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(data),
            });

            if (response.ok) {
                alert('Остановка успешно добавлена!');
                event.target.reset();
            } else {
                const error = await response.json();
                alert(`Ошибка: ${error.message}`);
            }
        } catch (err) {
            console.error('Ошибка добавления остановки:', err);
            alert('Не удалось добавить остановку. Проверьте соединение с сервером.');
        }
    });

    document.getElementById('add-schedule-form').addEventListener('submit', async (event) => {
        event.preventDefault();
        const formData = new FormData(event.target);
        const data = Object.fromEntries(formData.entries());

        data.arrival_time = data.arrival_time + ":00";
        data.departure_time = data.departure_time + ":00";

        try {
            const response = await fetch('/schedule', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(data),
            });

            if (response.ok) {
                alert('Расписание успешно добавлено!');
                event.target.reset();
            } else {
                const error = await response.json();
                alert(`Ошибка: ${error.message}`);
            }
        } catch (err) {
            console.error('Ошибка добавления расписания:', err);
            alert('Не удалось добавить расписание. Проверьте соединение с сервером.');
        }
    });

    async function loadRoutes(element) {
        try {
            const response = await fetch('/routes', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                }
            });

            if (!response.ok) throw new Error('Ошибка получения маршрутов');

            const routes = await response.json();
            const routeSelect = document.getElementById(element);

            routeSelect.innerHTML = '<option value="" disabled selected>Выберите маршрут</option>';

            routes.forEach(route => {
                const option = document.createElement('option');
                option.value = route.id;
                option.textContent = route.name;
                routeSelect.appendChild(option);
            });

            console.log('Маршруты загружены');
        } catch (error) {
            console.error('Ошибка загрузки маршрутов:', error);
        }
    }

    async function loadStops(element) {
        try {
            const response = await fetch('/stops', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                }
            });

            if (!response.ok) throw new Error('Ошибка получения остановок');

            const stops = await response.json();
            const stopSelect = document.getElementById(element);

            stopSelect.innerHTML = '<option value="" disabled selected>Выберите остановку</option>';

            stops.forEach(stop => {
                const option = document.createElement('option');
                option.value = stop.id;
                option.textContent = stop.name;
                stopSelect.appendChild(option);
            });

            console.log('Остановки загружены');
        } catch (error) {
            console.error('Ошибка загрузки остановок:', error);
        }
    }

    loadRoutes('route-id');
    loadStops('stop-id');

    document.getElementById('add-route-stop-form').addEventListener('submit', async (event) => {
        event.preventDefault();
        const formData = new FormData(event.target);
        const data = Object.fromEntries(formData.entries());

        try {
            const response = await fetch('/route-stops', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify(data),
            });

            if (response.ok) {
                alert('Остановка успешно добавлена к маршруту!');
                event.target.reset();
            } else {
                const error = await response.json();
                alert(`Ошибка: ${error.message}`);
            }
        } catch (err) {
            console.error('Ошибка добавления остановки к маршруту:', err);
            alert('Не удалось добавить остановку. Проверьте соединение с сервером.');
        }
    });

    loadRoutes('route-id-stop');
    loadStops('stop-id-order');

    document.getElementById('find-bus-form').addEventListener('submit', async (event) => {
        event.preventDefault();

        const fromStopId = document.getElementById('from-stop').value;
        const toStopId = document.getElementById('to-stop').value;

        if (!fromStopId || !toStopId) {
            alert('Пожалуйста, выберите обе остановки.');
            return;
        }

        try {
            const response = await fetch(`/api/find-bus?from=${fromStopId}&to=${toStopId}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                }
            });

            if (!response.ok) {
                const errorData = await response.json();
                console.error('Ошибка ответа от сервера:', errorData);
                throw new Error('Ошибка на сервере: ' + errorData.message);
            }

            const data = await response.json();
            displayBusResults(data);
        } catch (error) {
            console.error('Ошибка при поиске автобусов:', error);
            alert('Не удалось найти автобусы. Проверьте соединение с сервером. ' + error.message);
        }
    });

    function displayBusResults(data) {
        const resultsDiv = document.getElementById('bus-results');
        resultsDiv.innerHTML = '';

        if (!data.data || data.data.length === 0) {
            resultsDiv.innerHTML = '<p>Не найдено автобусов между указанными остановками.</p>';
            return;
        }

        data.data.forEach(route => {
            const routeDiv = document.createElement('div');
            routeDiv.classList.add('route-result');
            routeDiv.innerHTML = `
                <h4>Маршрут: ${route.route_name}</h4> <!-- Заменить на реальное поле маршрута -->
                <p>От: ${route.from_stop} <br> До: ${route.to_stop}</p> <!-- Заменить на реальные поля остановок -->
                <p>Ближайшие прибытия: ${route.arrival_times.join(', ')}</p> <!-- Пример с ближайшими временем -->
            `;
            resultsDiv.appendChild(routeDiv);
        });
    }


    loadStops('from-stop');
    loadStops('to-stop');

    </script>

</body>
</html>
