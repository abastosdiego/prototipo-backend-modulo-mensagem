<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240206134909 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE tramite_futuro_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE tramite_futuro (id INT NOT NULL, tramite_id INT NOT NULL, usuario_id INT NOT NULL, ordem SMALLINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_84B084D9820C2849 ON tramite_futuro (tramite_id)');
        $this->addSql('CREATE INDEX IDX_84B084D9DB38439E ON tramite_futuro (usuario_id)');
        $this->addSql('ALTER TABLE tramite_futuro ADD CONSTRAINT FK_84B084D9820C2849 FOREIGN KEY (tramite_id) REFERENCES tramite (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tramite_futuro ADD CONSTRAINT FK_84B084D9DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE tramite_futuro_id_seq CASCADE');
        $this->addSql('ALTER TABLE tramite_futuro DROP CONSTRAINT FK_84B084D9820C2849');
        $this->addSql('ALTER TABLE tramite_futuro DROP CONSTRAINT FK_84B084D9DB38439E');
        $this->addSql('DROP TABLE tramite_futuro');
    }
}
