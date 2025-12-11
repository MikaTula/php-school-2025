<?php

    declare(strict_types=1);

    namespace App\Utils;

    class DataUtils
    {
        public static function readDataSource(string $fileName): array
        {
            $ourData = file_get_contents($fileName);
            return json_decode($ourData, true);
        }

        public static function writeDataSource(string $fileName, mixed $data)
        {
            // make backup
            self::makeBackUp($fileName);

            file_put_contents($fileName, json_encode($data));
        }

        private static function makeBackUp(string $fileName): void
        {
            file_put_contents($fileName.'.'.date("Ymd-His"), file_get_contents($fileName));
        }
    }
