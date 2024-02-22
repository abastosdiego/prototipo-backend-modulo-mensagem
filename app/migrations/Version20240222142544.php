<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240222142544 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER INDEX idx_9f322f256335fff9 RENAME TO IDX_9F322F259EC8EFFB');
        $this->addSql('ALTER TABLE tramite_futuro ALTER tramite_id DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER INDEX idx_9f322f259ec8effb RENAME TO idx_9f322f256335fff9');
        $this->addSql('ALTER TABLE tramite_futuro ALTER tramite_id SET NOT NULL');
    }
}
