<?php

namespace geekcom\ValidatorDocs;

use geekcom\ValidatorDocs\Rules\Certidao;
use geekcom\ValidatorDocs\Rules\Cnh;
use geekcom\ValidatorDocs\Rules\Cnpj;
use geekcom\ValidatorDocs\Rules\Cns;
use geekcom\ValidatorDocs\Rules\Cpf;
use geekcom\ValidatorDocs\Rules\Ddd;
use geekcom\ValidatorDocs\Rules\InscricaoEstadual;
use geekcom\ValidatorDocs\Rules\Nis;
use geekcom\ValidatorDocs\Rules\Passaporte;
use geekcom\ValidatorDocs\Rules\Placa;
use geekcom\ValidatorDocs\Rules\Renavam;
use geekcom\ValidatorDocs\Rules\TituloEleitoral;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Validation\Validator as BaseValidator;

class Validator extends BaseValidator
{
    private ValidatorFormats $formatValidator;

    /**
     * Mantém uma única instância de cada regra durante o ciclo
     * de vida do Validator.
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

    protected function validateFormat($value, string $document): void
    {
        if (!empty($value)) {
            $this->formatValidator->execute($value, $document);
        }
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
        return $this->validateCpf($attribute, $value)
            || $this->validateCnpj($attribute, $value);
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
            ->validateInscricaoEstadual(
                $attribute,
                $value,
                $parameters
            );
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
     * @template T of object
     *
     * @param class-string<T> $class
     * @return T
     */
    private function validator(string $class): object
    {
        return $this->validators[$class]
            ??= new $class();
    }
}
