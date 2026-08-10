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

        /*
         * Reutiliza a dependência recebida em vez de criar um novo
         * ValidatorFormats a cada validação.
         */
        $this->formatValidator = $formatValidator;

        /*
         * Instancia as regras uma única vez durante o ciclo de vida
         * deste Validator, evitando alocações repetidas.
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

    protected function validateFormat(
        mixed $value,
        string $document,
        ?string $attribute = null
    ): bool {
        /*
         * Fail closed:
         *
         * um valor ausente/vazio nunca deve resultar em null implícito.
         * A validação retorna explicitamente false.
         */
        if (empty($value)) {
            return false;
        }

        return (bool) $this->formatValidator->execute(
            $value,
            $document
        );
    }

    protected function validateCpf($attribute, $value): bool
    {
        /*
         * Validação em cascata com short-circuit:
         *
         * 1. valida o formato;
         * 2. somente se o formato for válido executa a regra de CPF.
         *
         * Isso corrige o comportamento anterior, no qual o retorno de
         * validateFormat() era simplesmente descartado.
         */
        return $this->validateFormat($value, 'cpf', $attribute)
            && $this->cpf->validateCpf($attribute, $value);
    }

    protected function validateCnpj($attribute, $value): bool
    {
        return $this->cnpj->validateCnpj($attribute, $value);
    }

    protected function validateCpfCnpj($attribute, $value): bool
    {
        return $this->cpf->validateCpf($attribute, $value)
            || $this->cnpj->validateCnpj($attribute, $value);
    }

    protected function validateCnh($attribute, $value): bool
    {
        return $this->cnh->validateCnh($attribute, $value);
    }

    protected function validateTituloEleitor($attribute, $value): bool
    {
        return $this->tituloEleitoral
            ->validateTituloEleitor($attribute, $value);
    }

    protected function validateNis($attribute, $value): bool
    {
        return $this->nis->validateNis($attribute, $value);
    }

    protected function validateCns($attribute, $value): bool
    {
        return $this->cns->validateCns($attribute, $value);
    }

    protected function validateCertidao($attribute, $value): bool
    {
        return $this->certidao->validateCertidao($attribute, $value);
    }

    protected function validateInscricaoEstadual(
        $attribute,
        $value,
        $parameters
    ): bool {
        return $this->inscricaoEstadual
            ->validateInscricaoEstadual(
                $attribute,
                $value,
                $parameters
            );
    }

    protected function validateRenavam($attribute, $value): bool
    {
        return $this->renavam->validateRenavam($attribute, $value);
    }

    protected function validatePlaca($attribute, $value): bool
    {
        return $this->placa->validatePlaca($attribute, $value);
    }

    protected function validateDdd($attribute, $value): bool
    {
        return $this->ddd->validateDdd($attribute, $value);
    }

    protected function validatePassaporte($attribute, $value): bool
    {
        return $this->passaporte
            ->validatePassaporte($attribute, $value);
    }
}
