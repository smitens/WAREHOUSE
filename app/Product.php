<?php
namespace Warehouse\App;
require 'vendor/autoload.php';

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use JsonSerializable;

class Product implements JsonSerializable
{
    private string $id;
    private string $name;
    private int $quantity;
    private Carbon $createdAt;
    private ?Carbon $updatedAt;
    private ?Carbon $deletedAt;

    public function __construct(
        string $name,
        int $quantity,
        Carbon $createdAt,
        ?Carbon $updatedAt = null,
        ?Carbon $deletedAt = null,
        ?string $id = null)
    {
        $this->id = $id ?? Uuid::uuid4()->toString();
        $this->name = $name;
        $this->quantity = $quantity;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
        $this->updatedAt = Carbon::now();
    }

    public function reduceQuantity(int $quantity): void
    {
        $this->quantity -= $quantity;
        $this->updatedAt = Carbon::now();
    }

    public function addQuantity(int $quantity): void
    {
        $this->quantity += $quantity;
        $this->updatedAt = Carbon::now();
    }


    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }

    public function setCreatedAt(Carbon $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?Carbon
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?Carbon $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getDeletedAt(): ?Carbon
    {
        return $this->deletedAt;
    }
    public function setDeletedAt(?Carbon $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }

    public function jsonSerialize(): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "quantity" => $this->quantity,
            "createdAt" => $this->getCreatedAt()->toDateTimeString(),
            "updatedAt" => $this->getUpdatedAt() ? $this->getUpdatedAt()->toDateTimeString(): null,
            "deletedAt" => $this->getDeletedAt() ? $this->getDeletedAt()->toDateTimeString(): null,
        ];
    }

    public static function deserialize(array $data): Product
    {
        return new Product(
            $data['name'],
            (int) $data['quantity'],
            Carbon::parse($data['createdAt']),
            isset($data['updatedAt']) ? Carbon::parse($data['updatedAt']) : null,
            isset($data['deletedAt']) ? Carbon::parse($data['deletedAt']) : null,
            $data['id'] ?? null
        );
    }
}