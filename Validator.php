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
         * Reutiliza as dependências durante todo o ciclo de vida
         * deste Validator, evitando instanciações repetidas
         * durante cada chamada de validação.
         */
        $this->formatValidator = $formatValidator;

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
     * Valida previamente o formato do documento.
     *
     * Falha de forma segura para valores não escalares,
     * nulos ou vazios, evitando coerções inesperadas
     * de dados potencialmente não confiáveis.
     */
    protected function validateFormat(
        mixed $value,
        string $document,
        ?string $attribute = null
    ): bool {
        if (!is_scalar($value)) {
            return false;
        }

        $value = trim((string) $value);

        if ($value === '') {
            return false;
        }

        return $this->formatValidator->execute($value, $document) === true;
    }

    protected function validateCpf($attribute, $value): bool
    {
        /*
         * Validação em cascata:
         *
         * 1. O formato precisa ser válido.
         * 2. Somente então o algoritmo de CPF é executado.
         *
         * O short-circuit do && impede processamento desnecessário
         * caso o formato já seja inválido.
         */
        return $this->validateFormat($value, 'cpf')
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
        return $this->inscricaoEstadual->validateInscricaoEstadual(
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
