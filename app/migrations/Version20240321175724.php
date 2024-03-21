<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240321175724 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mensagem ADD usuario_autor_id INT NOT NULL');
        $this->addSql('ALTER TABLE mensagem ADD CONSTRAINT FK_9E4532B0FC15A927 FOREIGN KEY (usuario_autor_id) REFERENCES usuario (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_9E4532B0FC15A927 ON mensagem (usuario_autor_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mensagem DROP CONSTRAINT FK_9E4532B0FC15A927');
        $this->addSql('DROP INDEX IDX_9E4532B0FC15A927');
        $this->addSql('ALTER TABLE mensagem DROP usuario_autor_id');
    }
}
