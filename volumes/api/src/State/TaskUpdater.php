<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Constants\TaskStateEnum;
use App\Entity\Task;
use InvalidArgumentException;


final readonly class TaskUpdater implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $processor,
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
        if ($data->getStatus() === TaskStateEnum::DONE && $data->getCompletedAt() === null) {
            foreach ($data->getSubTask() as $task) {
                if ($task->getStatus() === TaskStateEnum::TODO) {
                    throw new InvalidArgumentException(
                        'To change the status, all subtasks must be in the "completed" status'
                    );
                }
            }
            $data->setCompletedAt(new \DateTimeImmutable());
        }
    }
}
