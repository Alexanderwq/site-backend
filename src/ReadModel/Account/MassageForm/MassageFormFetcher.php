<?php

namespace App\ReadModel\Account\MassageForm;

use Doctrine\DBAL\Connection;
use DomainException;

readonly class MassageFormFetcher
{
    public function __construct(
        private Connection $connection,
    )
    {
    }

    public function getEditInfo(string $formId, string $userId): array
    {
        $form = $this->connection->fetchAssociative(
            'SELECT
                id,
                name_value as name,
                phone_value as phone,
                description_value as description,
                date_of_birth as date_of_birth
             FROM account_massage_forms
             WHERE id = :id AND user_id = :userId',
            ['id' => $formId, 'userId' => $userId]
        );

        if (!$form) {
            throw new DomainException('Profile not found or access denied');
        }

        $pricesRaw = $this->connection->fetchAllAssociative(
            'SELECT location_value, duration_value, amount_value, day_time_value
             FROM account_massage_form_prices
             WHERE massage_form_id = :id',
            ['id' => $formId]
        );

        $price = [
            'home' => ['day' => ['oneHour' => 0, 'twoHour' => 0], 'night' => ['oneHour' => 0, 'twoHour' => 0]],
            'visit' => ['day' => ['oneHour' => 0, 'twoHour' => 0], 'night' => ['oneHour' => 0, 'twoHour' => 0]],
        ];

        foreach ($pricesRaw as $p) {
            $target = $p['location_value'] === 'home' ? 'home' : 'visit';
            $time = $p['day_time_value'] === 'day' ? 'day' : 'night';
            $hourKey = $p['duration_value'] === 1 ? 'oneHour' : 'twoHour';
            $price[$target][$time][$hourKey] = $p['amount_value'];
        }

        $additionalOptionsRaw = $this->connection->fetchAllAssociative(
            'SELECT
                additional_option_id as id,
                price_value as price,
                description_value as description
             FROM account_massage_form_additional_options
             WHERE massage_form_id = :id',
            ['id' => $formId]
        );

        $photosRaw = $this->connection->fetchAllAssociative(
            'SELECT id, name as name, is_main, is_preview as is_background
             FROM account_massage_form_photos
             WHERE massage_form_id = :id',
            ['id' => $formId]
        );

        $photos = array_map(function ($p) use ($formId) {
            $extensionPhoto = pathinfo($p['name'], PATHINFO_EXTENSION);
            $baseNamePhoto = pathinfo($p['name'], PATHINFO_FILENAME);
            $formattedName = $baseNamePhoto . '_800x800.' . $extensionPhoto;
            return [
                'id' => $p['id'],
                'idProfile' => $formId,
                'name' => $formattedName,
                'nameSmall' => $p['name'],
                'is_main' => (bool)$p['is_main'],
                'is_background' => (bool)$p['is_background'],
            ];
        }, $photosRaw);

        $metroRaw = $this->connection->fetchAllAssociative(
            'SELECT m.id as metro_id, m.name as metro_name
             FROM account_massage_form_metro_stations ms
             JOIN account_metro_stations m ON m.id = ms.metro_station_id
             WHERE ms.massage_form_id = :id',
            ['id' => $formId]
        );

        $metro = array_map(fn($m) => [
            'id' => $m['metro_id'],
            'profile_id' => $formId,
            'metro_id' => $m['metro_id'],
            'metro_name' => $m['metro_name'],
        ], $metroRaw);

        $districtRaw = $this->connection->fetchAllAssociative(
            'SELECT d.id as district_id, d.name
             FROM account_massage_form_districts md
             JOIN account_districts d ON d.id = md.district_id
             WHERE md.massage_form_id = :id',
            ['id' => $formId]
        );

        $districts = array_map(fn($d) => [
            'id' => $d['district_id'],
            'district_id' => $d['district_id'],
            'name' => $d['name'],
        ], $districtRaw);

        $dateTimestamp = strtotime($form['date_of_birth']);
        $dateOfBirth = [
            'day' => (int)date('d', $dateTimestamp),
            'month' => (int)date('m', $dateTimestamp),
            'year' => (int)date('Y', $dateTimestamp),
        ];

        return [
            'id' => $form['id'],
            'name' => $form['name'],
            'phone' => $form['phone'],
            'description' => $form['description'],
            'price' => $price,
            'additional_options' => $additionalOptionsRaw,
            'photos' => $photos,
            'dateOfBirth' => $dateOfBirth,
            'metro' => $metro,
            'districts' => $districts,
            'coords' => [],
            'social_links' => [
                'whatsapp' => '',
                'telegram' => '',
                'instagram' => '',
            ]
        ];
    }

    function listByUser(string $userId): array
    {
        $rows = $this->connection->createQueryBuilder()
            ->select(
                'f.*',
                'p.id AS photo_id',
                'p.name as photo_name',
                'p.is_main',
                'p.is_preview'
            )
            ->from('account_massage_forms', 'f')
            ->leftJoin(
                'f',
                'account_massage_form_photos',
                'p',
                'p.massage_form_id = f.id'
            )
            ->where('f.user_id = :user_id')
            ->setParameter('user_id', $userId)
            ->orderBy('f.id')
            ->fetchAllAssociative();

        $result = [];

        foreach ($rows as $row) {
            $formId = $row['id'];

            if (!isset($result[$formId])) {
                $result[$formId] = [
                    'id' => $row['id'],
                    'user_id' => $row['user_id'],
                    'name' => $row['name_value'],
                    'photos_small' => [],
                    'hasDescription' => strlen($row['description_value']) !== 0,
                    'hasMapMark' => !empty($row['coords']),
                    'photoVerified' => false,
                    'active' => !$row['disabled'],

                ];
            }

            if ($row['photo_id']) {
                $result[$formId]['photos_small'][] = $row['photo_name'];
            }
        }

        return array_values($result);
    }
}
