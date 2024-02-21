<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240220163650 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE tramite_passado_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE tramite_passado (id INT NOT NULL, tramite_id INT NOT NULL, usuario_id INT NOT NULL, data_hora TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_612E7E7820C2849 ON tramite_passado (tramite_id)');
        $this->addSql('CREATE INDEX IDX_612E7E7DB38439E ON tramite_passado (usuario_id)');
        $this->addSql('COMMENT ON COLUMN tramite_passado.data_hora IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE tramite_passado ADD CONSTRAINT FK_612E7E7820C2849 FOREIGN KEY (tramite_id) REFERENCES tramite (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tramite_passado ADD CONSTRAINT FK_612E7E7DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE tramite_passado_id_seq CASCADE');
        $this->addSql('ALTER TABLE tramite_passado DROP CONSTRAINT FK_612E7E7820C2849');
        $this->addSql('ALTER TABLE tramite_passado DROP CONSTRAINT FK_612E7E7DB38439E');
        $this->addSql('DROP TABLE tramite_passado');
    }
}
