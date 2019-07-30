<?php

require_once __DIR__ . '/vendor/autoload.php';

/**
 * Демонстрационный пример получения данных через API НЧТС.
 * @version 1.0
 * @author Заец Алексей Сергеевич <ZaecAS@nchts.tatenergo.ru>
 */

const STATUS_NORMAL = 200;

$username = 'demo';
$password = 'demopassword';
$proxy = false;
$timeout = 20.0;

/*
 * По умолчанию прокси отключён, но Вы можете указать свой в формате:
 *   'proxy' => [
 *       'http'  => 'http://proxy.company.org:3128',
 *   ]
 */

/*
 * Создаём клиент с заранее определённым настройками
 */
$client = (new App\HttpClient())->create($proxy, $timeout);

/*
 * Получение токена
 */
[$statusToken, $accessToken] = (new App\AccessToken())->get($client, $username, $password);
echo ((new \DateTime())->format('d.m.Y H:i:s')) . " Токен получен" . PHP_EOL;
if ($statusToken !== STATUS_NORMAL) {
    echo 'Error: ' . $statusToken . ' ' . $accessToken . PHP_EOL;
    return;
}

/*
 * Получение списка всех Узлов учёта
 * @var array $deviceList Массив со списком
 */
[$statusDeviceList, $deviceList] = (new App\Device())->getList($client, $accessToken);
echo ((new \DateTime())->format('d.m.Y H:i:s')) . " Список узлов учёта получен" . PHP_EOL;
if ($statusDeviceList !== STATUS_NORMAL) {
    echo 'Error: ' . $statusDeviceList . ' ' . $deviceList . PHP_EOL;
    return;
}
if (count($deviceList) === 0) {
    echo 'Не удалось загрузить список Узлов учёта' . PHP_EOL;
    return;
}

/*
 * Для примера взят Узел учёта: 12/10 (id = 100644)
 */
$deviceId = 100644;
$archive = new \App\Archive();
/*
 * Получение диапазона архива для прибора учёта, если это необходимо
 */
[$statusRange, $range] = $archive->range($client, $accessToken, $deviceId);
echo ((new \DateTime())->format('d.m.Y H:i:s')) . " Диапазон архива получен" . PHP_EOL;
if ($statusRange !== STATUS_NORMAL) {
    echo 'Error: ' . $statusRange . ' ' . $range . PHP_EOL;
    return;
}
echo "Глубина архива: $range->start, последние данные: $range->end" . PHP_EOL;

/*
 * Получение архива Узла учёта
 * Для примера забираем последние 3 дня
 */
$end = (new \DateTimeImmutable());
$start = $end->modify('-3 days');
[$statusColdWater, $dataColdWater] = $archive->loadColdWater($client,
    $accessToken, $deviceId, $start->format('d.m.Y'), $end->format('d.m.Y'));
echo ((new \DateTime())->format('d.m.Y H:i:s')) . " Архив получен" . PHP_EOL;
if ($statusColdWater !== STATUS_NORMAL) {
    echo 'Error: ' . $statusColdWater . ' ' . $dataColdWater . PHP_EOL;
    return;
}

/*
 * Выводим список(пример)
 * Доступно: time, hours, v5, v6, t5, t6, p5, p6
 */
$i = 0;
echo "# \t Наработка \t Дата \t V5 \t V6" . PHP_EOL;
foreach ($dataColdWater as $row) {
    echo ++$i . "\t $row->hours \t {$row->time} \t {$row->v5} \t {$row->v6}" . PHP_EOL;
}
