<?php

declare(strict_types=1);

namespace App\Core;

/** Validador encadeável com mensagens em português. */
final class Validator
{
    private array $data;
    private array $errors = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    private function val(string $field): string
    {
        return trim((string) ($this->data[$field] ?? ''));
    }

    private function add(string $field, string $message): void
    {
        $this->errors[$field][] = $message;
    }

    public function required(string $field, string $label): self
    {
        if ($this->val($field) === '') {
            $this->add($field, "{$label} é obrigatório.");
        }
        return $this;
    }

    public function email(string $field, string $label): self
    {
        $v = $this->val($field);
        if ($v !== '' && !filter_var($v, FILTER_VALIDATE_EMAIL)) {
            $this->add($field, "{$label} inválido.");
        }
        return $this;
    }

    public function url(string $field, string $label): self
    {
        $v = $this->val($field);
        if ($v !== '' && !filter_var($v, FILTER_VALIDATE_URL)) {
            $this->add($field, "{$label} deve ser uma URL válida (com http:// ou https://).");
        }
        return $this;
    }

    public function min(string $field, int $n, string $label): self
    {
        $v = $this->val($field);
        if ($v !== '' && mb_strlen($v) < $n) {
            $this->add($field, "{$label} deve ter ao menos {$n} caracteres.");
        }
        return $this;
    }

    public function max(string $field, int $n, string $label): self
    {
        if (mb_strlen($this->val($field)) > $n) {
            $this->add($field, "{$label} deve ter no máximo {$n} caracteres.");
        }
        return $this;
    }

    public function regex(string $field, string $pattern, string $message): self
    {
        $v = $this->val($field);
        if ($v !== '' && !preg_match($pattern, $v)) {
            $this->add($field, $message);
        }
        return $this;
    }

    public function matches(string $field, string $other, string $message): self
    {
        if ($this->val($field) !== $this->val($other)) {
            $this->add($field, $message);
        }
        return $this;
    }

    /** Adiciona erro se a condição for falsa (regras externas: unicidade etc). */
    public function check(bool $condition, string $field, string $message): self
    {
        if (!$condition) {
            $this->add($field, $message);
        }
        return $this;
    }

    public function fails(): bool
    {
        return $this->errors !== [];
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
