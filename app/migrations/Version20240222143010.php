<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240222143010 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE comentario_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mensagem_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tramite_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tramite_futuro_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tramite_passado_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE unidade_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE usuario_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE comentario (id INT NOT NULL, mensagem_id INT NOT NULL, unidade_id INT NOT NULL, usuario_id INT NOT NULL, texto VARCHAR(1000) NOT NULL, data_hora TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4B91E70248D9DAD0 ON comentario (mensagem_id)');
        $this->addSql('CREATE INDEX IDX_4B91E702EDF4B99B ON comentario (unidade_id)');
        $this->addSql('CREATE INDEX IDX_4B91E702DB38439E ON comentario (usuario_id)');
        $this->addSql('COMMENT ON COLUMN comentario.data_hora IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE mensagem (id INT NOT NULL, unidade_origem_id INT NOT NULL, data_hora VARCHAR(17) NOT NULL, assunto VARCHAR(100) NOT NULL, texto VARCHAR(1000) NOT NULL, observacao VARCHAR(1000) DEFAULT NULL, data_entrada DATE NOT NULL, sigilo VARCHAR(20) NOT NULL, prazo DATE DEFAULT NULL, data_autorizacao DATE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9E4532B017486C4E ON mensagem (unidade_origem_id)');
        $this->addSql('CREATE TABLE mensagem_unidades_destino (mensagem_id INT NOT NULL, unidade_id INT NOT NULL, PRIMARY KEY(mensagem_id, unidade_id))');
        $this->addSql('CREATE INDEX IDX_BCAF0BD948D9DAD0 ON mensagem_unidades_destino (mensagem_id)');
        $this->addSql('CREATE INDEX IDX_BCAF0BD9EDF4B99B ON mensagem_unidades_destino (unidade_id)');
        $this->addSql('CREATE TABLE mensagem_unidades_informacao (mensagem_id INT NOT NULL, unidade_id INT NOT NULL, PRIMARY KEY(mensagem_id, unidade_id))');
        $this->addSql('CREATE INDEX IDX_DBB9FE5848D9DAD0 ON mensagem_unidades_informacao (mensagem_id)');
        $this->addSql('CREATE INDEX IDX_DBB9FE58EDF4B99B ON mensagem_unidades_informacao (unidade_id)');
        $this->addSql('CREATE TABLE tramite (id INT NOT NULL, mensagem_id INT NOT NULL, unidade_id INT NOT NULL, usuario_atual_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9F322F2548D9DAD0 ON tramite (mensagem_id)');
        $this->addSql('CREATE INDEX IDX_9F322F25EDF4B99B ON tramite (unidade_id)');
        $this->addSql('CREATE INDEX IDX_9F322F259EC8EFFB ON tramite (usuario_atual_id)');
        $this->addSql('CREATE TABLE tramite_futuro (id INT NOT NULL, tramite_id INT DEFAULT NULL, usuario_id INT NOT NULL, ordem SMALLINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_84B084D9820C2849 ON tramite_futuro (tramite_id)');
        $this->addSql('CREATE INDEX IDX_84B084D9DB38439E ON tramite_futuro (usuario_id)');
        $this->addSql('CREATE TABLE tramite_passado (id INT NOT NULL, tramite_id INT NOT NULL, usuario_id INT NOT NULL, data_hora TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_612E7E7820C2849 ON tramite_passado (tramite_id)');
        $this->addSql('CREATE INDEX IDX_612E7E7DB38439E ON tramite_passado (usuario_id)');
        $this->addSql('COMMENT ON COLUMN tramite_passado.data_hora IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE unidade (id INT NOT NULL, sigla VARCHAR(30) NOT NULL, nome VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE usuario (id INT NOT NULL, unidade_id INT NOT NULL, nip VARCHAR(20) NOT NULL, nome VARCHAR(100) NOT NULL, email VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2265B05DEDF4B99B ON usuario (unidade_id)');
        $this->addSql('ALTER TABLE comentario ADD CONSTRAINT FK_4B91E70248D9DAD0 FOREIGN KEY (mensagem_id) REFERENCES mensagem (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comentario ADD CONSTRAINT FK_4B91E702EDF4B99B FOREIGN KEY (unidade_id) REFERENCES unidade (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comentario ADD CONSTRAINT FK_4B91E702DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mensagem ADD CONSTRAINT FK_9E4532B017486C4E FOREIGN KEY (unidade_origem_id) REFERENCES unidade (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mensagem_unidades_destino ADD CONSTRAINT FK_BCAF0BD948D9DAD0 FOREIGN KEY (mensagem_id) REFERENCES mensagem (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mensagem_unidades_destino ADD CONSTRAINT FK_BCAF0BD9EDF4B99B FOREIGN KEY (unidade_id) REFERENCES unidade (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mensagem_unidades_informacao ADD CONSTRAINT FK_DBB9FE5848D9DAD0 FOREIGN KEY (mensagem_id) REFERENCES mensagem (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mensagem_unidades_informacao ADD CONSTRAINT FK_DBB9FE58EDF4B99B FOREIGN KEY (unidade_id) REFERENCES unidade (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tramite ADD CONSTRAINT FK_9F322F2548D9DAD0 FOREIGN KEY (mensagem_id) REFERENCES mensagem (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tramite ADD CONSTRAINT FK_9F322F25EDF4B99B FOREIGN KEY (unidade_id) REFERENCES unidade (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tramite ADD CONSTRAINT FK_9F322F259EC8EFFB FOREIGN KEY (usuario_atual_id) REFERENCES usuario (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tramite_futuro ADD CONSTRAINT FK_84B084D9820C2849 FOREIGN KEY (tramite_id) REFERENCES tramite (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tramite_futuro ADD CONSTRAINT FK_84B084D9DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tramite_passado ADD CONSTRAINT FK_612E7E7820C2849 FOREIGN KEY (tramite_id) REFERENCES tramite (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tramite_passado ADD CONSTRAINT FK_612E7E7DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE usuario ADD CONSTRAINT FK_2265B05DEDF4B99B FOREIGN KEY (unidade_id) REFERENCES unidade (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE comentario_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mensagem_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tramite_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tramite_futuro_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tramite_passado_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE unidade_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE usuario_id_seq CASCADE');
        $this->addSql('ALTER TABLE comentario DROP CONSTRAINT FK_4B91E70248D9DAD0');
        $this->addSql('ALTER TABLE comentario DROP CONSTRAINT FK_4B91E702EDF4B99B');
        $this->addSql('ALTER TABLE comentario DROP CONSTRAINT FK_4B91E702DB38439E');
        $this->addSql('ALTER TABLE mensagem DROP CONSTRAINT FK_9E4532B017486C4E');
        $this->addSql('ALTER TABLE mensagem_unidades_destino DROP CONSTRAINT FK_BCAF0BD948D9DAD0');
        $this->addSql('ALTER TABLE mensagem_unidades_destino DROP CONSTRAINT FK_BCAF0BD9EDF4B99B');
        $this->addSql('ALTER TABLE mensagem_unidades_informacao DROP CONSTRAINT FK_DBB9FE5848D9DAD0');
        $this->addSql('ALTER TABLE mensagem_unidades_informacao DROP CONSTRAINT FK_DBB9FE58EDF4B99B');
        $this->addSql('ALTER TABLE tramite DROP CONSTRAINT FK_9F322F2548D9DAD0');
        $this->addSql('ALTER TABLE tramite DROP CONSTRAINT FK_9F322F25EDF4B99B');
        $this->addSql('ALTER TABLE tramite DROP CONSTRAINT FK_9F322F259EC8EFFB');
        $this->addSql('ALTER TABLE tramite_futuro DROP CONSTRAINT FK_84B084D9820C2849');
        $this->addSql('ALTER TABLE tramite_futuro DROP CONSTRAINT FK_84B084D9DB38439E');
        $this->addSql('ALTER TABLE tramite_passado DROP CONSTRAINT FK_612E7E7820C2849');
        $this->addSql('ALTER TABLE tramite_passado DROP CONSTRAINT FK_612E7E7DB38439E');
        $this->addSql('ALTER TABLE usuario DROP CONSTRAINT FK_2265B05DEDF4B99B');
        $this->addSql('DROP TABLE comentario');
        $this->addSql('DROP TABLE mensagem');
        $this->addSql('DROP TABLE mensagem_unidades_destino');
        $this->addSql('DROP TABLE mensagem_unidades_informacao');
        $this->addSql('DROP TABLE tramite');
        $this->addSql('DROP TABLE tramite_futuro');
        $this->addSql('DROP TABLE tramite_passado');
        $this->addSql('DROP TABLE unidade');
        $this->addSql('DROP TABLE usuario');
    }
}
