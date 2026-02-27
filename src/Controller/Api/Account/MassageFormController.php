<?php

namespace App\Controller\Api\Account;

use App\Mapper\PriceMapper;
use App\Model\Account\Entity\MassageForm\Id;
use App\Model\Account\UseCase\MassageForm\Create\Command;
use App\Model\Account\UseCase\MassageForm\Create\Handler;
use App\Model\Account\UseCase\MassageForm\EditAdditionalInfo\OptionDto;
use App\Model\Account\UseCase\MassageForm\Photos\Add\PhotoDto;
use App\ReadModel\Account\MassageForm\MassageFormFetcher;
use App\Service\UploaderPhoto\UploadPhotosService;
use DomainException;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MassageFormController extends AbstractController
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
    ) {
    }

    #[Route(path: '/api/lk/profile/{id}', name: 'massage_form.edit_info', methods: ['PUT'])]
    public function editInfo(string $id, Request $request, \App\Model\Account\UseCase\MassageForm\EditMainInfo\Handler $handler): JsonResponse
    {
        /** @var \App\Model\Account\UseCase\MassageForm\EditMainInfo\Command $command */
        $command = $this->serializer->deserialize($request->getContent(), \App\Model\Account\UseCase\MassageForm\EditMainInfo\Command::class, 'json');

        $command->id = $id;

        $errors = $this->validator->validate($command);

        if (count($errors)) {
            $json = $this->serializer->serialize($errors, 'json');
            return new JsonResponse($json, 400, [], true);
        }

        try {
            $handler->handle($command);
        } catch (DomainException $exception) {
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);
            return $this->json(['message' => $exception->getMessage()], 400);
        }

        return $this->json(['success' => true]);
    }

    #[Route(path: '/api/lk/profiles_list', name: 'massage_form.profiles_list')]
    public function profilesList(MassageFormFetcher $massageFormFetcher): JsonResponse
    {
        $userId = $this->getUser()->getId();
        return $this->json($massageFormFetcher->listByUser($userId));
    }

    #[Route(path: '/api/lk/edit_profile/profile_info', name: 'massage_form.edit_profile_info', methods: ['POST'])]
    public function profileInfo(Request $request, MassageFormFetcher $massageFormFetcher): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $massageFormId = $data['id'] ?? null;
        $userId = $this->getUser()->getId();

        return $this->json($massageFormFetcher->getEditInfo($massageFormId, $userId));
    }

    /**
     * @throws ExceptionInterface
     */
    #[Route(path: '/api/lk/profile', name: 'massage_form.create', methods: ['POST'])]
    public function create(Request $request, Handler $handler): JsonResponse
    {
        /** @var Command $command */
        $command = $this->serializer->deserialize($request->getContent(), Command::class, 'json');

        $command->id = Id::next(); // TODO: в дальнейшем сделать через событие получение id
        $command->userId = $this->getUser()->getId();

        $errors = $this->validator->validate($command);

        if (count($errors)) {
            $json = $this->serializer->serialize($errors, 'json');
            return new JsonResponse($json, 400, [], true);
        }

        try {
            $handler->handle($command);
        } catch (DomainException $exception) {
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);
            return $this->json(['message' => $exception->getMessage()], 400);
        }

        return $this->json(['id' => $command->id]);
    }

    /**
     * @throws Exception
     */
    #[Route(path: '/api/lk/profile/{profileId}/price', name: 'massage_form.edit_additional_info', methods: ['POST'])]
    public function editAdditionalInfo(string $profileId, Request $request, PriceMapper $priceMapper, \App\Model\Account\UseCase\MassageForm\EditAdditionalInfo\Handler $handler): JsonResponse
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $command = new \App\Model\Account\UseCase\MassageForm\EditAdditionalInfo\Command();
        $command->prices = $priceMapper->map($data['price']);
        $command->massageForm = $profileId;
        $command->options = array_map(function ($option) {
            $optionDto = new OptionDto();
            $optionDto->optionId = $option['id'];
            $optionDto->price = !empty($option['price']) ? $option['price'] : null;
            $optionDto->description = !empty($option['description']) ? $option['description'] : null;

            return $optionDto;
        }, $data['additionalOptions']);

        $errors = $this->validator->validate($command);

        if (count($errors)) {
            $json = $this->serializer->serialize($errors, 'json');
            return new JsonResponse($json, 400, [], true);
        }

        try {
            $handler->handle($command);
        } catch (DomainException $exception) {
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);
            return $this->json(['message' => $exception->getMessage()], 400);
        }

        return $this->json(['status' => true]);
    }

    #[Route(path: '/api/lk/profile/{profileId}/photos', name: 'massage_form.add_photo', methods: ['POST'])]
    public function addPhoto(string $profileId, Request $request, \App\Model\Account\UseCase\MassageForm\Photos\Add\Handler $handler): JsonResponse
    {
        $uploadPhotoService = new UploadPhotosService();

        $photos = $request->request->all('photo');
        $removePhotos = $request->request->all('delete_photo_ids');
        $uploadedPhotos = $request->files->all('photo');

        $resultPhotos = [];

        foreach ($uploadedPhotos as $index => $fileData) {
            $file = $fileData['file'] ?? null;

            if (!$file instanceof UploadedFile) {
                continue;
            }

            $isMain = !empty($photos[$index]['is_main']);
            $isPreview = !empty($photos[$index]['is_preview']);

            $photo = new PhotoDto(
                $uploadPhotoService->saveOriginPhoto($file),
                $isMain,
                $isPreview,
            );
            $resultPhotos[] = $photo;
        }

        $command = new \App\Model\Account\UseCase\MassageForm\Photos\Add\Command();
        $command->massageForm = $profileId;
        $command->photos = $resultPhotos;
        $command->removePhotos = $removePhotos;

        $errors = $this->validator->validate($command);

        if (count($errors)) {
            $json = $this->serializer->serialize($errors, 'json');
            return new JsonResponse($json, 400, [], true);
        }

        try {
            $handler->handle($command);
        }  catch (DomainException $exception) {
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);
            return $this->json(['message' => $exception->getMessage()], 400);
        }

        return $this->json(['status' => true]);
    }

    #[Route(path: '/remove/{profileId}', name: 'massage_form.remove_photo', methods: ['POST'])]
    public function removePhoto(string $profileId, Request $request, \App\Model\Account\UseCase\MassageForm\Photos\Remove\Handler $handler): JsonResponse
    {
        $command = $this->serializer->deserialize($request->getContent(), \App\Model\Account\UseCase\MassageForm\Photos\Remove\Command::class, 'json');
        $command->massageForm = $profileId;

        $errors = $this->validator->validate($command);

        if (count($errors)) {
            $json = $this->serializer->serialize($errors, 'json');
            return new JsonResponse($json, 400, [], true);
        }

        try {
            $handler->handle($command);
        }  catch (DomainException $exception) {
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);
            return $this->json(['message' => $exception->getMessage()], 400);
        }

        return $this->json(['status' => true]);
    }
}
