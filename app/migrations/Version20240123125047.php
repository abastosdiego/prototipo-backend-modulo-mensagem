<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240123125047 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE mensagem_unidades_destino (mensagem_id INT NOT NULL, unidade_id INT NOT NULL, PRIMARY KEY(mensagem_id, unidade_id))');
        $this->addSql('CREATE INDEX IDX_BCAF0BD948D9DAD0 ON mensagem_unidades_destino (mensagem_id)');
        $this->addSql('CREATE INDEX IDX_BCAF0BD9EDF4B99B ON mensagem_unidades_destino (unidade_id)');
        $this->addSql('CREATE TABLE mensagem_unidades_informacao (mensagem_id INT NOT NULL, unidade_id INT NOT NULL, PRIMARY KEY(mensagem_id, unidade_id))');
        $this->addSql('CREATE INDEX IDX_DBB9FE5848D9DAD0 ON mensagem_unidades_informacao (mensagem_id)');
        $this->addSql('CREATE INDEX IDX_DBB9FE58EDF4B99B ON mensagem_unidades_informacao (unidade_id)');
        $this->addSql('ALTER TABLE mensagem_unidades_destino ADD CONSTRAINT FK_BCAF0BD948D9DAD0 FOREIGN KEY (mensagem_id) REFERENCES mensagem (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mensagem_unidades_destino ADD CONSTRAINT FK_BCAF0BD9EDF4B99B FOREIGN KEY (unidade_id) REFERENCES unidade (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mensagem_unidades_informacao ADD CONSTRAINT FK_DBB9FE5848D9DAD0 FOREIGN KEY (mensagem_id) REFERENCES mensagem (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mensagem_unidades_informacao ADD CONSTRAINT FK_DBB9FE58EDF4B99B FOREIGN KEY (unidade_id) REFERENCES unidade (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mensagem_unidades_destino DROP CONSTRAINT FK_BCAF0BD948D9DAD0');
        $this->addSql('ALTER TABLE mensagem_unidades_destino DROP CONSTRAINT FK_BCAF0BD9EDF4B99B');
        $this->addSql('ALTER TABLE mensagem_unidades_informacao DROP CONSTRAINT FK_DBB9FE5848D9DAD0');
        $this->addSql('ALTER TABLE mensagem_unidades_informacao DROP CONSTRAINT FK_DBB9FE58EDF4B99B');
        $this->addSql('DROP TABLE mensagem_unidades_destino');
        $this->addSql('DROP TABLE mensagem_unidades_informacao');
    }
}
