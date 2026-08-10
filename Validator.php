<?php

declare(strict_types=1);

namespace geekcom\ValidatorDocs;

use geekcom\ValidatorDocs\Rules\{
    Certidao,
    Cnh,
    Cnpj,
    Cns,
    Cpf,
    Ddd,
    InscricaoEstadual,
    Nis,
    Passaporte,
    Placa,
    Renavam,
    TituloEleitoral
};
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Validation\Validator as BaseValidator;

final class Validator extends BaseValidator
{
    /**
     * Cache das instâncias das regras.
     *
     * @var array<class-string, object>
     */
    private array $ruleInstances = [];

    public function __construct(
        Translator $translator,
        private readonly ValidatorFormats $formatValidator,
        array $data,
        array $rules,
        array $messages = [],
        array $customAttributes = []
    ) {
        parent::__construct(
            $translator,
            $data,
            $rules,
            $messages,
            $customAttributes
        );
    }

    /**
     * Retorna uma única instância de cada regra durante
     * o ciclo de vida deste Validator.
     *
     * @template T of object
     *
     * @param class-string<T> $ruleClass
     *
     * @return T
     */
    private function rule(string $ruleClass): object
    {
        if (!isset($this->ruleInstances[$ruleClass])) {
            $this->ruleInstances[$ruleClass] = new $ruleClass();
        }

        return $this->ruleInstances[$ruleClass];
    }

    /**
     * Converte somente valores escalares/stringable em string.
     *
     * Arrays e objetos arbitrários são rejeitados para evitar
     * casts inesperados de dados recebidos externamente.
     */
    private function normalizeValue(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (is_string($value)) {
            return trim($value);
        }

        if (is_int($value) || is_float($value)) {
            return (string) $value;
        }

        if ($value instanceof \Stringable) {
            return trim((string) $value);
        }

        return null;
    }

    private function validateFormat(
        mixed $value,
        string $document,
        ?string $attribute = null
    ): bool {
        $normalizedValue = $this->normalizeValue($value);

        if ($normalizedValue === null || $normalizedValue === '') {
            return false;
        }

        return (bool) $this->formatValidator->execute(
            $normalizedValue,
            $document
        );
    }

    protected function validateCpf(string $attribute, mixed $value): bool
    {
        $value = $this->normalizeValue($value);

        if ($value === null) {
            return false;
        }

        if (!$this->validateFormat($value, 'cpf', $attribute)) {
            return false;
        }

        /** @var Cpf $rule */
        $rule = $this->rule(Cpf::class);

        return (bool) $rule->validateCpf($attribute, $value);
    }

    protected function validateCnpj(string $attribute, mixed $value): bool
    {
        $value = $this->normalizeValue($value);

        if ($value === null) {
            return false;
        }

        /** @var Cnpj $rule */
        $rule = $this->rule(Cnpj::class);

        return (bool) $rule->validateCnpj($attribute, $value);
    }

    protected function validateCpfCnpj(string $attribute, mixed $value): bool
    {
        $value = $this->normalizeValue($value);

        if ($value === null) {
            return false;
        }

        /** @var Cpf $cpf */
        $cpf = $this->rule(Cpf::class);

        if ($cpf->validateCpf($attribute, $value)) {
            return true;
        }

        /** @var Cnpj $cnpj */
        $cnpj = $this->rule(Cnpj::class);

        return (bool) $cnpj->validateCnpj($attribute, $value);
    }

    protected function validateCnh(string $attribute, mixed $value): bool
    {
        $value = $this->normalizeValue($value);

        if ($value === null) {
            return false;
        }

        /** @var Cnh $rule */
        $rule = $this->rule(Cnh::class);

        return (bool) $rule->validateCnh($attribute, $value);
    }

    protected function validateTituloEleitor(
        string $attribute,
        mixed $value
    ): bool {
        $value = $this->normalizeValue($value);

        if ($value === null) {
            return false;
        }

        /** @var TituloEleitoral $rule */
        $rule = $this->rule(TituloEleitoral::class);

        return (bool) $rule->validateTituloEleitor($attribute, $value);
    }

    protected function validateNis(string $attribute, mixed $value): bool
    {
        $value = $this->normalizeValue($value);

        if ($value === null) {
            return false;
        }

        /** @var Nis $rule */
        $rule = $this->rule(Nis::class);

        return (bool) $rule->validateNis($attribute, $value);
    }

    protected function validateCns(string $attribute, mixed $value): bool
    {
        $value = $this->normalizeValue($value);

        if ($value === null) {
            return false;
        }

        /** @var Cns $rule */
        $rule = $this->rule(Cns::class);

        return (bool) $rule->validateCns($attribute, $value);
    }

    protected function validateCertidao(string $attribute, mixed $value): bool
    {
        $value = $this->normalizeValue($value);

        if ($value === null) {
            return false;
        }

        /** @var Certidao $rule */
        $rule = $this->rule(Certidao::class);

        return (bool) $rule->validateCertidao($attribute, $value);
    }

    protected function validateInscricaoEstadual(
        string $attribute,
        mixed $value,
        array $parameters
    ): bool {
        $value = $this->normalizeValue($value);

        if ($value === null) {
            return false;
        }

        /** @var InscricaoEstadual $rule */
        $rule = $this->rule(InscricaoEstadual::class);

        return (bool) $rule->validateInscricaoEstadual(
            $attribute,
            $value,
            $parameters
        );
    }

    protected function validateRenavam(
        string $attribute,
        mixed $value
    ): bool {
        $value = $this->normalizeValue($value);

        if ($value === null) {
            return false;
        }

        /** @var Renavam $rule */
        $rule = $this->rule(Renavam::class);

        return (bool) $rule->validateRenavam($attribute, $value);
    }

    protected function validatePlaca(string $attribute, mixed $value): bool
    {
        $value = $this->normalizeValue($value);

        if ($value === null) {
            return false;
        }

        /** @var Placa $rule */
        $rule = $this->rule(Placa::class);

        return (bool) $rule->validatePlaca($attribute, $value);
    }

    protected function validateDdd(string $attribute, mixed $value): bool
    {
        $value = $this->normalizeValue($value);

        if ($value === null) {
            return false;
        }

        /** @var Ddd $rule */
        $rule = $this->rule(Ddd::class);

        return (bool) $rule->validateDdd($attribute, $value);
    }

    protected function validatePassaporte(
        string $attribute,
        mixed $value
    ): bool {
        $value = $this->normalizeValue($value);

        if ($value === null) {
            return false;
        }

        /** @var Passaporte $rule */
        $rule = $this->rule(Passaporte::class);

        return (bool) $rule->validatePassaporte($attribute, $value);
    }
}
