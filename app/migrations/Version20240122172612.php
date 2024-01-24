<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240122172612 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE mensagem_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE unidade_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE mensagem (id INT NOT NULL, unidade_origem_id INT NOT NULL, data_hora VARCHAR(17) NOT NULL, assunto VARCHAR(100) NOT NULL, texto VARCHAR(1000) NOT NULL, observacao VARCHAR(1000) DEFAULT NULL, data_entrada DATE NOT NULL, sigilo VARCHAR(20) NOT NULL, prazo DATE DEFAULT NULL, data_autorizacao DATE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9E4532B017486C4E ON mensagem (unidade_origem_id)');
        $this->addSql('CREATE TABLE unidade (id INT NOT NULL, sigla VARCHAR(30) NOT NULL, nome VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE mensagem ADD CONSTRAINT FK_9E4532B017486C4E FOREIGN KEY (unidade_origem_id) REFERENCES unidade (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE mensagem_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE unidade_id_seq CASCADE');
        $this->addSql('ALTER TABLE mensagem DROP CONSTRAINT FK_9E4532B017486C4E');
        $this->addSql('DROP TABLE mensagem');
        $this->addSql('DROP TABLE unidade');
    }
}
