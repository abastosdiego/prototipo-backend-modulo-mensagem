<?php

namespace App\Tests;

use App\Entity\Mensagem;
use App\Entity\Unidade;
use PHPUnit\Framework\TestCase;

class MensagemTest extends TestCase
{

    public function testCriacaoDeMensagem(): void
    {
        $mensagem = new Mensagem($this->getInputData(), 
                                 $this->getUnidadeOrigem(),
                                 $this->getUnidadesDestino(),
                                 $this->getUnidadesInformacao());

        $this->assertSame('Mensagem Teste', $mensagem->getAssunto());
        $dataHoje = new \DateTime('now');
        $this->assertSame($dataHoje->format('Ymd'), $mensagem->getDataEntrada()->format('Ymd'));
        $this->assertSame('20240131', $mensagem->getPrazo()->format('Ymd'));
        $this->assertNotNull($mensagem->getUnidadeOrigem());
        $this->assertSame('DAdM', $mensagem->getUnidadeOrigem()->getSigla());
        $this->assertCount(1, $mensagem->getUnidadesDestino());
        $this->assertCount(1, $mensagem->getUnidadesInformacao());
        
    }

    public function testMensagemSemPrazo(): void
    {
        $mensagem = new Mensagem($this->getInputaDataSemPrazo(), 
                                 $this->getUnidadeOrigem(),
                                 $this->getUnidadesDestino(),
                                 $this->getUnidadesInformacao());

        $this->assertNull($mensagem->getPrazo());
    }

    public function testMensagemSemUnidadesInformacao(): void
    {
        $mensagem = new Mensagem(inputData: $this->getInputaDataSemPrazo(), 
                                 unidadeOrigem: $this->getUnidadeOrigem(),
                                 unidadesDestino: $this->getUnidadesDestino());

        $this->assertCount(0, $mensagem->getUnidadesInformacao());
    }

    public function testAutorizacaoMensagem(): void {
        $mensagem = new Mensagem($this->getInputData(), 
                            $this->getUnidadeOrigem(),
                            $this->getUnidadesDestino(),
                            $this->getUnidadesInformacao());
        
        $this->assertFalse($mensagem->isAutorizado());
        $this->assertNull($mensagem->getDataHora());
        $mensagem->autorizar();
        $this->assertTrue($mensagem->isAutorizado());
        $this->assertNotNull($mensagem->getDataHora());
    }

    public function testMensagemPrazoInvalido(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Prazo inválido');

        $mensagem = new Mensagem($this->getInputDataPrazoInvalido(), 
                                 $this->getUnidadeOrigem(),
                                 $this->getUnidadesDestino(),
                                 $this->getUnidadesInformacao());

    }

    public function testAutorizacaoDuplicadaMensagem(): void {
        $mensagem = new Mensagem($this->getInputData(), 
                            $this->getUnidadeOrigem(),
                            $this->getUnidadesDestino(),
                            $this->getUnidadesInformacao());
        
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Mensagem já foi autorizada!');
        $mensagem->autorizar();
        $mensagem->autorizar();
    }

    public function testAutorizacaoMensagemSemUnidadeDestino(): void {
        $mensagem = new Mensagem(inputData: $this->getInputData(), 
                                 unidadeOrigem: $this->getUnidadeOrigem(),
                                 unidadesDestino: array());
        
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Mensagem sem destino não pode ser autorizada!');
        $mensagem->autorizar();
    }

    private function getInputData() {
        return array("assunto" => "Mensagem Teste",
                     "texto" => "bla bla bla",
                     "sigilo" => "Ostensivo",
                     "prazo" => "20240131",
                     "observacao" => "teste de observacao");
    }

    private function getInputaDataSemPrazo() {
        return array("assunto" => "Mensagem Teste",
                     "texto" => "bla bla bla",
                     "sigilo" => "Ostensivo",
                     "observacao" => "teste de observacao");
    }

    private function getInputDataPrazoInvalido() {
        return array("assunto" => "Mensagem Teste",
                     "texto" => "bla bla bla",
                     "sigilo" => "Ostensivo",
                     "prazo" => "20240132",
                     "observacao" => "teste de observacao");
    }

    private function getUnidadeOrigem() : Unidade {
        $unidadeOrigem = new Unidade();
        $unidadeOrigem->setSigla('DAdM');
        $unidadeOrigem->setNome('Diretoria de Administração da Marinha');

        return $unidadeOrigem;
    }

    private function getUnidadesDestino() : array {
        $unidadeDestino = new Unidade();
        $unidadeDestino->setSigla('DFM');
        $unidadeDestino->setNome('Diretoria de Finanças da Marinha');
        
        $unidadesDestino = [$unidadeDestino];
        return $unidadesDestino;
    }

    private function getUnidadesInformacao() : array {
        $unidadeInformacao = new Unidade();
        $unidadeInformacao->setSigla('DAbM');
        $unidadeInformacao->setNome('Diretoria de Abastecimento da Marinha');
        
        $unidadesInformacao = [$unidadeInformacao];
        return $unidadesInformacao;
    }
}
