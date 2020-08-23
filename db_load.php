<?php
/**
 * Zwraca pojedyncze zapytanie gotowe do wywołania
 *
 * @param string $filePath Ścieżka do pliku
 *
 * @return \Generator<string>
 */
function fetchQueries(string $filePath): \Generator
{
    $query = '';
    $file = fopen($filePath, 'r');
    while (false !== ($line = fgets($file))) {
        if ('--' === substr($line, 0, 2) || '' == $line) {
            continue;
        }

        $query .= $line;
        if (';' === substr(trim($line), -1, 1)) {
            yield $query;

            $query = '';
        }
    }

    fclose($file);
}

/**
 * Ładuje backup z pliku .sql
 *
 * @param string $filePath             Ścieżka do pliku
 * @param int    $numberOfBatchInQuery Liczba "paczek" w jednym zapytaniu
 *
 * @return bool
 */
function loadBackup(string $filePath, int $numberOfBatchInQuery = 10): bool
{
    $i = 0;
    try {
		// $db = new PDO(/*...*/);
        $db->beginTransaction();
        foreach (fetchQueries($filePath) as $query) {
            $db->query($query);
            if (++$i === $numberOfBatchInQuery) {
                $i = 0;
                $db->commit();
                $db->beginTransaction();
            }
        }

        $db->commit();
    } catch (\Exception $ex) {
		echo $ex;
        if ($db->inTransaction()) {
            $db->rollBack();
        }

        return false;
    }

    return true;
}

// Użycie
loadBackup('sciezka_do_pliku.sql');
