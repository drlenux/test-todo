<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Task;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;


final readonly class TaskCreator implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $processor,
        private Security $security,
        private UserRepository $userRepository,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if ($data instanceof Task) {
            $this->update($data);
        }

        return $this->processor->process($data, $operation, $uriVariables, $context);
    }

    private function update(Task $data): void
    {
        $email = $this->security->getUser()->getUserIdentifier();
        $user = $this->userRepository->findOneBy(['email' => $email]);
        $data->setUser($user);
        $data->setCreatedAt(new \DateTimeImmutable());
    }
}
