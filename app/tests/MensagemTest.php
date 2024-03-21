<?php

namespace App\Tests;

use App\Entity\Mensagem;
use App\Entity\Unidade;
use App\Entity\Usuario;
use PHPUnit\Framework\TestCase;

class MensagemTest extends TestCase
{

    public function testCriacaoDeMensagem(): void
    {
        $mensagem = new Mensagem($this->getInputData(),
                                 $this->getUsuario(),
                                 $this->getUnidadeOrigem(),
                                 $this->getUnidadesDestino(),
                                 $this->getUnidadesInformacao());

        $this->assertSame('Mensagem Teste', $mensagem->getAssunto());
        $dataHoje = new \DateTime('now');
        $this->assertSame($dataHoje->format('Ymd'), $mensagem->getDataEntrada()->format('Ymd'));
        $this->assertSame('20240131', $mensagem->getPrazoTransmissao()->format('Ymd'));
        $this->assertNotNull($mensagem->getUnidadeOrigem());
        $this->assertSame('DAdM', $mensagem->getUnidadeOrigem()->getSigla());
        $this->assertCount(1, $mensagem->getUnidadesDestino());
        $this->assertCount(1, $mensagem->getUnidadesInformacao());
        
    }

    public function testMensagemSemPrazoDeTransmissao(): void
    {
        $mensagem = new Mensagem($this->getInputaDataSemPrazoDeTransmissao(),
                                 $this->getUsuario(),
                                 $this->getUnidadeOrigem(),
                                 $this->getUnidadesDestino(),
                                 $this->getUnidadesInformacao());

        $this->assertNull($mensagem->getPrazoTransmissao());
    }

    public function testMensagemSemUnidadesInformacao(): void
    {
        $mensagem = new Mensagem(inputData: $this->getInputaDataSemPrazoDeTransmissao(), 
                                 usuario_autor: $this->getUsuario(),
                                 unidadeOrigem: $this->getUnidadeOrigem(),
                                 unidadesDestino: $this->getUnidadesDestino());

        $this->assertCount(0, $mensagem->getUnidadesInformacao());
    }

    //////// O teste está errado, corrigir !!!!!!!!!!!!!!!!!!!!!!!!!
    // public function testAutorizacaoMensagem(): void {
    //     $mensagem = new Mensagem($this->getInputData(), 
    //                             $this->getUsuario(),
    //                             $this->getUnidadeOrigem(),
    //                             $this->getUnidadesDestino(),
    //                             $this->getUnidadesInformacao());
        
    //     $this->assertFalse($mensagem->isAutorizado());
    //     $this->assertStringStartsWith('M',$mensagem->getDataHora());
    //     $mensagem->autorizar();
    //     $this->assertTrue($mensagem->isAutorizado());
    //     $this->assertStringStartsWith('R',$mensagem->getDataHora());
    // }

    public function testMensagemPrazoDeTransmissaoInvalido(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Prazo de transmissão inválido');

        $mensagem = new Mensagem($this->getInputDataPrazoTransmissaoInvalido(),
                                 $this->getUsuario(),
                                 $this->getUnidadeOrigem(),
                                 $this->getUnidadesDestino(),
                                 $this->getUnidadesInformacao());

    }

    // public function testAutorizacaoDuplicadaMensagem(): void {
    //     $mensagem = new Mensagem($this->getInputData(),
    //                         $this->getUsuario(),
    //                         $this->getUnidadeOrigem(),
    //                         $this->getUnidadesDestino(),
    //                         $this->getUnidadesInformacao());
        
    //     $this->expectException(\DomainException::class);
    //     $this->expectExceptionMessage('Mensagem já foi autorizada!');
    //     $mensagem->autorizar();
    //     $mensagem->autorizar();
    // }

    public function testAutorizacaoMensagemSemUnidadeDestino(): void {
        $mensagem = new Mensagem(inputData: $this->getInputData(), 
                                 usuario_autor: $this->getUsuario(),
                                 unidadeOrigem: $this->getUnidadeOrigem(),
                                 unidadesDestino: array());
        
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Mensagem sem OM destino não pode ser autorizada!');
        $mensagem->autorizar();
    }

    public function testAutorizacaoMensagemSemPrazoDeResposta(): void {
        $mensagem = new Mensagem($this->getInputData(), 
                            $this->getUsuario(),
                            $this->getUnidadeOrigem(),
                            $this->getUnidadesDestino(),
                            $this->getUnidadesInformacao());
        
        $this->assertFalse($mensagem->exigeResposta());
        $this->assertNull($mensagem->getPrazoResposta());
    }

    public function testAutorizacaoMensagemComPrazoDeResposta(): void {
        $mensagem = new Mensagem($this->getInputDataComPrazoDeResposta(), 
                            $this->getUsuario(),
                            $this->getUnidadeOrigem(),
                            $this->getUnidadesDestino(),
                            $this->getUnidadesInformacao());
        
        $this->assertTrue($mensagem->exigeResposta());
        $this->assertNotNull($mensagem->getPrazoResposta());
    }

    private function getInputData() {
        return array("assunto" => "Mensagem Teste",
                     "texto" => "bla bla bla",
                     "sigilo" => "Ostensivo",
                     "prazo_transmissao" => "20240131",
                     "observacao" => "teste de observacao");
    }

    private function getInputaDataSemPrazoDeTransmissao() {
        return array("assunto" => "Mensagem Teste",
                     "texto" => "bla bla bla",
                     "sigilo" => "Ostensivo",
                     "observacao" => "teste de observacao");
    }

    private function getInputDataPrazoTransmissaoInvalido() {
        return array("assunto" => "Mensagem Teste",
                     "texto" => "bla bla bla",
                     "sigilo" => "Ostensivo",
                     "prazo_transmissao" => "20240132",
                     "observacao" => "teste de observacao");
    }

    private function getInputDataComPrazoDeResposta() {
        return array("assunto" => "Mensagem Teste",
                     "texto" => "bla bla bla",
                     "sigilo" => "Ostensivo",
                     "prazo_transmissao" => "20240131",
                     "observacao" => "teste de observacao",
                     "exige_resposta" => true,
                     "prazo_resposta" => "20240501",);
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

    private function getUsuario() : Usuario {
        return new Usuario('17090148','Diego Bastos','d.bastos@marinha.mil.br',$this->getUnidadeOrigem());
    }
}
