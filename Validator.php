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
    private readonly ValidatorFormats $formatValidator;

    private readonly Cpf $cpf;
    private readonly Cnpj $cnpj;
    private readonly Cnh $cnh;
    private readonly TituloEleitoral $tituloEleitoral;
    private readonly Nis $nis;
    private readonly Cns $cns;
    private readonly Certidao $certidao;
    private readonly InscricaoEstadual $inscricaoEstadual;
    private readonly Renavam $renavam;
    private readonly Placa $placa;
    private readonly Ddd $ddd;
    private readonly Passaporte $passaporte;

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
         * As instâncias são criadas uma única vez por Validator,
         * evitando alocações repetidas durante múltiplas validações.
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
         * Não permita que valores vazios avancem implicitamente.
         *
         * Caso campos opcionais devam aceitar vazio, essa decisão deve
         * ficar nas regras do Laravel (nullable/sometimes), e não ser
         * inferida silenciosamente aqui.
         */
        if ($value === null || $value === '') {
            return false;
        }

        /*
         * Documentos pessoais devem chegar em representação escalar
         * previsível. Arrays/objetos são rejeitados para evitar
         * comportamento inesperado nas regras subsequentes.
         */
        if (!is_string($value) && !is_int($value)) {
            return false;
        }

        return $this->formatValidator->execute(
            (string) $value,
            $document
        ) === true;
    }

    protected function validateCpf(string $attribute, mixed $value): bool
    {
        /*
         * Fail fast:
         *
         * a validação específica só acontece se a validação de formato
         * tiver sido aprovada.
         */
        if (!$this->validateFormat($value, 'cpf', $attribute)) {
            return false;
        }

        return $this->cpf->validateCpf($attribute, $value);
    }

    protected function validateCnpj(string $attribute, mixed $value): bool
    {
        return $this->cnpj->validateCnpj($attribute, $value);
    }

    protected function validateCpfCnpj(string $attribute, mixed $value): bool
    {
        return $this->cpf->validateCpf($attribute, $value)
            || $this->cnpj->validateCnpj($attribute, $value);
    }

    protected function validateCnh(string $attribute, mixed $value): bool
    {
        return $this->cnh->validateCnh($attribute, $value);
    }

    protected function validateTituloEleitor(
        string $attribute,
        mixed $value
    ): bool {
        return $this->tituloEleitoral->validateTituloEleitor(
            $attribute,
            $value
        );
    }

    protected function validateNis(string $attribute, mixed $value): bool
    {
        return $this->nis->validateNis($attribute, $value);
    }

    protected function validateCns(string $attribute, mixed $value): bool
    {
        return $this->cns->validateCns($attribute, $value);
    }

    protected function validateCertidao(string $attribute, mixed $value): bool
    {
        return $this->certidao->validateCertidao($attribute, $value);
    }

    protected function validateInscricaoEstadual(
        string $attribute,
        mixed $value,
        array $parameters
    ): bool {
        return $this->inscricaoEstadual->validateInscricaoEstadual(
            $attribute,
            $value,
            $parameters
        );
    }

    protected function validateRenavam(string $attribute, mixed $value): bool
    {
        return $this->renavam->validateRenavam($attribute, $value);
    }

    protected function validatePlaca(string $attribute, mixed $value): bool
    {
        return $this->placa->validatePlaca($attribute, $value);
    }

    protected function validateDdd(string $attribute, mixed $value): bool
    {
        return $this->ddd->validateDdd($attribute, $value);
    }

    protected function validatePassaporte(
        string $attribute,
        mixed $value
    ): bool {
        return $this->passaporte->validatePassaporte($attribute, $value);
    }
}
