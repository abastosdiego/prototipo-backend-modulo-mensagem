<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240131133119 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE comentario_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE comentario (id INT NOT NULL, mensagem_id INT NOT NULL, unidade_id INT NOT NULL, usuario_id INT NOT NULL, texto VARCHAR(1000) NOT NULL, data_hora TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4B91E70248D9DAD0 ON comentario (mensagem_id)');
        $this->addSql('CREATE INDEX IDX_4B91E702EDF4B99B ON comentario (unidade_id)');
        $this->addSql('CREATE INDEX IDX_4B91E702DB38439E ON comentario (usuario_id)');
        $this->addSql('COMMENT ON COLUMN comentario.data_hora IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE comentario ADD CONSTRAINT FK_4B91E70248D9DAD0 FOREIGN KEY (mensagem_id) REFERENCES mensagem (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comentario ADD CONSTRAINT FK_4B91E702EDF4B99B FOREIGN KEY (unidade_id) REFERENCES unidade (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comentario ADD CONSTRAINT FK_4B91E702DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE comentario_id_seq CASCADE');
        $this->addSql('ALTER TABLE comentario DROP CONSTRAINT FK_4B91E70248D9DAD0');
        $this->addSql('ALTER TABLE comentario DROP CONSTRAINT FK_4B91E702EDF4B99B');
        $this->addSql('ALTER TABLE comentario DROP CONSTRAINT FK_4B91E702DB38439E');
        $this->addSql('DROP TABLE comentario');
    }
}
