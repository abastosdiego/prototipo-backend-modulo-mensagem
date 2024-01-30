<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240130173709 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE usuario_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE usuario (id INT NOT NULL, unidade_id INT NOT NULL, cpf VARCHAR(20) NOT NULL, nome VARCHAR(100) NOT NULL, email VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2265B05DEDF4B99B ON usuario (unidade_id)');
        $this->addSql('ALTER TABLE usuario ADD CONSTRAINT FK_2265B05DEDF4B99B FOREIGN KEY (unidade_id) REFERENCES unidade (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE usuario_id_seq CASCADE');
        $this->addSql('ALTER TABLE usuario DROP CONSTRAINT FK_2265B05DEDF4B99B');
        $this->addSql('DROP TABLE usuario');
    }
}
