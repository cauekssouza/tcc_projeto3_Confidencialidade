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
    private ValidatorFormats $formatValidator;

    /**
     * Cache das instâncias das regras.
     *
     * @var array<class-string, object>
     */
    private array $validators = [];

    public function __construct(
        Translator $translator,
        ValidatorFormats $formatValidator,
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

        $this->formatValidator = $formatValidator;
    }

    protected function validateFormat($value, string $document)
    {
        if (empty($value)) {
            return null;
        }

        return $this->formatValidator->execute($value, $document);
    }

    protected function validateCpf($attribute, $value): bool
    {
        $this->validateFormat($value, 'cpf');

        return $this->validator(Cpf::class)
            ->validateCpf($attribute, $value);
    }

    protected function validateCnpj($attribute, $value): bool
    {
        return $this->validator(Cnpj::class)
            ->validateCnpj($attribute, $value);
    }

    protected function validateCpfCnpj($attribute, $value): bool
    {
        return $this->validator(Cpf::class)->validateCpf($attribute, $value)
            || $this->validator(Cnpj::class)->validateCnpj($attribute, $value);
    }

    protected function validateCnh($attribute, $value): bool
    {
        return $this->validator(Cnh::class)
            ->validateCnh($attribute, $value);
    }

    protected function validateTituloEleitor($attribute, $value): bool
    {
        return $this->validator(TituloEleitoral::class)
            ->validateTituloEleitor($attribute, $value);
    }

    protected function validateNis($attribute, $value): bool
    {
        return $this->validator(Nis::class)
            ->validateNis($attribute, $value);
    }

    protected function validateCns($attribute, $value): bool
    {
        return $this->validator(Cns::class)
            ->validateCns($attribute, $value);
    }

    protected function validateCertidao($attribute, $value): bool
    {
        return $this->validator(Certidao::class)
            ->validateCertidao($attribute, $value);
    }

    protected function validateInscricaoEstadual(
        $attribute,
        $value,
        $parameters
    ): bool {
        return $this->validator(InscricaoEstadual::class)
            ->validateInscricaoEstadual($attribute, $value, $parameters);
    }

    protected function validateRenavam($attribute, $value): bool
    {
        return $this->validator(Renavam::class)
            ->validateRenavam($attribute, $value);
    }

    protected function validatePlaca($attribute, $value): bool
    {
        return $this->validator(Placa::class)
            ->validatePlaca($attribute, $value);
    }

    protected function validateDdd($attribute, $value): bool
    {
        return $this->validator(Ddd::class)
            ->validateDdd($attribute, $value);
    }

    protected function validatePassaporte($attribute, $value): bool
    {
        return $this->validator(Passaporte::class)
            ->validatePassaporte($attribute, $value);
    }

    /**
     * Retorna uma única instância de cada regra.
     *
     * @template T of object
     *
     * @param class-string<T> $class
     *
     * @return T
     */
    private function validator(string $class): object
    {
        return $this->validators[$class]
            ??= new $class();
    }
}
