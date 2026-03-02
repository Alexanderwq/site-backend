<?php

namespace App\Model\Account\Entity\MassageForm;

use App\Model\Account\Entity\District\District;
use App\Model\Account\Entity\MassageForm\Event\MassageFormPhotoAdded;
use App\Model\Account\Entity\MassageForm\Photo\Photo;
use App\Model\Account\Entity\MassageForm\Price\Amount;
use App\Model\Account\Entity\MassageForm\Price\DayTime;
use App\Model\Account\Entity\MassageForm\Price\Duration;
use App\Model\Account\Entity\MassageForm\Price\Location;
use App\Model\Account\Entity\MassageForm\Price\Price;
use App\Model\Account\Entity\MetroStation\MetroStation;
use App\Model\Account\UseCase\MassageForm\EditAdditionalInfo\PriceDto;
use App\Model\Account\Entity\MassageForm\Photo\Id as PhotoId;
use App\Model\Account\UseCase\MassageForm\Photos\Add\PhotoDto;
use App\Model\AggregateRoot;
use App\Model\EventsTrait;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DomainException;
use App\Model\Account\Entity\MassageForm\AdditionalOption\Price as MassageAdditionalOptionPrice;
use App\Model\Account\Entity\MassageForm\AdditionalOption\Description as MassageAdditionalOptionDescription;
use App\Model\Account\Entity\MassageForm\AdditionalOption\AdditionalOption as MassageFormAdditionalOption;

#[ORM\Entity, ORM\Table(name: 'account_massage_forms')]
class MassageForm implements AggregateRoot
{
    use EventsTrait;

    #[ORM\Id, ORM\Column(type: 'account_massage_form_id')]
    private Id $id;

    #[ORM\Column(type: 'account_massage_form_user_id')]
    private UserId $userId;

    #[ORM\Embedded(class: Phone::class)]
    private Phone $phone;

    #[ORM\Embedded(class: Name::class)]
    private Name $name;

    #[ORM\Embedded(class: Description::class)]
    private Description $description;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $dateOfBirth;

    #[ORM\Column(type: Types::BOOLEAN, nullable: false, options: ['default' => false])]
    private bool $disabled;

    #[ORM\Embedded(class: Experience::class)]
    private Experience $experience;

    #[ORM\OneToMany(targetEntity: Price::class, mappedBy: 'massageForm', cascade: ['all'], orphanRemoval: true)]
    private Collection $prices;

    #[ORM\JoinTable(name: 'account_massage_form_metro_stations')]
    #[ORM\JoinColumn(name: 'massage_form_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'metro_station_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: MetroStation::class)]
    private Collection $metroStations;

    #[ORM\JoinTable(name: 'account_massage_form_districts')]
    #[ORM\JoinColumn(name: 'massage_form_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'district_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: District::class)]
    private Collection $districts;

    #[ORM\OneToMany(targetEntity: Photo::class, mappedBy: 'massageForm', cascade: ['all'], orphanRemoval: true)]
    private Collection $photos;

    #[ORM\OneToMany(
        targetEntity: MassageFormAdditionalOption::class,
        mappedBy: 'massageForm',
        cascade: ['all'],
        orphanRemoval: true
    )]
    private Collection $additionalOptions;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $createdAt;

    public function __construct(
        Id $id,
        UserId $userId,
        Phone $phone,
        Name $name,
        Description $description,
        DateTimeImmutable $dateOfBirth,
        Experience $experience,
        DateTimeImmutable $createdAt,
        array $metroStations = [],
        array $districts = [],
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->phone = $phone;
        $this->name = $name;
        $this->description = $description;
        $this->dateOfBirth = $dateOfBirth;
        $this->experience = $experience;
        $this->prices = new ArrayCollection();
        $this->metroStations = new ArrayCollection($metroStations);
        $this->districts = new ArrayCollection($districts);
        $this->additionalOptions = new ArrayCollection();
        $this->photos = new ArrayCollection();
        $this->disabled = false;
        $this->createdAt = $createdAt;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function getDescription(): Description
    {
        return $this->description;
    }

    public function getPhone(): Phone
    {
        return $this->phone;
    }

    public function getExperience(): Experience
    {
        return $this->experience;
    }

    public function getDateOfBirth(): DateTimeImmutable
    {
        return $this->dateOfBirth;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getPrices(): array
    {
        return $this->prices->toArray();
    }

    public function getAdditionalOptions(): array
    {
        return $this->additionalOptions->toArray();
    }

    public function getPhotos(): array
    {
        return $this->photos->toArray();
    }

    public function changeName(Name $name): void
    {
        $this->name = $name;
    }

    public function changePhone(Phone $phone): void
    {
        $this->phone = $phone;
    }

    public function changeDateOfBirth(DateTimeImmutable $dateTimeImmutable): void
    {
        $this->dateOfBirth = $dateTimeImmutable;
    }

    public function changeDescription(Description $description): void
    {
        $this->description = $description;
    }

    public function changeExperience(Experience $experience): void
    {
        $this->experience = $experience;
    }

    public function addMetroStation(MetroStation $metroStation): void
    {
        $this->metroStations->add($metroStation);
    }

    public function removeMetroStation(MetroStation $metroStation): void
    {
        $this->metroStations->removeElement($metroStation);
    }

    /**
     * @param MetroStation[] $metroStations
     * @return void
     */
    public function changeMetroStations(array $metroStations): void
    {
        $this->metroStations->clear();

        foreach ($metroStations as $metroStation) {
            $this->addMetroStation($metroStation);
        }
    }

    public function addDistrict(District $district): void
    {
        $this->districts->add($district);
    }

    /**
     * @param District[] $districts
     * @return void
     */
    public function changeDistricts(array $districts): void
    {
        $this->districts->clear();

        foreach ($districts as $district) {
            $this->addDistrict($district);
        }
    }

    /**
     * @param PriceDto[] $prices
     * @return void
     */
    public function changePrices(array $prices): void
    {
        if (count($prices) === 0) {
            throw new DomainException('Должна быть указана цена');
        }
        $this->prices = new ArrayCollection();

        foreach ($prices as $priceDto) {
            $this->prices->add(new Price(
                $this,
                new Location($priceDto->place),
                new Duration($priceDto->time),
                new Amount($priceDto->amount),
                new DayTime($priceDto->dayTime),
            ));
        }
    }

    public function changeAdditionalOptions(array $options): void
    {
        $this->additionalOptions = new ArrayCollection();
        foreach ($options as $optionData) {
            $this->additionalOptions->add(new MassageFormAdditionalOption(
                $this,
                $optionData['option'],
                new MassageAdditionalOptionPrice($optionData['price']),
                new MassageAdditionalOptionDescription($optionData['description']),
            ));
        }
    }

    public function removePhoto(PhotoId $photoId): void
    {
        /** @var Photo $photo */
        foreach ($this->photos as $photo) {
            if ($photo->getId()->isEqual($photoId)) {
                $this->photos->removeElement($photo);
                return;
            }
        }

        throw new DomainException('Фото не найдено');
    }

    /**
     * @param PhotoDto[] $newPhotos
     * @param array $removePhotoIds
     * @return void
     */
    public function changePhotos(array $newPhotos, array $removePhotoIds = []): void
    {
        foreach ($this->photos as $photo) {
            if (in_array($photo->getId()->getValue(), $removePhotoIds, true)) {
                $this->photos->removeElement($photo);
            }
        }

        $this->assertEnoughSecondaryPhotos($this->photos, $newPhotos);
        $this->assertMainAndPreview($this->photos, $newPhotos);

        foreach ($newPhotos as $photo) {
            $this->photos->add(
                new Photo(
                    $this,
                    PhotoId::next(),
                    $photo->filename,
                    $photo->isPreview,
                    $photo->isMain,
                )
            );
            $this->recordEvent(new MassageFormPhotoAdded($photo->filename, $photo->isPreview, $photo->isMain));
        }
    }

    public function getPreviewPhoto(): ?Photo
    {
        return $this->photos->findFirst(fn ($_ , Photo $photo) => $photo->isPreview());
    }

    public function getMainPhoto(): ?Photo
    {
        return $this->photos->findFirst(fn ($_ , Photo $photo) => $photo->isMain());
    }

    private function assertEnoughSecondaryPhotos(
        Collection $remaining,
        array $newPhotos
    ): void {
        $existing = $remaining->filter(
            fn (Photo $p) => !$p->isMain() && !$p->isPreview()
        );

        $incoming = array_filter(
            $newPhotos,
            fn (PhotoDto $p) => !$p->isMain && !$p->isPreview
        );

        if (count($existing) + count($incoming) < 3) {
            throw new DomainException('Слишком мало фотографий');
        }
    }

    private function assertMainAndPreview(
        Collection $remaining,
        array $newPhotos
    ): void {
        $hasMain = $this->hasMainPhoto($remaining);
        $hasPreview = $this->hasPreviewPhoto($remaining);

        foreach ($newPhotos as $photo) {
            if ($photo->isMain) {
                if ($hasMain) {
                    throw new DomainException('Нужно удалить старое фото обложки');
                }
                $hasMain = true;
            }

            if ($photo->isPreview) {
                if ($hasPreview) {
                    throw new DomainException('Нужно удалить старое фото превью');
                }
                $hasPreview = true;
            }
        }

        if (!$hasMain) {
            throw new DomainException('Нет фотографии обложки');
        }

        if (!$hasPreview) {
            throw new DomainException('Нет превью фотографии');
        }
    }

    private function hasMainPhoto(Collection $photos): bool
    {
        return $photos->exists(fn ($_ , Photo $photo) => $photo->isMain());
    }

    private function hasPreviewPhoto(Collection $photos): bool
    {
        return $photos->exists(fn ($_ , Photo $photo) => $photo->isPreview());
    }
}
