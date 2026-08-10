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
use InvalidArgumentException;

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
        private ValidatorFormats $formatValidator,
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
     * Executa a validação de formato sem recriar ValidatorFormats.
     */
    protected function validateFormat(
        mixed $value,
        string $document,
        ?string $attribute = null
    ): bool {
        if ($value === null || $value === '') {
            return false;
        }

        if (!is_string($value) && !is_numeric($value)) {
            return false;
        }

        return (bool) $this->formatValidator->execute(
            (string) $value,
            $document
        );
    }

    /**
     * Retorna uma única instância de cada classe de regra
     * durante o ciclo de vida deste Validator.
     *
     * @template T of object
     *
     * @param class-string<T> $ruleClass
     * @return T
     */
    private function rule(string $ruleClass): object
    {
        if (!class_exists($ruleClass)) {
            throw new InvalidArgumentException(
                sprintf('Classe de validação "%s" não encontrada.', $ruleClass)
            );
        }

        return $this->ruleInstances[$ruleClass]
            ??= new $ruleClass();
    }

    protected function validateCpf(string $attribute, mixed $value): bool
    {
        if (!$this->isValidScalarValue($value)) {
            return false;
        }

        if (!$this->validateFormat($value, 'cpf', $attribute)) {
            return false;
        }

        /** @var Cpf $validator */
        $validator = $this->rule(Cpf::class);

        return (bool) $validator->validateCpf(
            $attribute,
            (string) $value
        );
    }

    protected function validateCnpj(string $attribute, mixed $value): bool
    {
        if (!$this->isValidScalarValue($value)) {
            return false;
        }

        /** @var Cnpj $validator */
        $validator = $this->rule(Cnpj::class);

        return (bool) $validator->validateCnpj(
            $attribute,
            (string) $value
        );
    }

    protected function validateCpfCnpj(string $attribute, mixed $value): bool
    {
        if (!$this->isValidScalarValue($value)) {
            return false;
        }

        $value = (string) $value;

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
        if (!$this->isValidScalarValue($value)) {
            return false;
        }

        /** @var Cnh $validator */
        $validator = $this->rule(Cnh::class);

        return (bool) $validator->validateCnh(
            $attribute,
            (string) $value
        );
    }

    protected function validateTituloEleitor(
        string $attribute,
        mixed $value
    ): bool {
        if (!$this->isValidScalarValue($value)) {
            return false;
        }

        /** @var TituloEleitoral $validator */
        $validator = $this->rule(TituloEleitoral::class);

        return (bool) $validator->validateTituloEleitor(
            $attribute,
            (string) $value
        );
    }

    protected function validateNis(string $attribute, mixed $value): bool
    {
        if (!$this->isValidScalarValue($value)) {
            return false;
        }

        /** @var Nis $validator */
        $validator = $this->rule(Nis::class);

        return (bool) $validator->validateNis(
            $attribute,
            (string) $value
        );
    }

    protected function validateCns(string $attribute, mixed $value): bool
    {
        if (!$this->isValidScalarValue($value)) {
            return false;
        }

        /** @var Cns $validator */
        $validator = $this->rule(Cns::class);

        return (bool) $validator->validateCns(
            $attribute,
            (string) $value
        );
    }

    protected function validateCertidao(
        string $attribute,
        mixed $value
    ): bool {
        if (!$this->isValidScalarValue($value)) {
            return false;
        }

        /** @var Certidao $validator */
        $validator = $this->rule(Certidao::class);

        return (bool) $validator->validateCertidao(
            $attribute,
            (string) $value
        );
    }

    protected function validateInscricaoEstadual(
        string $attribute,
        mixed $value,
        array $parameters
    ): bool {
        if (!$this->isValidScalarValue($value)) {
            return false;
        }

        /** @var InscricaoEstadual $validator */
        $validator = $this->rule(InscricaoEstadual::class);

        return (bool) $validator->validateInscricaoEstadual(
            $attribute,
            (string) $value,
            $parameters
        );
    }

    protected function validateRenavam(
        string $attribute,
        mixed $value
    ): bool {
        if (!$this->isValidScalarValue($value)) {
            return false;
        }

        /** @var Renavam $validator */
        $validator = $this->rule(Renavam::class);

        return (bool) $validator->validateRenavam(
            $attribute,
            (string) $value
        );
    }

    protected function validatePlaca(string $attribute, mixed $value): bool
    {
        if (!$this->isValidScalarValue($value)) {
            return false;
        }

        /** @var Placa $validator */
        $validator = $this->rule(Placa::class);

        return (bool) $validator->validatePlaca(
            $attribute,
            (string) $value
        );
    }

    protected function validateDdd(string $attribute, mixed $value): bool
    {
        if (!$this->isValidScalarValue($value)) {
            return false;
        }

        /** @var Ddd $validator */
        $validator = $this->rule(Ddd::class);

        return (bool) $validator->validateDdd(
            $attribute,
            (string) $value
        );
    }

    protected function validatePassaporte(
        string $attribute,
        mixed $value
    ): bool {
        if (!$this->isValidScalarValue($value)) {
            return false;
        }

        /** @var Passaporte $validator */
        $validator = $this->rule(Passaporte::class);

        return (bool) $validator->validatePassaporte(
            $attribute,
            (string) $value
        );
    }

    /**
     * Impede que arrays, objetos ou outros valores inesperados
     * cheguem às classes que manipulam documentos.
     */
    private function isValidScalarValue(mixed $value): bool
    {
        return is_string($value) || is_int($value);
    }
}
