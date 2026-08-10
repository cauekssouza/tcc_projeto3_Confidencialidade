<?php

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

class Validator extends BaseValidator
{
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

    protected function validateFormat(
        mixed $value,
        string $document,
        ?string $attribute = null
    ): bool {
        if (empty($value)) {
            return false;
        }

        return $this->formatValidator->execute($value, $document);
    }

    protected function validateCpf(string $attribute, mixed $value): bool
    {
        if (!$this->validateFormat($value, 'cpf', $attribute)) {
            return false;
        }

        return (new Cpf())->validateCpf($attribute, $value);
    }

    protected function validateCnpj(string $attribute, mixed $value): bool
    {
        return (new Cnpj())->validateCnpj($attribute, $value);
    }

    protected function validateCpfCnpj(string $attribute, mixed $value): bool
    {
        return (new Cpf())->validateCpf($attribute, $value)
            || (new Cnpj())->validateCnpj($attribute, $value);
    }

    protected function validateCnh(string $attribute, mixed $value): bool
    {
        return (new Cnh())->validateCnh($attribute, $value);
    }

    protected function validateTituloEleitor(string $attribute, mixed $value): bool
    {
        return (new TituloEleitoral())
            ->validateTituloEleitor($attribute, $value);
    }

    protected function validateNis(string $attribute, mixed $value): bool
    {
        return (new Nis())->validateNis($attribute, $value);
    }

    protected function validateCns(string $attribute, mixed $value): bool
    {
        return (new Cns())->validateCns($attribute, $value);
    }

    protected function validateCertidao(string $attribute, mixed $value): bool
    {
        return (new Certidao())->validateCertidao($attribute, $value);
    }

    protected function validateInscricaoEstadual(
        string $attribute,
        mixed $value,
        array $parameters
    ): bool {
        return (new InscricaoEstadual())
            ->validateInscricaoEstadual($attribute, $value, $parameters);
    }

    protected function validateRenavam(string $attribute, mixed $value): bool
    {
        return (new Renavam())->validateRenavam($attribute, $value);
    }

    protected function validatePlaca(string $attribute, mixed $value): bool
    {
        return (new Placa())->validatePlaca($attribute, $value);
    }

    protected function validateDdd(string $attribute, mixed $value): bool
    {
        return (new Ddd())->validateDdd($attribute, $value);
    }

    protected function validatePassaporte(string $attribute, mixed $value): bool
    {
        return (new Passaporte())->validatePassaporte($attribute, $value);
    }
}
