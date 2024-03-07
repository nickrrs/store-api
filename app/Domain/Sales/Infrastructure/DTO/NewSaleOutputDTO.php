<?php

namespace App\Domain\Sales\Infrastructure\DTO;

class NewSaleOutputDTO
{
    public function __construct(public $id, public $status, public $updatedAt, public $createdAt)
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            status: $data['status'],
            updatedAt: $data['updated_at'],
            createdAt: $data['created_at'],
        );
    }

    public function response(): array
    {
        return [
            'message' => 'Your sale was concluded, we will proccess the itens and shortly after update the status.',
            'data' => [
                'id' => $this->id,
                'status' => $this->status,
                'updated_at' => $this->updatedAt,
                'created_at' => $this->createdAt,
            ],
        ];
    }
}
