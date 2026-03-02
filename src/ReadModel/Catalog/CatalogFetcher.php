<?php

namespace App\ReadModel\Catalog;

use App\Controller\Api\Catalog\MassageFormCardView;
use App\Controller\Api\Catalog\PhotoView;
use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Connection;

readonly class CatalogFetcher
{
    private const int FEED_LIMIT_COUNT_CARDS = 20;

    public function __construct(
        private Connection $connection,
    ) {
    }

    public function fetchFeed(): array
    {
        $qb = $this->connection->createQueryBuilder();

        $qb
            ->select(
                'p.id AS id',
                'p.name_value AS name',
                'MIN(pr.amount_value) AS price_one_hour',
                "STRING_AGG(DISTINCT m.name, ', ') AS metro_stations"
            )
            ->from('account_massage_forms', 'p')
            ->leftJoin(
                'p',
                'account_massage_form_prices',
                'pr',
                'pr.massage_form_id = p.id AND pr.duration_value = 1'
            )
            ->innerJoin(
                'p',
                'account_massage_form_photos',
                'pp',
                'pp.massage_form_id = p.id AND pp.is_main = false'
            )
            ->join(
                'p',
                'account_massage_form_metro_stations',
                'pm',
                'pm.massage_form_id = p.id'
            )
            ->join(
                'pm',
                'account_metro_stations',
                'm',
                'm.id = pm.metro_station_id'
            )
            ->andWhere('p.disabled = false')
            ->groupBy('p.id')
            ->setMaxResults(self::FEED_LIMIT_COUNT_CARDS)
            ->orderBy('RANDOM()');

        $forms = $qb->executeQuery()->fetchAllAssociative();

        $cards = [];

        foreach ($forms as $form) {
            $cards[] = new MassageFormCardView(
                $form['id'],
                $form['name'],
                $form['price_one_hour'] ?? null,
                $form['metro_stations'],
            );
        }

        $photos = $this->getPhotos(array_map(fn (MassageFormCardView $card) => $card->id, $cards));

        foreach ($cards as $card) {
            $card->photos = array_values(array_filter(
                $photos,
                fn (PhotoView $photo) => $photo->massageFormId === $card->id && !$photo->isMain
            ));
        }

        return $cards;
    }

    private function getPhotos(array $massageFormIds): array
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('*')
            ->from('account_massage_form_photos')
            ->where('massage_form_id IN (:ids)')
            ->setParameter('ids', $massageFormIds, ArrayParameterType::STRING);
        $photos = $qb->fetchAllAssociative();

        return array_map(fn ($photo) => new PhotoView(
            $photo['id'],
            $photo['name'],
            $photo['massage_form_id'],
            $photo['is_main'],
            $photo['is_preview'],
        ), $photos);
    }
}
