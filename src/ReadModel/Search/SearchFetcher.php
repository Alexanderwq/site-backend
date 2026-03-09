<?php

namespace App\ReadModel\Search;

use App\ReadModel\Catalog\PhotoView;
use App\Service\Paginator\PaginationResult;
use App\Service\Paginator\Paginator;
use DateTimeImmutable;
use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Connection;

class SearchFetcher
{
    private const int LIMIT_IN_PAGE = 10;

    public function __construct(
        private readonly Connection $connection,
        private readonly Paginator  $paginator,
    ) {
    }

    public function fetch(int $page, Filter $filter): PaginationResult
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->select('p.*')
            ->from('account_massage_forms', 'p')
            ->leftJoin('p', 'account_massage_form_prices', 'pr1', "pr1.massage_form_id = p.id AND pr1.duration_value = 1 AND pr1.location_value = 'home'")
            ->leftJoin('p', 'account_massage_form_prices', 'pr2', "pr2.massage_form_id = p.id AND pr2.duration_value = 2 AND pr2.location_value = 'home'")
            ->leftJoin(
                 'p',
                 'account_massage_form_metro_stations',
                 'ms',
                 'ms.massage_form_id = p.id'
             )
            ->leftJoin(
                'p',
                'account_massage_form_districts',
                'd',
                'd.massage_form_id = p.id'
            )
            ->leftJoin(
                'p',
                'account_massage_form_additional_options',
                'ao',
                'ao.massage_form_id = p.id'
            )
            ->andWhere('p.disabled = false')
            ->groupBy('p.id');

        if ($filter->name) {
            $qb->andWhere('p.name_value LIKE :name')
                ->setParameter('name', '%' . $filter->name . '%');
        }

        if ($filter->phone) {
            $qb->andWhere('p.phone_value LIKE :phone')
                ->setParameter('phone', '%' . $filter->phone . '%');
        }

        if ($filter->experience) {
            $qb->andWhere('p.experience_value >= :experience')
                ->setParameter('experience', $filter->experience);
        }

        if ($filter->minAge) {
            $minBirthDate = (new DateTimeImmutable())
                ->modify('-' . $filter->minAge . ' years')
                ->format('Y-m-d');

            $qb->andWhere('p.date_of_birth <= :minBirthDate')
                ->setParameter('minBirthDate', $minBirthDate);
        }

        if ($filter->maxAge) {
            $maxDateBirth = (new DateTimeImmutable())
                ->modify('-' . $filter->maxAge . ' years')
                ->format('Y-m-d');

            $qb->andWhere('p.date_of_birth >= :maxBirthDate')
                ->setParameter('maxBirthDate', $maxDateBirth);
        }

        if ($filter->minPriceOneHourHome) {
            $qb->andWhere('pr1.amount_value >= :minPriceOneHourHome')
                ->setParameter('minPriceOneHourHome', $filter->minPriceOneHourHome);
        }

        if ($filter->maxPriceOneHourHome) {
            $qb->andWhere('pr1.amount_value <= :maxPriceOneHourHome')
                ->setParameter('maxPriceOneHourHome', $filter->maxPriceOneHourHome);
        }

        if ($filter->minPriceTwoHourHome) {
            $qb->andWhere('pr2.amount_value >= :minPriceTwoHourHome')
                ->setParameter('minPriceTwoHourHome', $filter->minPriceTwoHourHome);
        }

        if ($filter->maxPriceTwoHourHome) {
            $qb->andWhere('pr2.amount_value <= :maxPriceTwoHourHome')
                ->setParameter('maxPriceTwoHourHome', $filter->maxPriceTwoHourHome);
        }

        if ($filter->metroStations) {
            $qb->andWhere('ms.metro_station_id IN (:metroStations)')
                ->setParameter('metroStations', $filter->metroStations, ArrayParameterType::INTEGER);
        }

        if ($filter->districts) {
            $qb->andWhere('d.district_id IN (:districts)')
                ->setParameter('districts', $filter->districts, ArrayParameterType::INTEGER);
        }

        if ($filter->additionalOptions) {
            $qb->andWhere('ao.additional_option_id IN (:options)')
                ->setParameter('options', $filter->additionalOptions, ArrayParameterType::INTEGER)
                ->groupBy('p.id')
                ->having('COUNT(DISTINCT ao.additional_option_id) = :optionsCount')
                ->setParameter('optionsCount', count($filter->additionalOptions));
        }
        $forms = $this->paginator->paginate(
            $qb,
            $page,
            self::LIMIT_IN_PAGE,
        );

        $ids = array_map(fn ($form) => $form['id'], $forms->items);
        $forms->items = $this->buildCards($ids);

        return $forms;
    }

    private function buildCards(array $ids): array
    {
        $qb = $this->connection->createQueryBuilder();
        $result = $qb
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
            ->andWhere('p.id IN (:ids)')
            ->setParameter('ids', $ids, ArrayParameterType::STRING)
            ->groupBy('p.id')
            ->fetchAllAssociative();

        $cards = [];

        foreach ($result as $form) {
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
