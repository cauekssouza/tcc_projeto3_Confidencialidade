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
use Stringable;

class Validator extends BaseValidator
{
    private ValidatorFormats $formatValidator;

    private Cpf $cpf;
    private Cnpj $cnpj;
    private Cnh $cnh;
    private TituloEleitoral $tituloEleitoral;
    private Nis $nis;
    private Cns $cns;
    private Certidao $certidao;
    private InscricaoEstadual $inscricaoEstadual;
    private Renavam $renavam;
    private Placa $placa;
    private Ddd $ddd;
    private Passaporte $passaporte;

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

        /*
         * As regras são criadas somente uma vez por instância
         * do Validator, evitando new Cpf(), new Cnpj(), etc.
         * a cada validação.
         */
        $this->cpf = new Cpf();
        $this->cnpj = new Cnpj();
        $this->cnh = new Cnh();
        $this->tituloEleitoral = new TituloEleitoral();
        $this->nis = new Nis();
        $this->cns = new Cns();
        $this->certidao = new Certidao();
        $this->inscricaoEstadual = new InscricaoEstadual();
        $this->renavam = new Renavam();
        $this->placa = new Placa();
        $this->ddd = new Ddd();
        $this->passaporte = new Passaporte();
    }

    /**
     * Valida se um valor pode ser processado pelas regras de documentos.
     *
     * Evita passar arrays, resources ou objetos arbitrários para os
     * validadores, algo especialmente importante quando os valores
     * representam documentos pessoais.
     */
    private function normalizeValue(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (is_string($value)) {
            return $value;
        }

        if (is_int($value) || is_float($value)) {
            return (string) $value;
        }

        if ($value instanceof Stringable) {
            return (string) $value;
        }

        return null;
    }

    /**
     * Executa a validação de formato sem recriar ValidatorFormats.
     */
    protected function validateFormat(
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

    protected function validateCpf(
        string $attribute,
        mixed $value
    ): bool {
        $value = $this->normalizeValue($value);

        if ($value === null || $value === '') {
            return false;
        }

        if (!$this->validateFormat($value, 'cpf', $attribute)) {
            return false;
        }

        return (bool) $this->cpf->validateCpf(
            $attribute,
            $value
        );
    }

    protected function validateCnpj(
        string $attribute,
        mixed $value
    ): bool {
        $value = $this->normalizeValue($value);

        if ($value === null || $value === '') {
            return false;
        }

        return (bool) $this->cnpj->validateCnpj(
            $attribute,
            $value
        );
    }

    protected function validateCpfCnpj(
        string $attribute,
        mixed $value
    ): bool {
        $value = $this->normalizeValue($value);

        if ($value === null || $value === '') {
            return false;
        }

        return
            (bool) $this->cpf->validateCpf($attribute, $value)
            || (bool) $this->cnpj->validateCnpj($attribute, $value);
    }

    protected function validateCnh(
        string $attribute,
        mixed $value
    ): bool {
        $value = $this->normalizeValue($value);

        if ($value === null || $value === '') {
            return false;
        }

        return (bool) $this->cnh->validateCnh(
            $attribute,
            $value
        );
    }

    protected function validateTituloEleitor(
        string $attribute,
        mixed $value
    ): bool {
        $value = $this->normalizeValue($value);

        if ($value === null || $value === '') {
            return false;
        }

        return (bool) $this->tituloEleitoral->validateTituloEleitor(
            $attribute,
            $value
        );
    }

    protected function validateNis(
        string $attribute,
        mixed $value
    ): bool {
        $value = $this->normalizeValue($value);

        if ($value === null || $value === '') {
            return false;
        }

        return (bool) $this->nis->validateNis(
            $attribute,
            $value
        );
    }

    protected function validateCns(
        string $attribute,
        mixed $value
    ): bool {
        $value = $this->normalizeValue($value);

        if ($value === null || $value === '') {
            return false;
        }

        return (bool) $this->cns->validateCns(
            $attribute,
            $value
        );
    }

    protected function validateCertidao(
        string $attribute,
        mixed $value
    ): bool {
        $value = $this->normalizeValue($value);

        if ($value === null || $value === '') {
            return false;
        }

        return (bool) $this->certidao->validateCertidao(
            $attribute,
            $value
        );
    }

    protected function validateInscricaoEstadual(
        string $attribute,
        mixed $value,
        array $parameters = []
    ): bool {
        $value = $this->normalizeValue($value);

        if ($value === null || $value === '') {
            return false;
        }

        return (bool) $this->inscricaoEstadual
            ->validateInscricaoEstadual(
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

        if ($value === null || $value === '') {
            return false;
        }

        return (bool) $this->renavam->validateRenavam(
            $attribute,
            $value
        );
    }

    protected function validatePlaca(
        string $attribute,
        mixed $value
    ): bool {
        $value = $this->normalizeValue($value);

        if ($value === null || $value === '') {
            return false;
        }

        return (bool) $this->placa->validatePlaca(
            $attribute,
            $value
        );
    }

    protected function validateDdd(
        string $attribute,
        mixed $value
    ): bool {
        $value = $this->normalizeValue($value);

        if ($value === null || $value === '') {
            return false;
        }

        return (bool) $this->ddd->validateDdd(
            $attribute,
            $value
        );
    }

    protected function validatePassaporte(
        string $attribute,
        mixed $value
    ): bool {
        $value = $this->normalizeValue($value);

        if ($value === null || $value === '') {
            return false;
        }

        return (bool) $this->passaporte->validatePassaporte(
            $attribute,
            $value
        );
    }
}
