<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\TaskRepository;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class TaskReadAll implements ProviderInterface
{
    public function __construct(
        private TaskRepository $taskRepository,
        private Security $security
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        return $this->taskRepository->findBy([
            'parentTask' => null,
            'user' => $this->security->getUser()
        ]);
    }
}