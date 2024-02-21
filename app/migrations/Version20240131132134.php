<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240131132134 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE tramite_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE tramite (id INT NOT NULL, mensagem_id INT NOT NULL, unidade_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9F322F2548D9DAD0 ON tramite (mensagem_id)');
        $this->addSql('CREATE INDEX IDX_9F322F25EDF4B99B ON tramite (unidade_id)');
        $this->addSql('ALTER TABLE tramite ADD CONSTRAINT FK_9F322F2548D9DAD0 FOREIGN KEY (mensagem_id) REFERENCES mensagem (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tramite ADD CONSTRAINT FK_9F322F25EDF4B99B FOREIGN KEY (unidade_id) REFERENCES unidade (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE tramite_id_seq CASCADE');
        $this->addSql('ALTER TABLE tramite DROP CONSTRAINT FK_9F322F2548D9DAD0');
        $this->addSql('ALTER TABLE tramite DROP CONSTRAINT FK_9F322F25EDF4B99B');
        $this->addSql('DROP TABLE tramite');
    }
}
