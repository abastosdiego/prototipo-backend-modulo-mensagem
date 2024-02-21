<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240220140508 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tramite ADD usuario_atual_id INT NOT NULL');
        $this->addSql('ALTER TABLE tramite ADD CONSTRAINT FK_9F322F256335FFF9 FOREIGN KEY (usuario_atual_id) REFERENCES usuario (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_9F322F256335FFF9 ON tramite (usuario_atual_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE tramite DROP CONSTRAINT FK_9F322F256335FFF9');
        $this->addSql('DROP INDEX IDX_9F322F256335FFF9');
        $this->addSql('ALTER TABLE tramite DROP usuario_atual_id');
    }
}
